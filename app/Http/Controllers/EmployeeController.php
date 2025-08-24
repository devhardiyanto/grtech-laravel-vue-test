<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreEmployeeRequest;
use App\Http\Requests\UpdateEmployeeRequest;
use App\Http\Resources\EmployeeResource;
use App\Http\Resources\CompanyResource;
use App\Models\Company;
use App\Models\Employee;
use App\Notifications\NewEmployeeNotification;
use Illuminate\Http\Request;
use Inertia\Inertia;

class EmployeeController extends Controller
{
    public function index(Request $request)
    {
        $employees = Employee::with('company')
            ->when($request->search, function ($query, $search) {
                $query->where('first_name', 'like', "%{$search}%")
                    ->orWhere('last_name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            })
            ->when($request->company_id, function ($query, $companyId) {
                $query->where('company_id', $companyId);
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10)
            ->withQueryString();

        return Inertia::render('Employees/Index', [
            'employees' => EmployeeResource::collection($employees),
            'companies' => CompanyResource::collection(Company::all()),
            'filters' => $request->only(['search', 'company_id']),
        ]);
    }

    public function create()
    {
        return Inertia::render('Employees/Create', [
            'companies' => CompanyResource::collection(Company::all()),
        ]);
    }

    public function store(StoreEmployeeRequest $request)
    {
        $employee = Employee::create($request->validated());

        // Send email notification if company has email
        if ($employee->company && $employee->company->email) {
            try {
                $employee->company->notify(new NewEmployeeNotification($employee));
            } catch (\Exception $e) {
                \Log::error('Failed to send email notification: ' . $e->getMessage());
            }
        }

        return redirect()->route('employees.index')
            ->with('success', 'Employee created successfully.');
    }

    public function show(Employee $employee)
    {
        $employee->load('company');
        
        return Inertia::render('Employees/Show', [
            'employee' => new EmployeeResource($employee),
        ]);
    }

    public function edit(Employee $employee)
    {
        return Inertia::render('Employees/Edit', [
            'employee' => new EmployeeResource($employee),
            'companies' => CompanyResource::collection(Company::all()),
        ]);
    }

    public function update(UpdateEmployeeRequest $request, Employee $employee)
    {
        $employee->update($request->validated());

        return redirect()->route('employees.index')
            ->with('success', 'Employee updated successfully.');
    }

    public function destroy(Employee $employee)
    {
        $employee->delete();

        return redirect()->route('employees.index')
            ->with('success', 'Employee deleted successfully.');
    }
}