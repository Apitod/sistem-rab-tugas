@extends('layouts.app')

@section('header_title', 'Manajemen Aset')

@section('content')
@php
    $assets = \App\Models\Asset::orderBy('created_at','desc')->get();
@endphp
<div class="bg-white rounded-xl shadow p-6">
    <h2 class="text-lg font-semibold text-gray-700 mb-4">Daftar Aset</h2>

    @if($assets->isEmpty())
        <p class="text-gray-400 text-center py-12">Belum ada data aset terdaftar.</p>
    @else
        <div class="overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead>
                    <tr class="bg-gray-50 text-gray-500 uppercase text-xs">
                        <th class="text-left px-4 py-3">#</th>
                        <th class="text-left px-4 py-3">Nama Aset</th>
                        <th class="text-center px-4 py-3">Qty</th>
                        <th class="text-left px-4 py-3">Kondisi</th>
                        <th class="text-left px-4 py-3">Tgl Masuk</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($assets as $i => $a)
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-3 text-gray-400">{{ $i + 1 }}</td>
                        <td class="px-4 py-3 font-medium text-gray-800">{{ $a->name ?? $a->item_name ?? '-' }}</td>
                        <td class="px-4 py-3 text-center">{{ $a->quantity ?? '-' }}</td>
                        <td class="px-4 py-3 text-gray-500">{{ $a->condition ?? '-' }}</td>
                        <td class="px-4 py-3 text-gray-500">{{ optional($a->created_at)->format('d M Y') }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>
@endsection
