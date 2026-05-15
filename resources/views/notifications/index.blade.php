@extends('layouts.app')

@section('title', 'Notifications')

@section('breadcrumb')
    <li class="flex items-center">
        <svg class="w-4 h-4 text-gray-400 mx-2" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd"
                d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                clip-rule="evenodd"></path>
        </svg>
        <span class="text-gray-500 text-sm">Notifications</span>
    </li>
@endsection

@section('content')
    <div class="max-w-4xl mx-auto py-8 px-4">
        <!-- Header -->
        <div class="flex items-center justify-between mb-8 pb-4 border-b border-gray-100">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">Votre Centre de Notifications</h1>
                <p class="text-gray-500 text-sm mt-1">Gérez vos alertes et mises à jour système.</p>
            </div>

            @if($notifications->count() > 0)
                <form action="{{ route('notifications.markAllAsRead') }}" method="POST">
                    @csrf
                    <button type="submit"
                        class="text-sm font-semibold text-blue-600 hover:text-blue-700 flex items-center px-4 py-2 bg-blue-50 rounded-lg transition-colors">
                        <i class="fas fa-check-double mr-2"></i>
                        Tout marquer comme lu
                    </button>
                </form>
            @endif
        </div>

        <!-- Notification List -->
        <div class="bg-white rounded-lg border border-gray-200 shadow-sm overflow-hidden">
            <div class="divide-y divide-gray-100">
                @forelse($notifications as $notification)
                    <div class="p-5 flex items-start gap-4 hover:bg-gray-50 transition-colors relative">
                        <!-- Unread Marker -->
                        @if(!$notification->read_at)
                            <div class="absolute left-0 top-0 bottom-0 w-1 bg-blue-600"></div>
                        @endif

                        <!-- Simple Icon -->
                        <div class="flex-shrink-0 mt-1">
                            @if(($notification->data['type'] ?? '') === 'error')
                                <div class="w-10 h-10 rounded-full bg-red-50 text-red-500 flex items-center justify-center">
                                    <i class="fas fa-exclamation-circle text-lg"></i>
                                </div>
                            @elseif(($notification->data['type'] ?? '') === 'success')
                                <div class="w-10 h-10 rounded-full bg-green-50 text-green-500 flex items-center justify-center">
                                    <i class="fas fa-check-circle text-lg"></i>
                                </div>
                            @else
                                <div class="w-10 h-10 rounded-full bg-gray-50 text-gray-500 flex items-center justify-center">
                                    <i class="fas fa-bell text-lg"></i>
                                </div>
                            @endif
                        </div>

                        <!-- Content -->
                        <div class="flex-1 min-w-0">
                            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-1 mb-1">
                                <h3 class="text-sm font-bold text-gray-900 truncate">
                                    {{ $notification->data['message'] ?? 'Notification système' }}
                                </h3>
                                <span class="text-[11px] font-medium text-gray-400 whitespace-nowrap">
                                    {{ $notification->created_at->diffForHumans() }}
                                </span>
                            </div>

                            @if(isset($notification->data['description']))
                                <p class="text-sm text-gray-600 line-clamp-2">
                                    {{ $notification->data['description'] }}
                                </p>
                            @endif

                            @if(isset($notification->data['action_url']))
                                <div class="mt-3">
                                    <form action="{{ route('notifications.markAsRead', $notification->id) }}" method="POST"
                                        class="inline">
                                        @csrf
                                        <input type="hidden" name="redirect" value="{{ $notification->data['action_url'] }}">
                                        <button type="submit"
                                            class="inline-flex items-center text-xs font-bold text-blue-600 hover:underline">
                                            Consulter les détails
                                            <i class="fas fa-chevron-right ml-1 text-[8px]"></i>
                                        </button>
                                    </form>
                                </div>
                            @endif
                        </div>

                        <!-- Mark as read tick (small & unobtrusive) -->
                        @if(!$notification->read_at)
                            <form action="{{ route('notifications.markAsRead', $notification->id) }}" method="POST"
                                class="flex-shrink-0">
                                @csrf
                                <button type="submit" class="p-2 text-gray-300 hover:text-green-500 transition-colors"
                                    title="Marquer comme lu">
                                    <i class="fas fa-check"></i>
                                </button>
                            </form>
                        @endif
                    </div>
                @empty
                    <div class="py-20 text-center bg-gray-50/50">
                        <div
                            class="w-16 h-16 bg-white rounded-full flex items-center justify-center border border-gray-100 mx-auto mb-4">
                            <i class="fas fa-bell-slash text-2xl text-gray-200"></i>
                        </div>
                        <h2 class="text-lg font-bold text-gray-800">Aucune notification</h2>
                        <p class="text-sm text-gray-500 mt-2">Vous êtes à jour !</p>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Pagination -->
        @if($notifications->hasPages())
            <div class="mt-6">
                {{ $notifications->links() }}
            </div>
        @endif
    </div>
@endsection