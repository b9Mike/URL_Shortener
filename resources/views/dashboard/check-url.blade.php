@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">

            <div class="col-md-12 mb-4">

                 <div class="card p-4 shadow">
                    <h2 class="card-title text-center">Esta URL está protegida por contraseña</h2>

                    <form method="POST" action="{{ route('verify.password', ['code' => $code]) }}">
                        @csrf
                        <div class="mb-3">
                            @error('password')
                                <div class="alert alert-danger">
                                    {{ $message  }}
                                </div>
                            @enderror
                            <label for="password" class="form-label">Contraseña:</label>
                            <input type="password" name="password" id="password" class="form-control" required>
                        </div>

                        <button type="submit" class="btn  btn-primary-custom btn-lg d-block mx-auto mt-4">Verificar</button>
                    </form>

                 </div>
            </div>

        </div>
    </div>
@endsection