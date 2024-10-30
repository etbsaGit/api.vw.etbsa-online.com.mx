<?php

namespace App\Http\Controllers\Intranet;

use Illuminate\Http\Request;
use App\Models\Intranet\CustomerDoc;
use App\Http\Controllers\ApiController;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\Intranet\CustomerDoc\StoreCustomerDocRequest;
use App\Models\Intranet\Customer;
use App\Traits\UploadFiles;

class CustomerDocController extends ApiController
{
    use UploadFiles;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCustomerDocRequest $request)
    {
        $customerDoc = CustomerDoc::create($request->validated());
        $relativePath  = $this->saveDoc($request['base64'], $customerDoc->default_path_folder);
        $updateData = ['path' => $relativePath];
        $customerDoc->update($updateData);
        return $this->respond($customerDoc);
    }

    /**
     * Display the specified resource.
     */
    public function show(CustomerDoc $customerDoc)
    {
        return $this->respond($customerDoc);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StoreCustomerDocRequest $request, CustomerDoc $customerDoc)
    {
        $customerDoc->update($request->validated());

        if (!is_null($request['base64'])) {
            if ($customerDoc->path) {
                Storage::disk('s3')->delete($customerDoc->path);
            }
            $relativePath  = $this->saveDoc($request['base64'], $customerDoc->default_path_folder);
            $updateData = ['path' => $relativePath];
            $customerDoc->update($updateData);
        }

        return $this->respond($customerDoc);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(CustomerDoc $customerDoc)
    {
        Storage::disk('s3')->delete($customerDoc->path);
        $customerDoc->delete();
        return $this->respondSuccess();
    }

    public function getPerCustomer(Customer $customer)
    {
        $docs = CustomerDoc::where('customer_id', $customer->id)
            ->with('type')
            ->orderBy('updated_at', 'desc')
            ->get();

        return $this->respond($docs);
    }
}
