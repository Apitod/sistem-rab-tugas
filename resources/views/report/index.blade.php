@extends('layouts.app')

@section('header_title', 'Laporan RAB Disetujui')

@section('content')
<div class="bg-white rounded-xl shadow p-6">
    <div class="flex flex-wrap items-end gap-4 mb-6">
        <form method="GET" action="{{ route('tu.laporan.index') }}" class="flex flex-wrap gap-3">
            <div>
                <label class="block text-xs font-medium text-gray-600 mb-1">Dari</label>
                <input type="date" name="from" value="{{ request('from') }}" class="border rounded px-3 py-2 text-sm focus:ring-1 focus:ring-secondary focus:outline-none">
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-600 mb-1">Sampai</label>
                <input type="date" name="to" value="{{ request('to') }}" class="border rounded px-3 py-2 text-sm focus:ring-1 focus:ring-secondary focus:outline-none">
            </div>
            <div class="flex items-end">
                <button type="submit" class="bg-secondary text-white px-4 py-2 rounded-lg text-sm hover:bg-blue-700">
                    <i class="fa-solid fa-filter mr-1"></i> Filter
                </button>
            </div>
        </form>
        <div class="flex gap-2 ml-auto">
            <a href="{{ route('tu.laporan.pdf', request()->query()) }}" class="bg-red-500 text-white px-3 py-2 rounded-lg text-sm hover:bg-red-600 flex items-center">
                <i class="fa-solid fa-file-pdf mr-1"></i> PDF
            </a>
            <a href="{{ route('tu.laporan.excel', request()->query()) }}" class="bg-green-600 text-white px-3 py-2 rounded-lg text-sm hover:bg-green-700 flex items-center">
                <i class="fa-solid fa-file-excel mr-1"></i> Excel
            </a>
        </div>
    </div>

    @if($proposals->isEmpty())
        <p class="text-gray-400 text-center py-12">Tidak ada data RAB disetujui untuk periode ini.</p>
    @else
        <div class="mb-4 p-4 bg-green-50 border border-green-200 rounded-lg">
            <span class="text-sm text-green-700 font-medium">{{ $proposals->count() }} RAB</span>
            <span class="text-sm text-green-600"> — Total Anggaran: </span>
            <span class="text-sm font-bold text-green-800">Rp {{ number_format($proposals->sum('total_budget'), 0, ',', '.') }}</span>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead>
                    <tr class="bg-gray-50 text-gray-500 uppercase text-xs">
                        <th class="text-left px-4 py-3">#</th>
                        <th class="text-left px-4 py-3">Judul RAB</th>
                        <th class="text-left px-4 py-3">Pengusul</th>
                        <th class="text-left px-4 py-3">Tgl Diajukan</th>
                        <th class="text-right px-4 py-3">Total Anggaran</th>
                        <th class="text-center px-4 py-3">Detail</th>
                        <th class="text-center px-4 py-3">Kwitansi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($proposals as $i => $p)
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-3 text-gray-400">{{ $i + 1 }}</td>
                        <td class="px-4 py-3 font-medium text-gray-800">{{ $p->title }}</td>
                        <td class="px-4 py-3 text-gray-500">{{ $p->user->name ?? '-' }}</td>
                        <td class="px-4 py-3 text-gray-500">{{ $p->proposed_date }}</td>
                        <td class="px-4 py-3 text-right font-medium">Rp {{ number_format($p->total_budget, 0, ',', '.') }}</td>
                        <td class="px-4 py-3 text-center">
                            <button onclick="toggleDetail('detail-{{ $p->id }}')" class="text-secondary hover:underline text-xs">Lihat</button>
                        </td>
                        <td class="px-4 py-3 text-center">
                            <a href="{{ route('tu.kwitansi.show', $p->id) }}"
                               class="inline-flex items-center gap-1 bg-emerald-50 border border-emerald-200 text-emerald-700 hover:bg-emerald-100 px-2 py-1 rounded text-xs transition-colors">
                                <i class="fa-solid fa-receipt"></i> Kwitansi
                            </a>
                        </td>
                    </tr>
                    <tr id="detail-{{ $p->id }}" class="hidden bg-gray-50">
                        <td colspan="7" class="px-8 py-4">
                            <table class="min-w-full text-xs">
                                <thead><tr class="text-gray-400 uppercase">
                                    <th class="text-left py-1">Item</th>
                                    <th class="text-center py-1">Qty</th>
                                    <th class="text-center py-1">Satuan</th>
                                    <th class="text-right py-1">Harga</th>
                                    <th class="text-right py-1">Total</th>
                                </tr></thead>
                                <tbody>
                                    @foreach($p->details as $d)
                                    <tr class="border-t border-gray-200">
                                        <td class="py-1">{{ $d->item_name }}</td>
                                        <td class="py-1 text-center">{{ $d->quantity }}</td>
                                        <td class="py-1 text-center text-gray-400">{{ $d->unit }}</td>
                                        <td class="py-1 text-right">Rp {{ number_format($d->unit_price, 0, ',', '.') }}</td>
                                        <td class="py-1 text-right font-medium">Rp {{ number_format($d->total_price, 0, ',', '.') }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>
<script>
function toggleDetail(id) {
    const el = document.getElementById(id);
    el.classList.toggle('hidden');
}
</script>
@endsection
