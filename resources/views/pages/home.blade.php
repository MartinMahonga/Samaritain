@extends('layouts.base')

@section('title', 'Accueil')

@section('content')
    <h1 class="text-2xl">Home</h1>
    <p>Nom : {{ auth()->user()->name }}</p>
    <p>Email : {{ auth()->user()->email }}</p>
@endsection
