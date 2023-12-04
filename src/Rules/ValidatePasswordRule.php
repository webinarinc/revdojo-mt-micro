<?php

namespace Revdojo\MT\Rules;

use Illuminate\Contracts\Validation\Rule;

class ValidatePasswordRule implements Rule
{
    protected $isAtLeastOneLowercase = false;
    protected $isAtLeastOneNumeric = false;
    protected $isAtLeastOneUppercase = false;

    public function passes($attribute, $value)
    {
        return ($this->isAtLeastOneLowercase = (bool)preg_match('/^(?=.*[a-z]).*$/', $value))
            & ($this->isAtLeastOneNumeric = (bool)preg_match('/^(?=.*\d).*$/', $value))
            & ($this->isAtLeastOneUppercase = (bool)preg_match('/^(?=.*[A-Z]).*$/', $value));
    }

    public function message()
    {
        if (!$this->isAtLeastOneNumeric && !$this->isAtLeastOneUppercase && !$this->isAtLeastOneLowercase) {
            return 'Your password must have alphanumeric with at least 1 uppercase, 1 lowercase and 1 numeric.';
        }

        if (!$this->isAtLeastOneNumeric && !$this->isAtLeastOneUppercase) {
            return 'Your password must have at least 1 uppercase and 1 numeric.';
        }

        if (!$this->isAtLeastOneNumeric && !$this->isAtLeastOneLowercase) {
            return 'Your password must have at least 1 lowercase and 1 numeric.';
        }

        if (!$this->isAtLeastOneLowercase && !$this->isAtLeastOneUppercase) {
            return 'Your password must have at least 1 lowercase and 1 uppercase.';
        }

        if (!$this->isAtLeastOneLowercase) {
            return 'Your password must have at least 1 lowercase.';
        }

        if (!$this->isAtLeastOneNumeric) {
            return 'Your password must have at least 1 numeric.';
        }

        return 'Your password must have at least 1 uppercase.';
    }
}
