<!-- quote.blade.php -->

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Propuesta Económica</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            /* background-color: #f4f4f9; */
            background-color: #ffffff;
            color: #333;
            margin: 0;
            padding: 0px;
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
            margin: 0;
        }
        header {
            text-align: center;
            margin-bottom: 30px;
        }
        header img {
            max-width: 150px;
            height: auto;
        }
        h1, h2 {
            color: #2C3E50;
        }
        h1 {
            margin-bottom: 30px;
        }
        h2 {
            margin-top: 20px;
            border-bottom: 2px solid #2C3E50;
            padding-bottom: 5px;
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
    </style>
</head>
<body>
    <!-- Imagen de ancho completo al inicio -->
    <div class="banner-fullwidth">
        <img src="storage/images/banner.png" alt="Banner Empresa">
    </div>

    <!-- Forzamos el salto de página después de la imagen -->
    <div class="page-break"></div>

    <div class="banner-fullwidth">
        <img src="storage/images/banner2.png" alt="Banner Empresa">
    </div>

    <p>Estimado <strong>{{$customer}}</strong>, le presentamos la siguiente propuesta económica, así como las condiciones comerciales, esperando se ajusten a sus necesidades.</p>

    <h2>Detalles de la Cotización</h2>
    <ul>
        <li><strong>Folio de Cotización:</strong> {{ $folio }}</li>
        <li><strong>Fecha:</strong> {{ $fecha }}</li>
        <li><strong>Precio Unitario:</strong> ${{ number_format($precio_unitario, 2) }}</li>
        <li><strong>IVA (16%):</strong> ${{ number_format($iva, 2) }}</li>
        <li><strong>Precio Total (con IVA):</strong> ${{ number_format($precio_total, 2) }}</li>
        <li><strong>Condiciones de Pago:</strong> {{ $condiciones_pago }}</li>
        <li><strong>Fecha de Entrega:</strong> {{ $fecha_entrega }} semanas</li>
        <li><strong>Adicionales:</strong> {{ $adicionales }}</li>
        <li><strong>Vigencia:</strong> {{ $vigencia }}</li>
    </ul>

    <!-- Forzamos el salto de página después de la imagen -->
    <div class="page-break"></div>
    <div class="banner-fullwidth">
        <img src="storage/images/banner3.png" alt="Banner Empresa">
    </div>

    <h2>Postventa SIN LÍMITES</h2>
    <p>Quien viaja por la carretera en un camión Volkswagen, jamás estará solo. Nuestro soporte Postventa SIN LÍMITES mantiene tus unidades siempre productivas.</p>

    <h3>Servicios Adicionales</h3>
    <ul>
        <li><strong>VOLKS | Assist:</strong> Atención 24/7 para emergencias en carretera y consultas generales.</li>
        <li><strong>VOLKS | Piezas Originales:</strong> Refacciones con 1 año de garantía sin límite de kilometraje.</li>
        <li><strong>VOLKS | Training:</strong> Entrenamiento técnico y administrativo para optimizar el rendimiento.</li>
        <li><strong>VOLKS | Telematics:</strong> Tecnología avanzada para monitoreo en tiempo real de tus unidades.</li>
    </ul>

    <p>En espera de poder concretar su intención de compra, quedo a sus órdenes.</p>

    <header>
        <img src="storage/images/2anios.jpg" alt="Logo Empresa">
    </header>

    <table class="signature" style="width: 100%; border: none;">
        <tr>
            <td style="vertical-align: middle;">
                <img src="{{ $vendedor['foto'] }}" alt="Foto Vendedor" style="max-width: 100px; height: auto;border-radius: 50%;">
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




</body>
</html>
