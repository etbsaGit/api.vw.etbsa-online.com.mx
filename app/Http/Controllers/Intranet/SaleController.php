<?php

namespace App\Http\Controllers\Intranet;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\Intranet\Sale;
use App\Models\Intranet\Type;
use App\Models\Intranet\Quote;
use App\Models\Intranet\Agency;
use App\Models\Intranet\Status;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Intranet\Customer;
use App\Models\Intranet\Employee;
use App\Models\Intranet\SaleDate;
use App\Models\Intranet\Inventory;
use App\Http\Controllers\ApiController;
use App\Http\Requests\Intranet\Sale\PutSaleRequest;
use App\Http\Requests\Intranet\Sale\StoreSaleRequest;
use App\Models\Intranet\FollowUp;

class SaleController extends ApiController
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $filters = $request->all();
        $salesQuery = Sale::filterSales($filters)
            ->with('status', 'salesChannel', 'type', 'agency', 'customer', 'employee')
            ->orderBy('updated_at', 'desc'); // Ordenar del más reciente al más viejo

        $sales = $salesQuery->paginate(10);
        return $this->respond($sales);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreSaleRequest $request)
    {
        // Crear la venta
        $sale = Sale::create($request->validated());

        // Llamar a otra función si quote_id está presente
        if ($request->has('quote_id')) {
            $this->handleQuoteId($request->input('quote_id'));
        }

        // Obtener el ID del tipo con el nombre 'Registro'
        $type = Type::where('name', 'Registro')->first();

        if ($type) {
            // Crear el registro en `sale_dates`
            SaleDate::create([
                'date' => $sale->created_at->format('Y-m-d'),
                'sale_id' => $sale->id,
                'type_id' => $type->id,
            ]);
        }

        $inventoryId = $request->input('inventory_id');

        // Eliminar el inventario asociado
        if ($inventoryId) {
            $inventory = Inventory::find($inventoryId);

            if ($inventory) {
                // Establecer 'priority' a null antes de eliminar el inventario
                $inventory->priority = null;
                $inventory->save();  // Guardar los cambios en la base de datos

                // Eliminar el inventario con el ID proporcionado
                $inventory->delete();
            }
        }


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
        // Obtener el inventory_id actual y el nuevo desde la solicitud
        $currentInventoryId = $sale->inventory_id; // Suponiendo que tienes esta relación
        $newInventoryId = $request->input('inventory_id');

        // Verificar si el request tiene cancel = 1
        if ($request->input('cancel') == 1) {
            // Restaurar el inventario actual
            $currentInventory = Inventory::withTrashed()->find($currentInventoryId);
            if ($currentInventory) {
                $currentInventory->restore(); // Restaurar el inventario actual
            }
        } else {
            // Verificar si el inventory_id ha cambiado
            if ($currentInventoryId != $newInventoryId) {
                // Recuperar el inventario anterior
                $previousInventory = Inventory::withTrashed()->find($currentInventoryId);

                if ($previousInventory) {
                    $previousInventory->restore(); // Restaurar el inventario anterior
                }

                // Eliminar el nuevo inventario si existe
                if ($newInventoryId) {
                    $newInventory = Inventory::find($newInventoryId);
                    if ($newInventory) {
                        $newInventory->delete(); // Eliminar el nuevo inventario
                    }
                }
            }
        }

        // Actualizar la venta
        $sale->update($request->validated());

        return $this->respond($sale);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Sale $sale)
    {
        // Obtener el inventory_id de la venta
        $inventoryId = $sale->inventory_id;

        // Verificar si hay un inventory_id y restaurar el inventario asociado
        if ($inventoryId) {
            $inventory = Inventory::withTrashed()->find($inventoryId);
            if ($inventory) {
                $inventory->restore(); // Restaurar el inventario asociado
            }
        }

        // Eliminar la venta
        $sale->delete();

        return $this->respondSuccess();
    }

    public function getOptions()
    {
        $data = [
            'inventories' => Inventory::onlyTrashed()->get(),
            'vehicles' => Inventory::all(),
            'statuses' => Status::where('status_key', 'sales')->get(),
            'sales_channels' => Type::where('type_key', 'channel')->get(),
            'types' => Type::where('type_key', 'sales')->get(),
            'agencies' => Agency::all(),
            'customers' => Customer::all(),
            'employees' => Employee::all()
        ];

        return $this->respond($data);
    }

    public function getAgency(Request $request)
    {
        // Obtener mes y año del request
        $month = $request->input('month');
        $year = $request->input('year');
        $agency = Agency::find($request->input('agency_id'));

        $employees = $agency->employees->load('sales', 'quote', 'targets'); // Carga los empleados con sus relaciones

        // Obtener el ID del tipo "Venta" y "Prospección"
        $ventaTypeId = Type::where('name', 'Venta')->where('type_key', 'target')->value('id');
        $prospeccionTypeId = Type::where('name', 'Prospección')->where('type_key', 'target')->value('id');

        // Verificar si se encontraron los tipos
        if (!$ventaTypeId || !$prospeccionTypeId) {
            return $this->respond(['message' => 'Tipo "Venta" o "Prospección" no encontrado'], 404);
        }

        // Inicializar variables para el resumen de la sucursal
        $totalSalesAgency = 0;
        $totalQuotesAgency = 0;
        $totalTargetsSalesAgency = 0;
        $totalTargetsProspeccionAgency = 0;
        $totalQuantitySoldAgency = 0;
        $totalQuantityQuotesAgency = 0;
        $totalQuantityTargetsSalesAgency = 0; // Cantidad total de metas de ventas
        $totalQuantityTargetsProspeccionAgency = 0; // Cantidad total de metas de prospección

        foreach ($employees as $employee) {
            // Ventas del mes y año
            $salesForMonth = $employee->sales->filter(function ($sale) use ($month, $year) {
                $saleDate = Carbon::parse($sale->date);
                return $saleDate->month == $month && $saleDate->year == $year && $sale->cancel == 0;
            });

            // Quotes del mes y año
            $quotesForMonth = $employee->quote->filter(function ($quote) use ($month, $year) {
                return $quote->created_at->month == $month && $quote->created_at->year == $year;
            });

            // Filtrar metas del mes y año
            $targetsForMonth = $employee->targets->filter(function ($target) use ($month, $year, $ventaTypeId, $prospeccionTypeId) {
                return $target->month == $month && $target->year == $year &&
                    ($target->type_id == $ventaTypeId || $target->type_id == $prospeccionTypeId);
            });

            // Sumar las ventas
            $totalSales = $salesForMonth->sum('amount');
            $totalQuantitySold = $salesForMonth->count(); // Cantidad de ventas

            // Sumar las quotes
            $totalQuotes = $quotesForMonth->sum('amount');
            $totalQuantityQuotes = $quotesForMonth->count(); // Cantidad de quotes

            // Filtrar metas para ventas
            $targetsForSales = $targetsForMonth->filter(function ($target) use ($ventaTypeId) {
                return $target->type_id == $ventaTypeId;
            });

            // Filtrar metas para prospección
            $targetsForProspeccion = $targetsForMonth->filter(function ($target) use ($prospeccionTypeId) {
                return $target->type_id == $prospeccionTypeId;
            });

            // Sumar metas para ventas
            $totalTargetsSales = $targetsForSales->sum('value');
            $totalQuantityTargetsSales = $targetsForSales->sum('quantity');

            // Sumar metas para prospección
            $totalTargetsProspeccion = $targetsForProspeccion->sum('value');
            $totalQuantityTargetsProspeccion = $targetsForProspeccion->sum('quantity');

            // Sumar totales para la sucursal
            $totalSalesAgency += $totalSales;
            $totalQuotesAgency += $totalQuotes;
            $totalTargetsSalesAgency += $totalTargetsSales;
            $totalTargetsProspeccionAgency += $totalTargetsProspeccion;
            $totalQuantitySoldAgency += $totalQuantitySold;
            $totalQuantityQuotesAgency += $totalQuantityQuotes;
            $totalQuantityTargetsSalesAgency += $totalQuantityTargetsSales;
            $totalQuantityTargetsProspeccionAgency += $totalQuantityTargetsProspeccion;

            // Agregar un resumen por empleado
            $employee->sales_summary = [
                'sales' => [
                    'total_sales' => $totalSales,
                    'total_targets' => $totalTargetsSales,
                    'met_target' => $totalSales >= $totalTargetsSales,
                    'percentage_difference' => $totalTargetsSales > 0
                        ? number_format(($totalSales / $totalTargetsSales) * 100, 2)
                        : ($totalSales > 0 ? 100 : 0),
                    'difference' => $totalSales - $totalTargetsSales,
                    'total_quantity_sold' => $totalQuantitySold,
                    'total_quantity_targets' => $totalQuantityTargetsSales,
                    'quantity_met' => $totalQuantitySold >= $totalQuantityTargetsSales,
                    'quantity_percentage_difference' => $totalQuantityTargetsSales > 0
                        ? number_format(($totalQuantitySold / $totalQuantityTargetsSales) * 100, 2)
                        : ($totalQuantitySold > 0 ? 100 : 0),
                    'quantity_difference' => $quantityDifferenceSales = $totalQuantitySold - $totalQuantityTargetsSales,
                ],
                'quotes' => [
                    'total_quotes' => $totalQuotes,
                    'total_targets' => $totalTargetsProspeccion,
                    'met_target' => $totalQuotes >= $totalTargetsProspeccion,
                    'percentage_difference' => $totalTargetsProspeccion > 0
                        ? number_format(($totalQuotes / $totalTargetsProspeccion) * 100, 2)
                        : ($totalQuotes > 0 ? 100 : 0),
                    'difference' => $totalQuotes - $totalTargetsProspeccion,
                    'total_quantity_quotes' => $totalQuantityQuotes,
                    'total_quantity_targets' => $totalQuantityTargetsProspeccion,
                    'quantity_met' => $totalQuantityQuotes >= $totalQuantityTargetsProspeccion,
                    'quantity_percentage_difference' => $totalQuantityTargetsProspeccion > 0
                        ? number_format(($totalQuantityQuotes / $totalQuantityTargetsProspeccion) * 100, 2)
                        : ($totalQuantityQuotes > 0 ? 100 : 0),
                    'quantity_difference' => $quantityDifferenceProspeccion = $totalQuantityQuotes - $totalQuantityTargetsProspeccion,
                ],
            ];
        }

        // Resumen acumulado de la sucursal
        $agency_summary = [
            'sales' => [
                'total_sales' => $totalSalesAgency,
                'total_targets' => $totalTargetsSalesAgency,
                'met_target' => $totalSalesAgency >= $totalTargetsSalesAgency,
                'percentage_difference' => $totalTargetsSalesAgency > 0
                    ? number_format(($totalSalesAgency / $totalTargetsSalesAgency) * 100, 2)
                    : ($totalSalesAgency > 0 ? 100 : 0),
                'difference' => $totalSalesAgency - $totalTargetsSalesAgency,
                'total_quantity_sold' => $totalQuantitySoldAgency,
                'total_quantity_targets' => $totalQuantityTargetsSalesAgency,
                'quantity_met' => $totalQuantitySoldAgency >= $totalQuantityTargetsSalesAgency,
                'quantity_percentage_difference' => $totalQuantityTargetsSalesAgency > 0
                    ? number_format(($totalQuantitySoldAgency / $totalQuantityTargetsSalesAgency) * 100, 2)
                    : ($totalQuantitySoldAgency > 0 ? 100 : 0),
                'quantity_difference' => $totalQuantitySoldAgency - $totalQuantityTargetsSalesAgency,
            ],
            'quotes' => [
                'total_quotes' => $totalQuotesAgency,
                'total_targets' => $totalTargetsProspeccionAgency,
                'met_target' => $totalQuotesAgency >= $totalTargetsProspeccionAgency,
                'percentage_difference' => $totalTargetsProspeccionAgency > 0
                    ? number_format(($totalQuotesAgency / $totalTargetsProspeccionAgency) * 100, 2)
                    : ($totalQuotesAgency > 0 ? 100 : 0),
                'difference' => $totalQuotesAgency - $totalTargetsProspeccionAgency,
                'total_quantity_quotes' => $totalQuantityQuotesAgency,
                'total_quantity_targets' => $totalQuantityTargetsProspeccionAgency,
                'quantity_met' => $totalQuantityQuotesAgency >= $totalQuantityTargetsProspeccionAgency,
                'quantity_percentage_difference' => $totalQuantityTargetsProspeccionAgency > 0
                    ? number_format(($totalQuantityQuotesAgency / $totalQuantityTargetsProspeccionAgency) * 100, 2)
                    : ($totalQuantityQuotesAgency > 0 ? 100 : 0),
                'quantity_difference' => $totalQuantityQuotesAgency - $totalQuantityTargetsProspeccionAgency,
            ],
        ];

        return $this->respond([
            'employees' => $employees, // Resumen de cada empleado
            'agency_summary' => $agency_summary, // Resumen acumulado de la sucursal
        ]);
    }

    public function createPDF(Request $request)
    {
        // Obtener mes y año del request
        $month = $request->input('month');
        $year = $request->input('year');
        $agency = Agency::find($request->input('agency_id'));

        $data = $this->getAgency($request);

        // Define el nombre de la sucursal
        $branchName = $agency->name;

        // Convertir el número del mes a su nombre
        $monthName = Carbon::create()->month($month)->format('F');

        // Obtener los empleados como array
        $employees = $data->original['employees']->toArray();

        // Obtener los datos agencia como array
        $agencyData = $data->original['agency_summary'];

        // Generar el PDF nuevo
        $pdf = Pdf::loadView('pdf.sale.agency', [
            'agency' => $agencyData,
            'employees' => $employees,
            'branchName' => $branchName,
            'monthName' => $monthName,
            'year' => $year,
        ])->setPaper('a4', 'landscape'); // Aquí defines el papel y la orientación

        // Quitar para produccion, esto es para pruebas en postman
        // return $pdf->download('document.pdf');

        // Obtener el contenido del PDF como cadena binaria
        $pdfContent = $pdf->output();

        // Convertir el contenido a Base64
        $pdfBase64 = base64_encode($pdfContent);

        // Retornar el PDF en Base64
        return $this->respond($pdfBase64);
    }

    // Función que manejará el quote_id
    private function handleQuoteId($quoteId)
    {
        // Buscar los estados 'Ganada' y 'Perdida'
        $statusGanada = Status::where('status_key', 'quote')->where('name', 'Ganada')->first();
        $statusPerdida = Status::where('status_key', 'quote')->where('name', 'Perdida')->first();

        // Lógica para manejar el quote_id
        $quote = Quote::find($quoteId);

        if ($quote) {
            // Obtener el follow_up_id del quote
            $followUpId = $quote->follow_up_id;

            $statusFollowGanada = Status::where('status_key', 'followUp')->where('name', 'Venta ganada')->first();
            $follow = FollowUp::where('id', $followUpId)->first();
            $follow->status_id = $statusFollowGanada->id;
            $follow->save();

            // Buscar todos los quotes con el mismo follow_up_id
            $relatedQuotes = Quote::where('follow_up_id', $followUpId)->get();

            // Actualizar el status_id de cada quote relacionado a 'Perdida'
            foreach ($relatedQuotes as $relatedQuote) {
                $relatedQuote->status_id = $statusPerdida->id; // Asignar el id de 'Perdida'
                $relatedQuote->save(); // Guardar los cambios
            }

            // Cambiar el status_id del quote específico a 'Ganada'
            $quote->status_id = $statusGanada->id; // Asignar el id de 'Ganada'
            $quote->save(); // Guardar los cambios
        }
    }
}
