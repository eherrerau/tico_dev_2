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
            // Use PostgreSQL database directly
            $connection = $this->databaseManager->getConnection('default');
            $sql = "
                SELECT 
                    id, username, email, 
                    full_name, team, role,
                    status, created_at, updated_at
                FROM users 
                WHERE id = :id AND status = 'active'
            ";

            $stmt = $connection->prepare($sql);
            $stmt->execute(['id' => $id]);
            $result = $stmt->fetch();
            
            if (!$result) {
                return null;
            }
            
            // Format keys to match expected format (camelCase)
            return [
                'usrId' => $result['id'],
                'usrName' => $result['username'],
                'usrMail' => $result['email'],
                'nameToDisplay' => $result['full_name'],
                'teamID' => $result['team'],
                'role' => $result['role'],
                'active' => $result['status'] === 'active' ? 1 : 0,
                'created_at' => $result['created_at'],
                'updated_at' => $result['updated_at'],
            ];
        } catch (\Exception $e) {
            error_log("Error finding user by ID: " . $e->getMessage());
            return null;
        }
    }

    public function findByUsername(string $username): ?array
    {
        try {
            // Use PostgreSQL database directly
            $connection = $this->databaseManager->getConnection('default');
            $sql = "
                SELECT 
                    id, username, email, 
                    full_name, team, role,
                    status, password, created_at, updated_at
                FROM users 
                WHERE username = :username AND status = 'active'
            ";

            $stmt = $connection->prepare($sql);
            $stmt->execute(['username' => $username]);
            $result = $stmt->fetch();

            if (!$result) {
                return null;
            }
            
            // Format keys to match expected format (camelCase)
            return [
                'usrId' => $result['id'],
                'usrName' => $result['username'],
                'usrMail' => $result['email'],
                'nameToDisplay' => $result['full_name'],
                'teamID' => $result['team'],
                'role' => $result['role'],
                'active' => $result['status'] === 'active' ? 1 : 0,
                'password_hash' => $result['password'],
                'created_at' => $result['created_at'],
                'updated_at' => $result['updated_at'],
            ];
        } catch (\Exception $e) {
            error_log("Error finding user by username: " . $e->getMessage());
            return null;
        }
    }

    public function findByEmail(string $email): ?array
    {
        try {
            // Use PostgreSQL database directly
            $connection = $this->databaseManager->getConnection('default');
            $sql = "
                SELECT 
                    id, username, email, 
                    full_name, team, role,
                    status, created_at, updated_at
                FROM users 
                WHERE email = :email AND status = 'active'
            ";

            $stmt = $connection->prepare($sql);
            $stmt->execute(['email' => $email]);
            $result = $stmt->fetch();
            
            if (!$result) {
                return null;
            }
            
            // Format keys to match expected format (camelCase)
            return [
                'usrId' => $result['id'],
                'usrName' => $result['username'],
                'usrMail' => $result['email'],
                'nameToDisplay' => $result['full_name'],
                'teamID' => $result['team'],
                'role' => $result['role'],
                'active' => $result['status'] === 'active' ? 1 : 0,
                'created_at' => $result['created_at'],
                'updated_at' => $result['updated_at'],
            ];
        } catch (\Exception $e) {
            error_log("Error finding user by email: " . $e->getMessage());
            return null;
        }
    }

    public function authenticate(string $username, string $password, int $teamId = 0): ?array
    {
        // Try PostgreSQL database authentication
        $user = $this->findByUsername($username);

        if ($user && isset($user['password_hash']) && $this->passwordManager->verify($password, $user['password_hash'])) {
            // Check if password needs rehashing
            if ($this->passwordManager->needsRehash($user['password_hash'])) {
                $this->updatePasswordHash($user['usrId'], $password);
            }
            
            // Add the selected team_id to the user data if provided
            if ($teamId > 0) {
                $user['selectedTeamId'] = $teamId;
            }
            
            return $user;
        }

        return null;
    }

    private function updatePasswordHash(int $userId, string $password): void
    {
        try {
            $newHash = $this->passwordManager->hash($password);
            
            // Update in PostgreSQL database
            $connection = $this->databaseManager->getConnection('default');
            $sql = 'UPDATE users SET password = :password, updated_at = CURRENT_TIMESTAMP WHERE id = :id';
            $stmt = $connection->prepare($sql);
            $stmt->execute(['password' => $newHash, 'id' => $userId]);
        } catch (\Exception $e) {
            // Log error but don't throw
            error_log("Failed to update password hash for user {$userId}: " . $e->getMessage());
        }
    }

    public function getUserRoles(int $userId): array
    {
        try {
            // Use PostgreSQL database directly
            $connection = $this->databaseManager->getConnection('default');
            $sql = "SELECT role FROM users WHERE id = :id AND status = 'active'";
            $stmt = $connection->prepare($sql);
            $stmt->execute(['id' => $userId]);
            $result = $stmt->fetch();

            if ($result && $result['role']) {
                return [$result['role']];
            }

            return ['user']; // default role
        } catch (\Exception $e) {
            error_log("Error fetching user roles: " . $e->getMessage());
            return ['user']; // default role
        }
    }

    public function getUserProducts(int $userId): array
    {
        try {
            // Use PostgreSQL database - for now, return default products
            // TODO: Implement proper product assignment system
            $connection = $this->databaseManager->getConnection('default');
            $sql = "SELECT id FROM users WHERE id = :id AND status = 'active'";
            $stmt = $connection->prepare($sql);
            $stmt->execute(['id' => $userId]);

            if ($stmt->fetch()) {
                // Return default products for users
                return [
                    ['productId' => 1, 'productName' => 'Default Product'],
                ];
            }

            return [];
        } catch (\Exception $e) {
            error_log("Error fetching user products: " . $e->getMessage());
            return [];
        }
    }

    public function getTeams(): array
    {
        try {
            // Use PostgreSQL database directly
            $connection = $this->databaseManager->getConnection('default');
            $sql = 'SELECT DISTINCT ON (name) id as "teamID", name as "teamName" FROM teams ORDER BY name, id';
            $stmt = $connection->prepare($sql);
            $stmt->execute();
            $teams = $stmt->fetchAll();
            
            // Ensure we have the correct array keys (PDO might return lowercase)
            $formattedTeams = [];
            foreach ($teams as $team) {
                $formattedTeams[] = [
                    'teamID' => $team['teamID'] ?? $team['teamid'] ?? '',
                    'teamName' => $team['teamName'] ?? $team['teamname'] ?? '',
                ];
            }
            
            return $formattedTeams;
        } catch (\Exception $e) {
            error_log("Error fetching teams: " . $e->getMessage());
            // Return default teams as fallback
            return [
                ['teamID' => 1, 'teamName' => 'Support'],
                ['teamID' => 2, 'teamName' => 'IT'],
                ['teamID' => 3, 'teamName' => 'Engineering'],
                ['teamID' => 4, 'teamName' => 'QA'],
                ['teamID' => 5, 'teamName' => 'Management'],
            ];
        }
    }
}
