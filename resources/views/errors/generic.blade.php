@extends('layouts.app')

@section('content')
<div class="container text-center mt-5">
    <h2>Error</h2>
    <p>{{ $message ?? 'Error inesperado' }}</p>
    <a href="{{ url()->previous() }}" class="btn btn-primary">Volver</a>
</div>
@endsection
