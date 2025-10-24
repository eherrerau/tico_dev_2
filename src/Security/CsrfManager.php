<?php

declare(strict_types=1);

namespace Tico\Security;

use Tico\Config\Config;

class CsrfManager
{
    private const TOKEN_LENGTH = 32;
    private readonly string $sessionKey;

    public function __construct()
    {
        $config = Config::getInstance();
        $this->sessionKey = $config->get('security.csrf_token_name', 'csrf_token');

        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    /**
     * Generate a new CSRF token
     */
    public function generateToken(): string
    {
        $token = bin2hex(random_bytes(self::TOKEN_LENGTH));
        $_SESSION[$this->sessionKey] = $token;
        return $token;
    }

    /**
     * Get the current CSRF token
     */
    public function getToken(): string
    {
        if (!isset($_SESSION[$this->sessionKey])) {
            return $this->generateToken();
        }

        return $_SESSION[$this->sessionKey];
    }

    /**
     * Validate a CSRF token
     */
    public function validateToken(string $token): bool
    {
        if (!isset($_SESSION[$this->sessionKey])) {
            return false;
        }

        return hash_equals($_SESSION[$this->sessionKey], $token);
    }

    /**
     * Generate HTML input field for CSRF token
     */
    public function getTokenField(): string
    {
        $token = $this->getToken();
        return sprintf(
            '<input type="hidden" name="%s" value="%s">',
            htmlspecialchars($this->sessionKey, ENT_QUOTES, 'UTF-8'),
            htmlspecialchars($token, ENT_QUOTES, 'UTF-8')
        );
    }

    /**
     * Get token for AJAX requests
     */
    public function getTokenForAjax(): array
    {
        return [
            'name' => $this->sessionKey,
            'value' => $this->getToken(),
        ];
    }

    /**
     * Regenerate token (call after successful form submission)
     */
    public function regenerateToken(): string
    {
        unset($_SESSION[$this->sessionKey]);
        return $this->generateToken();
    }
}
