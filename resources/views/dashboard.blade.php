<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            Tableau de Bord Administrateur
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="grid grid-cols-1 gap-6 md:grid-cols-3">
                        <div class="p-6 rounded-lg bg-blue-50">
                            <h3 class="mb-2 text-lg font-semibold">Utilisateurs</h3>
                            <p class="text-3xl font-bold">{{ \App\Models\User::count() }}</p>
                            {{-- <a href="{{ route('admin.users.index') }}"
                                class="inline-block mt-2 text-blue-600 hover:text-blue-800">Gérer →</a> --}}
                        </div>

                        <div class="p-6 rounded-lg bg-green-50">
                            <h3 class="mb-2 text-lg font-semibold">rrrrrrrrrrrrrrrrrrRôles</h3>
                            <p class="text-3xl font-bold">6</p>
                            <span class="inline-block mt-2 text-gray-600">Admin, ITS, Inspecteur, etc.</span>
                        </div>

                        <div class="p-6 rounded-lg bg-purple-50">
                            <h3 class="mb-2 text-lg font-semibold">Système</h3>
                            <p class="text-lg">SIGR-ITS - Suivi ITS</p>
                            <span class="text-gray-600">Version 1.0</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>