<?php

namespace App\Domains\Companies\Services;

use App\Domains\Companies\Models\Company;
use App\Domains\Users\Models\User;
use App\Support\DocumentValidator;
use Illuminate\Database\Eloquent\Collection;

class CompanyService
{
    public function listForOwner(User $owner): Collection
    {
        return $owner->companies()->orderByDesc('created_at')->get();
    }

    public function create(User $owner, array $data): Company
    {
        $data['documento'] = DocumentValidator::normalize($data['documento']);
        $data['owner_user_id'] = $owner->id;

        return Company::create($data);
    }

    public function update(Company $company, array $data): Company
    {
        if (isset($data['documento'])) {
            $data['documento'] = DocumentValidator::normalize($data['documento']);
        }

        $company->update($data);

        return $company->fresh();
    }
}
