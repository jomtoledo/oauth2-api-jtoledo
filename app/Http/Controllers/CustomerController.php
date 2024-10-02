<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Customer;

class CustomerController extends Controller
{
    /**
     * Display the customers management page
     */
    public function index()
    {
        $customers = Customer::all() ?: [];
        // if (request()->is('api/*')) {
        //     return response()->json($customers);
        // }
        return view('customer.index', compact('customers')); 
    }
}
