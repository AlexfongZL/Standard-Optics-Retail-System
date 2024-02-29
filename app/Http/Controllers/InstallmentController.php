<?php

namespace App\Http\Controllers;
use App\Models\Customers;
use App\Models\Sales;
use App\Models\Installments;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;


class InstallmentController extends Controller
{
    public function list(){
        $not_fully_paid_sales = Sales::where('is_paid', false)
                                    ->orderBy('created_at')
                                    ->paginate(10);
        
        // get each sales record's customer name
        foreach ($not_fully_paid_sales as $not_fully_paid_sale){
            if ($not_fully_paid_sale->customers_id != null) {
                $customer = Customers::where('id', $not_fully_paid_sale->customers_id)
                                    ->first(); // Fetch the customer record

                // Check if the customer record exists
                if ($customer) {
                    $not_fully_paid_sale->customer_name = $customer->name; // Assign the customer name to the sales object
                    $not_fully_paid_sale->customer_id = $customer->id;
                }
            }

            if (Installments::where('sales_id', $not_fully_paid_sale->id)->exists()) {
                $not_fully_paid_sale->paid_installment = Installments::where('sales_id', $not_fully_paid_sale->id)
                                                                ->sum('payment_amount');
            } else {
                $not_fully_paid_sale->paid_installment = 0;
            }

            // remaining = price - deposit - sum of payments
            $not_fully_paid_sale->remaining = $not_fully_paid_sale->price - $not_fully_paid_sale->deposit - $not_fully_paid_sale->paid_installment;
        }

        return view('installment.list', compact('not_fully_paid_sales'));
    }

    // update installment
    // CR "U" D
    public function update_installment(Request $request){
        $validator = Validator::make($request->all(), [
            'sales_id' => 'required|exists:sales,id',
            'installment_id' => 'required|exists:installments,id',
        ]);

        if ($validator->fails()) {
            // Return validation errors
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            $installmentUpdate = Installments::where('id', $request->input('installment_id'))
                                            ->where('sales_id', $request->input('sales_id'))
                                            ->firstOrFail();

            $installmentUpdate->update([
                'payment_amount' => $request->input('new_installment_value'),
            ]);

            // Return success response
            return response()->json([
                'message' => 'Installment updated successfully',
            ]);

        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to delete degree'], 500);
        }
    }

    // C "R" UD
    public function fetch_all_installment(Request $request){
        $validator = Validator::make($request->all(), [
            'sales_id' => 'required|exists:sales,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try{
            $installments = Installments::where('sales_id', $request->input('sales_id'))
                            ->select('id', 'payment_amount', 'created_at')
                            ->orderBy('created_at','desc')
                            ->get();

            return response()->json([
                $installments,
            ]);
        }catch (\Exception $e) {
            // Return error message if something went wrong
            return response()->json(['message' => 'Failed to fetch all degree data'], 500);
        }  
    }

    // function to delete degree based on degree id && customer id CRU "D"
    public function delete_installment(Request $request){
        $validator = Validator::make($request->all(), [
            'sales_id' => 'required|exists:sales,id',
            'installment_id' => 'required|exists:installments,id',
        ]);

        if ($validator->fails()) {
            // Return validation errors
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            $installmentDelete = Installments::where('id', $request->input('installment_id'))
                                            ->where('sales_id', $request->input('sales_id'))
                                            ->firstOrFail();

            $installmentDelete->delete();

            // Return success response
            return response()->json([
                'message' => 'Installment deleted successfully',
            ]);

        } catch (\Exception $e) {
            // Return error response if deletion fails
            return response()->json(['error' => 'Failed to delete installment'], 500);
        }
    }

    // to add new installment into a particular sale "C" RUD
    public function add_new_installment(Request $request){
        $validator = Validator::make($request->all(), [
            'id' => 'required|exists:sales,id',
            'payment_amount' => 'required|numeric|between:0,99999',
        ]);

        // Check if the validation fails
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try{
            Installments::create([
                'sales_id' => $request->input('id'),
                'payment_amount' => $request->input('payment_amount', 0.0),
            ]);

            return response()->json([
                // 'message' => 'Data saved successfully',
                'id' => $request->input('id'),
                'payment_amount' => $request->input('payment_amount'),
            ]);
        } catch (\Exception $e) {
            // Return error message if something went wrong
            return response()->json(['message' => 'Failed to save data'], 500);
        }       
    }
}
