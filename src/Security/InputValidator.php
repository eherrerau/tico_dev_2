<?php

declare(strict_types=1);

namespace Tico\Security;

use Respect\Validation\Validator as v;

class InputValidator
{
    /**
     * Sanitize and validate input data
     */
    public function sanitize(array $data, array $rules): array
    {
        $sanitized = [];
        $errors = [];

        foreach ($rules as $field => $rule) {
            $value = $data[$field] ?? null;

            try {
                // Apply sanitization first
                $sanitizedValue = $this->applySanitization($value, $rule);

                // Then apply validation
                $this->applyValidation($sanitizedValue, $rule, $field);

                $sanitized[$field] = $sanitizedValue;
            } catch (\InvalidArgumentException $e) {
                $errors[$field] = $e->getMessage();
            }
        }

        if ($errors !== []) {
            throw new \InvalidArgumentException('Validation failed: ' . json_encode($errors));
        }

        return $sanitized;
    }

    private function applySanitization($value, array $rule)
    {
        if ($value === null || $value === '') {
            return $value;
        }

        $sanitizers = $rule['sanitize'] ?? [];

        foreach ($sanitizers as $sanitizer) {
            switch ($sanitizer) {
                case 'trim':
                    $value = trim((string) $value);
                    break;
                case 'strip_tags':
                    $value = strip_tags((string) $value);
                    break;
                case 'htmlspecialchars':
                    $value = htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
                    break;
                case 'filter_email':
                    $value = filter_var($value, FILTER_SANITIZE_EMAIL);
                    break;
                case 'filter_url':
                    $value = filter_var($value, FILTER_SANITIZE_URL);
                    break;
                case 'alphanumeric_only':
                    $value = preg_replace('/[^a-zA-Z0-9]/', '', (string) $value);
                    break;
                case 'numbers_only':
                    $value = preg_replace('/[^0-9]/', '', (string) $value);
                    break;
            }
        }

        return $value;
    }

    private function applyValidation($value, array $rule, string $field): void
    {
        $validators = $rule['validate'] ?? [];

        foreach ($validators as $validatorName => $validatorRule) {
            $validator = $this->createValidator($validatorName, $validatorRule);

            if (!$validator->validate($value)) {
                throw new \InvalidArgumentException("Field '{$field}' failed validation: {$validatorName}");
            }
        }
    }

    private function createValidator(string $name, $rule): v
    {
        switch ($name) {
            case 'required':
                return v::notEmpty();

            case 'email':
                return v::email();

            case 'length':
                if (is_array($rule)) {
                    return v::length($rule['min'] ?? null, $rule['max'] ?? null);
                }
                return v::length(null, $rule);

            case 'numeric':
                return v::numeric();

            case 'integer':
                return v::intVal();

            case 'alpha':
                return v::alpha();

            case 'alphanumeric':
                return v::alnum();

            case 'min':
                return v::min($rule);

            case 'max':
                return v::max($rule);

            case 'in':
                return v::in($rule);

            case 'regex':
                return v::regex($rule);

            case 'url':
                return v::url();

            case 'date':
                return v::date($rule ?? 'Y-m-d');

            case 'phone':
                return v::phone();

            case 'sql_injection':
                return v::callback(function ($value): bool {
                    $suspiciousPatterns = [
                        '/(\bselect\b|\binsert\b|\bupdate\b|\bdelete\b|\bdrop\b|\bcreate\b|\balter\b|\bexec\b|\bunion\b)/i',
                        '/(\-\-|\#|\/\*|\*\/)/i',
                        '/(\bor\b|\band\b).*(\=|\<|\>)/i',
                        '/(\;|\||&&)/i',
                    ];

                    foreach ($suspiciousPatterns as $suspiciouPattern) {
                        if (preg_match($suspiciouPattern, $value)) {
                            return false;
                        }
                    }
                    return true;
                });

            default:
                throw new \InvalidArgumentException("Unknown validator: {$name}");
        }
    }

    /**
     * Validate user login data
     */
    public function validateLogin(array $data): array
    {
        $rules = [
            'username' => [
                'sanitize' => ['trim', 'strip_tags'],
                'validate' => [
                    'required' => true,
                    'length' => ['min' => 3, 'max' => 50],
                    'alphanumeric' => true,
                    'sql_injection' => true,
                ],
            ],
            'password' => [
                'sanitize' => ['trim'],
                'validate' => [
                    'required' => true,
                    'length' => ['min' => 1, 'max' => 255],
                ],
            ],
            'team_id' => [
                'sanitize' => ['trim', 'strip_tags'],
                'validate' => [
                    'required' => true,
                ],
            ],
        ];

        return $this->sanitize($data, $rules);
    }

    /**
     * Validate case data
     */
    public function validateCase(array $data): array
    {
        $rules = [
            'case_number' => [
                'sanitize' => ['trim', 'alphanumeric_only'],
                'validate' => [
                    'required' => true,
                    'length' => ['min' => 1, 'max' => 20],
                    'sql_injection' => true,
                ],
            ],
            'engineer_id' => [
                'sanitize' => ['numbers_only'],
                'validate' => [
                    'required' => true,
                    'integer' => true,
                    'min' => 1,
                ],
            ],
            'product_id' => [
                'sanitize' => ['numbers_only'],
                'validate' => [
                    'integer' => true,
                    'min' => 1,
                ],
            ],
            'priority' => [
                'sanitize' => ['trim'],
                'validate' => [
                    'in' => ['low', 'medium', 'high', 'critical'],
                ],
            ],
        ];

        return $this->sanitize($data, $rules);
    }

    /**
     * Validate user profile data
     */
    public function validateUserProfile(array $data): array
    {
        $rules = [
            'name' => [
                'sanitize' => ['trim', 'htmlspecialchars'],
                'validate' => [
                    'required' => true,
                    'length' => ['min' => 2, 'max' => 100],
                    'sql_injection' => true,
                ],
            ],
            'email' => [
                'sanitize' => ['trim', 'filter_email'],
                'validate' => [
                    'required' => true,
                    'email' => true,
                    'length' => ['max' => 255],
                ],
            ],
            'phone' => [
                'sanitize' => ['trim'],
                'validate' => [
                    'length' => ['max' => 20],
                    'regex' => '/^[\+]?[0-9\s\-\(\)]+$/',
                ],
            ],
            'timezone' => [
                'sanitize' => ['trim'],
                'validate' => [
                    'length' => ['max' => 50],
                    'regex' => '/^[A-Za-z_\/]+$/',
                ],
            ],
        ];

        return $this->sanitize($data, $rules);
    }
}
