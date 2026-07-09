@extends('layouts.app')

@section('header_title', 'Manajemen Kwitansi')

@section('content')
<div class="max-w-5xl space-y-6">

    {{-- Header --}}
    <div class="bg-white rounded-xl shadow p-6">
        <div class="flex items-center justify-between mb-4">
            <div>
                <h2 class="text-xl font-bold text-gray-800 flex items-center gap-2">
                    <i class="fa-solid fa-receipt text-secondary"></i>
                    Manajemen Kwitansi RAB
                </h2>
                <p class="text-sm text-gray-500 mt-1">Daftar RAB yang telah disetujui dan siap diterbitkan kwitansi.</p>
            </div>
        </div>

        {{-- Filter --}}
        <form method="GET" action="{{ route('tu.kwitansi.index') }}" class="flex flex-wrap gap-3 items-end">
            <div>
                <label class="block text-xs font-medium text-gray-600 mb-1">Cari</label>
                <input type="text" name="search" value="{{ request('search') }}"
                       placeholder="Judul / Nomor RAB..."
                       class="border rounded px-3 py-2 text-sm focus:ring-1 focus:ring-secondary focus:outline-none w-56">
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-600 mb-1">Dari</label>
                <input type="date" name="from" value="{{ request('from') }}"
                       class="border rounded px-3 py-2 text-sm focus:ring-1 focus:ring-secondary focus:outline-none">
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-600 mb-1">Sampai</label>
                <input type="date" name="to" value="{{ request('to') }}"
                       class="border rounded px-3 py-2 text-sm focus:ring-1 focus:ring-secondary focus:outline-none">
            </div>
            <div class="flex gap-2">
                <button type="submit" class="bg-secondary text-white px-4 py-2 rounded-lg text-sm hover:bg-blue-700">
                    <i class="fa-solid fa-filter mr-1"></i> Filter
                </button>
                @if(request()->hasAny(['search','from','to']))
                <a href="{{ route('tu.kwitansi.index') }}" class="border border-gray-300 text-gray-600 px-4 py-2 rounded-lg text-sm hover:bg-gray-50">
                    <i class="fa-solid fa-xmark mr-1"></i> Reset
                </a>
                @endif
            </div>
        </form>
    </div>

    {{-- Session Messages --}}
    @if(session('success'))
    <div class="bg-green-100 border border-green-300 text-green-800 px-4 py-3 rounded-lg text-sm">
        <i class="fa-solid fa-circle-check mr-2"></i>{{ session('success') }}
    </div>
    @endif

    {{-- Tabel Kwitansi --}}
    <div class="bg-white rounded-xl shadow overflow-hidden">
        @if($proposals->isEmpty())
            <div class="flex flex-col items-center justify-center py-16 text-gray-400">
                <i class="fa-solid fa-receipt text-5xl mb-3 text-gray-200"></i>
                <p class="text-sm">Belum ada RAB yang disetujui untuk diterbitkan kwitansi.</p>
            </div>
        @else
            {{-- Summary bar --}}
            <div class="px-6 py-3 bg-emerald-50 border-b border-emerald-100 flex items-center gap-4">
                <span class="text-sm text-emerald-700">
                    <i class="fa-solid fa-circle-check mr-1"></i>
                    <strong>{{ $proposals->count() }}</strong> RAB disetujui
                </span>
                <span class="text-sm text-emerald-600">—</span>
                <span class="text-sm font-semibold text-emerald-800">
                    Total: Rp {{ number_format($proposals->sum('total_budget'), 0, ',', '.') }}
                </span>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead>
                        <tr class="bg-gray-50 text-gray-500 uppercase text-xs border-b">
                            <th class="text-left px-4 py-3">#</th>
                            <th class="text-left px-4 py-3">Nomor RAB</th>
                            <th class="text-left px-4 py-3">Judul RAB</th>
                            <th class="text-left px-4 py-3">Pengusul</th>
                            <th class="text-left px-4 py-3">Tgl Disetujui</th>
                            <th class="text-right px-4 py-3">Total Anggaran</th>
                            <th class="text-center px-4 py-3">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach($proposals as $i => $p)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-4 py-3 text-gray-400">{{ $i + 1 }}</td>
                            <td class="px-4 py-3">
                                <span class="font-mono text-sm font-semibold text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded">
                                    {{ $p->rab_number ?? '-' }}
                                </span>
                            </td>
                            <td class="px-4 py-3 font-medium text-gray-800">{{ $p->title }}</td>
                            <td class="px-4 py-3 text-gray-500">{{ $p->user->name ?? '-' }}</td>
                            <td class="px-4 py-3 text-gray-500 text-xs">{{ $p->updated_at->format('d M Y') }}</td>
                            <td class="px-4 py-3 text-right font-semibold text-gray-800">
                                Rp {{ number_format($p->total_budget, 0, ',', '.') }}
                            </td>
                            <td class="px-4 py-3 text-center">
                                <a href="{{ route('tu.kwitansi.show', $p->id) }}"
                                   class="inline-flex items-center gap-1.5 bg-secondary text-white px-3 py-1.5 rounded-lg text-xs hover:bg-blue-700 transition-colors">
                                    <i class="fa-solid fa-receipt"></i> Kwitansi
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>

</div>
@endsection
