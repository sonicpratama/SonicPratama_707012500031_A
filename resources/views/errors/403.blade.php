
@extends('layouts.app')

@section('title', 'Akses Ditolak')

@section('content')

    <div class="empty-state">
        <i class="bi bi-shield-lock"></i>

        <h1 class="display-4 fw-bold text-danger mt-2">403</h1>

        <h5 class="mb-2">Akses Ditolak</h5>

        <p class="mb-4">
            {{ $exception->getMessage() ?: 'Anda tidak memiliki hak akses ke halaman ini.' }}
        </p>

        <a href="{{ route('dashboard') }}" class="btn btn-primary">
            <i class="bi bi-house me-1"></i>
            Kembali ke Dashboard
        </a>
    </div>

@endsection
