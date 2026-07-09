@extends('layouts.app')

@section('header_title', 'Kwitansi Saya — ' . $proposal->rab_number)

@section('content')
<div class="max-w-3xl space-y-4">

    {{-- Session Messages --}}
    @if(session('success'))
    <div class="bg-green-100 border border-green-300 text-green-800 px-4 py-3 rounded-lg text-sm no-print">
        <i class="fa-solid fa-circle-check mr-2"></i>{{ session('success') }}
    </div>
    @endif

    {{-- Badge Notifikasi --}}
    <div class="bg-emerald-50 border border-emerald-200 rounded-xl px-5 py-4 flex items-start gap-4 no-print">
        <div class="flex-shrink-0 mt-0.5">
            <div class="w-10 h-10 rounded-full bg-emerald-100 flex items-center justify-center">
                <i class="fa-solid fa-file-invoice text-emerald-600 text-lg"></i>
            </div>
        </div>
        <div>
            <p class="text-sm font-semibold text-emerald-800">Kwitansi RAB Anda Telah Tersedia</p>
            <p class="text-xs text-emerald-700 mt-0.5">
                Dokumen di bawah merupakan bukti sah pencairan anggaran untuk RAB <strong>{{ $proposal->rab_number }}</strong>.
                Simpan atau cetak kwitansi ini sebagai arsip.
            </p>
        </div>
    </div>

    {{-- Toolbar (tidak tercetak) --}}
    <div class="flex items-center gap-3 no-print">
        <a href="{{ route('pengusul.rab.index') }}" class="text-secondary hover:underline text-sm">
            <i class="fa-solid fa-arrow-left mr-1"></i> Kembali ke Daftar RAB
        </a>
        <div class="ml-auto">
            <button onclick="window.print()"
                    class="flex items-center gap-2 bg-secondary text-white px-4 py-2 rounded-lg text-sm hover:bg-blue-700 transition-colors">
                <i class="fa-solid fa-print"></i> Cetak Kwitansi
            </button>
        </div>
    </div>

    {{-- ============================================================ --}}
    {{-- DOKUMEN KWITANSI (sama persis dengan versi TU)              --}}
    {{-- ============================================================ --}}
    <div id="kwitansi-doc" class="bg-white rounded-xl shadow-lg p-8 print-area relative overflow-hidden">

        {{-- Watermark --}}
        <div class="absolute inset-0 flex items-center justify-center pointer-events-none select-none opacity-[0.04]">
            <p class="text-[120px] font-black uppercase tracking-widest text-gray-800 rotate-[-30deg]">SAH</p>
        </div>

        {{-- ── KOP SURAT UIN ALAUDDIN MAKASSAR ── --}}
        <div class="border-b-4 border-double border-secondary pb-4 mb-6">
            <div class="flex items-center gap-4">
                <div class="flex-shrink-0 w-20 h-20">
                    <div class="w-20 h-20 rounded-full bg-gradient-to-br from-emerald-700 to-emerald-900 flex items-center justify-center shadow">
                        <div class="text-center">
                            <i class="fa-solid fa-mosque text-white text-2xl"></i>
                            <p class="text-white text-[6px] font-bold mt-0.5 leading-tight">UIN</p>
                        </div>
                    </div>
                </div>
                <div class="flex-1 text-center">
                    <p class="text-[10px] font-semibold text-gray-500 uppercase tracking-widest">Kementerian Agama Republik Indonesia</p>
                    <h1 class="text-base font-extrabold text-gray-900 uppercase leading-tight">Universitas Islam Negeri Alauddin Makassar</h1>
                    <p class="text-sm font-semibold text-secondary">Fakultas Sains dan Teknologi</p>
                    <p class="text-xs text-gray-600">Jurusan Sistem Informasi / Ilmu Komputer</p>
                    <p class="text-[10px] text-gray-400 mt-0.5">Jl. H.M. Yasin Limpo No. 36, Romangpolong, Gowa 92113 | www.uin-alauddin.ac.id</p>
                </div>
                <div class="w-20 flex-shrink-0"></div>
            </div>
        </div>

        {{-- ── JUDUL KWITANSI ── --}}
        <div class="text-center mb-8">
            <h2 class="text-2xl font-extrabold text-gray-800 uppercase tracking-widest">K W I T A N S I</h2>
            <p class="text-sm text-gray-500 mt-1">Bukti Sah Pencairan Anggaran Rencana Anggaran Biaya (RAB)</p>
            <div class="mt-2 inline-block bg-emerald-100 border border-emerald-300 rounded-lg px-4 py-1">
                <span class="text-xs font-mono font-bold text-emerald-700">No: {{ $nomorKwitansi }}</span>
            </div>
        </div>

        {{-- ── DATA KWITANSI ── --}}
        <div class="space-y-3 mb-8 bg-gray-50 rounded-lg px-5 py-4 text-sm">
            <div class="grid grid-cols-3 gap-2">
                <span class="font-semibold text-gray-600 col-span-1">Nomor RAB</span>
                <span class="col-span-2 font-bold text-gray-800 font-mono">: {{ $proposal->rab_number ?? '-' }}</span>
            </div>
            <div class="grid grid-cols-3 gap-2">
                <span class="font-semibold text-gray-600 col-span-1">Judul Kegiatan</span>
                <span class="col-span-2 font-semibold text-gray-800">: {{ $proposal->title }}</span>
            </div>
            <div class="grid grid-cols-3 gap-2">
                <span class="font-semibold text-gray-600 col-span-1">Nama Pengusul</span>
                <span class="col-span-2 text-gray-800">: {{ $proposal->user->name ?? '-' }}</span>
            </div>
            <div class="grid grid-cols-3 gap-2">
                <span class="font-semibold text-gray-600 col-span-1">Tanggal Pengajuan</span>
                <span class="col-span-2 text-gray-800">: {{ $proposal->proposed_date->format('d F Y') }}</span>
            </div>
            <div class="grid grid-cols-3 gap-2">
                <span class="font-semibold text-gray-600 col-span-1">Tanggal Disetujui</span>
                <span class="col-span-2 text-gray-800">: {{ $dekanLog ? $dekanLog->created_at->format('d F Y') : $proposal->updated_at->format('d F Y') }}</span>
            </div>
            @if($dekanLog)
            <div class="grid grid-cols-3 gap-2">
                <span class="font-semibold text-gray-600 col-span-1">Disetujui Oleh</span>
                <span class="col-span-2 text-gray-800">: {{ $dekanLog->verifier->name ?? 'Dekan' }}</span>
            </div>
            @endif
        </div>

        {{-- ── TABEL RINCIAN ANGGARAN ── --}}
        <div class="mb-6">
            <h3 class="text-sm font-bold text-gray-700 uppercase tracking-wide mb-2 border-l-4 border-secondary pl-3">Rincian Anggaran yang Dicairkan</h3>
            <div class="overflow-x-auto rounded-lg border border-gray-200">
                <table class="min-w-full text-sm">
                    <thead>
                        <tr class="bg-secondary text-white text-xs uppercase">
                            <th class="text-left px-3 py-2">#</th>
                            <th class="text-left px-3 py-2">Nama Item</th>
                            <th class="text-center px-3 py-2">Qty</th>
                            <th class="text-center px-3 py-2">Satuan</th>
                            <th class="text-right px-3 py-2">Harga Satuan</th>
                            <th class="text-right px-3 py-2">Total</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach($proposal->details as $i => $d)
                        <tr class="{{ $i % 2 === 0 ? 'bg-white' : 'bg-gray-50' }}">
                            <td class="px-3 py-2 text-gray-400 text-xs">{{ $i + 1 }}</td>
                            <td class="px-3 py-2 font-medium text-gray-800">{{ $d->item_name }}</td>
                            <td class="px-3 py-2 text-center text-gray-600">{{ $d->quantity }}</td>
                            <td class="px-3 py-2 text-center text-gray-500">{{ $d->unit }}</td>
                            <td class="px-3 py-2 text-right text-gray-600">Rp {{ number_format($d->unit_price, 0, ',', '.') }}</td>
                            <td class="px-3 py-2 text-right font-semibold text-gray-800">Rp {{ number_format($d->total_price, 0, ',', '.') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr class="bg-secondary text-white font-bold">
                            <td colspan="5" class="px-3 py-2.5 text-right text-sm uppercase tracking-wide">TOTAL ANGGARAN DICAIRKAN</td>
                            <td class="px-3 py-2.5 text-right text-base">Rp {{ number_format($proposal->total_budget, 0, ',', '.') }}</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>

        {{-- ── TERBILANG ── --}}
        <div class="mb-8 bg-yellow-50 border border-yellow-200 rounded-lg px-4 py-3">
            <p class="text-xs font-semibold text-yellow-700 uppercase tracking-wide mb-0.5">Terbilang:</p>
            <p class="text-sm text-gray-800 italic font-medium">
                — Rp {{ number_format($proposal->total_budget, 0, ',', '.') }} —
            </p>
        </div>

        {{-- ── KOLOM TANDA TANGAN ── --}}
        <div class="grid grid-cols-2 gap-8 mt-8 text-sm">
            <div class="text-center">
                <p class="text-gray-600 font-medium">Yang Menerima,</p>
                <p class="text-xs text-gray-400 mb-14">(Pengusul)</p>
                <div class="border-b border-gray-400 w-48 mx-auto"></div>
                <p class="mt-1 font-semibold text-gray-800">{{ $proposal->user->name ?? '________________' }}</p>
                <p class="text-xs text-gray-500">Pengusul RAB</p>
            </div>
            <div class="text-center">
                <p class="text-gray-600 font-medium">Mengetahui &amp; Menyetujui,</p>
                <p class="text-xs text-gray-400">(Dekan)</p>
                @if($proposal->signature_path)
                <div class="flex justify-center my-2">
                    <img src="{{ Storage::url($proposal->signature_path) }}"
                         alt="Tanda Tangan Dekan"
                         class="h-16 object-contain">
                </div>
                @else
                <div class="h-14"></div>
                @endif
                <div class="border-b border-gray-400 w-48 mx-auto"></div>
                <p class="mt-1 font-semibold text-gray-800">{{ $dekanLog?->verifier?->name ?? '________________' }}</p>
                <p class="text-xs text-gray-500">Dekan Fakultas Sains dan Teknologi</p>
                <p class="text-xs text-gray-500">UIN Alauddin Makassar</p>
            </div>
        </div>

        {{-- ── STEMPEL SAH ── --}}
        <div class="absolute bottom-8 right-8 opacity-60 pointer-events-none select-none rotate-[-15deg]">
            <div class="w-28 h-28 rounded-full border-4 border-emerald-600 flex flex-col items-center justify-center">
                <div class="w-24 h-24 rounded-full border-2 border-emerald-400 flex flex-col items-center justify-center">
                    <p class="text-emerald-600 font-black text-[10px] uppercase tracking-widest">Sudah</p>
                    <p class="text-emerald-700 font-black text-xl uppercase">SAH</p>
                    <p class="text-emerald-600 font-black text-[10px] uppercase tracking-widest">Dicairkan</p>
                </div>
            </div>
        </div>

        {{-- ── FOOTER ── --}}
        <div class="mt-8 pt-4 border-t border-dashed border-gray-200 text-center">
            <p class="text-xs text-gray-400">
                Kwitansi ini diterbitkan oleh Tata Usaha UIN Alauddin Makassar &bull;
                Dicetak: {{ now()->format('d F Y, H:i') }} WIB &bull;
                Dokumen sah dan dapat dipertanggungjawabkan
            </p>
        </div>
    </div>

    {{-- Tombol bawah --}}
    <div class="flex justify-between no-print">
        <a href="{{ route('pengusul.rab.index') }}" class="text-secondary hover:underline text-sm">
            <i class="fa-solid fa-arrow-left mr-1"></i> Kembali
        </a>
        <button onclick="window.print()"
                class="flex items-center gap-2 bg-emerald-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-emerald-700 transition-colors">
            <i class="fa-solid fa-print"></i> Cetak Kwitansi
        </button>
    </div>
</div>

<style>
@media print {
    .no-print { display: none !important; }
    body { background: white !important; }
    #kwitansi-doc {
        box-shadow: none !important;
        border-radius: 0 !important;
        padding: 20px !important;
        max-width: 100% !important;
    }
    .opacity-60 { opacity: 0.5 !important; }
}
</style>
@endsection
