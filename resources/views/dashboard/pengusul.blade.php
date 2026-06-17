@extends('layouts.app')

@section('header_title', 'Dashboard Pengusul')

@section('content')
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
    @php
        $proposals = \App\Models\RabProposal::where('user_id', auth()->id())->get();
    @endphp
    <div class="bg-white rounded-xl shadow p-6 flex items-center space-x-4">
        <div class="w-12 h-12 rounded-full bg-blue-100 flex items-center justify-center">
            <i class="fa-solid fa-file-invoice-dollar text-secondary text-xl"></i>
        </div>
        <div>
            <p class="text-sm text-gray-500">Total RAB Diajukan</p>
            <p class="text-2xl font-bold text-gray-800">{{ $proposals->count() }}</p>
        </div>
    </div>
    <div class="bg-white rounded-xl shadow p-6 flex items-center space-x-4">
        <div class="w-12 h-12 rounded-full bg-yellow-100 flex items-center justify-center">
            <i class="fa-solid fa-clock text-warning text-xl"></i>
        </div>
        <div>
            <p class="text-sm text-gray-500">Menunggu Verifikasi</p>
            <p class="text-2xl font-bold text-gray-800">{{ $proposals->whereIn('status', ['pending_kaprodi','pending_wd','pending_dekan'])->count() }}</p>
        </div>
    </div>
    <div class="bg-white rounded-xl shadow p-6 flex items-center space-x-4">
        <div class="w-12 h-12 rounded-full bg-green-100 flex items-center justify-center">
            <i class="fa-solid fa-circle-check text-success text-xl"></i>
        </div>
        <div>
            <p class="text-sm text-gray-500">Disetujui</p>
            <p class="text-2xl font-bold text-gray-800">{{ $proposals->where('status', 'approved')->count() }}</p>
        </div>
    </div>
</div>

<div class="bg-white rounded-xl shadow p-6">
    <div class="flex items-center justify-between mb-4">
        <h2 class="text-lg font-semibold text-gray-700">RAB Terbaru</h2>
        <a href="{{ route('pengusul.rab.create') }}" class="bg-secondary text-white px-4 py-2 rounded-lg text-sm hover:bg-blue-700 transition-colors">
            <i class="fa-solid fa-plus mr-1"></i> Ajukan RAB Baru
        </a>
    </div>
    @if($proposals->isEmpty())
        <p class="text-gray-400 text-center py-8">Belum ada RAB yang diajukan. <a href="{{ route('pengusul.rab.create') }}" class="text-secondary underline">Ajukan sekarang</a>.</p>
    @else
        <div class="overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead>
                    <tr class="bg-gray-50 text-gray-500 uppercase text-xs">
                        <th class="text-left px-4 py-3">Judul RAB</th>
                        <th class="text-left px-4 py-3">Tanggal Diajukan</th>
                        <th class="text-right px-4 py-3">Total Anggaran</th>
                        <th class="text-center px-4 py-3">Status</th>
                        <th class="text-center px-4 py-3">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($proposals->sortByDesc('created_at')->take(10) as $p)
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-3 font-medium text-gray-800">{{ $p->title }}</td>
                        <td class="px-4 py-3 text-gray-500">{{ $p->proposed_date }}</td>
                        <td class="px-4 py-3 text-right">Rp {{ number_format($p->total_budget, 0, ',', '.') }}</td>
                        <td class="px-4 py-3 text-center">
                            @php
                                $colors = ['pending_kaprodi'=>'yellow','pending_wd'=>'yellow','pending_dekan'=>'blue','approved'=>'green','rejected'=>'red','revision'=>'orange'];
                                $c = $colors[$p->status] ?? 'gray';
                            @endphp
                            <span class="px-2 py-1 rounded-full text-xs font-semibold bg-{{ $c }}-100 text-{{ $c }}-700">
                                {{ ucfirst(str_replace('_', ' ', $p->status)) }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-center">
                            <a href="{{ route('pengusul.rab.show', $p->id) }}" class="text-secondary hover:underline text-xs">Detail</a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>
@endsection
