<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function search(Request $request){
        $date = $request['date'];
        $customer = Customer::orderby('time')->where('date', $date)->get();
        return response()->json($customer,200);
    }
}
