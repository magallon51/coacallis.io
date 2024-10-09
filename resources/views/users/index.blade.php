@extends('layouts.app')

@section('content')
    <div class="container">
        <h1 class="mb-4">Lista de Usuarios</h1>


        @livewire('users-index')
    </div>
@endsection
