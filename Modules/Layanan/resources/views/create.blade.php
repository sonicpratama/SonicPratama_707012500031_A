
@extends('layouts.app')

@section('title', 'Tambah Layanan')

@section('content')

    <div class="row justify-content-center">
        <div class="col-lg-7">

            {{-- Tombol Kembali --}}
            <a
                href="{{ route('layanan.index') }}"
                class="text-decoration-none small"
            >
                <i class="bi bi-arrow-left"></i>
                Kembali ke daftar layanan
            </a>

            <h4 class="mt-2 mb-4">
                Tambah Layanan
            </h4>

            <div class="card">
                <div class="card-body p-4">

                    <form
                        action="{{ route('layanan.store') }}"
                        method="POST"
                    >
                        @csrf

                        {{-- Form Data Layanan --}}
                        @include('layanan::partials.form', [
                            'layanan' => null
                        ])

                        {{-- Tombol Aksi --}}
                        <div class="d-flex justify-content-end gap-2">

                            <a
                                href="{{ route('layanan.index') }}"
                                class="btn btn-light"
                            >
                                Batal
                            </a>

                            <button
                                type="submit"
                                class="btn btn-primary"
                            >
                                Simpan
                            </button>

                        </div>
                    </form>

                </div>
            </div>

        </div>
    </div>

@endsection
