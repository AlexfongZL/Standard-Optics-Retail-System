<?php

namespace App\Http\Controllers;
use App\Models\Customers;
use App\Models\Sales;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SaleController extends Controller
{
    public function list(){
        $sales = Sales::orderBy('created_at', 'desc')
                        ->paginate(7);

        foreach ($sales as $sale) {
            if ($sale->customers_id != null) {
                $customer = Customers::where('id', $sale->customers_id)->first(); // Fetch the customer record
        
                // Check if the customer record exists
                if ($customer) {
                    $sale->customer_name = $customer->name; // Assign the customer name to the sales object
                    $sale->customer_id = $customer->id;
                }
            }
        }

        return view('sale.list', compact('sales'));
        // return view('sale.list');
    }
}
