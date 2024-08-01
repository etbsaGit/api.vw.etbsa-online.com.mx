<?php

namespace App\Http\Controllers\Intranet;

use Illuminate\Http\Request;
use App\Models\Intranet\Sale;
use App\Models\Intranet\Vehicle;
use App\Http\Controllers\ApiController;
use App\Http\Requests\Intranet\Sale\PutSaleRequest;
use App\Http\Requests\Intranet\Sale\StoreSaleRequest;
use App\Models\Intranet\Agency;
use App\Models\Intranet\Customer;
use App\Models\Intranet\Employee;
use App\Models\Intranet\Status;
use App\Models\Intranet\Type;

class SaleController extends ApiController
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $filters = $request->all();
        $sales = Sale::filterSales($filters)->with('vehicle','status','salesChannel','type','agency','customer','employee')->paginate(10);
        return $this->respond($sales);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreSaleRequest $request)
    {
        $sale = Sale::create($request->validated());
        return $this->respondCreated($sale);
    }

    /**
     * Display the specified resource.
     */
    public function show(Sale $sale)
    {
        return $this->respond($sale);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(PutSaleRequest $request, Sale $sale)
    {
        $sale->update($request->validated());
        return $this->respond($sale);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Sale $sale)
    {
        $sale->delete();
        return $this->respondSuccess();
    }

    public function getOptions()
    {
        $data = [
            'vehicles' => Vehicle::all(),
            'statuses' => Status::where('status_key', 'sales')->get(),
            'sales_channels' => Type::where('type_key', 'channel')->get(),
            'types' => Type::where('type_key', 'sales')->get(),
            'agencies' => Agency::all(),
            'customers' => Customer::all(),
            'employees' => Employee::all()
        ];

        return $this->respond($data);
    }
}
