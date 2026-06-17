@extends('layouts.app')

@section('header_title', 'Notifikasi')

@section('content')
<div class="bg-white rounded-xl shadow p-6 max-w-3xl">
    <div class="flex items-center justify-between mb-4">
        <h2 class="text-lg font-semibold text-gray-700">Semua Notifikasi</h2>
        @if($notifications->total() > 0)
        <span class="text-xs text-gray-400">{{ $notifications->total() }} notifikasi</span>
        @endif
    </div>

    @if($notifications->isEmpty())
        <div class="text-center py-12 text-gray-400">
            <i class="fa-solid fa-bell-slash text-4xl mb-3 block"></i>
            <p>Tidak ada notifikasi.</p>
        </div>
    @else
        <ul class="divide-y divide-gray-100">
            @foreach($notifications as $notif)
            <li class="py-4 flex items-start space-x-4 {{ !$notif->is_read ? 'bg-blue-50 -mx-6 px-6' : '' }}">
                <div class="mt-1 w-2.5 h-2.5 rounded-full flex-shrink-0 {{ !$notif->is_read ? 'bg-secondary' : 'bg-gray-300' }}"></div>
                <div class="flex-grow">
                    <p class="text-sm text-gray-800 {{ !$notif->is_read ? 'font-medium' : '' }}">{{ $notif->message }}</p>
                    <p class="text-xs text-gray-400 mt-1">{{ $notif->created_at->diffForHumans() }}</p>
                </div>
                @if(!$notif->is_read)
                <form method="POST" action="{{ route('notifications.read', $notif->id) }}" class="flex-shrink-0">
                    @csrf
                    <button type="submit" class="text-xs text-secondary hover:underline">Tandai dibaca</button>
                </form>
                @endif
            </li>
            @endforeach
        </ul>
        <div class="mt-4">
            {{ $notifications->links() }}
        </div>
    @endif
</div>
@endsection
