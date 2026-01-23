@extends('layouts.admin')

@section('admin-content')
    <div class="admin-container">

        <!-- ENCABEZADO -->
        <div class="panel-header">
            <h2 class="title">Reportes Generales del Sistema</h2>

            <div class="actions-top">
                <a href="{{ route('admin.reportes.export.excel') }}" class="btn-create" target="_blank">

                    📊 Exportar Excel
                </a>

                <a href="{{ route('admin.reportes.export.pdf') }}" class="btn-create" target="_blank"
                    style="background:#6c757d;">

                    📄 Exportar PDF
                </a>
            </div>
        </div>

        <!-- RESUMEN GENERAL -->
        <div class="table-wrapper">
            <table class="modern-table">
                <thead>
                    <tr>
                        <th>Indicador</th>
                        <th>Total</th>
                    </tr>
                </thead>

                <tbody>
                    <tr>
                        <td>Empresas Registradas</td>
                        <td><strong>{{ $empresas }}</strong></td>
                    </tr>

                    <tr>
                        <td>Estudiantes Registrados</td>
                        <td><strong>{{ $estudiantes }}</strong></td>
                    </tr>

                    <tr>
                        <td>Ofertas Activas</td>
                        <td><strong>{{ $ofertas_activas }}</strong></td>
                    </tr>

                    <tr>
                        <td>Postulaciones Totales</td>
                        <td><strong>{{ $postulaciones }}</strong></td>
                    </tr>
                </tbody>
            </table>
        </div>

    </div>
@endsection
