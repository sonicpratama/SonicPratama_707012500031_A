
@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')

    {{-- Sambutan Pengguna --}}
    <div class="mb-4">
        <h4 class="mb-1">
            Halo, {{ auth()->user()->name }}
        </h4>

        <p class="text-muted mb-0">
            Anda login sebagai
            <strong>{{ auth()->user()->role->label() }}</strong>.
            Pilih menu di bawah untuk mulai bekerja.
        </p>
    </div>

    {{-- Ringkasan Dashboard --}}
    <div class="row g-3">

        {{-- Total Layanan --}}
        <div class="col-md-6 col-lg-4">
            <div class="card h-100">
                <div class="card-body d-flex align-items-center gap-3">

                    <span class="icon-box blue">
                        <i class="bi bi-hdd-network"></i>
                    </span>

                    <div class="flex-grow-1">
                        <div class="text-muted small">
                            {{ auth()->user()->isAdmin() ? 'Total Layanan' : 'Layanan SPBE Tersedia' }}
                        </div>

                        <div class="fs-4 fw-semibold">
                            {{ $totalLayanan }}
                        </div>
                    </div>

                </div>

                <div class="card-footer bg-white border-top-0 pt-0">
                    <a
                        href="{{ route('layanan.index') }}"
                        class="small text-decoration-none"
                    >
                        {{ auth()->user()->isAdmin() ? 'Kelola layanan' : 'Lihat daftar layanan' }}
                        <i class="bi bi-arrow-right"></i>
                    </a>
                </div>
            </div>
        </div>

        {{-- Total Pengaduan --}}
        <div class="col-md-6 col-lg-4">
            <div class="card h-100">
                <div class="card-body d-flex align-items-center gap-3">

                    <span class="icon-box amber">
                        <i class="bi bi-chat-left-text"></i>
                    </span>

                    <div class="flex-grow-1">
                        <div class="text-muted small">
                            {{ auth()->user()->isAdmin() ? 'Total Pengaduan' : 'Pengaduan Saya' }}
                        </div>

                        <div class="fs-4 fw-semibold">
                            {{ $totalPengaduan }}
                        </div>
                    </div>

                </div>

                <div class="card-footer bg-white border-top-0 pt-0">
                    <a
                        href="{{ route('pengaduan.index') }}"
                        class="small text-decoration-none"
                    >
                        {{ auth()->user()->isAdmin() ? 'Lihat semua pengaduan' : 'Buat / kelola pengaduan' }}
                        <i class="bi bi-arrow-right"></i>
                    </a>
                </div>
            </div>
        </div>

        @if (auth()->user()->isAdmin())

            {{-- Warga Terdaftar (Khusus Admin) --}}
            <div class="col-md-6 col-lg-4">
                <div class="card h-100">
                    <div class="card-body d-flex align-items-center gap-3">

                        <span class="icon-box green">
                            <i class="bi bi-people"></i>
                        </span>

                        <div class="flex-grow-1">
                            <div class="text-muted small">
                                Warga Terdaftar
                            </div>

                            <div class="fs-4 fw-semibold">
                                {{ $totalWarga }}
                            </div>
                        </div>

                    </div>
                </div>
            </div>

            {{-- Rata-rata Tingkat Kematangan (Khusus Admin) --}}
            <div class="col-md-6 col-lg-4">
                <div class="card h-100">
                    <div class="card-body d-flex align-items-center gap-3">

                        <span class="icon-box gray">
                            <i class="bi bi-bar-chart-line"></i>
                        </span>

                        <div class="flex-grow-1">
                            <div class="text-muted small">
                                Rata-rata Tingkat Kematangan
                            </div>

                            <div class="fs-4 fw-semibold">
                                {{ $rataKematangan }} / 5
                            </div>
                        </div>

                    </div>
                </div>
            </div>

        @endif

    </div>

    <div class="row g-3 mt-1">

        {{-- Layanan per Kategori (Khusus Admin) --}}
        @if (auth()->user()->isAdmin())
            <div class="col-lg-5">
                <div class="card h-100">
                    <div class="card-header py-3 fw-semibold">
                        Layanan per Kategori
                    </div>

                    <div class="card-body">
                        @forelse ($perKategori as $kategori => $total)
                            <div class="mb-3">
                                <div class="d-flex justify-content-between small mb-1">
                                    <span>{{ ucfirst($kategori) }}</span>
                                    <span class="fw-semibold">{{ $total }}</span>
                                </div>

                                <div class="progress" style="height: 8px">
                                    <div
                                        class="progress-bar"
                                        style="width: {{ $totalLayanan ? ($total / $totalLayanan) * 100 : 0 }}%"
                                    ></div>
                                </div>
                            </div>
                        @empty
                            <p class="text-muted mb-0">Belum ada data layanan.</p>
                        @endforelse
                    </div>
                </div>
            </div>
        @endif

        {{-- Pengaduan Terbaru --}}
        <div class="{{ auth()->user()->isAdmin() ? 'col-lg-7' : 'col-12' }}">
            <div class="card h-100">
                <div class="card-header d-flex justify-content-between align-items-center py-3">
                    <span class="fw-semibold">
                        {{ auth()->user()->isAdmin() ? 'Pengaduan Terbaru' : 'Pengaduan Terakhir Saya' }}
                    </span>

                    <a href="{{ route('pengaduan.index') }}" class="small text-decoration-none">
                        Lihat semua
                    </a>
                </div>

                <ul class="list-group list-group-flush">
                    @forelse ($pengaduanTerbaru as $pengaduan)
                        <li class="list-group-item">
                            <div class="fw-medium">
                                {{ $pengaduan->layanan?->nama_layanan ?? '-' }}
                            </div>

                            <div class="small text-muted">
                                @if (auth()->user()->isAdmin())
                                    {{ $pengaduan->user?->name }} &bull;
                                @endif
                                {{ $pengaduan->created_at->format('d/m/Y H:i') }}
                            </div>

                            <div class="small">
                                {{ \Illuminate\Support\Str::limit($pengaduan->deskripsi_pengaduan, 100) }}
                            </div>
                        </li>
                    @empty
                        <li class="list-group-item">
                            <div class="empty-state py-4">
                                <i class="bi bi-inbox"></i>

                                <p class="mt-2 mb-0">
                                    {{ auth()->user()->isAdmin() ? 'Belum ada pengaduan.' : 'Anda belum pernah mengirim pengaduan.' }}
                                </p>
                            </div>
                        </li>
                    @endforelse
                </ul>
            </div>
        </div>

    </div>

@endsection
