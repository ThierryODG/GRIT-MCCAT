@extends('layouts.app')

@section('title', 'Mon Profil')

@section('content')
    <div class="py-12 bg-gray-50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">

            <!-- Header Section -->
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Mon Profil</h1>
                    <p class="mt-1 text-sm text-gray-600">Gérez vos informations personnelles et sécurisez votre compte.</p>
                </div>
                <div class="hidden sm:block">
                    <span
                        class="inline-flex items-center px-4 py-2 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                        <i class="fas fa-user-shield mr-2"></i>
                        {{ Auth::user()->role_label ?? 'Utilisateur' }}
                    </span>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Left Column: Navigation/Summary (Optional, can be removed if not needed) -->
                <div class="lg:col-span-1 space-y-6">
                    <!-- User Card -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-xl border border-gray-100">
                        <div class="p-6 text-center">
                            <div
                                class="w-24 h-24 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-full flex items-center justify-center mx-auto mb-4 shadow-lg">
                                <span class="text-3xl font-bold text-white">{{ substr(Auth::user()->name, 0, 1) }}</span>
                            </div>
                            <h2 class="text-xl font-bold text-gray-900">{{ Auth::user()->name }}</h2>
                            <p class="text-sm text-gray-500">{{ Auth::user()->email }}</p>
                            <div class="mt-4 pt-4 border-t border-gray-100">
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-500">Membre depuis</span>
                                    <span
                                        class="font-medium text-gray-900">{{ Auth::user()->created_at->format('M Y') }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Column: Forms -->
                <div class="lg:col-span-2 space-y-8">
                    <!-- Update Profile Information -->
                    <div
                        class="bg-white overflow-hidden shadow-sm sm:rounded-xl border border-gray-100 transition hover:shadow-md">
                        <div class="p-6 sm:p-8">
                            <div class="flex items-center mb-6">
                                <div class="p-2 bg-blue-50 rounded-lg mr-4">
                                    <i class="fas fa-id-card text-blue-600 text-xl"></i>
                                </div>
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-900">Informations du profil</h3>
                                    <p class="text-sm text-gray-500">Mettez à jour vos informations de profil et votre
                                        adresse email.</p>
                                </div>
                            </div>
                            <div class="max-w-xl">
                                @include('profile.partials.update-profile-information-form')
                            </div>
                        </div>
                    </div>

                    <!-- Update Password -->
                    <div
                        class="bg-white overflow-hidden shadow-sm sm:rounded-xl border border-gray-100 transition hover:shadow-md">
                        <div class="p-6 sm:p-8">
                            <div class="flex items-center mb-6">
                                <div class="p-2 bg-indigo-50 rounded-lg mr-4">
                                    <i class="fas fa-lock text-indigo-600 text-xl"></i>
                                </div>
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-900">Sécurité</h3>
                                    <p class="text-sm text-gray-500">Assurez-vous d'utiliser un mot de passe long et
                                        aléatoire pour rester en sécurité.</p>
                                </div>
                            </div>
                            <div class="max-w-xl">
                                @include('profile.partials.update-password-form')
                            </div>
                        </div>
                    </div>

                    <!-- Delete User -->
                    <div
                        class="bg-white overflow-hidden shadow-sm sm:rounded-xl border border-red-100 transition hover:shadow-md">
                        <div class="p-6 sm:p-8">
                            <div class="flex items-center mb-6">
                                <div class="p-2 bg-red-50 rounded-lg mr-4">
                                    <i class="fas fa-exclamation-triangle text-red-600 text-xl"></i>
                                </div>
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-900 text-red-600">Zone Danger</h3>
                                    <p class="text-sm text-gray-500">Supprimer définitivement votre compte.</p>
                                </div>
                            </div>
                            <div class="max-w-xl">
                                @include('profile.partials.delete-user-form')
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection