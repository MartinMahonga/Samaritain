@extends('layouts.base')

@isset($title)
    @section('title', $title)
@endisset

@section('content')
    {{ $slot }}
@endsection
