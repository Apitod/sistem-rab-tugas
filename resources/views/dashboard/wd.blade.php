@extends('layouts.app')

@section('header_title', 'Dashboard WD Keuangan')

@section('content')
<div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
    <div class="bg-white rounded-xl shadow p-6 flex items-center space-x-4">
        <div class="w-12 h-12 rounded-full bg-yellow-100 flex items-center justify-center">
            <i class="fa-solid fa-hourglass-half text-warning text-xl"></i>
        </div>
        <div>
            <p class="text-sm text-gray-500">Menunggu Verifikasi WD</p>
            <p class="text-2xl font-bold text-gray-800">{{ $proposals->count() }}</p>
        </div>
    </div>
    <div class="bg-white rounded-xl shadow p-6 flex items-center space-x-4">
        <div class="w-12 h-12 rounded-full bg-blue-100 flex items-center justify-center">
            <i class="fa-solid fa-money-bill-wave text-secondary text-xl"></i>
        </div>
        <div>
            <p class="text-sm text-gray-500">Total Anggaran Pending</p>
            <p class="text-2xl font-bold text-gray-800">Rp {{ number_format($proposals->sum('total_budget'), 0, ',', '.') }}</p>
        </div>
    </div>
</div>

<div class="bg-white rounded-xl shadow p-6">
    <h2 class="text-lg font-semibold text-gray-700 mb-4">RAB Menunggu Verifikasi WD</h2>
    @if($proposals->isEmpty())
        <p class="text-gray-400 text-center py-8">Tidak ada RAB yang perlu diverifikasi.</p>
    @else
        <div class="overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead>
                    <tr class="bg-gray-50 text-gray-500 uppercase text-xs">
                        <th class="text-left px-4 py-3">Judul RAB</th>
                        <th class="text-left px-4 py-3">Pengusul</th>
                        <th class="text-left px-4 py-3">Tanggal</th>
                        <th class="text-right px-4 py-3">Total</th>
                        <th class="text-center px-4 py-3">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($proposals as $p)
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-3 font-medium text-gray-800">{{ $p->title }}</td>
                        <td class="px-4 py-3 text-gray-500">{{ $p->user->name ?? '-' }}</td>
                        <td class="px-4 py-3 text-gray-500">{{ $p->proposed_date }}</td>
                        <td class="px-4 py-3 text-right">Rp {{ number_format($p->total_budget, 0, ',', '.') }}</td>
                        <td class="px-4 py-3 text-center">
                            <a href="{{ route('wd.rab.show', $p->id) }}" class="text-secondary hover:underline text-xs">Review</a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>
@endsection
