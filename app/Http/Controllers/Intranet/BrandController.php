<?php

namespace App\Http\Controllers\Intranet;

use App\Traits\UploadFiles;
use Illuminate\Http\Request;
use App\Models\Intranet\Brand;
use App\Http\Controllers\ApiController;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\Intranet\Brand\PutBrandRequest;
use App\Http\Requests\Intranet\Brand\StoreBrandRequest;

class BrandController extends ApiController
{
    use UploadFiles;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return $this->respond(Brand::get());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreBrandRequest $request)
    {
        $brand = Brand::create($request->validated());

        if (!is_null($request['base64'])) {
            $relativePath  = $this->saveImage($request['base64'], $brand->default_path_folder);
            $request['base64'] = $relativePath;
            $updateData = ['logo' => $relativePath];
            $brand->update($updateData);
        }
        return $this->respond($brand);
    }

    /**
     * Display the specified resource.
     */
    public function show(Brand $brand)
    {
        return $this->respond($brand);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(PutBrandRequest $request, Brand $brand)
    {
        $brand->update($request->validated());

        if (!is_null($request['base64'])) {
            if ($brand->logo) {
                Storage::disk('s3')->delete($brand->logo);
            }
            $relativePath  = $this->saveImage($request['base64'], $brand->default_path_folder);
            $request['base64'] = $relativePath;
            $updateData = ['logo' => $relativePath];
            $brand->update($updateData);
        }

        return $this->respond($brand);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Brand $brand)
    {
        $brand->delete();
        return $this->respondSuccess();
    }
}
