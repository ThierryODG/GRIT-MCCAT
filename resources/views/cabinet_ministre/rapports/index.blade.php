@extends('layouts.app')

@section('title', 'Cabinet Ministre')

@section('breadcrumb')
    <li>
        <div class="flex items-center">
            <svg class="w-4 h-4 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
            </svg>
            <span class="ml-1 text-gray-700">Rapport</span>
        </div>
    </li>
@endsection

@section('content')
    <div class="p-6 bg-white rounded-lg shadow">
        <h1 class="text-2xl font-bold text-gray-800">Tableau de Bord du Cabinet du Ministre</h1>
        <p class="mt-2 text-gray-600">Bienvenue, {{ Auth::user()->name }} !</p>
    </div>
@endsection
