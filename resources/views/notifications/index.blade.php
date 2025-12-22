@extends('layouts.app')

@section('title', 'Notifications')

@section('breadcrumb')
    <li class="flex items-center">
        <svg class="w-4 h-4 text-gray-400 mx-2" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd"
                d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                clip-rule="evenodd"></path>
        </svg>
        <span class="text-gray-500 font-medium">Centre de notifications</span>
    </li>
@endsection

@section('content')
    <div class="max-w-4xl mx-auto space-y-8 py-6">

        <!-- Header Section -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h1 class="text-3xl font-black text-gray-900 tracking-tight">Vos Notifications</h1>
                <p class="text-gray-500 font-medium mt-1">Restez informé des dernières mises à jour de vos missions.</p>
            </div>

            @if($notifications->count() > 0)
                <form action="{{ route('notifications.markAllAsRead') }}" method="POST">
                    @csrf
                    <button type="submit"
                        class="inline-flex items-center px-6 py-3 bg-white border border-gray-200 rounded-2xl text-sm font-bold text-gray-700 hover:bg-gray-50 hover:border-indigo-300 hover:text-indigo-600 shadow-sm transition-all duration-200">
                        <i class="fas fa-check-double mr-2 text-xs"></i>
                        Tout marquer comme lu
                    </button>
                </form>
            @endif
        </div>

        <!-- Notifications List -->
        <div class="bg-white rounded-[2.5rem] border border-gray-100 shadow-xl shadow-gray-100/50 overflow-hidden">
            <div class="divide-y divide-gray-50">
                @forelse($notifications as $notification)
                    <div class="group relative p-8 hover:bg-indigo-50/20 transition-all duration-300 flex items-start 
                            {{ $notification->read_at ? 'opacity-60' : 'bg-blue-50/10' }}">

                        <!-- Decorative status dot for unread -->
                        @if(!$notification->read_at)
                            <div class="absolute left-0 top-0 bottom-0 w-1 bg-indigo-600 rounded-r-full"></div>
                        @endif

                        <!-- Icon Container -->
                        <div class="flex-shrink-0 mr-6">
                            <div class="w-14 h-14 rounded-2xl flex items-center justify-center shadow-lg transition-transform group-hover:scale-110
                                    @if(($notification->data['type'] ?? '') === 'error') bg-rose-50 text-rose-600 
                                    @elseif(($notification->data['type'] ?? '') === 'success') bg-emerald-50 text-emerald-600 
                                    @else bg-indigo-50 text-indigo-600 @endif">

                                @if(isset($notification->data['icon']))
                                    <i class="fas 
                                                @if($notification->data['icon'] === 'check-circle') fa-check-circle 
                                                @elseif($notification->data['icon'] === 'x-circle') fa-times-circle 
                                                @else fa-bell @endif text-xl"></i>
                                @else
                                    <i class="fas fa-bell text-xl"></i>
                                @endif
                            </div>
                        </div>

                        <!-- Content Section -->
                        <div class="flex-1 min-w-0">
                            <div class="flex flex-col md:flex-row md:items-center justify-between gap-2 mb-2">
                                <h4 class="text-base font-black text-gray-900 group-hover:text-indigo-700 transition-colors">
                                    {{ $notification->data['message'] ?? 'Nouvelle notification' }}
                                </h4>
                                <span
                                    class="text-xs font-bold text-gray-400 bg-gray-50 px-3 py-1 rounded-full whitespace-nowrap">
                                    {{ $notification->created_at->diffForHumans() }}
                                </span>
                            </div>

                            @if(isset($notification->data['action_url']))
                                <div class="mt-4 flex items-center space-x-4">
                                    <form action="{{ route('notifications.markAsRead', $notification->id) }}" method="POST"
                                        class="inline">
                                        @csrf
                                        <input type="hidden" name="redirect" value="{{ $notification->data['action_url'] }}">
                                        <button type="submit"
                                            class="inline-flex items-center text-sm font-black text-indigo-600 hover:text-indigo-800 tracking-tight group/btn">
                                            Consulter les détails
                                            <i
                                                class="fas fa-arrow-right ml-2 text-[10px] transition-transform group-hover/btn:translate-x-1"></i>
                                        </button>
                                    </form>
                                </div>
                            @endif
                        </div>

                        <!-- Quick Mark As Read (only for unread) -->
                        @if(!$notification->read_at)
                            <div class="ml-6 opacity-0 group-hover:opacity-100 transition-opacity">
                                <form action="{{ route('notifications.markAsRead', $notification->id) }}" method="POST">
                                    @csrf
                                    <button type="submit"
                                        class="w-10 h-10 rounded-xl bg-white border border-gray-100 text-gray-400 hover:text-emerald-600 hover:border-emerald-200 transition-all shadow-sm"
                                        title="Marquer comme lu">
                                        <i class="fas fa-check"></i>
                                    </button>
                                </form>
                            </div>
                        @endif
                    </div>
                @empty
                    <div class="py-24 text-center">
                        <div class="w-24 h-24 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-6">
                            <i class="fas fa-envelope-open text-4xl text-gray-200"></i>
                        </div>
                        <h3 class="text-xl font-black text-gray-900">Aucune notification</h3>
                        <p class="text-gray-500 font-medium max-w-xs mx-auto mt-2">Vous avez traité toutes vos notifications
                            récentes. Revenez plus tard !</p>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Pagination Container -->
        @if($notifications->hasPages())
            <div class="px-8 py-6 bg-white border border-gray-100 rounded-[2.5rem] shadow-xl shadow-gray-100/50">
                {{ $notifications->links() }}
            </div>
        @endif
    </div>
@endsection