@extends('layouts.app')

@section('header_title', 'Dashboard Tata Usaha')

@section('content')
@php
    $approved = \App\Models\RabProposal::where('status','disetujui')->count();
    $thisMonth = \App\Models\RabProposal::where('status','disetujui')
        ->whereMonth('updated_at', now()->month)->sum('total_budget');
@endphp
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
    <div class="bg-white rounded-xl shadow p-6 flex items-center space-x-4">
        <div class="w-12 h-12 rounded-full bg-green-100 flex items-center justify-center">
            <i class="fa-solid fa-circle-check text-success text-xl"></i>
        </div>
        <div>
            <p class="text-sm text-gray-500">Total RAB Disetujui</p>
            <p class="text-2xl font-bold text-gray-800">{{ $approved }}</p>
        </div>
    </div>
    <div class="bg-white rounded-xl shadow p-6 flex items-center space-x-4">
        <div class="w-12 h-12 rounded-full bg-blue-100 flex items-center justify-center">
            <i class="fa-solid fa-calendar-check text-secondary text-xl"></i>
        </div>
        <div>
            <p class="text-sm text-gray-500">Anggaran Bulan Ini</p>
            <p class="text-2xl font-bold text-gray-800">Rp {{ number_format($thisMonth, 0, ',', '.') }}</p>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    <div class="bg-white rounded-xl shadow p-6">
        <h2 class="text-lg font-semibold text-gray-700 mb-3">Aksi Cepat</h2>
        <div class="space-y-3">
            <a href="{{ route('tu.laporan.index') }}" class="flex items-center space-x-3 p-3 rounded-lg border hover:border-secondary hover:bg-blue-50 transition-colors">
                <i class="fa-solid fa-file-pdf text-red-500 text-lg w-6"></i>
                <span class="text-sm font-medium text-gray-700">Lihat &amp; Export Laporan</span>
            </a>
            <a href="{{ route('tu.kwitansi.index') }}" class="flex items-center space-x-3 p-3 rounded-lg border hover:border-secondary hover:bg-blue-50 transition-colors">
                <i class="fa-solid fa-receipt text-emerald-500 text-lg w-6"></i>
                <span class="text-sm font-medium text-gray-700">Manajemen Kwitansi</span>
            </a>
            <a href="{{ route('tu.aset.index') }}" class="flex items-center space-x-3 p-3 rounded-lg border hover:border-secondary hover:bg-blue-50 transition-colors">
                <i class="fa-solid fa-boxes-stacked text-yellow-500 text-lg w-6"></i>
                <span class="text-sm font-medium text-gray-700">Manajemen Aset</span>
            </a>
        </div>
    </div>
</div>
@endsection
