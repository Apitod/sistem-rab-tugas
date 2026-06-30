@extends('layouts.app')

@section('header_title', 'Detail RAB: ' . $proposal->title)

@section('content')
@php
    $role           = auth()->user()->role;
    $revisionItems  = $proposal->details->where('revision_flag', true);
    $revisionCount  = $revisionItems->count();
    $colors = [
        'pending_kaprodi' => 'yellow',
        'pending_wd'      => 'yellow',
        'pending_dekan'   => 'blue',
        'disetujui'       => 'green',
        'ditolak'         => 'red',
        'revisi'          => 'orange',
    ];
    $labels = [
        'pending_kaprodi' => 'Menunggu Kaprodi',
        'pending_wd'      => 'Menunggu WD',
        'pending_dekan'   => 'Menunggu Dekan',
        'disetujui'       => 'Disetujui',
        'ditolak'         => 'Ditolak',
        'revisi'          => 'Perlu Revisi',
    ];
    $c = $colors[$proposal->status] ?? 'gray';
@endphp

<div class="max-w-4xl space-y-6">

    {{-- Notifikasi --}}
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

    {{-- Banner Revisi (untuk Pengusul saat status revisi) --}}
    @if($proposal->status === 'revisi' && $role === 'pengusul' && $revisionCount > 0)
    <div class="bg-orange-50 border border-orange-300 text-orange-800 px-4 py-4 rounded-xl flex items-start gap-3">
        <i class="fa-solid fa-triangle-exclamation text-orange-500 text-lg mt-0.5 flex-shrink-0"></i>
        <div>
            <p class="font-semibold">{{ $revisionCount }} item perlu direvisi</p>
            <p class="text-sm mt-0.5">Periksa item yang ditandai di bawah, perbaiki, lalu ajukan ulang.</p>
        </div>
    </div>
    @endif

    {{-- Header Card --}}
    <div class="bg-white rounded-xl shadow p-6">
        <div class="flex items-start justify-between">
            <div>
                <h2 class="text-xl font-bold text-gray-800">{{ $proposal->title }}</h2>
                <p class="text-sm text-gray-500 mt-1">Diajukan: {{ $proposal->proposed_date }} &bull; Pengusul: {{ $proposal->user->name ?? '-' }}</p>
            </div>
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

    {{-- ================================================================ --}}
    {{-- TABEL RINCIAN ANGGARAN                                           --}}
    {{-- ================================================================ --}}
    <div class="bg-white rounded-xl shadow p-6">
        <h3 class="font-semibold text-gray-700 mb-4">Rincian Anggaran</h3>

        {{-- Check All (hanya untuk Kaprodi saat pending) --}}
        @if($role === 'kaprodi' && $proposal->status === 'pending_kaprodi')
        <div class="flex items-center gap-2 mb-3 pb-3 border-b border-gray-100">
            <input type="checkbox" id="check-all"
                   class="w-4 h-4 rounded border-gray-300 text-orange-500 focus:ring-orange-400 cursor-pointer">
            <label for="check-all" class="text-sm font-medium text-gray-600 cursor-pointer select-none">
                Tandai Semua Item untuk Direvisi
            </label>
        </div>
        @endif

        <div class="overflow-x-auto">
            <table class="min-w-full text-sm" id="items-table">
                <thead>
                    <tr class="bg-gray-50 text-gray-500 uppercase text-xs">
                        @if($role === 'kaprodi' && $proposal->status === 'pending_kaprodi')
                        <th class="text-center px-3 py-3 w-10">Revisi</th>
                        @endif
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
                    {{-- ─── Baris item ─── --}}
                    <tr class="item-row transition-colors duration-150
                        @if($d->revision_flag && $role !== 'kaprodi') bg-orange-50 @else hover:bg-gray-50 @endif"
                        data-item-id="{{ $d->id }}">

                        {{-- Checkbox (hanya Kaprodi) — tanpa name, JS yang akan collect --}}
                        @if($role === 'kaprodi' && $proposal->status === 'pending_kaprodi')
                        <td class="px-3 py-3 text-center">
                            <input type="checkbox"
                                   class="item-checkbox w-4 h-4 rounded border-gray-300 text-orange-500 focus:ring-orange-400 cursor-pointer"
                                   data-item-id="{{ $d->id }}"
                                   data-index="{{ $i }}">
                        </td>
                        @endif

                        <td class="px-4 py-3 text-gray-400">{{ $i + 1 }}</td>
                        <td class="px-4 py-3 font-medium text-gray-800">
                            <div class="flex items-center gap-2">
                                {{-- Badge revisi (untuk Pengusul) --}}
                                @if($d->revision_flag && $role === 'pengusul')
                                <span class="inline-flex items-center gap-1 bg-orange-100 text-orange-700 text-xs font-semibold px-2 py-0.5 rounded-full">
                                    <i class="fa-solid fa-triangle-exclamation text-[10px]"></i> Perlu Direvisi
                                </span>
                                @endif
                                {{ $d->item_name }}
                            </div>
                            {{-- Alasan revisi (untuk Pengusul) --}}
                            @if($d->revision_flag && $d->revision_reason && $role === 'pengusul')
                            <div class="mt-1.5 flex items-start gap-1.5 text-orange-700 text-xs bg-orange-50 border border-orange-200 rounded-md px-2.5 py-1.5">
                                <i class="fa-solid fa-comment-dots flex-shrink-0 mt-0.5"></i>
                                <span>{{ $d->revision_reason }}</span>
                            </div>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-center">{{ $d->quantity }}</td>
                        <td class="px-4 py-3 text-center text-gray-500">{{ $d->unit }}</td>
                        <td class="px-4 py-3 text-right">Rp {{ number_format($d->unit_price, 0, ',', '.') }}</td>
                        <td class="px-4 py-3 text-right font-medium">Rp {{ number_format($d->total_price, 0, ',', '.') }}</td>
                    </tr>

                    {{-- ─── Baris alasan revisi (hanya Kaprodi, toggle via JS) ─── --}}
                    @if($role === 'kaprodi' && $proposal->status === 'pending_kaprodi')
                    <tr class="reason-row hidden bg-orange-50" id="reason-row-{{ $i }}">
                        <td></td>
                        <td colspan="6" class="px-4 pb-3 pt-1">
                            <label class="block text-xs font-medium text-orange-700 mb-1">
                                <i class="fa-solid fa-pen-to-square mr-1"></i>
                                Alasan revisi untuk "<span class="font-semibold">{{ $d->item_name }}</span>"
                            </label>
                            <textarea
                                rows="2"
                                placeholder="Tuliskan alasan revisi untuk item ini..."
                                class="reason-textarea w-full border border-orange-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-orange-400 focus:outline-none resize-none"
                                data-index="{{ $i }}"></textarea>
                        </td>
                    </tr>
                    @endif
                    @endforeach
                </tbody>
                <tfoot>
                    <tr class="border-t-2 border-gray-300 bg-gray-50">
                        <td colspan="{{ ($role === 'kaprodi' && $proposal->status === 'pending_kaprodi') ? 6 : 5 }}"
                            class="px-4 py-3 text-right font-bold text-gray-700">TOTAL ANGGARAN</td>
                        <td class="px-4 py-3 text-right font-bold text-secondary text-base">
                            Rp {{ number_format($proposal->total_budget, 0, ',', '.') }}
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>

    {{-- ================================================================ --}}
    {{-- AKSI KAPRODI                                                      --}}
    {{-- ================================================================ --}}
    @if($role === 'kaprodi' && $proposal->status === 'pending_kaprodi')
    <div class="bg-white rounded-xl shadow p-6">
        <h3 class="font-semibold text-gray-700 mb-4">Tindakan Verifikasi</h3>

        {{-- Form Revisi Per-Item --}}
        <form method="POST" action="{{ route('kaprodi.rab.revisi', $proposal->id) }}" id="form-revisi">
            @csrf
            <input type="hidden" name="action" value="revisi">

            {{-- Catatan global (opsional) --}}
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Catatan Tambahan (opsional)</label>
                <input type="text" name="notes"
                       placeholder="Catatan umum untuk pengusul..."
                       class="w-full border rounded-lg px-3 py-2 text-sm focus:ring-1 focus:ring-secondary focus:outline-none">
            </div>

            <div class="flex flex-wrap items-center gap-3">
                {{-- Tombol Minta Revisi --}}
                <button type="submit" id="btn-revisi"
                        disabled
                        class="flex items-center gap-2 bg-orange-500 text-white px-5 py-2 rounded-lg text-sm
                               hover:bg-orange-600 transition-colors disabled:opacity-40 disabled:cursor-not-allowed">
                    <i class="fa-solid fa-rotate-left"></i>
                    Minta Revisi Item
                    <span id="revisi-count" class="bg-orange-300 text-orange-900 text-xs font-bold px-1.5 py-0.5 rounded-full hidden">0</span>
                </button>
            </div>
        </form>

        {{-- Form Setujui (terpisah agar tidak bentrok dengan revision_items) --}}
        <form method="POST" action="{{ route('kaprodi.rab.verify', $proposal->id) }}" class="mt-3">
            @csrf
            <input type="hidden" name="action" value="verify">
            <input type="text" name="notes" placeholder="Catatan persetujuan (opsional)"
                   class="border rounded px-3 py-2 text-sm mr-2 focus:ring-1 focus:ring-secondary focus:outline-none mb-2">
            <button type="submit"
                    class="bg-success text-white px-5 py-2 rounded-lg text-sm hover:opacity-90 transition-opacity">
                <i class="fa-solid fa-check mr-1"></i> Setujui & Teruskan ke WD
            </button>
        </form>
    </div>
    @endif

    {{-- ================================================================ --}}
    {{-- AKSI WD KEUANGAN (tanpa input Nomor RAB)                         --}}
    {{-- ================================================================ --}}
    @if($role === 'wd_keuangan' && $proposal->status === 'pending_wd')
    <div class="bg-white rounded-xl shadow p-6">
        <h3 class="font-semibold text-gray-700 mb-4">Tindakan WD Keuangan</h3>
        <div class="flex flex-wrap gap-3">
            <form method="POST" action="{{ route('wd.rab.verify', $proposal->id) }}">
                @csrf
                <input type="hidden" name="action" value="verify">
                <input type="text" name="notes" placeholder="Catatan (opsional)"
                       class="border rounded px-3 py-2 text-sm mr-2 focus:ring-1 focus:ring-secondary focus:outline-none">
                <button type="submit" class="bg-success text-white px-4 py-2 rounded-lg text-sm hover:opacity-90">
                    <i class="fa-solid fa-check mr-1"></i> Setujui & Teruskan ke Dekan
                </button>
            </form>
            <form method="POST" action="{{ route('wd.rab.tolak', $proposal->id) }}">
                @csrf
                <input type="hidden" name="action" value="tolak">
                <input type="text" name="notes" placeholder="Alasan penolakan"
                       class="border rounded px-3 py-2 text-sm mr-2 focus:ring-1 focus:ring-secondary focus:outline-none">
                <button type="submit" class="bg-danger text-white px-4 py-2 rounded-lg text-sm hover:opacity-90">
                    <i class="fa-solid fa-xmark mr-1"></i> Tolak
                </button>
            </form>
        </div>
    </div>
    @endif

    {{-- ================================================================ --}}
    {{-- AKSI DEKAN (tetap ada Nomor RAB & e-signature)                   --}}
    {{-- ================================================================ --}}
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
                    <input type="text" name="rab_number" placeholder="RAB/2024/001"
                           class="w-full border rounded px-3 py-2 text-sm focus:ring-1 focus:ring-secondary focus:outline-none">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Catatan</label>
                    <input type="text" name="notes" placeholder="Catatan dekan (opsional)"
                           class="w-full border rounded px-3 py-2 text-sm focus:ring-1 focus:ring-secondary focus:outline-none">
                </div>
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Tanda Tangan Digital</label>
                <div class="border-2 border-dashed border-gray-300 rounded-lg p-2 bg-gray-50 inline-block">
                    <canvas id="signature-pad" width="400" height="150"
                            class="bg-white rounded cursor-crosshair border border-gray-200"></canvas>
                </div>
                <div class="flex gap-2 mt-2">
                    <button type="button" id="clear-sig"
                            class="text-xs text-red-600 border border-red-300 px-3 py-1 rounded hover:bg-red-50">
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
        ctx.strokeStyle = '#1e3a5f'; ctx.lineWidth = 2; ctx.lineCap = 'round'; ctx.lineJoin = 'round';
        function pos(e) { const r = canvas.getBoundingClientRect(); const src = e.touches ? e.touches[0] : e; return { x: src.clientX - r.left, y: src.clientY - r.top }; }
        canvas.addEventListener('mousedown',  e => { drawing = true; const p = pos(e); ctx.beginPath(); ctx.moveTo(p.x, p.y); });
        canvas.addEventListener('mousemove',  e => { if (!drawing) return; const p = pos(e); ctx.lineTo(p.x, p.y); ctx.stroke(); hasSig = true; document.getElementById('sig-status').textContent = 'Tanda tangan tersimpan'; document.getElementById('sig-status').className = 'text-xs text-green-600 self-center'; });
        canvas.addEventListener('mouseup',    () => drawing = false);
        canvas.addEventListener('mouseleave', () => drawing = false);
        canvas.addEventListener('touchstart',  e => { e.preventDefault(); drawing = true; const p = pos(e); ctx.beginPath(); ctx.moveTo(p.x, p.y); }, { passive: false });
        canvas.addEventListener('touchmove',   e => { e.preventDefault(); if (!drawing) return; const p = pos(e); ctx.lineTo(p.x, p.y); ctx.stroke(); hasSig = true; document.getElementById('sig-status').textContent = 'Tanda tangan tersimpan'; document.getElementById('sig-status').className = 'text-xs text-green-600 self-center'; }, { passive: false });
        canvas.addEventListener('touchend',    () => drawing = false);
        document.getElementById('clear-sig').addEventListener('click', () => { ctx.clearRect(0, 0, canvas.width, canvas.height); hasSig = false; document.getElementById('sig-status').textContent = 'Tanda tangani di kotak di atas'; document.getElementById('sig-status').className = 'text-xs text-gray-400 self-center'; });
        document.getElementById('dekan-form').addEventListener('submit', function() { if (hasSig) document.getElementById('signature-data').value = canvas.toDataURL('image/png'); });
    })();
    </script>
    @endif

    {{-- ================================================================ --}}
    {{-- TOMBOL AJUKAN ULANG (Pengusul, status revisi)                    --}}
    {{-- ================================================================ --}}
    @if($role === 'pengusul' && $proposal->status === 'revisi')
    <div class="bg-white rounded-xl shadow p-6">
        <h3 class="font-semibold text-gray-700 mb-2">Ajukan Ulang</h3>
        <p class="text-sm text-gray-500 mb-4">Setelah memperbaiki item yang ditandai, ajukan ulang RAB ini untuk diverifikasi kembali oleh Kaprodi.</p>
        <form method="POST" action="{{ route('pengusul.rab.resubmit', $proposal->id) }}">
            @csrf
            <button type="submit"
                    class="bg-secondary text-white px-5 py-2 rounded-lg text-sm hover:bg-blue-700 transition-colors"
                    onclick="return confirm('Yakin ingin mengajukan ulang RAB ini?')">
                <i class="fa-solid fa-paper-plane mr-1"></i> Ajukan Ulang ke Kaprodi
            </button>
        </form>
    </div>
    @endif

    {{-- ================================================================ --}}
    {{-- LOG VERIFIKASI                                                    --}}
    {{-- ================================================================ --}}
    @if($proposal->verificationLogs->count() > 0)
    <div class="bg-white rounded-xl shadow p-6">
        <h3 class="font-semibold text-gray-700 mb-4">Riwayat Verifikasi</h3>
        <ol class="relative border-l border-gray-200 space-y-4 ml-3">
            @foreach($proposal->verificationLogs as $log)
            <li class="ml-4">
                <div class="absolute w-3 h-3 bg-secondary rounded-full -left-1.5 border border-white"></div>
                <p class="text-sm font-medium text-gray-800">{{ $log->verifier->name ?? '-' }}
                    <span class="text-gray-400 font-normal text-xs">&bull; {{ $log->created_at->format('d M Y H:i') }}</span>
                </p>
                <p class="text-xs text-gray-500">{{ $log->status_checked }}
                    @if($log->notes) &mdash; {{ $log->notes }} @endif
                </p>
            </li>
            @endforeach
        </ol>
    </div>
    @endif

    <div class="text-sm">
        <a href="javascript:history.back()" class="text-secondary hover:underline">
            <i class="fa-solid fa-arrow-left mr-1"></i> Kembali
        </a>
    </div>
