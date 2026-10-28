<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Site;
use App\Models\Company;
class SiteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $sites = Site::paginate(10);
        return view('admin.sites.index', compact('sites'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $companies = Company::where('is_active', 1)->get(['id', 'name']);
        return view('admin.sites.create', compact('companies'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validate = $request->validate([
            'name' => 'required|string|max:100',
            'address' => 'required|string|min:12|max:2555',

            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',

            'company_id' => [
                'required',
                'integer',
                'exists:companies,id',
            ],

            'is_active' => 'sometimes|boolean',
        ]);
        Site::create($validate);
        return redirect()->route('admin.sites.index')->with('success', 'Site Created');
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
    public function edit(Site $site)
    {
        $companies = Company::where('is_active', 1)->get(['id', 'name']);
        return view('admin.sites.edit', compact('site', 'companies'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Site $site)
    {
        $validate = $request->validate([
            'name' => 'required|string|max:100',
            'address' => 'required|string|min:12|max:2555',

            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',

            'company_id' => [
                'required',
                'integer',
                'exists:companies,id',
            ],

            'is_active' => 'sometimes|boolean',
        ]);
        $site->update($validate);
        return redirect()->route('admin.sites.index')->with('success', 'Site Updated');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
