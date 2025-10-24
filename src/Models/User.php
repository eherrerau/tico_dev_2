<?php

declare(strict_types=1);

namespace Tico\Models;

use Tico\Database\DatabaseManager;
use Tico\Security\PasswordManager;

class User
{
    private DatabaseManager $db;
    private PasswordManager $passwordManager;

    public function __construct()
    {
        $this->db = DatabaseManager::getInstance();
        $this->passwordManager = new PasswordManager();
    }

    public function findById(int $id): ?array
    {
        $sql = "
            SELECT 
                usrId, usrName, usrMail, nameToDisplay, phoneExt, 
                teamID, birthday, premier, active, globalUser, timeZone,
                created_at, updated_at
            FROM UserDetails 
            WHERE usrId = :id AND active = 1
        ";
        
        return $this->db->fetchOne($sql, ['id' => $id]);
    }

    public function findByUsername(string $username): ?array
    {
        $sql = "
            SELECT 
                usrId, usrName, usrMail, nameToDisplay, phoneExt, 
                teamID, birthday, premier, active, globalUser, timeZone,
                password_hash, created_at, updated_at
            FROM UserDetails 
            WHERE usrName = :username AND active = 1
        ";
        
        return $this->db->fetchOne($sql, ['username' => $username]);
    }

    public function findByEmail(string $email): ?array
    {
        $sql = "
            SELECT 
                usrId, usrName, usrMail, nameToDisplay, phoneExt, 
                teamID, birthday, premier, active, globalUser, timeZone,
                created_at, updated_at
            FROM UserDetails 
            WHERE usrMail = :email AND active = 1
        ";
        
        return $this->db->fetchOne($sql, ['email' => $email]);
    }

    public function authenticate(string $username, string $password, int $teamId): ?array
    {
        // First try modern authentication
        $user = $this->findByUsername($username);
        
        if ($user && isset($user['password_hash'])) {
            if ($this->passwordManager->verify($password, $user['password_hash'])) {
                // Check if password needs rehashing
                if ($this->passwordManager->needsRehash($user['password_hash'])) {
                    $this->updatePasswordHash($user['usrId'], $password);
                }
                return $user;
            }
        }

        // Fallback to legacy authentication for migration
        if ($this->authenticateLegacy($username, $password, $teamId)) {
            // Migrate to modern password hashing
            $this->migratePasswordToModern($username, $password);
            return $this->findByUsername($username);
        }

        return null;
    }

    private function authenticateLegacy(string $username, string $password, int $teamId): bool
    {
        try {
            $sql = "EXEC usp_login_autentication ?, ?, ?";
            $stmt = $this->db->executeQuery($sql, [$username, sha1($password), $teamId]);
            
            $result = $stmt->fetch();
            return $result && $result !== 0;
        } catch (\Exception $e) {
            return false;
        }
    }

    private function migratePasswordToModern(string $username, string $password): void
    {
        $newHash = $this->passwordManager->hash($password);
        
        $sql = "UPDATE UserDetails SET password_hash = :hash WHERE usrName = :username";
        $this->db->executeQuery($sql, [
            'hash' => $newHash,
            'username' => $username
        ]);
    }

    public function create(array $userData): int
    {
        // Hash password if provided
        if (isset($userData['password'])) {
            $userData['password_hash'] = $this->passwordManager->hash($userData['password']);
            unset($userData['password']);
        }

        $userData['created_at'] = date('Y-m-d H:i:s');
        $userData['updated_at'] = date('Y-m-d H:i:s');

        return (int)$this->db->insert('UserDetails', $userData);
    }

    public function update(int $id, array $userData): bool
    {
        // Hash password if provided
        if (isset($userData['password'])) {
            $userData['password_hash'] = $this->passwordManager->hash($userData['password']);
            unset($userData['password']);
        }

        $userData['updated_at'] = date('Y-m-d H:i:s');

        $rowsAffected = $this->db->update('UserDetails', $userData, ['usrId' => $id]);
        return $rowsAffected > 0;
    }

