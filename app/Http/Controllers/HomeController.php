<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Company; 
use App\Models\Employee; 
use Illuminate\Support\Facades\DB; 

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $totalCompanies = Company::count();
        $totalEmployees = Employee::count();

        
        $companiesWithEmail = Company::whereNotNull('email')->count();
        $companiesWithoutEmail = Company::whereNull('email')->count();

       
        $recentCompanies = Company::orderBy('created_at', 'desc')->limit(5)->get();
        $recentEmployees = Employee::orderBy('created_at', 'desc')->limit(5)->get();

        return view('home', compact(
            'totalCompanies',
            'totalEmployees',
            'companiesWithEmail',
            'companiesWithoutEmail',
            'recentCompanies',
            'recentEmployees'
        ));
    }
}
