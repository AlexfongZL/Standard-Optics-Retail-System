<?php

namespace App\Http\Controllers;
use App\Models\Customers;
use App\Models\Sales;
use App\Models\Degrees;
use App\Models\Installments;
use Illuminate\Http\Request;
// use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class SaleController extends Controller
{
    private function validateId($id){
        // Perform validation or sanitization on the ID as needed
        // Example: validate that the ID is a positive integer
        return (int) $id;
    }

    public function list(){
        $sales = Sales::orderBy('created_at', 'desc')
                        ->paginate(10);

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

    // to create installment record (currently not used, if system thoroughly checked,
    // confirmed not affecting anything, delete these lines)
    
    // public function installment($sales_id,$sales_payment){
    //     Installments::create([
    //         'sales_id' => $sales_id,
    //         'payment_amount' => $sales_payment,
    //     ]);
    // }

    // create new sale
    public function store(Request $request){
        $checkbox = $request->input('checkbox'); // checkbox to input customer details
        $customerId = $request->input('customerId');
        $name = $request->input('name');
        $ic_passport_num = $request->input('ic_passport_num');
        $telephone_num = $request->input('telephone_num');
        $address = $request->input('address');
        $currentLeftEyeDegree = $request->input('left_eye_degree');
        $currentRightEyeDegree = $request->input('right_eye_degree');
        $remarks = $request->input('remarks');

        $sale_date = strtotime($request->input('sale_date'));
        $description = $request->input('description');
        $price = $request->input('price');
        $is_paid = $request->input('is_paid');
        $deposit = $request->input('deposit');
        
        // if "Insert Customer Details" checkbox is ticked
        if($checkbox){
             // if customer exist
            if($customerId !== null){ // if insert customer (existed customer)
                $this->create_sale_function($description, $price, $is_paid, $deposit, $customerId, $sale_date);

                $latestDegrees = Degrees::where('customers_id', $customerId)
                                        ->orderBy('created_at', 'desc')
                                        ->first();
                
                if (!$latestDegrees || $latestDegrees->left_eye_degree !== $currentLeftEyeDegree || $latestDegrees->right_eye_degree !== $currentRightEyeDegree) {
                    // Add the new degrees to the database
                    Degrees::create([
                        'customers_id' => $customerId,
                        'left_eye_degree' => $currentLeftEyeDegree,
                        'right_eye_degree' => $currentRightEyeDegree,
                    ]);
                }
    
                return response()->json([
                    'message' => 'Sales Data Added Successfully with customer id included.',
                    'checkbox' => $request->input('checkbox'),
                    'sale_date' => $sale_date,
                ]);
    
            }else{ // if insert totally new customer (non-existed customer) with details
                $validated_customer_data = $request->validate([
                    'name' => 'nullable|string|max:255',
                    'ic_passport_num' => 'nullable|string|max:30',
                    'telephone_num' => 'nullable|string|max:30',
                    'address' => 'nullable|string|max:255',
                    'remarks' => 'nullable|string|max:255'
                ]);

                // Apply rtrim to all values in $validated_customer_data
                foreach ($validated_customer_data as $key => $value) {
                    // Remove whitespace after the last character in the value
                    $validated_customer_data[$key] = rtrim($value);
                }

                $new_customer = Customers::create($validated_customer_data);

                $this->create_sale_function($description, $price, $is_paid, $deposit, $new_customer->id, $sale_date);

                if($request->left_eye_degree || $request->right_eye_degree != null){
                    // inserting into "degrees" table
                    $new_customer_id = $new_customer->id;
                    $validated_customer_degree = $request->validate([
                        'left_eye_degree' => 'nullable|string|max:255',
                        'right_eye_degree' => 'nullable|string|max:255',
                    ]);

                    // Apply rtrim to all values in $validated_customer_degree
                    foreach ($validated_customer_degree as $key => $value) {
                        // Remove whitespace after the last character in the value
                        $validated_customer_degree[$key] = rtrim($value);
                    }

                    Degrees::create([
                        'customers_id' => $new_customer_id,
                        'left_eye_degree' => $validated_customer_degree['left_eye_degree'],
                        'right_eye_degree' => $validated_customer_degree['right_eye_degree'],
                    ]);
                }

                return response()->json([
                    'message' => 'Sales Data Added Successfully with customer id included.',
                    'checkbox' => $request->input('checkbox'),
                    'sale_date' => $sale_date,
                ]);
            }
        }else{ // if "Insert Customer Details" checkbox is not ticked, customer id is === null            
            $this->create_sale_function($description, $price, $is_paid, $deposit, null, $sale_date);

            return response()->json([
                'message' => 'Sales Data Added Successfully without customer id.',
                'description' => $description,
                'price' => $price,
                'is_paid' => $is_paid,
                'deposit' => $deposit,
                'customerId' => $customerId,
                'sale_date' => $sale_date,
            ]);
        }
    }

    // to see the sale's detail
    public function detail($id){
        // Validate the ID parameter
        $validatedId = $this->validateId($id);

        // Fetch sale details with eager loading for installments
        $sale_details = Sales::where('id', $validatedId)
                            ->first();

        // Check if sale exists and handle potential errors
        if (!$sale_details) {
            return abort(404, 'Sale not found'); // Or handle differently based on your application logic
        }

        $sale_details->customer_details = Customers::where('id', $sale_details->customers_id)
                                                ->select('id','name','telephone_num')
                                                ->first();

        // Order installments if needed (assuming you want them in descending order)
        $installments = Installments::where('sales_id', $validatedId)
                                    ->orderBy('created_at','desc')
                                    ->get();

        // return view('sale.detail', compact('sale_details'));
        return view('sale.detail', [
            'sale_details' => $sale_details,
            'installments' => $installments,
        ]);
    }

    // update installment, CR "U" D.
    public function update_sale(Request $request){

        // if the new_sale_date is provided
        if($request->input('new_sale_date')){
            $timestamp = strtotime($request->input('new_sale_date'));
            
            if ($timestamp === false) {
                return response()->json(['error' => 'Invalid date format.'], 400);
            }

            $request->merge(['new_sale_date' => $timestamp]);
        }else {
            // If new_sale_date is not provided, set it to null or a default value
            $request->merge(['new_sale_date' => null]);
        }    

        $validator = Validator::make($request->all(), [
            'sales_id' => 'required|exists:sales,id',
            'new_sale_date' => 'nullable|integer|between:0,2147483647',
        ]);

        if ($validator->fails()) {
            // Return validation errors
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            $saleUpdate = Sales::where('id', $request->input('sales_id'))->firstOrFail();

            // user want to change sale's full price
            if ($request->input('new_price_value')) {
                $saleUpdate->update([
                    'price' => $request->input('new_price_value'),
                    'created_at' => $request->input('new_sale_date'),
                    'updated_at' => $request->input('new_sale_date'),
                ]);

                return response()->json([
                    'message' => 'The sale price or date has been updated. 销售价格或日期已成功更新。',
                    'status' => 'success',
                ]);
            }

            // user want to change sale's deposit
            if ($request->input('new_deposit_value')) {
                // if deposit is inserted to the sale that have is_paid = 1, then
                // change is_paid = 0.

                $total_installment_paid_value = Installments::where('sales_id', $request->input('sales_id'))
                                                ->sum('payment_amount');

                $full_price_value = $saleUpdate->price;
                $deposit_paid_value = $request->input('new_deposit_value');
                $total_installment_paid_value = $total_installment_paid_value ? ($total_installment_paid_value ?? 0.00) : 0.00;

                $is_paid = ($deposit_paid_value + $total_installment_paid_value >= $full_price_value) ? 1 : 0;

                $saleUpdate->update([
                    'deposit' => $request->input('new_deposit_value'),
                    'is_paid' => $is_paid,
                ]);

                return response()->json([
                    'message' => 'The sale deposit has been updated. 销售抵押金已成功更新。',
                    'status' => 'success',
                ]);
            }

            // user want to change sale's description
            if ($request->input('sale_description')) {
                $saleUpdate->update(['description' => $request->input('sale_description')]);
                return redirect()->route('sale.detail', ['id' => $saleUpdate])->with('success', 'Sale Description Updated Successfully. 销售记录已成功更新。');
            }

            // Return success response
            // return response()->json([
            //     'message' => 'Sale details updated successfully'
            // ]);

        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to update sale details'
            ], 500);
        }
    }

    // function to delete degree based on sale id && customer id
    public function delete_sale(Request $request){
        // to determine whether the request to delete come from 
        // AJAX request (delete from customer detail page) or 
        // non-AJAX request (delete from sale detail page).

        // THE REASON TO DIFFERENTIATE REQUEST MADE FROM AJAX AND NON-AJAX (NORMAL SALE DETAIL)
        // IS BECAUSE IN NON-AJAX PAGE, THERE MAY EXIST SALE THAT DOES NOT HAVE CUSTOMER ID.
        $fromAJAX = $request->input('fromAJAX');

        if($fromAJAX){
            $validator = Validator::make($request->all(), [
                // id = sales id
                'id' => 'required|exists:sales,id',
                'customers_id' => 'required|exists:customers,id', 
            ]);
    
            if ($validator->fails()) {
                // Return validation errors
                return response()->json(['errors' => $validator->errors()], 422);
            }
    
            try {
                // delete sales, without the customer id attached into it
                $sale = Sales::where('id', $request->input('id'))
                                ->where('customers_id', $request->input('customers_id'))
                                ->firstOrFail()
                                ->delete();
    
                // delete the installments that's under the deleted sale
                $saleInstallment = Installments::where('sales_id', $request->input('id'))->first();
    
                if ($saleInstallment) {
                    $saleInstallment->delete();
                }
    
                // Return success response
                    return response()->json([
                        'message' => 'Sale and subsequent installment data deleted successfully',
                        // 'id' => $request->input('customers_id'),
                    ]);
                    
            } catch (\Exception $e) {
                // Return error response if deletion fails
                return response()->json(['error' => 'Failed to delete degree'], 500);
            }
        }else{
            $validator = Validator::make($request->all(), [
                // id = sales id
                'id' => 'required|exists:sales,id'
            ]);
    
            if ($validator->fails()) {
                // Return validation errors
                return response()->json(['errors' => $validator->errors()], 422);
            }
    
            try {
                // delete sales, without the customer id attached into it
                $sale = Sales::where('id', $request->input('id'))
                                ->firstOrFail()
                                ->delete();
    
                // delete the installments that's under the deleted sale
                $saleInstallment = Installments::where('sales_id', $request->input('id'))->first();
    
                if ($saleInstallment) {
                    $saleInstallment->delete();
                }
    
                // Return success response
                return redirect()->route('sale.list')->with('success', 'Sale Successfully Deleted ');
                    
            } catch (\Exception $e) {
                // Return error response if deletion fails
                return response()->json(['error' => 'Failed to delete degree'], 500);
            }
        }
    }

    // A function specifically used to create sale.
    // This function is used by Store() function
    public function create_sale_function(string $description, float $price, bool $is_paid, float $deposit, ?int $customerId, int $sale_date) {
        $validator = Validator::make([
            'description' => $description,
            'price' => $price,
            'is_paid' => $is_paid,
            'deposit' => $deposit,
            'customers_id' => $customerId,
            'sale_date' => $sale_date,
        ], 
        [
            'description' => 'nullable|string|max:255',
            'price' => 'required|numeric|between:0,99999',
            'is_paid' => 'required|boolean',
            'deposit' => 'nullable|numeric|between:0,99999',
            // 'customers_id' => 'required|exists:customers,id',
            'customers_id' => 'nullable',
            'sale_date' => 'required|integer|between:0,2147483647',
        ]);

        if ($validator->fails()) {
            // Validation failed, return error messages or throw an exception
            return $validator->errors();
        }

        // Convert Unix timestamp to Carbon instance
        $sale_dateCarbon = Carbon::createFromTimestamp($sale_date);

        // Validation passed, create the sale
        $sale = Sales::create([
            'description' => $description,
            'price' => $price,
            'is_paid' => $is_paid,
            'deposit' => $deposit,
            'customers_id' => $customerId ?? null,
            'fully_paid_date' => $is_paid ? $sale_dateCarbon : null,
        ]);

        $sale->created_at = $sale_dateCarbon;
        $sale->updated_at = $sale_dateCarbon;
        $sale->save();

        return response()->json(['error' => 'Description of the error'], 422);
    }
}