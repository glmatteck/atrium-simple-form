<?php
namespace App\Helpers;

class Validator
{
    private array $rules = [];
    private array $errors = [];

    public function addRule(string $field, string $rules, string $message = '')
    {
        $this->rules[$field] = [
            'rules' => $rules,
            'message' => $message
        ];
    }

    public function removeRule(string $field)
    {
        unset($this->rules[$field]);
    }

    public function validate(array $data): array
    {
        $this->errors = [];

        foreach ($this->rules as $field => $ruleSet) {
            $value = $data[$field] ?? null;
            $rules = explode('|', $ruleSet['rules']);
            $message = $ruleSet['message'];

            foreach ($rules as $rule) {
                if (!$this->applyRule($field, $value, $rule, $message)) {
                    break; // Stop checking other rules for this field if one fails
                }
            }
        }

        return $this->errors;
    }

    private function applyRule(string $field, $value, string $rule, string $message): bool
    {
        // Parse rule and parameters (e.g., "max:30" becomes ["max", "30"])
        $params = explode(':', $rule);
        $ruleName = $params[0];
        $ruleValue = $params[1] ?? null;

        switch ($ruleName) {
            case 'required':
                if (empty($value) && $value !== '0') {
                    $this->errors[$field] = $message ?: "The {$field} field is required.";
                    return false;
                }
                break;

            case 'optional':
                if (empty($value) && $value !== '0') {
                    return true; // Skip other validations if optional and empty
                }
                break;

            case 'numeric':
                if (!is_numeric($value)) {
                    $this->errors[$field] = $message ?: "The {$field} field must be numeric.";
                    return false;
                }
                break;

            case 'alpha':
                if (!ctype_alpha($value)) {
                    $this->errors[$field] = $message ?: "The {$field} field must contain only letters.";
                    return false;
                }
                break;

            case 'in':
                $allowedValues = explode(',', $ruleValue);
                if (!in_array($value, $allowedValues)) {
                    $this->errors[$field] = $message ?: "The {$field} field must be one of: " . implode(', ', $allowedValues);
                    return false;
                }
                break;

            case 'max':
                if (is_string($value) && strlen($value) > (int)$ruleValue) {
                    $this->errors[$field] = $message ?: "The {$field} field must not exceed {$ruleValue} characters.";
                    return false;
                }
                if (is_numeric($value) && $value > (float)$ruleValue) {
                    $this->errors[$field] = $message ?: "The {$field} field must not exceed {$ruleValue}.";
                    return false;
                }
                break;

            case 'min':
                if (is_string($value) && strlen($value) < (int)$ruleValue) {
                    $this->errors[$field] = $message ?: "The {$field} field must be at least {$ruleValue} characters.";
                    return false;
                }
                if (is_numeric($value) && $value < (float)$ruleValue) {
                    $this->errors[$field] = $message ?: "The {$field} field must be at least {$ruleValue}.";
                    return false;
                }
                break;

            case 'email':
                if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
                    $this->errors[$field] = $message ?: "The {$field} field must be a valid email address.";
                    return false;
                }
                break;

            case 'url':
                if (!filter_var($value, FILTER_VALIDATE_URL)) {
                    $this->errors[$field] = $message ?: "The {$field} field must be a valid URL.";
                    return false;
                }
                break;
        }

        return true;
    }

    public function getErrors(): array
    {
        return $this->errors;
    }

    public function hasErrors(): bool
    {
        return !empty($this->errors);
    }
}