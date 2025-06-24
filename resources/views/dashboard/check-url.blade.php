@extends('layouts.app')

@section('content')
    <div class="container">
        <h2>Esta URL está protegida por contraseña</h2>

        <form method="POST" action="{{ route('verify.password', ['code' => $code]) }}">
            @csrf
            <div class="mb-3">
                <label for="password" class="form-label">Contraseña:</label>
                <input type="password" name="password" id="password" class="form-control" required>
            </div>

            <button type="submit" class="btn btn-primary">Verificar</button>
        </form>
    </div>
@endsection