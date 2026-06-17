@extends('layouts.app')

@section('header_title', 'Daftar RAB Saya')

@section('content')
<div class="bg-white rounded-xl shadow p-6">
    <div class="flex items-center justify-between mb-4">
        <h2 class="text-lg font-semibold text-gray-700">RAB yang Diajukan</h2>
        <a href="{{ route('pengusul.rab.create') }}" class="bg-secondary text-white px-4 py-2 rounded-lg text-sm hover:bg-blue-700 transition-colors">
            <i class="fa-solid fa-plus mr-1"></i> Ajukan RAB Baru
        </a>
    </div>

    @if($proposals->isEmpty())
        <p class="text-gray-400 text-center py-12">Belum ada RAB. <a href="{{ route('pengusul.rab.create') }}" class="text-secondary underline">Ajukan sekarang</a>.</p>
    @else
        <div class="overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead>
                    <tr class="bg-gray-50 text-gray-500 uppercase text-xs">
                        <th class="text-left px-4 py-3">#</th>
                        <th class="text-left px-4 py-3">Judul RAB</th>
                        <th class="text-left px-4 py-3">Tanggal Diajukan</th>
                        <th class="text-right px-4 py-3">Total Anggaran</th>
                        <th class="text-center px-4 py-3">Status</th>
                        <th class="text-center px-4 py-3">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($proposals as $i => $p)
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-3 text-gray-400">{{ $i + 1 }}</td>
                        <td class="px-4 py-3 font-medium text-gray-800">{{ $p->title }}</td>
                        <td class="px-4 py-3 text-gray-500">{{ $p->proposed_date }}</td>
                        <td class="px-4 py-3 text-right font-medium">Rp {{ number_format($p->total_budget, 0, ',', '.') }}</td>
                        <td class="px-4 py-3 text-center">
                            @php
                                $colors = ['pending_kaprodi'=>'yellow','pending_wd'=>'yellow','pending_dekan'=>'blue','approved'=>'green','rejected'=>'red','revision'=>'orange'];
                                $labels = ['pending_kaprodi'=>'Menunggu Kaprodi','pending_wd'=>'Menunggu WD','pending_dekan'=>'Menunggu Dekan','approved'=>'Disetujui','rejected'=>'Ditolak','revision'=>'Perlu Revisi'];
                                $c = $colors[$p->status] ?? 'gray';
                            @endphp
                            <span class="px-2 py-1 rounded-full text-xs font-semibold bg-{{ $c }}-100 text-{{ $c }}-700">
                                {{ $labels[$p->status] ?? ucfirst($p->status) }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-center">
                            <a href="{{ route('pengusul.rab.show', $p->id) }}" class="text-secondary hover:underline text-xs font-medium">Detail</a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>
@endsection
