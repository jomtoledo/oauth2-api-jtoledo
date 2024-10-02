<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Customer;

class CustomerController extends Controller
{
    /**
     * @ApiDescription(section="Customer", description="Creates a Customer")
     * @ApiMethod(type="post")
     * @ApiRoute(name="/api/customer")
     * @apiBody(sample="{
     *   'first_name': 'string',
     *   'last_name': 'string',
     *   'age': 'integer',
     *   'dob': 'date',
     *   'email': 'string'
     * }")
     * @ApiReturnHeaders(sample="HTTP 201 OK")
     * @ApiReturn(type="object", sample="{
     *    'message': 'Customer added successfully.',
     *    'customer': object
     * }")
     */
    public function insert(Request $request)
    {
        $request->validate([
            'first_name' => 'required|max:255',
            'last_name' => 'required|max:255',
            'age' => 'required|integer',
            'dob' => 'required|date',
            'email' => 'required|email|max:100',
        ]);

        $customer = Customer::create($request->all());

        return response()->json([
            'message' => 'Customer added successfully.',
            'customer' => $customer
        ], 201);
    }

    /**
     * @ApiDescription(section="Customer", description="Gets all Customers")
     * @ApiMethod(type="get")
     * @ApiRoute(name="/api/customers")
     * @ApiReturnHeaders(sample="HTTP 200 OK")
     * @ApiReturn(type="array", sample="[{
     *    'id': 'number',
     *    'first_name': 'string',
     *    'last_name': 'string',
     *    'age': 'integer',
     *    'dob': 'date',
     *    'email': 'string',
     *    'created_at': 'date',
     *    'updated_at': 'date'
     * }]")
     */
    public function index()
    {
        $customers = Customer::all() ?: [];
        return response()->json($customers);
    }

    /**
     * @ApiDescription(section="Customer", description="Gets a Customer Details")
     * @ApiMethod(type="get")
     * @ApiRoute(name="/api/customer/:id")
     * @ApiParams(name="id", type="number", nullable=false, description="Customer's ID")
     * @ApiReturnHeaders(sample="HTTP 200 OK")
     * @ApiReturn(type="object", sample="{
     *    'id': 'number',
     *    'first_name': 'string',
     *    'last_name': 'string',
     *    'age': 'integer',
     *    'dob': 'date',
     *    'email': 'string',
     *    'created_at': 'date',
     *    'updated_at': 'date'
     * }")
     */
    public function details($id)
    {
        $customer = Customer::findOrFail($id);
        return response()->json($customer);
    }

    /**
     * @ApiDescription(section="Customer", description="Updates a Customer")
     * @ApiMethod(type="put")
     * @ApiRoute(name="/api/customer/:id")
     * @ApiParams(name="id", type="number", nullable=false, description="Customer's ID")
     * @apiBody(sample="{
     *   'first_name': 'string',
     *   'last_name': 'string',
     *   'age': 'integer',
     *   'dob': 'date',
     *   'email': 'string'
     * }")
     * @ApiReturnHeaders(sample="HTTP 200 OK")
     * @ApiReturn(type="object", sample="{
     *    'message': 'Customer updated successfully.',
     *    'customer': object
     * }")
     */
    public function update(Request $request, $id)
    {
        try {
            // var_dump($id); die();
            $request->validate([
                'first_name' => 'required|max:255',
                'last_name' => 'required|max:255',
                'age' => 'required|integer',
                'dob' => 'required|date',
                'email' => 'required|email|max:100'
            ]);
            
            $customer = Customer::findOrFail($id);
            $customer->update($request->all());
    
            return response()->json([
                'message' => 'Customer updated successfully.',
                'customer' => $customer
            ]);
        } catch (ValidationException $e) {
            // Log the errors
            \Log::error('Validation errors: ', $e->validator->errors()->toArray());
            // var_dump($e->validator->errors()->toArray());
            return response()->json(['errors' => $e->validator->errors()], 400);
        }
    }

    /**
     * @ApiDescription(section="Customer", description="Deletes a Customer")
     * @ApiMethod(type="delete")
     * @ApiRoute(name="/api/customer/:id")
     * @ApiParams(name="id", type="number", nullable=false, description="Customer's ID")
     * @ApiReturnHeaders(sample="HTTP 200 OK")
     * @ApiReturn(type="object", sample="{
     *    'message': 'Customer deleted successfully.'
     * }")
     */
    public function delete($id)
    {
        $customer = Customer::findOrFail($id);
        $customer->delete();

        return response()->json(['message' => 'Customer deleted successfully.']);
    }
}
