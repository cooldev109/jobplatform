<?php

namespace App\Domains\Companies\Policies;

use App\Domains\Companies\Models\Company;
use App\Domains\Users\Models\User;

class CompanyPolicy
{
    public function view(User $user, Company $company): bool
    {
        return $user->id === $company->owner_user_id;
    }

    public function update(User $user, Company $company): bool
    {
        return $user->id === $company->owner_user_id && $user->isCompanyOwner();
    }

    public function delete(User $user, Company $company): bool
    {
        return $user->id === $company->owner_user_id && $user->isCompanyOwner();
    }
}
