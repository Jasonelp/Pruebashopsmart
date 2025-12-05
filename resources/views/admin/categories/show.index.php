@extends('layouts.app')

@section('content')
<h1>Detalle de Categoría</h1>
<p>Nombre: {{ $category->name }}</p>
<p>Descripción: {{ $category->description }}</p>
<a href="{{ route('categories.index') }}">Volver</a>
@endsection
