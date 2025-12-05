@extends('layouts.app')

@section('content')
<h1>Crear Categoría</h1>
<form method="POST" action="{{ route('categories.store') }}">
    @csrf
    <div class="mb-3">
        <label class="form-label">Nombre</label>
        <input type="text" class="form-control" name="name" required>
    </div>
    <div class="mb-3">
        <label class="form-label">Descripción</label>
        <input type="text" class="form-control" name="description">
    </div>
    <button type="submit" class="btn btn-primary">Guardar</button>
</form>
<a href="{{ route('categories.index') }}">Volver</a>
@endsection
