<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Admin_Fixed;

class AdminController extends Controller
{
    public function dashboard()
    {
        return view('admin.dashboard');
    }

    public function index()
    {
        
    
        $fixed = Admin_Fixed::first();
 
        //
        //$orders="";
     //   dd($fixed);
        return view('admin.showFixed',['money'=>$fixed]);

    }


    public function create(Request $request)
    {
        return view('admin.fixedMoney');
         
       
  
    }
    public function store(Request $request)
    { 
        $money=Admin_Fixed::create(['fixedMoney'=>$request->fixedMoney]);
        //return view('admin.showFixed');
        return redirect()->route('admin.showFixed')->with('success', 'Order is created successfully.');

         
       
  
    }
}
