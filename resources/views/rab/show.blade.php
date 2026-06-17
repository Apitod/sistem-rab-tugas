@extends('layouts.app')

@section('header_title', 'Detail RAB: ' . $proposal->title)

@section('content')
<div class="max-w-4xl space-y-6">
    @if(session('success'))
    <div class="bg-green-100 border border-green-300 text-green-800 px-4 py-3 rounded-lg text-sm">
        <i class="fa-solid fa-circle-check mr-2"></i>{{ session('success') }}
    </div>
    @endif
    @if(session('error'))
    <div class="bg-red-100 border border-red-300 text-red-800 px-4 py-3 rounded-lg text-sm">
        <i class="fa-solid fa-triangle-exclamation mr-2"></i>{{ session('error') }}
    </div>
    @endif
    @if($errors->any())
    <div class="bg-red-100 border border-red-300 text-red-800 px-4 py-3 rounded-lg text-sm">
        <ul class="list-disc list-inside space-y-1">
            @foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach
        </ul>
    </div>
    @endif
    {{-- Header Card --}}
    <div class="bg-white rounded-xl shadow p-6">
        <div class="flex items-start justify-between">
            <div>
                <h2 class="text-xl font-bold text-gray-800">{{ $proposal->title }}</h2>
                <p class="text-sm text-gray-500 mt-1">Diajukan: {{ $proposal->proposed_date }} &bull; Pengusul: {{ $proposal->user->name ?? '-' }}</p>
            </div>
            @php
                $colors = ['pending_kaprodi'=>'yellow','pending_wd'=>'yellow','pending_dekan'=>'blue','approved'=>'green','rejected'=>'red','revision'=>'orange'];
                $labels = ['pending_kaprodi'=>'Menunggu Kaprodi','pending_wd'=>'Menunggu WD','pending_dekan'=>'Menunggu Dekan','approved'=>'Disetujui','rejected'=>'Ditolak','revision'=>'Perlu Revisi'];
                $c = $colors[$proposal->status] ?? 'gray';
            @endphp
            <span class="px-3 py-1.5 rounded-full text-sm font-semibold bg-{{ $c }}-100 text-{{ $c }}-700">
                {{ $labels[$proposal->status] ?? ucfirst($proposal->status) }}
            </span>
        </div>

        @if($proposal->tor_file_path)
        <div class="mt-4">
            <a href="{{ Storage::url($proposal->tor_file_path) }}" target="_blank"
               class="inline-flex items-center space-x-2 text-sm text-secondary hover:underline">
                <i class="fa-solid fa-file-pdf text-red-500"></i>
                <span>Lihat File TOR</span>
            </a>
        </div>
        @endif
    </div>

    {{-- Rincian --}}
    <div class="bg-white rounded-xl shadow p-6">
        <h3 class="font-semibold text-gray-700 mb-4">Rincian Anggaran</h3>
        <div class="overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead>
                    <tr class="bg-gray-50 text-gray-500 uppercase text-xs">
                        <th class="text-left px-4 py-3">#</th>
                        <th class="text-left px-4 py-3">Nama Item</th>
                        <th class="text-center px-4 py-3">Qty</th>
                        <th class="text-center px-4 py-3">Satuan</th>
                        <th class="text-right px-4 py-3">Harga Satuan</th>
                        <th class="text-right px-4 py-3">Total</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($proposal->details as $i => $d)
                    <tr>
                        <td class="px-4 py-3 text-gray-400">{{ $i + 1 }}</td>
                        <td class="px-4 py-3 font-medium text-gray-800">{{ $d->item_name }}</td>
                        <td class="px-4 py-3 text-center">{{ $d->quantity }}</td>
                        <td class="px-4 py-3 text-center text-gray-500">{{ $d->unit }}</td>
                        <td class="px-4 py-3 text-right">Rp {{ number_format($d->unit_price, 0, ',', '.') }}</td>
                        <td class="px-4 py-3 text-right font-medium">Rp {{ number_format($d->total_price, 0, ',', '.') }}</td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr class="border-t-2 border-gray-300 bg-gray-50">
                        <td colspan="5" class="px-4 py-3 text-right font-bold text-gray-700">TOTAL ANGGARAN</td>
                        <td class="px-4 py-3 text-right font-bold text-secondary text-base">Rp {{ number_format($proposal->total_budget, 0, ',', '.') }}</td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>

    {{-- Action (kaprodi/wd/dekan) --}}
    @php $role = auth()->user()->role; @endphp

    @if($role === 'kaprodi' && $proposal->status === 'pending_kaprodi')
    <div class="bg-white rounded-xl shadow p-6">
        <h3 class="font-semibold text-gray-700 mb-4">Tindakan Verifikasi</h3>
        <div class="flex flex-wrap gap-3">
            <form method="POST" action="{{ route('kaprodi.rab.verify', $proposal->id) }}">
                @csrf
                <input type="hidden" name="action" value="verify">
                <input type="text" name="notes" placeholder="Catatan (opsional)" class="border rounded px-3 py-2 text-sm mr-2 focus:ring-1 focus:ring-secondary focus:outline-none">
                <button type="submit" class="bg-success text-white px-4 py-2 rounded-lg text-sm hover:opacity-90">
                    <i class="fa-solid fa-check mr-1"></i> Setujui
                </button>
            </form>
            <form method="POST" action="{{ route('kaprodi.rab.revisi', $proposal->id) }}">
                @csrf
                <input type="hidden" name="action" value="revisi">
                <input type="text" name="notes" placeholder="Alasan revisi" class="border rounded px-3 py-2 text-sm mr-2 focus:ring-1 focus:ring-secondary focus:outline-none">
                <button type="submit" class="bg-warning text-white px-4 py-2 rounded-lg text-sm hover:opacity-90">
                    <i class="fa-solid fa-rotate-left mr-1"></i> Minta Revisi
                </button>
            </form>
        </div>
    </div>
    @endif

    @if($role === 'wd_keuangan' && $proposal->status === 'pending_wd')
    <div class="bg-white rounded-xl shadow p-6">
        <h3 class="font-semibold text-gray-700 mb-4">Tindakan WD Keuangan</h3>
        <div class="flex flex-wrap gap-3">
            <form method="POST" action="{{ route('wd.rab.verify', $proposal->id) }}">
                @csrf
                <input type="hidden" name="action" value="verify">
                <input type="text" name="notes" placeholder="Catatan (opsional)" class="border rounded px-3 py-2 text-sm mr-2 focus:ring-1 focus:ring-secondary focus:outline-none">
                <button type="submit" class="bg-success text-white px-4 py-2 rounded-lg text-sm hover:opacity-90">
                    <i class="fa-solid fa-check mr-1"></i> Setujui
                </button>
            </form>
            <form method="POST" action="{{ route('wd.rab.tolak', $proposal->id) }}">
                @csrf
                <input type="hidden" name="action" value="tolak">
                <input type="text" name="notes" placeholder="Alasan penolakan" class="border rounded px-3 py-2 text-sm mr-2 focus:ring-1 focus:ring-secondary focus:outline-none">
                <button type="submit" class="bg-danger text-white px-4 py-2 rounded-lg text-sm hover:opacity-90">
                    <i class="fa-solid fa-xmark mr-1"></i> Tolak
                </button>
            </form>
        </div>
    </div>
    @endif

    @if($role === 'dekan' && $proposal->status === 'pending_dekan')
    <div class="bg-white rounded-xl shadow p-6">
        <h3 class="font-semibold text-gray-700 mb-4">Persetujuan Dekan</h3>
        <form method="POST" action="{{ route('dekan.rab.setujui', $proposal->id) }}" id="dekan-form">
            @csrf
            <input type="hidden" name="action" value="setujui">
            <input type="hidden" name="signature" id="signature-data">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nomor RAB</label>
                    <input type="text" name="rab_number" placeholder="RAB/2024/001" class="w-full border rounded px-3 py-2 text-sm focus:ring-1 focus:ring-secondary focus:outline-none">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Catatan</label>
                    <input type="text" name="notes" placeholder="Catatan dekan (opsional)" class="w-full border rounded px-3 py-2 text-sm focus:ring-1 focus:ring-secondary focus:outline-none">
                </div>
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Tanda Tangan Digital</label>
                <div class="border-2 border-dashed border-gray-300 rounded-lg p-2 bg-gray-50 inline-block">
                    <canvas id="signature-pad" width="400" height="150" class="bg-white rounded cursor-crosshair border border-gray-200"></canvas>
                </div>
                <div class="flex gap-2 mt-2">
                    <button type="button" id="clear-sig" class="text-xs text-red-600 border border-red-300 px-3 py-1 rounded hover:bg-red-50">
                        <i class="fa-solid fa-eraser mr-1"></i> Hapus
                    </button>
                    <span id="sig-status" class="text-xs text-gray-400 self-center">Tanda tangani di kotak di atas</span>
                </div>
            </div>
            <button type="submit" class="bg-success text-white px-6 py-2 rounded-lg text-sm hover:opacity-90">
                <i class="fa-solid fa-stamp mr-1"></i> Setujui & Tanda Tangani
            </button>
        </form>
    </div>
    <script>
    (function() {
        const canvas = document.getElementById('signature-pad');
        const ctx = canvas.getContext('2d');
        let drawing = false, hasSig = false;

        ctx.strokeStyle = '#1e3a5f';
        ctx.lineWidth = 2;
        ctx.lineCap = 'round';
        ctx.lineJoin = 'round';

        function pos(e) {
            const r = canvas.getBoundingClientRect();
            const src = e.touches ? e.touches[0] : e;
            return { x: src.clientX - r.left, y: src.clientY - r.top };
        }

        canvas.addEventListener('mousedown',  e => { drawing = true; const p = pos(e); ctx.beginPath(); ctx.moveTo(p.x, p.y); });
        canvas.addEventListener('mousemove',  e => { if (!drawing) return; const p = pos(e); ctx.lineTo(p.x, p.y); ctx.stroke(); hasSig = true; document.getElementById('sig-status').textContent = 'Tanda tangan tersimpan'; document.getElementById('sig-status').className = 'text-xs text-green-600 self-center'; });
        canvas.addEventListener('mouseup',    () => drawing = false);
        canvas.addEventListener('mouseleave', () => drawing = false);

        canvas.addEventListener('touchstart',  e => { e.preventDefault(); drawing = true; const p = pos(e); ctx.beginPath(); ctx.moveTo(p.x, p.y); }, { passive: false });
        canvas.addEventListener('touchmove',   e => { e.preventDefault(); if (!drawing) return; const p = pos(e); ctx.lineTo(p.x, p.y); ctx.stroke(); hasSig = true; document.getElementById('sig-status').textContent = 'Tanda tangan tersimpan'; document.getElementById('sig-status').className = 'text-xs text-green-600 self-center'; }, { passive: false });
        canvas.addEventListener('touchend',    () => drawing = false);

        document.getElementById('clear-sig').addEventListener('click', () => {
            ctx.clearRect(0, 0, canvas.width, canvas.height);
            hasSig = false;
            document.getElementById('sig-status').textContent = 'Tanda tangani di kotak di atas';
            document.getElementById('sig-status').className = 'text-xs text-gray-400 self-center';
        });

        document.getElementById('dekan-form').addEventListener('submit', function(e) {
            if (hasSig) {
                document.getElementById('signature-data').value = canvas.toDataURL('image/png');
            }
        });
    })();
    </script>
    @endif

    {{-- Log Verifikasi --}}
    @if($proposal->verificationLogs->count() > 0)
    <div class="bg-white rounded-xl shadow p-6">
        <h3 class="font-semibold text-gray-700 mb-4">Riwayat Verifikasi</h3>
        <ol class="relative border-l border-gray-200 space-y-4 ml-3">
            @foreach($proposal->verificationLogs as $log)
            <li class="ml-4">
                <div class="absolute w-3 h-3 bg-secondary rounded-full -left-1.5 border border-white"></div>
                <p class="text-sm font-medium text-gray-800">{{ $log->verifier->name ?? '-' }} <span class="text-gray-400 font-normal text-xs">&bull; {{ $log->created_at->format('d M Y H:i') }}</span></p>
                <p class="text-xs text-gray-500">{{ $log->action }} @if($log->notes) &mdash; {{ $log->notes }} @endif</p>
            </li>
            @endforeach
        </ol>
    </div>
    @endif

    <div class="text-sm">
        <a href="javascript:history.back()" class="text-secondary hover:underline"><i class="fa-solid fa-arrow-left mr-1"></i> Kembali</a>
    </div>
</div>
@endsection
