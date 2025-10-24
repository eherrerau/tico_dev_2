<?php

declare(strict_types=1);

namespace Tico\Models;

use Tico\Database\DatabaseManager;
use Tico\Security\PasswordManager;

class TestUser
{
    private readonly DatabaseManager $databaseManager;
    private readonly PasswordManager $passwordManager;

    public function __construct()
    {
        $this->databaseManager = DatabaseManager::getInstance();
        $this->passwordManager = new PasswordManager();
    }

    public function findById(int $id): ?array
    {
        try {
            // Try test database first
            $connection = $this->databaseManager->getConnection('test');
            $sql = "
                SELECT 
                    id as usrId, username as usrName, email as usrMail, 
                    full_name as nameToDisplay, team as teamID, role,
                    status as active, created_at, updated_at
                FROM users 
                WHERE id = :id AND status = 'active'
            ";

            $stmt = $connection->prepare($sql);
            $stmt->execute(['id' => $id]);
            return $stmt->fetch() ?: null;
        } catch (\Exception) {
            // Fallback to original database
            return $this->findByIdOriginal($id);
        }
    }

    public function findByUsername(string $username): ?array
    {
        try {
            // Try test database first
            $connection = $this->databaseManager->getConnection('test');
            $sql = "
                SELECT 
                    id as usrId, username as usrName, email as usrMail, 
                    full_name as nameToDisplay, team as teamID, role,
                    status as active, password, created_at, updated_at
                FROM users 
                WHERE username = :username AND status = 'active'
            ";

            $stmt = $connection->prepare($sql);
            $stmt->execute(['username' => $username]);
            $result = $stmt->fetch();

            if ($result) {
                // Rename password field to match expected format
                $result['password_hash'] = $result['password'];
                unset($result['password']);
                return $result;
            }

            return null;
        } catch (\Exception) {
            // Fallback to original database
            return $this->findByUsernameOriginal($username);
        }
    }

    public function findByEmail(string $email): ?array
    {
        try {
            // Try test database first
            $connection = $this->databaseManager->getConnection('test');
            $sql = "
                SELECT 
                    id as usrId, username as usrName, email as usrMail, 
                    full_name as nameToDisplay, team as teamID, role,
                    status as active, created_at, updated_at
                FROM users 
                WHERE email = :email AND status = 'active'
            ";

            $stmt = $connection->prepare($sql);
            $stmt->execute(['email' => $email]);
            return $stmt->fetch() ?: null;
        } catch (\Exception) {
            // Fallback to original database
            return $this->findByEmailOriginal($email);
        }
    }

    public function authenticate(string $username, string $password, int $teamId = 0): ?array
    {
        // First try test database authentication
        $user = $this->findByUsername($username);

        if ($user && isset($user['password_hash']) && $this->passwordManager->verify($password, $user['password_hash'])) {
            // Check if password needs rehashing
            if ($this->passwordManager->needsRehash($user['password_hash'])) {
                $this->updatePasswordHash($user['usrId'], $password);
            }
            return $user;
        }

        // Fallback to legacy authentication for migration
        if ($teamId > 0) {
            return $this->authenticateLegacy($username, $password, $teamId);
        }

        return null;
    }

    private function findByIdOriginal(int $id): ?array
    {
        try {
            $connection = $this->databaseManager->getConnection('default');
            $sql = '
                SELECT 
                    usrId, usrName, usrMail, nameToDisplay, phoneExt, 
                    teamID, birthday, premier, active, globalUser, timeZone,
                    created_at, updated_at
                FROM UserDetails 
                WHERE usrId = :id AND active = 1
            ';

            $stmt = $connection->prepare($sql);
            $stmt->execute(['id' => $id]);
            return $stmt->fetch() ?: null;
        } catch (\Exception) {
            return null;
        }
    }

    private function findByUsernameOriginal(string $username): ?array
    {
        try {
            $connection = $this->databaseManager->getConnection('default');
            $sql = '
                SELECT 
                    usrId, usrName, usrMail, nameToDisplay, phoneExt, 
                    teamID, birthday, premier, active, globalUser, timeZone,
                    password_hash, created_at, updated_at
                FROM UserDetails 
                WHERE usrName = :username AND active = 1
            ';

            $stmt = $connection->prepare($sql);
            $stmt->execute(['username' => $username]);
            return $stmt->fetch() ?: null;
        } catch (\Exception) {
            return null;
        }
    }

    private function findByEmailOriginal(string $email): ?array
    {
        try {
            $connection = $this->databaseManager->getConnection('default');
            $sql = '
                SELECT 
                    usrId, usrName, usrMail, nameToDisplay, phoneExt, 
                    teamID, birthday, premier, active, globalUser, timeZone,
                    created_at, updated_at
                FROM UserDetails 
                WHERE usrMail = :email AND active = 1
            ';

            $stmt = $connection->prepare($sql);
            $stmt->execute(['email' => $email]);
            return $stmt->fetch() ?: null;
        } catch (\Exception) {
            return null;
        }
    }

