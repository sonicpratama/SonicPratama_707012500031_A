
{{-- Nama Layanan --}}
<div class="mb-3">
    <label for="nama_layanan" class="form-label">
        Nama Layanan <span class="text-danger">*</span>
    </label>

    <input
        type="text"
        class="form-control @error('nama_layanan') is-invalid @enderror"
        id="nama_layanan"
        name="nama_layanan"
        value="{{ old('nama_layanan', $layanan?->nama_layanan) }}"
        placeholder="Contoh: Pembuatan KTP Elektronik Online"
        required
    >

    @error('nama_layanan')
        <div class="invalid-feedback">
            {{ $message }}
        </div>
    @enderror
</div>

<div class="row g-3 mb-3">

    {{-- Kategori Layanan --}}
    <div class="col-md-6">
        <label for="kategori_layanan" class="form-label">
            Kategori Layanan <span class="text-danger">*</span>
        </label>

        <select
            class="form-select @error('kategori_layanan') is-invalid @enderror"
            id="kategori_layanan"
            name="kategori_layanan"
            required
        >
            <option value="">-- Pilih kategori --</option>

            @foreach ($kategoriList as $key => $label)
                <option
                    value="{{ $key }}"
                    @selected(old('kategori_layanan', $layanan?->kategori_layanan) === $key)
                >
                    {{ $label }}
                </option>
            @endforeach
        </select>

        @error('kategori_layanan')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
        @enderror
    </div>

    {{-- Tingkat Kematangan --}}
    <div class="col-md-6">
        <label for="tingkat_kematangan" class="form-label">
            Tingkat Kematangan (1-5) <span class="text-danger">*</span>
        </label>

        <select
            class="form-select @error('tingkat_kematangan') is-invalid @enderror"
            id="tingkat_kematangan"
            name="tingkat_kematangan"
            required
        >
            <option value="">-- Pilih tingkat --</option>

            @for ($i = 1; $i <= 5; $i++)
                <option
                    value="{{ $i }}"
                    @selected((int) old('tingkat_kematangan', $layanan?->tingkat_kematangan) === $i)
                >
                    Level {{ $i }}
                </option>
            @endfor
        </select>

        @error('tingkat_kematangan')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
        @enderror
    </div>

</div>

{{-- Perangkat Daerah --}}
<div class="mb-3">
    <label for="perangkat_daerah" class="form-label">
        Perangkat Daerah Pengelola <span class="text-danger">*</span>
    </label>

    <input
        type="text"
        class="form-control @error('perangkat_daerah') is-invalid @enderror"
        id="perangkat_daerah"
        name="perangkat_daerah"
        value="{{ old('perangkat_daerah', $layanan?->perangkat_daerah) }}"
        placeholder="Contoh: Dinas Komunikasi dan Informatika"
        required
    >

    @error('perangkat_daerah')
        <div class="invalid-feedback">
            {{ $message }}
        </div>
    @enderror
</div>

{{-- Deskripsi --}}
<div class="mb-4">
    <label for="deskripsi" class="form-label">
        Deskripsi <span class="text-danger">*</span>
    </label>

    <textarea
        class="form-control @error('deskripsi') is-invalid @enderror"
        id="deskripsi"
        name="deskripsi"
        rows="4"
        required
    >{{ old('deskripsi', $layanan?->deskripsi) }}</textarea>

    @error('deskripsi')
        <div class="invalid-feedback">
            {{ $message }}
        </div>
    @enderror

    <div class="form-text">
        Minimal 10 karakter.
    </div>
</div>
