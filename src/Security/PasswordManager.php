<?php

declare(strict_types=1);

namespace Tico\Security;

use Tico\Config\Config;

class PasswordManager
{
    private readonly Config $config;

    public function __construct()
    {
        $this->config = Config::getInstance();
    }

    /**
     * Hash a password using PHP's password_hash() function
     */
    public function hash(string $password): string
    {
        $this->config->get('security.password_cost', 12);

        return password_hash($password, PASSWORD_ARGON2ID, [
            'memory_cost' => 65536, // 64 MB
            'time_cost' => 4,       // 4 iterations
            'threads' => 3,         // 3 threads
        ]);
    }

    /**
     * Verify a password against its hash
     */
    public function verify(string $password, string $hash): bool
    {
        return password_verify($password, $hash);
    }

    /**
     * Check if a hash needs to be rehashed (due to cost changes)
     */
    public function needsRehash(string $hash): bool
    {
        $this->config->get('security.password_cost', 12);

        return password_needs_rehash($hash, PASSWORD_ARGON2ID, [
            'memory_cost' => 65536,
            'time_cost' => 4,
            'threads' => 3,
        ]);
    }

    /**
     * Migrate legacy SHA1 passwords to modern hashing
     */
    public function migrateLegacyPassword(string $plainPassword, string $legacySha1Hash): ?string
    {
        // Verify the plain password against the SHA1 hash
        if (sha1($plainPassword) === $legacySha1Hash) {
            // Create a new secure hash
            return $this->hash($plainPassword);
        }

        return null;
    }

    /**
     * Generate a random password
     */
    public function generateRandomPassword(int $length = 12): string
    {
        $characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*';
        $password = '';

        for ($i = 0; $i < $length; $i++) {
            $password .= $characters[random_int(0, strlen($characters) - 1)];
        }

        return $password;
    }

    /**
     * Validate password strength
     */
    public function validatePasswordStrength(string $password): array
    {
        $errors = [];

        if (strlen($password) < 8) {
            $errors[] = 'Password must be at least 8 characters long';
        }

        if (!preg_match('/[a-z]/', $password)) {
            $errors[] = 'Password must contain at least one lowercase letter';
        }

        if (!preg_match('/[A-Z]/', $password)) {
            $errors[] = 'Password must contain at least one uppercase letter';
        }

        if (!preg_match('/\d/', $password)) {
            $errors[] = 'Password must contain at least one number';
        }

        if (!preg_match('/[^a-zA-Z0-9]/', $password)) {
            $errors[] = 'Password must contain at least one special character';
        }

        return $errors;
    }
}
