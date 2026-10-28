<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Company;

class CompanyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $companies = Company::orderByDesc('id')->paginate(10);
        return view('admin.companies.index',compact('companies'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.companies.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'=>'required|string|max:255',
            'notes'=>'nullable|string|max:255',
            'code'=>'required|string|max:10',
            'is_active'=>'nullable|boolean'
        ]);
        Company::create($validated);
        return redirect()->route('admin.companies.index')->with('success','Company Created.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Company $company)
    {
        return view('admin.companies.edit',compact('company'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Company $company)
    {
        $validate = $request->validate([
            'name'=>'required|string|max:255',
            'notes'=>'nullable|string|max:255',
            'code'=>'required|string|max:10',
            'is_active'=>'nullable|boolean'
        ]);
        $company->update($validate);
        return redirect()->route('admin.companies.index')->with('success','Company Updated.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
