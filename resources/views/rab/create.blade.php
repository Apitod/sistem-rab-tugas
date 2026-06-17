@extends('layouts.app')

@section('header_title', 'Ajukan RAB Baru')

@section('content')
<div class="bg-white rounded-xl shadow p-6 max-w-4xl">
    <form method="POST" action="{{ route('pengusul.rab.store') }}" enctype="multipart/form-data">
        @csrf

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-1">Judul RAB <span class="text-red-500">*</span></label>
                <input type="text" name="title" value="{{ old('title') }}" required
                    class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-secondary @error('title') border-red-500 @enderror">
                @error('title') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Diajukan <span class="text-red-500">*</span></label>
                <input type="date" name="proposed_date" value="{{ old('proposed_date', date('Y-m-d')) }}" required
                    class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-secondary @error('proposed_date') border-red-500 @enderror">
                @error('proposed_date') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">File TOR (PDF) <span class="text-red-500">*</span></label>
                <input type="file" name="tor_file" accept=".pdf" required
                    class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-secondary @error('tor_file') border-red-500 @enderror">
                @error('tor_file') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
        </div>

        <hr class="my-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="font-semibold text-gray-700">Rincian Item RAB</h3>
            <button type="button" id="addItem" class="text-sm bg-blue-50 text-secondary border border-secondary px-3 py-1.5 rounded-lg hover:bg-blue-100">
                <i class="fa-solid fa-plus mr-1"></i> Tambah Item
            </button>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full text-sm mb-4" id="itemTable">
                <thead>
                    <tr class="bg-gray-50 text-gray-500 uppercase text-xs">
                        <th class="text-left px-3 py-2">Nama Item</th>
                        <th class="text-left px-3 py-2 w-24">Qty</th>
                        <th class="text-left px-3 py-2 w-32">Satuan</th>
                        <th class="text-left px-3 py-2 w-40">Harga Satuan</th>
                        <th class="text-right px-3 py-2 w-40">Total</th>
                        <th class="px-3 py-2 w-12"></th>
                    </tr>
                </thead>
                <tbody id="itemBody">
                    <tr class="item-row border-t">
                        <td class="px-3 py-2"><input type="text" name="items[0][item_name]" required placeholder="Nama item" class="w-full border rounded px-2 py-1 text-sm focus:outline-none focus:ring-1 focus:ring-secondary"></td>
                        <td class="px-3 py-2"><input type="number" name="items[0][quantity]" required min="1" value="1" class="w-full border rounded px-2 py-1 text-sm focus:outline-none focus:ring-1 focus:ring-secondary qty"></td>
                        <td class="px-3 py-2"><input type="text" name="items[0][unit]" required placeholder="pcs / buah / set" class="w-full border rounded px-2 py-1 text-sm focus:outline-none focus:ring-1 focus:ring-secondary"></td>
                        <td class="px-3 py-2"><input type="number" name="items[0][unit_price]" required min="0" value="0" class="w-full border rounded px-2 py-1 text-sm focus:outline-none focus:ring-1 focus:ring-secondary price"></td>
                        <td class="px-3 py-2 text-right font-medium total">Rp 0</td>
                        <td class="px-3 py-2 text-center"><button type="button" class="removeItem text-red-400 hover:text-red-600"><i class="fa-solid fa-trash"></i></button></td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="flex items-center justify-between mt-6">
            <div class="text-sm text-gray-600">
                Total Anggaran: <span id="grandTotal" class="font-bold text-gray-800 text-lg ml-1">Rp 0</span>
            </div>
            <div class="flex space-x-3">
                <a href="{{ route('pengusul.rab.index') }}" class="px-4 py-2 border rounded-lg text-sm text-gray-600 hover:bg-gray-50">Batal</a>
                <button type="submit" class="bg-secondary text-white px-6 py-2 rounded-lg text-sm hover:bg-blue-700 transition-colors">
                    <i class="fa-solid fa-paper-plane mr-1"></i> Ajukan RAB
                </button>
            </div>
        </div>
    </form>
</div>

<script>
let rowIdx = 1;
function formatRp(n) { return 'Rp ' + Math.round(n).toLocaleString('id-ID'); }
function recalc() {
    let grand = 0;
    document.querySelectorAll('.item-row').forEach(row => {
        const q = parseFloat(row.querySelector('.qty').value) || 0;
        const p = parseFloat(row.querySelector('.price').value) || 0;
        const t = q * p;
        grand += t;
        row.querySelector('.total').textContent = formatRp(t);
    });
    document.getElementById('grandTotal').textContent = formatRp(grand);
}
document.getElementById('itemBody').addEventListener('input', recalc);
document.getElementById('addItem').addEventListener('click', () => {
    const tr = document.createElement('tr');
    tr.className = 'item-row border-t';
    tr.innerHTML = `
        <td class="px-3 py-2"><input type="text" name="items[${rowIdx}][item_name]" required placeholder="Nama item" class="w-full border rounded px-2 py-1 text-sm focus:outline-none focus:ring-1 focus:ring-secondary"></td>
        <td class="px-3 py-2"><input type="number" name="items[${rowIdx}][quantity]" required min="1" value="1" class="w-full border rounded px-2 py-1 text-sm focus:outline-none focus:ring-1 focus:ring-secondary qty"></td>
        <td class="px-3 py-2"><input type="text" name="items[${rowIdx}][unit]" required placeholder="pcs / buah / set" class="w-full border rounded px-2 py-1 text-sm focus:outline-none focus:ring-1 focus:ring-secondary"></td>
        <td class="px-3 py-2"><input type="number" name="items[${rowIdx}][unit_price]" required min="0" value="0" class="w-full border rounded px-2 py-1 text-sm focus:outline-none focus:ring-1 focus:ring-secondary price"></td>
        <td class="px-3 py-2 text-right font-medium total">Rp 0</td>
        <td class="px-3 py-2 text-center"><button type="button" class="removeItem text-red-400 hover:text-red-600"><i class="fa-solid fa-trash"></i></button></td>`;
    document.getElementById('itemBody').appendChild(tr);
    rowIdx++;
});
document.getElementById('itemBody').addEventListener('click', e => {
    if (e.target.closest('.removeItem')) {
        const rows = document.querySelectorAll('.item-row');
        if (rows.length > 1) { e.target.closest('.item-row').remove(); recalc(); }
    }
});
</script>
@endsection
