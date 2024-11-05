<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Propuesta Económica</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #ffffff;
            color: #333;
            margin: 0;
            padding: 0;
        }

        /* Imagen de ancho completo al inicio */
        .banner-fullwidth {
            margin: 0;
            padding: 0;
            width: 100%;
        }

        .banner-fullwidth img {
            width: 100%;
            height: auto;
            display: block;
            margin: 0 0 20px;
            /* Margen inferior para separar del siguiente contenido */
        }

        header {
            text-align: center;
            margin-bottom: 30px;
        }

        header img {
            max-width: 150px;
            height: auto;
        }

        h1,
        h2 {
            color: #2C3E50;
        }

        h1 {
            margin-bottom: 30px;
        }

        h2 {
            margin-top: 40px;
            /* Espacio superior */
            margin-bottom: 20px;
            /* Espacio inferior */
            border-bottom: 2px solid #2C3E50;
            padding-bottom: 5px;
            clear: both;
            /* Asegura que no haya superposición */
        }

        h3 {
            margin-top: 40px;
            /* Espacio superior para h3 */
            margin-bottom: 20px;
            /* Espacio inferior para h3 */
            clear: both;
            /* Asegura que no haya superposición */
        }

        ul {
            list-style-type: none;
            padding: 0;
        }

        ul li {
            padding: 8px;
            margin-bottom: 10px;
            background-color: #fff;
            border: 1px solid #ddd;
            border-radius: 5px;
        }

        ul li strong {
            color: #2980B9;
        }

        p {
            font-size: 16px;
            line-height: 1.5;
        }

        footer {
            text-align: center;
            margin-top: 40px;
            padding-top: 20px;
            border-top: 1px solid #ddd;
            color: #7f8c8d;
        }

        footer p {
            font-size: 14px;
        }

        .signature {
            margin-top: 50px;
            text-align: left;
            font-size: 16px;
            font-weight: bold;
            color: #2C3E50;
        }

        .signature span {
            display: block;
            margin-top: 5px;
            font-weight: normal;
            font-size: 14px;
            color: #7f8c8d;
        }

        /* Clase para forzar salto de página */
        .page-break {
            page-break-after: always;
        }

        /* Estilo general para la tabla */
        .table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        /* Estilo para las celdas de la tabla */
        .table td,
        .table th {
            padding: 12px;
            border: 1px solid #BDC3C7;
            /* Bordes de color gris claro */
            text-align: left;
            color: #2C3E50;
            /* Color de texto negro para mejor contraste */
        }

        /* Fondo de las filas de la tabla */
        .table tr:nth-child(even) {
            background-color: #ECF0F1;
            /* Fondo blanco para las filas pares */
        }

        .table tr:nth-child(odd) {
            background-color: #D5DBDB;
            /* Fondo azul claro para las filas impares */
        }

        /* Encabezado de la tabla */
        .table th {
            background-color: #3498DB;
            /* Un azul claro para el fondo del encabezado */
            color: white;
            /* Texto blanco en el encabezado */
        }

        /* Estilo para los datos de las celdas */
        .table td {
            color: #2C3E50;
            /* Texto en color negro */
        }
    </style>
</head>

