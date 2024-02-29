<?php

namespace App\Http\Controllers;
use App\Models\Customers;
use App\Models\Sales;
use App\Models\Degrees;
use App\Models\Installments;
use Illuminate\Http\Request;
// use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Validator;

class SaleController extends Controller
{
    private function validateId($id)
    {
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

    // to create installment record
    public function installment($sales_id,$sales_payment){
        Installments::create([
            'sales_id' => $sales_id,
            'payment_amount' => $sales_payment,
        ]);
    }

    // create new sale
    public function store(Request $request){
        $customerId = $request->input('customerId');
        $name = $request->input('name');
        $ic_passport_num = $request->input('ic_passport_num');
        $telephone_num = $request->input('telephone_num');
        $address = $request->input('address');
        $currentLeftEyeDegree = $request->input('left_eye_degree');
        $currentRightEyeDegree = $request->input('right_eye_degree');
        $remarks = $request->input('remarks');

        $description = $request->input('description');
        $price = $request->input('price');
        $is_paid = $request->input('is_paid');
        $deposit = $request->input('deposit');
        // $sale_remaining = $request->input('sale_remaining');

        $latestDegrees = Degrees::where('customers_id', $customerId)
                                ->orderBy('created_at', 'desc')
                                ->first();
        
        // if existing customer details is insert...
        if ($customerId !== null && Customers::where('id', $customerId)->exists())
        {
            Sales::create(array_merge(
                $request->validate([
                    'description' => 'nullable|string|max:255',
                    // 'price' => 'required|numeric|max:10',
                    'price' => 'required|numeric|between:0,99999',
                    'is_paid' => 'required|boolean',
                    // 'deposit' => 'nullable|numeric|max:10',
                    'deposit' => 'nullable|numeric|between:0,99999',
                ]),
                ['customers_id' => $customerId, 'fully_paid_date' => $is_paid ? now() : null]
            ));

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
            ]);

        }else{
            Sales::create(array_merge(
                $request->validate([
                    'description' => 'nullable|string|max:255',
                    // 'price' => 'required|numeric|max:10',
                    'price' => 'required|numeric|between:0,99999',
                    'is_paid' => 'required|boolean',
                    // 'deposit' => 'nullable|numeric|max:10',
                    'deposit' => 'nullable|numeric|between:0,99999',
                ]),
                ['fully_paid_date' => $is_paid ? now() : null]
            ));

            return response()->json([
                'message' => 'Sales Data Added Successfully without customer id.',
            ]);
        }

        // return Redirect::route('sale.list')->with('data', json($results));
        // else{// new customer, new sale
        //     // @@@@@@@@@@@@@@@ CUSTOMER @@@@@@@@@@@@@@@ 

        //     // $new_customer = Customers::create(array_merge(
        //     //     $request->validate([
        //     //         'name' => 'nullable|string|max:255',
        //     //         'ic_passport_num' => 'nullable|string|max:30',
        //     //         'telephone_num' => 'nullable|string|max:30',
        //     //         'address' => 'nullable|string|max:255',
        //     //         'remarks' => 'nullable|string|max:255'
        //     //     ]),
        //     //     ['created_at' => now()]
        //     // ));
            
        //     $validated_customer_data = $request->validate([
        //         'name' => 'nullable|string|max:255',
        //         'ic_passport_num' => 'nullable|string|max:30',
        //         'telephone_num' => 'nullable|string|max:30',
        //         'address' => 'nullable|string|max:255',
        //         'remarks' => 'nullable|string|max:255'
        //     ]);

        //     $validated_customer_data = array_map('rtrim', $validated_customer_data);

        //     $new_customer = Customers::create(array_merge(
        //         $validated_customer_data,
        //         ['created_at' => now()]
        //     ));
        //     // @@@@@@@@@@@@@@@ CUSTOMER END @@@@@@@@@@@@@@@ 




        //     // @@@@@@@@@@@@@@@ SALES @@@@@@@@@@@@@@@ 
            
        //     // Sales::create(array_merge(
        //     //     $request->validate([
        //     //         'description' => 'nullable|string|max:255',
        //     //         'price' => 'required|numeric',
        //     //         'is_paid' => 'nullable|string|max:30',
        //     //         'deposit' => 'required|numeric',
        //     //     ]),
        //     //     ['customers_id' => $new_customer->id, 'created_at' => now()]
        //     // ));
        //     $validated_sale_data = $request->validate([
        //         'description' => 'nullable|string|max:255',
        //         'price' => 'required|numeric',
        //         'is_paid' => 'nullable|string|max:30',
        //         'deposit' => 'required|numeric',
        //     ]);

        //     $validated_sale_data = array_map('rtrim', $validated_sale_data);

        //     Saless::create(array_merge(
        //         $validated_sale_data,
        //         ['customers_id' => $new_customer->id, 'created_at' => now()]
        //     ));

        //     // @@@@@@@@@@@@@@@ SALES END @@@@@@@@@@@@@@@ 




        //     // @@@@@@@@@@@@@@@ DEGREE @@@@@@@@@@@@@@@ 
        //     if($request->input('left_eye_degree') || $request->input('right_eye_degree') != null){
        //         $validated_customer_degree = $request->validate([
        //             'left_eye_degree' => 'nullable|string|max:255',
        //             'right_eye_degree' => 'nullable|string|max:255',
        //         ]);

        //         $validated_customer_degree = array_map('rtrim', $validated_customer_degree);
    
        //         Degrees::create([
        //             'customers_id' => $new_customer->id,
        //             'left_eye_degree' => $validated_customer_degree['left_eye_degree'],
        //             'right_eye_degree' => $validated_customer_degree['right_eye_degree'],
        //         ]);
        //     }

        //     // @@@@@@@@@@@@@@@ DEGREE END @@@@@@@@@@@@@@@ 
        // }
        
    }

    // to see the sale's detail
    public function detail($id)
    {
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
                                                ->select('name','telephone_num')
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

}
