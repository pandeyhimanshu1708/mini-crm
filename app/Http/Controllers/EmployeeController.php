<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Company;
use App\Http\Requests\EmployeeRequest;
use Symfony\Component\HttpFoundation\StreamedResponse; 
use Illuminate\Support\Facades\Storage; 
use App\Models\Attachment; 
use App\Models\Note; 

class EmployeeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $employees = Employee::with('company')->paginate(10);
        return view('employees.index', compact('employees'));
    }

    public function create()
    {
        $companies = Company::orderBy('name')->get();
        return view('employees.create', compact('companies'));
    }

    public function store(EmployeeRequest $request)
    {
        Employee::create($request->validated());

        return redirect()->route('employees.index')
                         ->with('success', 'Employee created successfully.');
    }

    public function show(Employee $employee)
    {
        // Eager load notes and attachments with their users for display
        $employee->load(['notes.user', 'attachments.user']);
        return view('employees.show', compact('employee'));
    }

    public function edit(Employee $employee)
    {
        $companies = Company::orderBy('name')->get();
        return view('employees.edit', compact('employee', 'companies'));
    }

    public function update(EmployeeRequest $request, Employee $employee)
    {
        $employee->update($request->validated());

        return redirect()->route('employees.index')
                         ->with('success', 'Employee updated successfully.');
    }

    public function destroy(Employee $employee)
    {
        // Delete associated notes and attachments
        $employee->notes()->delete();
        foreach ($employee->attachments as $attachment) {
            if (Storage::disk('public')->exists($attachment->path)) {
                Storage::disk('public')->delete($attachment->path);
            }
            $attachment->delete();
        }

        $employee->delete();

        return redirect()->route('employees.index')
                         ->with('success', 'Employee deleted successfully.');
    }

    /**
     * Export employees data to CSV.
     */
    public function exportCsv()
    {
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="employees.csv"',
        ];

        $callback = function() {
            $employees = Employee::with('company')->get(); 
            $file = fopen('php://output', 'w');

            
            fputcsv($file, ['ID', 'First Name', 'Last Name', 'Company', 'Email', 'Phone', 'Created At', 'Updated At']);

            foreach ($employees as $employee) {
                fputcsv($file, [
                    $employee->id,
                    $employee->first_name,
                    $employee->last_name,
                    $employee->company->name ?? 'N/A', 
                    $employee->email,
                    $employee->phone,
                    $employee->created_at,
                    $employee->updated_at,
                ]);
            }
            fclose($file);
        };

        return new StreamedResponse($callback, 200, $headers);
    }
}