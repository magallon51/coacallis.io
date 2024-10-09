@extends('layouts.app')

@section('content')

    @if(session('info'))
        <div class="alert alert-success">
            <strong>{{session('info')}}</strong>
        </div>
    @endif
    <div class="container">
        <h1 class="mb-4">Asignar un rol</h1>
        <div class="card">
            <div class="card-body">
                <p class="h5">Nombre</p>
                <p class="form-control">{{$user->name}}</p>

                <h2 class="h5">Listado de Roles</h2>
                {!! Form::model($user, ['route' => ['users.update', $user], 'method' => 'put']) !!}
                    @foreach ($roles as $role)
                        <div>
                            <label>
                                {!! Form::checkbox('roles[]', $role->id, null, ['class' => 'mr-1']) !!}
                                {{$role->name}}

                            </label>
                        </div>
                    @endforeach

                    {!! Form::submit('Asignar Rol', ['class' => 'btn btn-primary mt-2']) !!}
                {!! Form::close() !!}
            </div>
        </div>
    </div>
@endsection
