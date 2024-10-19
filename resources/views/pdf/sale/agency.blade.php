<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporte de Ventas</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            margin: 20px;
            background-color: #f8f9fa;
            color: #343a40;
        }

        h1, h2, h3 {
            text-align: center;
            color: #007bff;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            background-color: #ffffff;
        }

        th, td {
            border: 1px solid #dee2e6;
            padding: 12px;
            text-align: left;
        }

        th {
            background-color: #007bff;
            color: white;
        }

        tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        .employee-info {
            margin: 20px 0;
            padding: 10px;
            border: 1px solid #007bff;
            border-radius: 5px;
            background-color: #e9ecef;
        }

        .employee-info img {
            max-width: 100px;
            margin-right: 10px;
            vertical-align: middle;
        }

        .header-table {
            width: 100%;
            margin-bottom: 20px;
            border: none;
        }

        .header-table img {
            max-width: 50px;
            vertical-align: middle;
        }

        .header-table h1 {
            margin: 0;
        }

        .total-label {
            font-weight: bold;
            color: #dc3545;
        }

        hr {
            border: 1px solid #007bff;
            margin: 20px 0;
        }
    </style>
</head>

<body>
    <table class="header-table">
        <tr>
            <td><img src="storage/images/logo.png" alt="Logo Empresa"></td>
            <td><h1>Reporte de Ventas</h1></td>
            <td><h2>Sucursal: {{ $branchName }}</h2></td>
            <td><h2>{{ $monthName }} {{ $year }}</h2></td>
        </tr>
    </table>

    <div class="employee-info">
        <h3 class="total-label">Total sucursal</h3>
    </div>

    <table>
        <thead>
            <tr>
                <th></th>
                <th>Monto total</th>
                <th>Meta por monto</th>
                <th>Diferencia</th>
                <th>Alcance por monto</th>
                <th>Cantidad total</th>
                <th>Meta por cantidades</th>
                <th>Diferencia</th>
                <th>Alcance por cantidad</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Ventas</td>
                <td>${{ number_format($agency['sales']['total_sales'], 2) }}</td>
                <td>${{ number_format($agency['sales']['total_targets'], 2) }}</td>
                <td>${{ number_format($agency['sales']['difference'], 2) }}</td>
                <td>{{ $agency['sales']['percentage_difference'] }}%</td>
                <td>{{ $agency['sales']['total_quantity_sold'] }}</td>
                <td>{{ $agency['sales']['total_quantity_targets'] }}</td>
                <td>{{ $agency['sales']['quantity_difference'] }}</td>
                <td>{{ $agency['sales']['quantity_percentage_difference'] }}%</td>
            </tr>
            <tr>
                <td>Cotizaciones</td>
                <td>${{ number_format($agency['quotes']['total_quotes'], 2) }}</td>
                <td>${{ number_format($agency['quotes']['total_targets'], 2) }}</td>
                <td>${{ number_format($agency['quotes']['difference'], 2) }}</td>
                <td>{{ $agency['quotes']['percentage_difference'] }}%</td>
                <td>{{ $agency['quotes']['total_quantity_quotes'] }}</td>
                <td>{{ $agency['quotes']['total_quantity_targets'] }}</td>
                <td>{{ $agency['quotes']['quantity_difference'] }}</td>
                <td>{{ $agency['quotes']['quantity_percentage_difference'] }}%</td>
            </tr>
        </tbody>
    </table>

    @foreach ($employees as $employee)
        <div class="employee-info">
            <h3><strong>Nombre:</strong> {{ $employee['fullName'] }}</h3>
        </div>

        <table>
            <thead>
                <tr>
                    <th></th>
                    <th>Monto total</th>
                    <th>Meta por monto</th>
                    <th>Diferencia</th>
                    <th>Alcance por monto</th>
                    <th>Cantidad total</th>
                    <th>Meta por cantidades</th>
                    <th>Diferencia</th>
                    <th>Alcance por cantidad</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Ventas</td>
                    <td>${{ number_format($employee['sales_summary']['sales']['total_sales'], 2) }}</td>
                    <td>${{ number_format($employee['sales_summary']['sales']['total_targets'], 2) }}</td>
                    <td>${{ number_format($employee['sales_summary']['sales']['difference'], 2) }}</td>
                    <td>{{ $employee['sales_summary']['sales']['percentage_difference'] }}%</td>
                    <td>{{ $employee['sales_summary']['sales']['total_quantity_sold'] }}</td>
                    <td>{{ $employee['sales_summary']['sales']['total_quantity_targets'] }}</td>
                    <td>{{ $employee['sales_summary']['sales']['quantity_difference'] }}</td>
                    <td>{{ $employee['sales_summary']['sales']['quantity_percentage_difference'] }}%</td>
                </tr>
                <tr>
                    <td>Cotizaciones</td>
                    <td>${{ number_format($employee['sales_summary']['quotes']['total_quotes'], 2) }}</td>
                    <td>${{ number_format($employee['sales_summary']['quotes']['total_targets'], 2) }}</td>
                    <td>${{ number_format($employee['sales_summary']['quotes']['difference'], 2) }}</td>
                    <td>{{ $employee['sales_summary']['quotes']['percentage_difference'] }}%</td>
                    <td>{{ $employee['sales_summary']['quotes']['total_quantity_quotes'] }}</td>
                    <td>{{ $employee['sales_summary']['quotes']['total_quantity_targets'] }}</td>
                    <td>{{ $employee['sales_summary']['quotes']['quantity_difference'] }}</td>
                    <td>{{ $employee['sales_summary']['quotes']['quantity_percentage_difference'] }}%</td>
                </tr>
            </tbody>
        </table>

        <hr>
    @endforeach

</body>

</html>
