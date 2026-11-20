
@extends('layouts.app')

@section('title', auth()->user()->isAdmin() ? 'Kelola Layanan' : 'Daftar Layanan')

@section('content')

    {{-- Header Halaman --}}
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-4">
        <div>
            <h4 class="mb-1">
                {{ auth()->user()->isAdmin() ? 'Kelola Layanan' : 'Daftar Layanan' }}
            </h4>
            <p class="text-muted mb-0 small">
                Layanan SPBE milik perangkat daerah Kota Bandung.
            </p>
        </div>

        {{-- Tombol tambah hanya untuk Admin --}}
        @if (auth()->user()->isAdmin())
            <a href="{{ route('layanan.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-lg me-1"></i>
                Tambah Layanan
            </a>
        @endif
    </div>

    {{-- Tabel Layanan --}}
    <div class="card">

        {{-- Form Pencarian dan Filter --}}
        <div class="card-header py-3">
            <form
                method="GET"
                action="{{ route('layanan.index') }}"
                class="row g-2 align-items-center"
            >
                <div class="col-sm-6 col-lg-4">
                    <div class="input-group">
                        <span class="input-group-text bg-white">
                            <i class="bi bi-search"></i>
                        </span>

                        <input
                            type="search"
                            name="search"
                            value="{{ $search }}"
                            class="form-control border-start-0"
                            placeholder="Cari nama, perangkat daerah, deskripsi..."
                        >
                    </div>
                </div>

                <div class="col-sm-3 col-lg-3">
                    <select
                        name="kategori"
                        class="form-select"
                        onchange="this.form.submit()"
                        aria-label="Filter kategori"
                    >
                        <option value="">Semua kategori</option>

                        @foreach ($kategoriList as $key => $label)
                            <option
                                value="{{ $key }}"
                                @selected($selectedKategori === $key)
                            >
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-auto">
                    <button type="submit" class="btn btn-light border">
                        Cari
                    </button>

                    @if ($search !== '' || $selectedKategori)
                        <a
                            href="{{ route('layanan.index') }}"
                            class="btn btn-link text-decoration-none"
                        >
                            Reset
                        </a>
                    @endif
                </div>
            </form>
        </div>

        {{-- Isi Tabel --}}
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th width="5%">No</th>
                        <th>Nama Layanan</th>
                        <th>Kategori</th>
                        <th>Perangkat Daerah</th>
                        <th>Tingkat</th>

                        @if (auth()->user()->isAdmin())
                            <th class="text-end">Aksi</th>
                        @endif
                    </tr>
                </thead>

                <tbody>
                    @forelse ($layanans as $layanan)
                        <tr>
                            <td class="text-muted">
                                {{ $layanans->firstItem() + $loop->index }}
                            </td>

                            <td>
                                <div class="fw-medium">
                                    {{ $layanan->nama_layanan }}
                                </div>

                                <div class="small text-muted">
                                    {{ \Illuminate\Support\Str::limit($layanan->deskripsi, 80) }}
                                </div>
                            </td>

                            <td>
                                <span class="badge text-bg-light border">
                                    {{ $kategoriList[$layanan->kategori_layanan] ?? ucfirst($layanan->kategori_layanan) }}
                                </span>
                            </td>

                            <td>
                                {{ $layanan->perangkat_daerah }}
                            </td>

                            <td>
                                <div
                                    class="level-dots"
                                    title="Tingkat {{ $layanan->tingkat_kematangan }} dari 5"
                                >
                                    @for ($i = 1; $i <= 5; $i++)
                                        <span class="{{ $i <= $layanan->tingkat_kematangan ? 'on' : '' }}"></span>
                                    @endfor
                                </div>

                                <small class="text-muted">
                                    Level {{ $layanan->tingkat_kematangan }}
                                </small>
                            </td>

                            {{-- Tombol edit dan hapus hanya untuk Admin --}}
                            @if (auth()->user()->isAdmin())
                                <td class="text-end text-nowrap">
                                    {{-- Tombol Edit --}}
                                    <a
                                        href="{{ route('layanan.edit', $layanan) }}"
                                        class="btn btn-sm btn-outline-primary"
                                    >
                                        <i class="bi bi-pencil"></i>
                                        Edit
                                    </a>

                                    {{-- Tombol Hapus --}}
                                    <form
                                        action="{{ route('layanan.destroy', $layanan) }}"
                                        method="POST"
                                        class="d-inline"
                                        data-confirm="Layanan &quot;{{ $layanan->nama_layanan }}&quot; akan dihapus beserta pengaduan terkait dan tidak bisa dikembalikan."
                                    >
                                        @csrf
                                        @method('DELETE')

                                        <button
                                            type="submit"
                                            class="btn btn-sm btn-outline-danger"
                                        >
                                            <i class="bi bi-trash"></i>
                                            Hapus
                                        </button>
                                    </form>
                                </td>
                            @endif
                        </tr>

                    @empty
                        <tr>
                            <td colspan="{{ auth()->user()->isAdmin() ? 6 : 5 }}">
                                <div class="empty-state">
                                    <i class="bi bi-inbox"></i>

                                    <p class="mt-2 mb-0">
                                        Data layanan tidak ditemukan.
                                    </p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if ($layanans->hasPages())
            <div class="card-footer bg-white d-flex flex-wrap justify-content-between align-items-center gap-2">

                <small class="text-muted">
                    Menampilkan {{ $layanans->firstItem() }}-{{ $layanans->lastItem() }}
                    dari {{ $layanans->total() }} layanan
                </small>

                {{ $layanans->links('pagination::bootstrap-5') }}

            </div>
        @endif

    </div>

@endsection
