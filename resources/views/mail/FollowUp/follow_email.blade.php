<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Follow Up</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f7f7f7;
            margin: 0;
            padding: 20px;
        }

        .container {
            max-width: 600px;
            margin: auto;
            background: #ffffff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        h1 {
            color: #333;
            font-size: 24px;
            margin-bottom: 20px;
        }

        p {
            color: #555;
            line-height: 1.6;
            margin: 10px 0;
        }

        .footer {
            margin-top: 20px;
            padding: 10px 0;
            border-top: 1px solid #eaeaea;
            text-align: center;
            color: #777;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .footer img {
            width: 40px;
            /* Tamaño del avatar */
            height: 40px;
            border-radius: 50%;
            /* Hacerlo circular */
            margin-right: 10px;
            /* Espacio entre la imagen y el texto */
        }

        .highlight {
            color: #007BFF;
            /* Color destacado */
            font-weight: bold;
        }
    </style>
</head>

<body>
    <div class="container">
        <h1>Hola, {{ $data['to_name'] }}</h1>

        <p>Se acaba de generar un nuevo seguimiento a <span class="highlight">{{ $data['customer']->name }}</span>, cuya
            dirección pertenece a tus zonas asignadas.</p>
        <p>El modelo de interés es <span class="highlight">{{ $data['follow_up']->vehicle->name }}</span>.</p>
        <p>Favor de ponerte en contacto con <span class="highlight">{{ $data['follow_up']->employee->fullName }}</span>
            de la sucursal <span class="highlight">{{ $data['follow_up']->employee->agency->name }}</span>, quien fue
            quien creó el seguimiento.</p>

        <p>Puedes consultar el seguimiento en la plataforma <a href="https://intranet.vw.etbsa-online.com.mx"
                target="_blank"
                style="color: #007BFF; text-decoration: none;">https://intranet.vw.etbsa-online.com.mx</a></p>

        <p>Gracias por tu atención.</p>

        <div class="footer">
            <img src="{{ asset('storage/images/logo.png') }}" alt="Avatar"> <!-- Cambia el path si es necesario -->
            <p>VW Camiones y autobuses Bajio &copy; {{ date('Y') }}</p>
        </div>
    </div>
</body>

</html>
