@extends('layouts.dashboard')

@section('content')
    <!-- Top Grid Section (Three cards) -->
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4 shrink-0">
        <x-card class="aspect-video" />
        <x-card class="aspect-video" />
        <x-card class="aspect-video" />
    </div>

    <!-- Bottom Wide Container -->
    <x-card class="flex-1 min-h-[350px] w-full" />
    hjjkj
    <x-card class="flex-1 min-h-[350px] w-full" />
    <x-card class="flex-1 min-h-[350px] w-full" />
    <x-card class="flex-1 min-h-[350px] w-full" />
    jhjjkkjkkjdkdk
@endsection