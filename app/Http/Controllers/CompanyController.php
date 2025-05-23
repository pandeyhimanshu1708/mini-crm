<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Http\Requests\CompanyRequest;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Mail;
use App\Mail\NewCompanyNotification;
use Symfony\Component\HttpFoundation\StreamedResponse;
use App\Models\Attachment;
use App\Models\Note;


class CompanyController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $companies = Company::paginate(10);
        return view('companies.index', compact('companies'));
    }

    public function create()
    {
        return view('companies.create');
    }

    public function store(CompanyRequest $request)
    {
        $data = $request->validated();

        if ($request->hasFile('logo')) {
            $logoPath = $request->file('logo')->store('public/logos');
            $data['logo'] = str_replace('public/', '', $logoPath);
        }

        $company = Company::create($data);

        try {
            Mail::to('himanshupandey1708@gmail.com')->send(new NewCompanyNotification($company));
        } catch (\Exception $e) {
            \Log::error('Failed to send new company notification email: ' . $e->getMessage());
        }

        return redirect()->route('companies.index')
                         ->with('success', 'Company created successfully.');
    }

    public function show(Company $company)
    {
        // Eager load notes and attachments with their users for display
        $company->load(['notes.user', 'attachments.user']);
        return view('companies.show', compact('company'));
    }

    public function edit(Company $company)
    {
        return view('companies.edit', compact('company'));
    }

    public function update(CompanyRequest $request, Company $company)
    {
        $data = $request->validated();

        if ($request->hasFile('logo')) {
            if ($company->logo && Storage::disk('public')->exists('logos/' . $company->logo)) {
                Storage::disk('public')->delete('logos/' . $company->logo);
            }
            $logoPath = $request->file('logo')->store('public/logos');
            $data['logo'] = str_replace('public/', '', $logoPath);
        } elseif (isset($data['logo_removed']) && $data['logo_removed'] === '1') {
            if ($company->logo && Storage::disk('public')->exists('logos/' . $company->logo)) {
                Storage::disk('public')->delete('logos/' . $company->logo);
            }
            $data['logo'] = null;
        }

        $company->update($data);

        return redirect()->route('companies.index')
                         ->with('success', 'Company updated successfully.');
    }

    public function destroy(Company $company)
    {
        if ($company->logo && Storage::disk('public')->exists('logos/' . $company->logo)) {
            Storage::disk('public')->delete('logos/' . $company->logo);
        }

        // Delete associated notes and attachments
        $company->notes()->delete();
        foreach ($company->attachments as $attachment) {
            if (Storage::disk('public')->exists($attachment->path)) {
                Storage::disk('public')->delete($attachment->path);
            }
            $attachment->delete();
        }

        $company->delete();

        return redirect()->route('companies.index')
                         ->with('success', 'Company deleted successfully.');
    }

    /**
     * Export companies data to CSV.
     */
    public function exportCsv()
    {
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="companies.csv"',
        ];

        $callback = function() {
            $companies = Company::all();
            $file = fopen('php://output', 'w');

           
            fputcsv($file, ['ID', 'Name', 'Email', 'Website', 'Created At', 'Updated At']);

            foreach ($companies as $company) {
                fputcsv($file, [
                    $company->id,
                    $company->name,
                    $company->email,
                    $company->website,
                    $company->created_at,
                    $company->updated_at,
                ]);
            }
            fclose($file);
        };

        return new StreamedResponse($callback, 200, $headers);
    }
}