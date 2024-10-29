<?php

namespace App\Http\Controllers\Intranet;

use Carbon\Carbon;
use setasign\Fpdi\Fpdi;
use App\Traits\UploadFiles;
use Illuminate\Http\Request;
use App\Models\Intranet\Type;
use App\Models\Intranet\Quote;
use App\Models\Intranet\Status;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Intranet\FollowUp;
use App\Models\Intranet\Inventory;
use App\Models\Intranet\Additional;
use App\Http\Controllers\ApiController;
use Illuminate\Support\Facades\Storage;
use setasign\Fpdi\PdfParser\StreamReader;
use App\Http\Requests\Intranet\Quote\StoreQuoteRequest;

class QuoteController extends ApiController
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
    public function store(StoreQuoteRequest $request)
    {
        $quote = Quote::create($request->validated());

        $additionals = $request['additionals'];

        foreach ($additionals as $additional) {
            $additional['quote_id'] = $quote->id;

            // Crear un nuevo registro en la base de datos
            Additional::create($additional);
        }

        $src = null;
        $srcQR = null;

        Carbon::setLocale('es');
        $fecha = $quote->created_at->locale('es')->translatedFormat('d \d\e F \d\e Y');
        $vigencia = Carbon::parse($quote->expiration_date)->locale('es')->translatedFormat('d \d\e F \d\e Y');

        if ($quote->employee->pic) {
            $imageData = base64_encode(file_get_contents($quote->employee->pic));
            $src = 'data:image/jpeg;base64,' . $imageData;
        }

        if ($quote->employee->qr) {
            $imageDataQR = base64_encode(file_get_contents($quote->employee->qr));
            $srcQR = 'data:image/jpeg;base64,' . $imageDataQR;
        }

        $images = $request['images'];

        $data = [
            'customer' => $quote->customer->name,
            'folio' => $quote->id,
            'fecha' => $fecha,
            'precio_unitario' => $quote->amount,
            'iva' => ($quote->amount) * 0.16,
            'precio_total' => ($quote->amount) * 1.16,
            'condiciones_pago' => $quote->type->name,
            'fecha_entrega' => $quote->lead_time,
            'adicionales' => $quote->additionals,
            'vigencia' => $vigencia,
            'modelo' => $quote->inventory->vehicle->name,
            'images' => $images,
            'vendedor' => [
                'nombre' => $quote->employee->fullName,
                'telefono' => $quote->employee->phone,
                'email' => $quote->employee->user->email,
                'empresa' => $quote->employee->agency->name,
                'direccion' => $quote->employee->agency->fullAddress,
                'foto' => $src,
                'qr' => $srcQR
            ]
        ];

        // Generar el PDF nuevo
        $pdf = Pdf::loadView('pdf.quote.quote', $data);

        // Quitar para produccion, esto es para pruebas en postman
        // return $pdf->download('document.pdf');

        $newPdfContent = $pdf->output();

        // Obtener el array de documentos adicionales (puede estar vacío)
        $additionalDocs = $quote->inventory->vehicle->vehicleDocs; // Array de objetos con realpath

        // Combinar los PDFs
        $combinedPdfContent = $this->combinePdfs($newPdfContent, $additionalDocs);

        // Convertir a Base64 y guardar
        $pdfBase64 = base64_encode($combinedPdfContent);
        $pdfBase64WithPrefix = 'data:application/pdf;base64,' . $pdfBase64;

        // Guardar el documento combinado
        $path = $this->saveDoc($pdfBase64WithPrefix, $quote->default_path_folder);
        $quote->update(['path' => $path]);

        return $this->respond($quote);
    }

    /**
     * Display the specified resource.
     */
    public function show(Quote $quote)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StoreQuoteRequest $request, Quote $quote)
    {
        $src = null;
        $srcQR = null;

        // Verificar si el quote tiene additionals
        if ($quote->additionals()->exists()) {
            // Borrar todos los additionals asociados al quote
            $quote->additionals()->delete();
        }

        // Eliminar el archivo existente de S3 si existe
        if ($quote->path) {
            Storage::disk('s3')->delete($quote->path);
        }

        // Actualizar la cotización
        $quote->update($request->validated());

        $additionals = $request['additionals'];

        foreach ($additionals as $additional) {
            $additional['quote_id'] = $quote->id;

            // Crear un nuevo registro en la base de datos
            Additional::create($additional);
        }

        Carbon::setLocale('es');
        $fecha = $quote->created_at->locale('es')->translatedFormat('d \d\e F \d\e Y');
        $vigencia = Carbon::parse($quote->expiration_date)->locale('es')->translatedFormat('d \d\e F \d\e Y');

        if ($quote->employee->pic) {
            $imageData = base64_encode(file_get_contents($quote->employee->pic));
            $src = 'data:image/jpeg;base64,' . $imageData;
        }

        if ($quote->employee->qr) {
            $imageDataQR = base64_encode(file_get_contents($quote->employee->qr));
            $srcQR = 'data:image/jpeg;base64,' . $imageDataQR;
        }

        $images = $request['images'];

        $data = [
            'customer' => $quote->customer->name,
            'folio' => $quote->id,
            'fecha' => $fecha,
            'precio_unitario' => $quote->amount,
            'iva' => ($quote->amount) * 0.16,
            'precio_total' => ($quote->amount) * 1.16,
            'condiciones_pago' => $quote->type->name,
            'fecha_entrega' => $quote->lead_time,
            'adicionales' => $quote->additionals,
            'vigencia' => $vigencia,
            'modelo' => $quote->inventory->vehicle->name,
            'images' => $images,
            'vendedor' => [
                'nombre' => $quote->employee->fullName,
                'telefono' => $quote->employee->phone,
                'email' => $quote->employee->user->email,
                'empresa' => $quote->employee->agency->name,
                'direccion' => $quote->employee->agency->fullAddress,
                'foto' => $src,
                'qr' => $srcQR
            ]
        ];

        // Generar el nuevo PDF
        $pdf = Pdf::loadView('pdf.quote.quote', $data);

        // Quitar para produccion, esto es para pruebas en postman
        // return $pdf->download('document.pdf');

        $newPdfContent = $pdf->output();

        // Obtener el array de documentos adicionales (puede estar vacío)
        $additionalDocs = $quote->inventory->vehicle->vehicleDocs; // Array de objetos con realpath

        // Combinar los PDFs
        $combinedPdfContent = $this->combinePdfs($newPdfContent, $additionalDocs);

        // Convertir a Base64 y guardar
        $pdfBase64 = base64_encode($combinedPdfContent);
        $pdfBase64WithPrefix = 'data:application/pdf;base64,' . $pdfBase64;

        // Guardar el documento combinado
        $path = $this->saveDoc($pdfBase64WithPrefix, $quote->default_path_folder);
        $quote->update(['path' => $path]);

        return $this->respond($quote);
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Quote $quote)
    {
        // Eliminar el archivo existente de S3 si existe
        if ($quote->path) {
            Storage::disk('s3')->delete($quote->path);
        }

        $quote->delete();
        return $this->respondSuccess();
    }

    public function getPerFollow(FollowUp $followUp)
    {
        $quotes = Quote::where('follow_up_id', $followUp->id)->with('status', 'type', 'inventory.vehicle', 'additionals')->get();
        return $this->respond($quotes);
    }

    public function getOptions()
    {
        $data = [
            'statuses' => Status::where('status_key', 'quote')->get(),
            'types' => Type::where('type_key', 'quote')->get(),
            'inventories' => Inventory::where('priority', 1)->with('prices')->get(),
        ];
        return $this->respond($data);
    }

    private function combinePdfs($pdf1, $additionalDocs = [])
    {
        $pdf = new Fpdi();

        // Procesar el primer PDF (el generado)
        $pageCount1 = $pdf->setSourceFile(StreamReader::createByString($pdf1));
        for ($i = 1; $i <= $pageCount1; $i++) {
            $templateId = $pdf->importPage($i);
            $size = $pdf->getTemplateSize($templateId);

            // Ajustar la orientación
            $orientation = ($size['width'] > $size['height']) ? 'L' : 'P';
            $pdf->AddPage($orientation, [$size['width'], $size['height']]);
            $pdf->useTemplate($templateId);
        }

        // Procesar documentos adicionales (PDFs e imágenes)
        foreach ($additionalDocs as $doc) {
            if (!empty($doc->realpath)) {
                // Si es un PDF
                if (pathinfo($doc->realpath, PATHINFO_EXTENSION) === 'pdf') {
                    $existingPdfContent = file_get_contents($doc->realpath);
                    if ($existingPdfContent !== false) {
                        $pageCount = $pdf->setSourceFile(StreamReader::createByString($existingPdfContent));
                        for ($i = 1; $i <= $pageCount; $i++) {
                            $templateId = $pdf->importPage($i);
                            $size = $pdf->getTemplateSize($templateId);
                            $orientation = ($size['width'] > $size['height']) ? 'L' : 'P';
                            $pdf->AddPage($orientation, [$size['width'], $size['height']]);
                            $pdf->useTemplate($templateId);
                        }
                    }
                }
                // Si es una imagen
                elseif (in_array(pathinfo($doc->realpath, PATHINFO_EXTENSION), ['jpg', 'jpeg', 'png', 'gif'])) {
                    $pdf->AddPage(); // Añadir nueva página para la imagen
                    $pdf->Image($doc->realpath, 10, 10, 190); // Ajusta las coordenadas y el tamaño según necesites
                }
            }
        }

        // Devolver el PDF combinado como cadena
        return $pdf->Output('S'); // 'S' para devolver como string
    }
}
