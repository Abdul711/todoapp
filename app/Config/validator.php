<?php
namespace App\Config;

class Validator
{
    protected array $errors = [];

    public function validate(array $data, array $rules): bool
    {
        foreach ($rules as $field => $ruleList) {
            $rulesArr = explode('|', $ruleList);
            foreach ($rulesArr as $rule) {
                if ($rule === 'required' && empty($data[$field])) {
                    $this->errors[$field][] = 'The ' . $field . ' field is required.';
                }

                if (str_starts_with($rule, 'min:')) {
                    $min = (int) explode(':', $rule)[1];
                    if (strlen($data[$field] ?? '') < $min) {
                        $this->errors[$field][] = "The $field must be at least $min characters.";
                    }
                }

                if (str_starts_with($rule, 'max:')) {
                    $max = (int) explode(':', $rule)[1];
                    if (strlen($data[$field] ?? '') > $max) {
                        $this->errors[$field][] = "The $field may not be greater than $max characters.";
                    }
                }

                if ($rule === 'email' && !filter_var($data[$field] ?? '', FILTER_VALIDATE_EMAIL)) {
                    $this->errors[$field][] = 'The ' . $field . ' must be a valid email address.';
                }
            }
        }

        return empty($this->errors);
    }

    public function errors(): array
    {
        return $this->errors;
    }
}
