@extends('layouts.app')

@push('styles')
   <style>
    .expired-container {
        max-width: 600px;
        margin: 40px auto;
        background-color: white;
        padding: 40px;
        border-radius: 12px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        text-align: center;
        display: flex;
        flex-direction: column;
        align-items: center;
    }

    .expired-container img {
        max-width: 250px;
        margin-bottom: 20px;
    }

    .expired-container h1 {
        color: #e74c3c;
        font-size: 28px;
        margin-bottom: 10px;
    }

    .expired-container p {
        font-size: 16px;
        color: #555;
        margin-bottom: 30px;
    }

    .expired-container button {
        background-color: #7b80d6;
        color: white;
        border: none;
        padding: 12px 24px;
        border-radius: 5px;
        cursor: pointer;
        font-size: 16px;
        transition: background-color 0.3s ease;
    }

    .expired-container button:hover {
        background-color: #5a5ed1;
    }

    .img-expirada {
        width: 100%;
        max-width: 100px;
        height: auto;
    }


   </style>
@endpush


@section('content')
    
    <div class="expired-container">
        <img src="{{ asset('images/404.png') }}" alt="URL expirada" class="img-expirada">
        <h1>URL Expirada</h1>
        <p>La URL que estás intentando acceder ha vencido. Debes reactivarla para volver a usarla.</p>
       
        <a type="submit" href="/"  class="btn btn-primary-custom ">Volver al inicio</a>
        
    </div>

@endsection