    private function updatePasswordHash(int $userId, string $password): void
    {
        $newHash = $this->passwordManager->hash($password);
        
        $this->db->update('UserDetails', 
            ['password_hash' => $newHash, 'updated_at' => date('Y-m-d H:i:s')], 
            ['usrId' => $userId]
        );
    }

    public function updatePassword(int $userId, string $newPassword): bool
    {
        $hash = $this->passwordManager->hash($newPassword);
        
        $rowsAffected = $this->db->update('UserDetails', 
            ['password_hash' => $hash, 'updated_at' => date('Y-m-d H:i:s')], 
            ['usrId' => $userId]
        );
        
        return $rowsAffected > 0;
    }

    public function deactivate(int $id): bool
    {
        $rowsAffected = $this->db->update('UserDetails', 
            ['active' => 0, 'updated_at' => date('Y-m-d H:i:s')], 
            ['usrId' => $id]
        );
        
        return $rowsAffected > 0;
    }

    public function getAllActiveUsers(): array
    {
        $sql = "
            SELECT 
                usrId, usrName, usrMail, nameToDisplay, phoneExt, 
                teamID, birthday, premier, active, globalUser, timeZone,
                created_at, updated_at
            FROM UserDetails 
            WHERE active = 1 
            ORDER BY nameToDisplay
        ";
        
        return $this->db->fetchAll($sql);
    }

    public function getUsersByTeam(int $teamId): array
    {
        $sql = "
            SELECT 
                usrId, usrName, usrMail, nameToDisplay, phoneExt, 
                teamID, birthday, premier, active, globalUser, timeZone,
                created_at, updated_at
            FROM UserDetails 
            WHERE teamID = :teamId AND active = 1 
            ORDER BY nameToDisplay
        ";
        
        return $this->db->fetchAll($sql, ['teamId' => $teamId]);
    }

    public function getUserRoles(int $userId): array
    {
        $sql = "
            SELECT r.roleId, r.roleDesc, r.roleShortDesc
            FROM UserRoles ur
            INNER JOIN Roles r ON ur.roleId = r.roleId
            WHERE ur.usrId = :userId
        ";
        
        return $this->db->fetchAll($sql, ['userId' => $userId]);
    }

    public function getUserProducts(int $userId): array
    {
        $sql = "
            SELECT p.productId, p.productDesc
            FROM UserProducts up
            INNER JOIN Products p ON up.productId = p.productId
            WHERE up.usrId = :userId
        ";
        
        return $this->db->fetchAll($sql, ['userId' => $userId]);
    }

    public function hasRole(int $userId, int $roleId): bool
    {
        $sql = "SELECT COUNT(*) FROM UserRoles WHERE usrId = :userId AND roleId = :roleId";
        $count = $this->db->fetchColumn($sql, ['userId' => $userId, 'roleId' => $roleId]);
        
        return $count > 0;
    }

    public function assignRole(int $userId, int $roleId): bool
    {
        try {
            $this->db->insert('UserRoles', [
                'usrId' => $userId,
                'roleId' => $roleId,
                'created_at' => date('Y-m-d H:i:s')
            ]);
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    public function removeRole(int $userId, int $roleId): bool
    {
        $rowsAffected = $this->db->delete('UserRoles', [
            'usrId' => $userId,
            'roleId' => $roleId
        ]);
        
        return $rowsAffected > 0;
    }

    public function assignProduct(int $userId, int $productId): bool
    {
        try {
            $this->db->insert('UserProducts', [
                'usrId' => $userId,
                'productId' => $productId,
                'created_at' => date('Y-m-d H:i:s')
            ]);
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    public function removeProduct(int $userId, int $productId): bool
    {
        $rowsAffected = $this->db->delete('UserProducts', [
            'usrId' => $userId,
            'productId' => $productId
        ]);
        
        return $rowsAffected > 0;
    }
}
