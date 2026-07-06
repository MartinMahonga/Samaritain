@extends('layouts.base')

@section('title', 'Payment Status')

@section('content')
    <div class="container mt-5">
        @if (session('success'))
            <div class="alert alert-success" role="alert">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger" role="alert">
                {{ session('error') }}
            </div>
        @endif

        <h1>Payment Status</h1>
        <p>User: {{ $transaction->user->name }}</p>
        <p>Amount: {{ $transaction->amount }}</p>
        <p>Status: {{ $transaction->status }}</p>
        <p>Transaction ID: {{ $transaction->transaction_id }}</p>
    </div>
@endsection