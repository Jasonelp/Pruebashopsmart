@extends('layouts.app')

@section('content')
<h1>Editar Categoría</h1>
<form method="POST" action="{{ route('categories.update', $category->id) }}">
    @csrf
    @method('PUT')
    <div class="mb-3">
        <label class="form-label">Nombre</label>
        <input type="text" class="form-control" name="name" value="{{ $category->name }}" required>
    </div>
    <div class="mb-3">
        <label class="form-label">Descripción</label>
        <input type="text" class="form-control" name="description" value="{{ $category->description }}">
    </div>
    <button type="submit" class="btn btn-primary">Actualizar</button>
</form>
<a href="{{ route('categories.index') }}">Volver</a>
@endsection
