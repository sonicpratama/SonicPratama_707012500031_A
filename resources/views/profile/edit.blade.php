
@extends('layouts.app')

@section('title', 'Profil')

@section('content')

    <div class="row justify-content-center">
        <div class="col-lg-7">

            <h4 class="mb-4">
                Profil Saya
            </h4>

            <div class="card">
                <div class="card-body p-4">

                    <form
                        action="{{ route('profile.update') }}"
                        method="POST"
                    >
                        @csrf
                        @method('PUT')

                        {{-- Email (tidak dapat diubah) --}}
                        <div class="mb-3">
                            <label for="email" class="form-label">
                                Email
                            </label>

                            <input
                                type="email"
                                class="form-control"
                                id="email"
                                value="{{ $user->email }}"
                                disabled
                            >
                        </div>

                        {{-- Role (tidak dapat diubah) --}}
                        <div class="mb-3">
                            <label for="role" class="form-label">
                                Role
                            </label>

                            <input
                                type="text"
                                class="form-control"
                                id="role"
                                value="{{ $user->role->label() }}"
                                disabled
                            >
                        </div>

                        {{-- Nama Lengkap --}}
                        <div class="mb-3">
                            <label for="name" class="form-label">
                                Nama Lengkap <span class="text-danger">*</span>
                            </label>

                            <input
                                type="text"
                                class="form-control @error('name') is-invalid @enderror"
                                id="name"
                                name="name"
                                value="{{ old('name', $user->name) }}"
                                required
                            >

                            @error('name')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <hr class="my-4">

                        {{-- Password dan Konfirmasi Password --}}
                        <div class="row g-3 mb-4">

                            <div class="col-md-6">
                                <label for="password" class="form-label">
                                    Password Baru
                                </label>

                                <div class="input-group has-validation">
                                    <input
                                        type="password"
                                        class="form-control @error('password') is-invalid @enderror"
                                        id="password"
                                        name="password"
                                        autocomplete="new-password"
                                    >

                                    <button
                                        type="button"
                                        class="btn btn-outline-secondary"
                                        data-toggle-password="password"
                                        aria-label="Tampilkan password"
                                    >
                                        <i class="bi bi-eye"></i>
                                    </button>

                                    @error('password')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                <div class="form-text">
                                    Kosongkan jika tidak ingin mengubah password.
                                </div>
                            </div>

                            <div class="col-md-6">
                                <label for="password_confirmation" class="form-label">
                                    Ulangi Password Baru
                                </label>

                                <input
                                    type="password"
                                    class="form-control"
                                    id="password_confirmation"
                                    name="password_confirmation"
                                    autocomplete="new-password"
                                >
                            </div>

                        </div>

                        {{-- Tombol Aksi --}}
                        <div class="d-flex justify-content-end">
                            <button type="submit" class="btn btn-primary">
                                Simpan Perubahan
                            </button>
                        </div>
                    </form>

                </div>
            </div>

        </div>
    </div>

@endsection
