<?php

namespace Revdojo\MT\Rules;

use Illuminate\Contracts\Validation\Rule;

class TenantAssignRule implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    protected $request;
    protected $message;
    public function __construct($request)
    {
        $this->request = $request;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        if (!config('tenancy.tenant')) {
            return true;
        }

        return $value == config('tenancy.tenant')->id;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        $tenant = config('tenancy.tenant');
        return "Unable to proceed. Tenant ID is invalid, You are in Tenant '$tenant->name' with id of '$tenant->id'";
    }
}
