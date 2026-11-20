<?php

namespace Modules\Layanan\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Modules\Layanan\Http\Requests\LayananRequest;
use Modules\Layanan\Models\Layanan;

class LayananController extends Controller
{
    /**
     * Display a listing of services, optionally filtered by keyword and category.
     */
    public function index(Request $request): View
    {
        $search = $request->string('search')->trim()->toString();

        $kategori = $request->string('kategori')->toString();
        $kategori = array_key_exists($kategori, Layanan::KATEGORI) ? $kategori : null;

        $layanans = Layanan::query()
            ->cari($search)
            ->when(
                $kategori,
                fn ($query) => $query->where('kategori_layanan', $kategori)
            )
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('layanan::index', [
            'layanans' => $layanans,
            'search' => $search,
            'selectedKategori' => $kategori,
            'kategoriList' => Layanan::KATEGORI,
        ]);
    }

    /**
     * Show the form for creating a new service.
     */
    public function create(): View
    {
        return view('layanan::create', [
            'kategoriList' => Layanan::KATEGORI,
        ]);
    }

    /**
     * Store a newly created service.
     */
    public function store(LayananRequest $request): RedirectResponse
    {
        Layanan::create($request->validated());

        return redirect()
            ->route('layanan.index')
            ->with('success', 'Data layanan berhasil ditambahkan.');
    }

    /**
     * Show the form for editing the specified service.
     */
    public function edit(Layanan $layanan): View
    {
        return view('layanan::edit', [
            'layanan' => $layanan,
            'kategoriList' => Layanan::KATEGORI,
        ]);
    }

    /**
     * Update the specified service.
     */
    public function update(
        LayananRequest $request,
        Layanan $layanan
    ): RedirectResponse {
        $layanan->update($request->validated());

        return redirect()
            ->route('layanan.index')
            ->with('success', 'Data layanan berhasil diperbarui.');
    }

    /**
     * Remove the specified service together with the evidence files of its
     * complaints (the complaint rows are removed by the foreign key cascade).
     */
    public function destroy(Layanan $layanan): RedirectResponse
    {
        foreach ($layanan->pengaduan as $pengaduan) {
            if ($pengaduan->file_bukti) {
                Storage::disk('public')->delete($pengaduan->file_bukti);
            }
        }

        $layanan->delete();

        return redirect()
            ->route('layanan.index')
            ->with('success', 'Data layanan berhasil dihapus.');
    }
}
