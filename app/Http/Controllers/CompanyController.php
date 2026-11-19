<?php

namespace App\Http\Controllers;

use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;

class CompanyController extends Controller
{
    public function index()
    {
        $companies = Company::latest()->get();

        return view('companies.index', compact('companies'));
    }

    // Show create form
    public function create()
    {
        return view('companies.create');
    }

    // Store company
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:companies,name',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        Company::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name) . '-' . Str::lower(Str::random(5)),
        ]);

        return redirect()->route('companies.index')
            ->with('success', 'Company created successfully!');
    }
}
