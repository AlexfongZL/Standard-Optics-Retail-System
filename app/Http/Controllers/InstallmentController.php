<?php

namespace App\Http\Controllers;
use App\Models\Customers;
use App\Models\Sales;
use App\Models\Installments;
use Illuminate\Support\Facades\Log;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;


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

    // update installment, CR "U" D
    public function update_installment(Request $request){
        $timestamp = strtotime($request->input('new_installment_date'));

        // Check if the conversion was successful
        if ($timestamp === false) {
            return response()->json(['error' => 'Invalid date format.'], 400);
        }

        // Replace the original date string with the timestamp in the request data
        $request->merge(['new_installment_date' => $timestamp]);

        $validator = Validator::make($request->all(), [
            'sales_id' => 'required|exists:sales,id',
            'installment_id' => 'required|exists:installments,id',
            'new_installment_date' => 'nullable|integer|between:0,2147483647',
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
                'created_at' => $request->input('new_installment_date'),
                'updated_at' => $request->input('new_installment_date'),
            ]);

            $this->update_is_paid_status($request->input('sales_id'));

            // Return success response
            return response()->json([
                'message' => 'Installment updated successfully. 分期付款更新成功。',
                'status' => 'success',
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to update installment. 更新分期付款失败。',
                'status' => 'failure',
            ], 500);
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
            return response()->json(['message' => 'Failed to fetch all installment data'], 500);
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

            $this->update_is_paid_status($request->input('sales_id'));

            // Return success response
            return response()->json([
                'message' => 'Installment deleted successfully',
                'sales_id' => $request->input('sales_id'),
            ]);

        } catch (\Exception $e) {
            // Return error response if deletion fails
            return response()->json(['error' => 'Failed to delete installment'], 500);
        }
    }

    // to add new installment into a particular sale "C" RUD
    public function add_new_installment(Request $request){
        $installment_date = strtotime($request->input('installment_date'));

        $validator = Validator::make([
            'sales_id' => $request->input('sales_id'),
            'payment_amount' => $request->input('payment_amount'),
            'installment_date' => $installment_date,
        ], [
            'sales_id' => 'required|exists:sales,id',
            'payment_amount' => 'required|numeric|between:0,99999',
            'installment_date' => 'required|integer|between:0,2147483647',
        ]);

        // Check if the validation fails
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $installment_dateCarbon = Carbon::createFromTimestamp($installment_date);

        try{
            $sale_is_paid_status = Sales::where('id', $request->input('sales_id'))->value('is_paid');

            // if the sale is not fully paid, only then can update installment
            if(!$sale_is_paid_status){
                // create/pay new installment
                $installment = Installments::create([
                    'sales_id' => $request->input('sales_id'),
                    'payment_amount' => $request->input('payment_amount', 0.0),
                ]);

                $installment->created_at = $installment_dateCarbon;
                $installment->updated_at = $installment_dateCarbon;
                $installment->save();

                $this->update_is_paid_status($request->input('sales_id'));

                return response()->json([
                    'message' => 'New installment saved successfully. 新分期付款已成功保存。',
                    'status' => 'success',
                ]);
            }else{
                return response()->json([
                    'message' => 'The sale has been fully paid, unable to add any installment. 销售款项已全额支付，无法添加任何分期付款。',
                    'status' => 'failure',
                ]);
            }

        } catch (\Exception $e) {
            // Return error message if something went wrong
            return response()->json(['message' => 'Failed to save data'], 500);
        }       
    }

    // function to update is_paid_status in installment table
    private function update_is_paid_status(int $saleId): void{
        try {
            // Retrieve sale details, ensuring necessary fields are present
            $sale = Sales::where('id', $saleId)
                        ->firstOrFail();

            // Calculate total installment paid
            $total_installment_paid_value = Installments::where('sales_id', $saleId)
                                                ->sum('payment_amount');

            // Validate price, deposit, and payment amounts
            $full_price_value = $sale ? ($sale->price ?? 0.00) : 0.00;
            $deposit_paid_value = $sale ? ($sale->deposit ?? 0.00) : 0.00;
            $total_installment_paid_value = $total_installment_paid_value ? ($total_installment_paid_value ?? 0.00) : 0.00;

            $is_paid = ($deposit_paid_value + $total_installment_paid_value >= $full_price_value) ? 1 : 0;
            
            if ($sale->is_paid !== $is_paid) {
                $sale->is_paid = $is_paid;
                $sale->save();
            }
        } catch (Exception $e) {
            echo 'Error updating is_paid status: ' . $e->getMessage();
        }
    }
}