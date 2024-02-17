<?php

namespace App\Http\Controllers;
use App\Models\Customers;
use App\Models\Degrees;
use App\Models\Sales;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CustomerController extends Controller
{
    // list all the customer detail with pages
    public function list(){
         
        $customers = Customers::orderBy('created_at', 'desc')
                        ->paginate(7);

        foreach ($customers as $customer) {
            // Find the latest degree details for the customer
            $latestDegree = Degrees::where('customers_id', $customer->id)
                                  ->orderBy('created_at', 'desc')
                                  ->first();

            // Assign the latest degree details to the customer
            $customer->latest_left_eye_degree = $latestDegree ? $latestDegree->left_eye_degree : null;
            $customer->latest_right_eye_degree = $latestDegree ? $latestDegree->right_eye_degree : null;
        }

        // return view('customer.list',['customers' => $customers]);
        return view('customer.list', compact('customers'));
    }

    // create new customer
    public function store(Request $request){
        // inserting into "customers" table
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
        

        return view('customer.create');
    }

    // show customer details
    public function detail($id){
        $customer = Customers::findOrFail($id);

        $degrees = Degrees::where('customers_id', $id)
                            ->select('id','left_eye_degree', 'right_eye_degree', 'created_at')
                            ->orderBy('created_at', 'desc')
                            ->get();

        // Assign the latest degree details to the customer
        // $customer->left_eye_degree = $degrees ? $degrees->left_eye_degree : null;
        // $customer->right_eye_degree = $degrees ? $degrees->right_eye_degree : null;

        $sales = Sales::where('customers_id', $id)
                        ->orderBy('created_at', 'desc')
                        ->get();
        
        return view('customer.detail', [
            'sales' => $sales,
            'degrees' => $degrees,
            'customer' => $customer,
        ]);
    }

    // auto suggest in the customer list's search box
    public function suggest(Request $request){
        $query = $request->input('query');

        // Fetch suggested customer names based on the query
        $suggestions = Customers::where('name', 'like', '%' . $query . '%')
                                ->select('id', 'name') // Select id and name fields
                                ->get();

        return response()->json($suggestions);
    }

    // to search all the related name input from the search box and list it all with pages
    public function search(Request $request){
        $query = $request->input('query');

        // Search for customers whose name contains the search query
        $results = Customers::where('name', 'like', '%' . $query . '%')
                            ->paginate(7);

        $results->appends(['query' => $query]);

        foreach ($results as $result) {
            // Find the latest degree details for the customer
            $latestDegree = Degrees::where('customers_id', $result->id)
                                ->orderBy('created_at', 'desc')
                                ->first();

            // Assign the latest degree details to the customer
            $result->latest_left_eye_degree = $latestDegree ? $latestDegree->left_eye_degree : null;
            $result->latest_right_eye_degree = $latestDegree ? $latestDegree->right_eye_degree : null;
        }

        return view('customer.list',['customers' => $results]);
        // return redirect()->route('customer.list', ['customers' => $results]);

    }

    // to update customer details
    public function update(Request $request){
        // Retrieve the customer's ID from the request or any other source
        $customerId = $request->input('id');

        // Retrieve the existing customer record
        $customer = Customers::find($customerId);

        if ($customer) {
            // Get the validated data from the request
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

            // Compare each attribute individually
            $attributesChanged = false;
            foreach ($validated_customer_data as $key => $value) {
                if ($customer->$key != $value) {
                    $attributesChanged = true;
                    break;
                }
            }

            // If any attribute has changed, update the record
            if ($attributesChanged) {
                $customer->update($validated_customer_data + ['updated_at' => now()]);
            }else{
                return redirect()->route('customer.detail', ['id' => $customerId])->with('success', 'No customer details to update.');
            }

            return redirect()->route('customer.detail', ['id' => $customerId])->with('success', 'Customer details updated successfully.');
        }

        else {
            return redirect()->route('customer.list')->withErrors(['error' => 'Error while updating customer details. Please contact the system admin.']);
        }
    }

    // to add new customer degree
    public function add_new_degree(Request $request){

        // Define the validation rules for the JSON data
        $validator = Validator::make($request->all(), [
            'id' => 'required|exists:customers,id',
            'left_eye_degree' => 'nullable|string|max:255',
            'right_eye_degree' => 'nullable|string|max:255',
        ]);

        // Check if the validation fails
        if ($validator->fails()) {
            // Return validation errors
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try{
            // Create a new Degree record
            Degrees::create([
                'customers_id' => $request->input('id'),
                'left_eye_degree' => $request->input('leftEyeDegree'),
                'right_eye_degree' => $request->input('rightEyeDegree'),
                'created_at' => now(),
            ]);

            return response()->json([
                'message' => 'Data saved successfully',
                'id' => $request->input('id'),
            ]);
        } catch (\Exception $e) {
            // Return error message if something went wrong
            return response()->json(['message' => 'Failed to save data'], 500);
        }       
    }

    // fetch all customer degree.
    // This function is called from javascript after user add new customer degree
    public function fetch_all_degree(Request $request){
        $validator = Validator::make($request->all(), [
            'id' => 'required|exists:customers,id',
        ]);

        if ($validator->fails()) {
            // Return validation errors
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try{
            $degrees = Degrees::where('customers_id', $request->input('id'))
                            ->select('id', 'left_eye_degree', 'right_eye_degree', 'created_at')
                            ->orderBy('created_at', 'desc')
                            ->get();

            return response()->json([
                // 'message' => 'Customer ID exist',
                // 'customer id' => $request->input('id'),
                $degrees
            ]);
        }catch (\Exception $e) {
            // Return error message if something went wrong
            return response()->json(['message' => 'Failed to fetch all degree data'], 500);
        }  
    }

    // function to delete degree based on degree id && customer id
    public function delete_degree(Request $request){
        $validator = Validator::make($request->all(), [
            'id' => 'required|exists:degrees,id',
            'customers_id' => 'required|exists:customers,id',
        ]);

        if ($validator->fails()) {
            // Return validation errors
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            $degree = Degrees::where('id', $request->input('id'))
                            ->where('customers_id', $request->input('customers_id'))
                            ->firstOrFail();

            $degree->delete();

            // Return success response
            return response()->json([
                'message' => 'Degree deleted successfully',
                // 'id' => $request->input('customers_id'),
            ]);

        } catch (\Exception $e) {
            // Return error response if deletion fails
            return response()->json(['error' => 'Failed to delete degree'], 500);
        }
    }

    // function to update degree based on degree id && customer id
    public function update_degree(Request $request){
        $validator = Validator::make($request->all(), [
            'id' => 'required|exists:degrees,id',
            'customers_id' => 'required|exists:customers,id',
            'left_eye_degree' => 'nullable|string|max:255',
            'right_eye_degree' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            // Return validation errors
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            $degree = Degrees::where('id', $request->input('id'))
                            ->where('customers_id', $request->input('customers_id'))
                            ->firstOrFail();

            $degree->update([
                'left_eye_degree' => $request->input('left_eye_degree'),
                'right_eye_degree' => $request->input('right_eye_degree'),
            ]);

            // Return success response
            return response()->json([
                'message' => 'Degree updated successfully',
                // 'id' => $request->input('customers_id'),
            ]);

        } catch (\Exception $e) {
            // Return error response if deletion fails
            return response()->json(['error' => 'Failed to delete degree'], 500);
        }
    }
}
