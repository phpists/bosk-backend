<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CustomerController extends Controller
{
    public function __construct() {
        $this->middleware('auth:api');
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        return response()->json(DB::table('customers')->where('user_id', $request->user()->id)->get());
    }

    /**
     * Store a newly created resource in storage.
     * @throws \Exception
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'client_name' => 'required',
            'phone' => 'max:20',
            'email' => '',
            'first_name' => '',
            'last_name' => '',
            'currency' => 'required',
            'address_1' => 'required',
            'address_2' => '',
            'city' => 'required',
            'postal_code' => 'required|max:18',
            'country' => 'required',
            'province' => '',
            'website' => '',
            'notes' => '',
            'VAT' => 'integer',
            'footer' => ''
        ]);

        if(!array_key_exists('VAT', $validatedData)) {
            $validatedData['VAT'] = random_int(0, 100);
        }

        if(!array_key_exists('footer', $validatedData)) {
            $validatedData['footer'] = fake()->text();
        }

        $validatedData['user_id'] = $request->user()->id;

        $customer = new Customer($validatedData);
        $customer->save();
        return response()->json($customer, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, int $customer_id)
    {
        $customer = DB::table('customers')->where('user_id', $request->user()->id)->find($customer_id);
        if ($customer === null) {
            return response()->json([
                'status' => false,
                'message' => 'Not found'
            ], 404);
        }
        return response()->json($customer);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, int $customer_id)
    {
        $customer = Customer::all()->where('user_id', $request->user()->id)->find($customer_id);
        if ($customer === null) {
            return response()->json([
                'status' => false,
                'message' => 'Not found'
            ], 404);
        }
        $columns_list = [
            'client_name',
            'phone',
            'email',
            'first_name',
            'last_name',
            'currency',
            'address_1',
            'address_2',
            'city',
            'postal_code',
            'country',
            'province',
            'website',
            'notes'
        ];
        $customer->update($request->only($columns_list));
        return response()->json($customer, 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, int $customer_id)
    {
        $customer = Customer::all()->where('user_id', $request->user()->id)->find($customer_id);
        if ($customer === null) {
            return response()->json([
                'status' => false,
                'message' => 'Not found'
            ], 404);
        }

        $customer->delete();
        return response()->json([
            'status' => true,
            'message' => 'success'
        ], 200);
    }

    public function count_customers(Request $request) {
        return response()->json(DB::table('customers')->where('user_id', $request->user()->id)->count());
    }
}
