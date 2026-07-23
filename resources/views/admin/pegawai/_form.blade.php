@php $p = $pegawai ?? null; @endphp

<div>
    <label class="block text-sm text-gray-600 mb-1">Nama Lengkap</label>
    <input type="text" name="name" value="{{ old('name', $p->name ?? '') }}" required
        class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#7a1f2b]/30">
</div>
<div>
    <label class="block text-sm text-gray-600 mb-1">NIP / NRP (maks 18 karakter)</label>
    <input type="text" name="nip_nrp" maxlength="18" value="{{ old('nip_nrp', $p->nip_nrp ?? '') }}" required
        class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#7a1f2b]/30">
    @if (!$p)
        <p class="text-xs text-gray-400 mt-1">Password awal akan otomatis sama dengan NIP/NRP ini.</p>
    @endif
</div>
<div>
    <label class="block text-sm text-gray-600 mb-1">Tempat Lahir</label>
    <input type="text" name="tempat_lahir" value="{{ old('tempat_lahir', $p->tempat_lahir ?? '') }}"
        class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#7a1f2b]/30">
</div>
<div>
    <label class="block text-sm text-gray-600 mb-1">Tanggal Lahir</label>
    <input type="date" name="tanggal_lahir" value="{{ old('tanggal_lahir', isset($p->tanggal_lahir) ? $p->tanggal_lahir->format('Y-m-d') : '') }}"
        class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#7a1f2b]/30">
</div>

<div>
    <label class="block text-sm text-gray-600 mb-1">Jenis Personel</label>
    @php
        $jenisOptions = [
            'TNI', 'PNS', 'PPPK', 'CAPEG',
            'Eselon I', 'Eselon II', 'Eselon III', 'Eselon IV',
            'Setingkat Eselon III', 'Setingkat Eselon IV',
            'BP', 'Paja/Abit Unhan',
        ];
        $selectedJenis = old('jenis_personel', $p->jenis_personel ?? '');
    @endphp
    <select name="jenis_personel" id="jenis_personel"
        class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#7a1f2b]/30">
        <option value="">-- Pilih --</option>
        @foreach ($jenisOptions as $jenis)
            <option value="{{ $jenis }}" {{ $selectedJenis === $jenis ? 'selected' : '' }}>{{ $jenis }}</option>
        @endforeach
    </select>
</div>

<div>
    <label id="label-pangkat-gol" class="block text-sm text-gray-600 mb-1">Pangkat / Golongan</label>
    <input type="text" name="pangkat_gol" id="pangkat_gol" value="{{ old('pangkat_gol', $p->pangkat_gol ?? '') }}"
        class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#7a1f2b]/30">
</div>

<div>
    <label class="block text-sm text-gray-600 mb-1">Jabatan / Satker</label>
    <input type="text" name="jabatan_satker" value="{{ old('jabatan_satker', $p->jabatan_satker ?? '') }}"
        class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#7a1f2b]/30">
</div>


<script>
    (function () {
        const labelMap = {
            'TNI': 'Pangkat / Korps / NRP',
            'PNS': 'Pangkat / Gol / NIP',
            'PPPK': 'Pangkat / Gol / NIP',
            'CAPEG': 'Pangkat / Gol / NIP',
            'Eselon I': 'Pangkat / Korps / NRP',
            'Eselon II': 'Pangkat / Korps / NRP',
            'Eselon III': 'Pangkat / Korps / NRP',
            'Eselon IV': 'Pangkat / Korps / NRP',
            'Setingkat Eselon III': 'Pangkat / Korps / NRP',
            'Setingkat Eselon IV': 'Pangkat / Korps / NRP',
            'BP': 'Pangkat / Gol / NRP / NIP',
            'Paja/Abit Unhan': 'Pangkat / Korps / NRP',
        };

        const select = document.getElementById('jenis_personel');
        const label = document.getElementById('label-pangkat-gol');

        function updateLabel() {
            label.textContent = labelMap[select.value] || 'Pangkat / Golongan';
        }

        select.addEventListener('change', updateLabel);
        updateLabel(); // set label yang benar pas halaman pertama dibuka (misal waktu edit data lama)
    })();
</script>