    private function authenticateLegacy(string $username, string $password, int $teamId): ?array
    {
        try {
            $connection = $this->databaseManager->getConnection('default');
            $sql = '
                SELECT 
                    u.usrId, u.usrName, u.usrMail, u.nameToDisplay, u.phoneExt,
                    u.teamID, u.birthday, u.premier, u.active, u.globalUser, u.timeZone
                FROM UserDetails u
                INNER JOIN UserPasswords p ON u.usrId = p.usrId
                WHERE u.usrName = :username 
                AND p.password = :password 
                AND u.teamID = :teamId 
                AND u.active = 1
            ';

            $stmt = $connection->prepare($sql);
            $hashedPassword = sha1($password); // Legacy SHA1 hashing

            $stmt->execute([
                'username' => $username,
                'password' => $hashedPassword,
                'teamId' => $teamId,
            ]);

            $user = $stmt->fetch();

            if ($user) {
                // Migrate to modern password hashing
                $this->updatePasswordHash($user['usrId'], $password);
            }

            return $user ?: null;
        } catch (\Exception) {
            return null;
        }
    }

    private function updatePasswordHash(int $userId, string $password): void
    {
        try {
            $newHash = $this->passwordManager->hash($password);

            // Try to update in test database first
            try {
                $connection = $this->databaseManager->getConnection('test');
                $sql = 'UPDATE users SET password = :password WHERE id = :id';
                $stmt = $connection->prepare($sql);
                $stmt->execute(['password' => $newHash, 'id' => $userId]);
            } catch (\Exception) {
                // Fallback to original database
                $connection = $this->databaseManager->getConnection('default');
                $sql = 'UPDATE UserDetails SET password_hash = :password WHERE usrId = :id';
                $stmt = $connection->prepare($sql);
                $stmt->execute(['password' => $newHash, 'id' => $userId]);
            }
        } catch (\Exception $e) {
            // Log error but don't throw
            error_log("Failed to update password hash for user {$userId}: " . $e->getMessage());
        }
    }

    public function getUserRoles(int $userId): array
    {
        try {
            // Try test database first
            $connection = $this->databaseManager->getConnection('test');
            $sql = "SELECT role FROM users WHERE id = :id AND status = 'active'";
            $stmt = $connection->prepare($sql);
            $stmt->execute(['id' => $userId]);
            $result = $stmt->fetch();

            if ($result && $result['role']) {
                return [$result['role']];
            }

            return ['user']; // default role
        } catch (\Exception) {
            // Fallback to original database
            try {
                $connection = $this->databaseManager->getConnection('default');
                $sql = '
                    SELECT r.roleName 
                    FROM UserRoles ur
                    INNER JOIN Roles r ON ur.roleId = r.roleId
                    WHERE ur.userId = :id AND ur.active = 1
                ';
                $stmt = $connection->prepare($sql);
                $stmt->execute(['id' => $userId]);
                $roles = $stmt->fetchAll();

                return array_column($roles, 'roleName');
            } catch (\Exception) {
                return ['user']; // default role
            }
        }
    }

    public function getUserProducts(int $userId): array
    {
        try {
            // Try test database first - for test users, return default products
            $connection = $this->databaseManager->getConnection('test');
            $sql = "SELECT id FROM users WHERE id = :id AND status = 'active'";
            $stmt = $connection->prepare($sql);
            $stmt->execute(['id' => $userId]);

            if ($stmt->fetch()) {
                // Return default products for test users
                return [
                    ['productId' => 1, 'productName' => 'Default Product'],
                ];
            }

            return [];
        } catch (\Exception) {
            // Fallback to original database
            try {
                $connection = $this->databaseManager->getConnection('default');
                $sql = '
                    SELECT p.productId, p.productName 
                    FROM UserProducts up
                    INNER JOIN Products p ON up.productId = p.productId
                    WHERE up.userId = :id AND up.active = 1
                ';
                $stmt = $connection->prepare($sql);
                $stmt->execute(['id' => $userId]);
                return $stmt->fetchAll();
            } catch (\Exception) {
                return [];
            }
        }
    }

    public function getTeams(): array
    {
        try {
            // Try test database first
            $connection = $this->databaseManager->getConnection('test');
            $sql = "SELECT DISTINCT team as teamName, team as teamID FROM users WHERE status = 'active'";
            $stmt = $connection->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll();
        } catch (\Exception) {
            // Fallback to original database
            try {
                $connection = $this->databaseManager->getConnection('default');
                $sql = 'SELECT teamID, teamName FROM Teams WHERE active = 1 ORDER BY teamName';
                $stmt = $connection->prepare($sql);
                $stmt->execute();
                return $stmt->fetchAll();
            } catch (\Exception) {
                // Return default teams
                return [
                    ['teamID' => 1, 'teamName' => 'Support'],
                    ['teamID' => 2, 'teamName' => 'IT'],
                    ['teamID' => 3, 'teamName' => 'Admin'],
                ];
            }
        }
    }
}
