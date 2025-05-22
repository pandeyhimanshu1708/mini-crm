<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Http\Requests\CompanyRequest;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use App\Mail\NewCompanyNotification; // We will create this Mail class

class CompanyController extends Controller
{
    /**
     * Apply middleware to protect routes.
     */
    public function __construct()
    {
        $this->middleware('auth'); // Ensure only authenticated users can access
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $companies = Company::paginate(10); // 10 entries per page
        return view('companies.index', compact('companies'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('companies.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CompanyRequest $request)
    {
        $data = $request->validated();

        if ($request->hasFile('logo')) {
            $logoPath = $request->file('logo')->store('public/logos');
            $data['logo'] = str_replace('public/', '', $logoPath); // Store path relative to storage/app/public
        }

        $company = Company::create($data);

        // Send email notification
        try {
            Mail::to('himanshupandey1708@gmail.com')->send(new NewCompanyNotification($company));
        } catch (\Exception $e) {
            // Log the error if email sending fails
            \Log::error('Failed to send new company notification email: ' . $e->getMessage());
        }


        return redirect()->route('companies.index')
                         ->with('success', 'Company created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Company $company)
    {
        return view('companies.show', compact('company'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Company $company)
    {
        return view('companies.edit', compact('company'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CompanyRequest $request, Company $company)
    {
        $data = $request->validated();

        if ($request->hasFile('logo')) {
            // Delete old logo if exists
            if ($company->logo && Storage::disk('public')->exists('logos/' . $company->logo)) {
                Storage::disk('public')->delete('logos/' . $company->logo);
            }
            $logoPath = $request->file('logo')->store('public/logos');
            $data['logo'] = str_replace('public/', '', $logoPath);
        } elseif (isset($data['logo_removed']) && $data['logo_removed'] === '1') {
            // If logo is explicitly marked for removal
            if ($company->logo && Storage::disk('public')->exists('logos/' . $company->logo)) {
                Storage::disk('public')->delete('logos/' . $company->logo);
            }
            $data['logo'] = null;
        }


        $company->update($data);

        return redirect()->route('companies.index')
                         ->with('success', 'Company updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Company $company)
    {
        if ($company->logo && Storage::disk('public')->exists('logos/' . $company->logo)) {
            Storage::disk('public')->delete('logos/' . $company->logo);
        }

        $company->delete();

        return redirect()->route('companies.index')
                         ->with('success', 'Company deleted successfully.');
    }
}
