<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Reporte Empleabilidad CFT Magallanes</title>

    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
            color: #333;
        }

        h1 {
            text-align: center;
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 25px;
        }

        th,
        td {
            border: 1px solid #999;
            padding: 6px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }

        .section-title {
            margin-top: 30px;
            margin-bottom: 10px;
            font-weight: bold;
            font-size: 14px;
        }
    </style>
</head>

<body>
    <h1>Reporte General de Empleabilidad</h1>

    <table>
        <tr>
            <th>Empresas Registradas</th>
            <td>{{ $empresas }}</td>
        </tr>
        <tr>
            <th>Estudiantes Registrados</th>
            <td>{{ $estudiantes }}</td>
        </tr>
        <tr>
            <th>Ofertas Activas</th>
            <td>{{ $ofertas_activas }}</td>
        </tr>
        <tr>
            <th>Postulaciones Totales</th>
            <td>{{ $postulaciones }}</td>
        </tr>
    </table>

    <div class="section-title">Detalle de Empresas</div>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre Comercial</th>
                <th>Correo</th>
                <th>RUT</th>
                <th>Fecha Registro</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($empresas_list as $e)
                <tr>
                    <td>{{ $e->id }}</td>
                    <td>{{ $e->nombre }}</td>
                    <td>{{ $e->email }}</td>
                    <td>{{ $e->rut }}</td>
                    <td>{{ $e->creado_en }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="section-title">Detalle de Estudiantes</div>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Correo</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($estudiantes_list as $s)
                <tr>
                    <td>{{ $s->id }}</td>
                    <td>{{ $s->nombre }} {{ $s->apellido }}</td>
                    <td>{{ $s->email }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

</body>

</html>
