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
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'client_name' => 'required',
            'phone' => 'required',
            'email' => 'required',
            'first_name' => 'required',
            'last_name' => 'required',
            'currency' => 'required',
            'address_1' => 'required',
            'address_2' => 'required',
            'city' => 'required',
            'postal_code' => 'required',
            'country' => 'required',
            'province' => 'required',
            'website' => 'required',
            'notes' => 'required'
        ]);

        $validatedData['user_id'] = $request->user()->id;

        DB::table('customers')->insert($validatedData);

        return response()->json(null, 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, int $customer_id)
    {
        $customer = DB::table('customers')->where('user_id', $request->user()->id)->find($customer_id);
        if ($customer === null) {
            return response()->json(null, 404);
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
            return response()->json(null, 404);
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
        return response()->json(null, 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, int $customer_id)
    {
        $customer = Customer::all()->where('user_id', $request->user()->id)->find($customer_id);
        if ($customer === null) {
            return response()->json(null, 404);
        }

        $customer->delete();
        return response()->json(null, 200);
    }

    public function count_customers(Request $request) {
        return response()->json(DB::table('customers')->where('user_id', $request->user()->id)->count());
    }
}