</div>

{{-- ================================================================ --}}
{{-- JAVASCRIPT: Checkbox Logic untuk Kaprodi                         --}}
{{-- ================================================================ --}}
@if($role === 'kaprodi' && $proposal->status === 'pending_kaprodi')
<script>
(function () {
    const checkAll   = document.getElementById('check-all');
    const checkboxes = document.querySelectorAll('.item-checkbox');
    const btnRevisi  = document.getElementById('btn-revisi');
    const countBadge = document.getElementById('revisi-count');

    /** Update tombol & badge berdasarkan jumlah item yang dicentang */
    function updateRevisiButton() {
        const checked = document.querySelectorAll('.item-checkbox:checked');
        const n = checked.length;
        btnRevisi.disabled = (n === 0);
        countBadge.textContent = n;
        countBadge.classList.toggle('hidden', n === 0);

        // Sync check-all state
        checkAll.indeterminate = (n > 0 && n < checkboxes.length);
        checkAll.checked = (n === checkboxes.length && checkboxes.length > 0);
    }

    /** Toggle baris alasan + highlight baris item */
    function toggleReasonRow(checkbox) {
        const idx       = checkbox.dataset.index;
        const reasonRow = document.getElementById('reason-row-' + idx);
        const itemRow   = checkbox.closest('tr');

        if (checkbox.checked) {
            reasonRow.classList.remove('hidden');
            itemRow.classList.add('bg-orange-50');
            itemRow.classList.remove('hover:bg-gray-50');
        } else {
            reasonRow.classList.add('hidden');
            itemRow.classList.remove('bg-orange-50');
            itemRow.classList.add('hover:bg-gray-50');
            // Kosongkan textarea jika un-check
            reasonRow.querySelector('textarea').value = '';
        }
    }

    // Event: tiap checkbox individu
    checkboxes.forEach(cb => {
        cb.addEventListener('change', function () {
            toggleReasonRow(this);
            updateRevisiButton();
        });
    });

    // Event: Check All
    checkAll.addEventListener('change', function () {
        checkboxes.forEach(cb => {
            cb.checked = this.checked;
            toggleReasonRow(cb);
        });
        updateRevisiButton();
    });

    // Validasi form sebelum submit: pastikan semua item yang dicentang punya alasan
    document.getElementById('form-revisi').addEventListener('submit', function (e) {
        e.preventDefault();

        const checked = document.querySelectorAll('.item-checkbox:checked');

        // 1. Minimal 1 item harus dipilih
        if (checked.length === 0) {
            alert('Pilih minimal 1 item yang perlu direvisi.');
            return;
        }

        // 2. Semua item yang dicentang wajib ada alasannya
        let valid = true;
        checked.forEach(function(cb) {
            const idx      = cb.dataset.index;
            const textarea = document.querySelector('#reason-row-' + idx + ' textarea');
            if (!textarea || textarea.value.trim() === '') {
                valid = false;
                textarea.classList.add('border-red-500', 'ring-1', 'ring-red-400');
                textarea.focus();
            } else {
                textarea.classList.remove('border-red-500', 'ring-1', 'ring-red-400');
            }
        });
        if (!valid) {
            alert('Isi alasan revisi untuk semua item yang ditandai.');
            return;
        }

        // 3. Hapus hidden inputs lama (jika ada dari percobaan sebelumnya)
        var old = this.querySelectorAll('input[name^="revision_items"]');
        old.forEach(function(el) { el.remove(); });

        // 4. Inject hidden inputs HANYA untuk item yang dicentang (index sequential)
        var seqIndex = 0;
        checked.forEach(function(cb) {
            var itemId   = cb.dataset.itemId;
            var idx      = cb.dataset.index;
            var textarea = document.querySelector('#reason-row-' + idx + ' textarea');
            var reason   = textarea ? textarea.value.trim() : '';

            var inputId   = document.createElement('input');
            inputId.type  = 'hidden';
            inputId.name  = 'revision_items[' + seqIndex + '][id]';
            inputId.value = itemId;
            document.getElementById('form-revisi').appendChild(inputId);

            var inputReason   = document.createElement('input');
            inputReason.type  = 'hidden';
            inputReason.name  = 'revision_items[' + seqIndex + '][reason]';
            inputReason.value = reason;
            document.getElementById('form-revisi').appendChild(inputReason);

            seqIndex++;
        });

        // 5. Submit form setelah hidden inputs siap
        this.submit();
    });
})();
</script>
@endif
@endsection
