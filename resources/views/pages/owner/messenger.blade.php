@extends('layouts.owner')

@section('title', 'Messagerie')

@section('content')
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-800 dark:text-white">Messagerie</h1>
        <p class="text-gray-500 dark:text-gray-400 mt-1">Communiquez avec vos locataires.</p>
    </div>

    <x-messenger.widget />
@endsection