<body>
    <!-- Imagen de ancho completo al inicio -->
    <div class="banner-fullwidth">
        <img src="storage/images/banner.png" alt="Banner Empresa">
    </div>

    <div class="page-break"></div>

    <div class="banner-fullwidth">
        <img src="storage/images/banner2.png" alt="Banner Empresa">
    </div>

    <p>Estimado <strong>{{ $customer }}</strong>, le presentamos la siguiente propuesta económica, así como las
        condiciones comerciales, esperando se ajusten a sus necesidades.</p>

    <h2>Detalles de la Cotización</h2>
    <table class="table table-bordered">
        <tr>
            <td><strong>Folio de Cotización:</strong></td>
            <td>{{ $folio }}</td>
        </tr>
        <tr>
            <td><strong>Fecha:</strong></td>
            <td>{{ $fecha }}</td>
        </tr>
        <tr>
            <td><strong>Precio Unitario:</strong></td>
            <td>${{ number_format($precio_total_sin_iva, 2) }}</td>
        </tr>
        <tr>
            <td><strong>IVA (16%):</strong></td>
            <td>${{ number_format($iva, 2) }}</td>
        </tr>
        <tr>
            <td><strong>Precio Total (con IVA):</strong></td>
            <td>${{ number_format($precio_total, 2) }}</td>
        </tr>
        <tr>
            <td><strong>Condiciones de Pago:</strong></td>
            <td>{{ $condiciones_pago }}</td>
        </tr>
        <tr>
            <td><strong>Fecha de Entrega:</strong></td>
            <td>{{ $fecha_entrega }} semanas</td>
        </tr>
        <tr>
            <td><strong>Vigencia:</strong></td>
            <td>{{ $vigencia }}</td>
        </tr>
    </table>

    <h2>Detalle del equipo</h2>
    <table class="table table-bordered">
        <tr>
            <td><strong>{{ $modelo }}</strong></td>
            <td>${{ number_format($precio_unitario, 2) }}</td>
        </tr>
        @if (isset($adicionales) && count($adicionales) > 0)
            @foreach ($adicionales as $adicional)
                <tr>
                    <td><strong>{{ $adicional->name }}</strong></td>
                    <td>${{ number_format($adicional->price, 2) }}</td>
                </tr>
            @endforeach
        @endif
    </table>


    {{-- @if (isset($adicionales) && count($adicionales) > 0)
        <h2>Equipo aliado</h2>
        {{$adicionales}}
        {{$precio_unitario}}
        <table class="table table-bordered">
            @foreach ($adicionales as $adicional)
                <tr>
                    <td><strong>{{ $adicional->name }}</strong></td>
                    <td>${{ $adicional->price }}</td>
                </tr>
            @endforeach
        </table>
    @endif --}}


    <div class="page-break"></div>

    <div class="banner-fullwidth">
        <img src="storage/images/banner3.png" alt="Banner Empresa">
    </div>

    <h2>Postventa SIN LÍMITES</h2>
    <p>Quien viaja por la carretera en un camión Volkswagen, jamás estará solo. Nuestro soporte Postventa SIN LÍMITES
        mantiene tus unidades siempre productivas.</p>

    <h3>Servicios Adicionales</h3>
    <ul>
        <li><strong>VOLKS | Assist:</strong> Atención 24/7 para emergencias en carretera y consultas generales.</li>
        <li><strong>VOLKS | Piezas Originales:</strong> Refacciones con 1 año de garantía sin límite de kilometraje.
        </li>
        <li><strong>VOLKS | Training:</strong> Entrenamiento técnico y administrativo para optimizar el rendimiento.
        </li>
        <li><strong>VOLKS | Telematics:</strong> Tecnología avanzada para monitoreo en tiempo real de tus unidades.</li>
    </ul>

    <p>En espera de poder concretar su intención de compra, quedo a sus órdenes.</p>

    <header>
        <img src="storage/images/2anios.jpg" alt="Logo Empresa">
    </header>

    <table class="signature" style="width: 100%; border: none;">
        <tr>
            <td style="vertical-align: middle;">
                <img src="{{ $vendedor['foto'] }}" alt="Foto Vendedor"
                    style="max-width: 100px; height: auto;border-radius: 50%;">
            </td>
            <td style="vertical-align: top; padding-right: 20px;">
                <p style="margin: 0;">{{ $vendedor['nombre'] }}</p>
                <span style="display: block;">Teléfono: {{ $vendedor['telefono'] }}</span>
                <span style="display: block;">Correo Electrónico: {{ $vendedor['email'] }}</span>
                <span style="display: block;">Sucursal: {{ $vendedor['empresa'] }}</span>
                <span style="display: block;">{{ $vendedor['direccion'] }}</span>
            </td>
            <td style="vertical-align: middle;">
                <img src="{{ $vendedor['qr'] }}" alt="Foto Vendedor" style="max-width: 100px; height: auto;">
            </td>
        </tr>
    </table>



    @if (isset($images) && count($images) > 0)
        <div class="page-break"></div>
        @foreach ($images as $image)
            <img src="{{ $image }}" alt="Imagen Adicional" style="width: 100%; height: auto; display: block;">
        @endforeach
    @endif


</body>

</html>
