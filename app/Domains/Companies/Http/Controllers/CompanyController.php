<?php

namespace App\Domains\Companies\Http\Controllers;

use App\Domains\Companies\Http\Requests\StoreCompanyRequest;
use App\Domains\Companies\Http\Requests\UpdateCompanyRequest;
use App\Domains\Companies\Http\Resources\CompanyResource;
use App\Domains\Companies\Models\Company;
use App\Domains\Companies\Services\CompanyService;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class CompanyController extends Controller
{
    public function __construct(private readonly CompanyService $companies)
    {
    }

    public function index(Request $request): AnonymousResourceCollection
    {
        return CompanyResource::collection($this->companies->listForOwner($request->user()));
    }

    public function store(StoreCompanyRequest $request): JsonResponse
    {
        $company = $this->companies->create($request->user(), $request->validated());

        return (new CompanyResource($company))->response()->setStatusCode(201);
    }

    public function show(Request $request, Company $company): CompanyResource
    {
        $this->authorize('view', $company);

        return new CompanyResource($company);
    }

    public function update(UpdateCompanyRequest $request, Company $company): CompanyResource
    {
        $this->authorize('update', $company);

        return new CompanyResource($this->companies->update($company, $request->validated()));
    }
}
