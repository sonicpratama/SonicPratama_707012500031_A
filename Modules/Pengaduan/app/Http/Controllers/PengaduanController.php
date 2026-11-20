<?php

namespace Modules\Pengaduan\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Modules\Layanan\Models\Layanan;
use Modules\Pengaduan\Http\Requests\PengaduanRequest;
use Modules\Pengaduan\Models\Pengaduan;

class PengaduanController extends Controller
{
    /**
     * Display the page. Data is loaded via AJAX.
     */
    public function index(): View
    {
        return view('pengaduan::index', [
            'layanans' => Layanan::orderBy('nama_layanan')->get(['id', 'nama_layanan']),
        ]);
    }

    /**
     * Return the complaints as JSON.
     * Admin sees all of them, warga only sees their own.
     */
    public function getData(Request $request): JsonResponse
    {
        $user = $request->user();

        $items = Pengaduan::with(['user:id,name', 'layanan:id,nama_layanan'])
            ->when(! $user->isAdmin(), fn ($query) => $query->where('user_id', $user->id))
            ->latest()
            ->get()
            ->map(fn (Pengaduan $pengaduan) => [
                'id' => $pengaduan->id,
                'layanan' => $pengaduan->layanan?->nama_layanan,
                'pelapor' => $pengaduan->user?->name,
                'deskripsi_pengaduan' => $pengaduan->deskripsi_pengaduan,
                'nama_file' => $pengaduan->nama_file,
                'file_url' => $pengaduan->file_url,
                'is_image' => $pengaduan->is_image,
                'tanggal' => $pengaduan->created_at->format('d/m/Y H:i'),
                'can_edit' => $pengaduan->user_id === $user->id,
                'can_delete' => $user->isAdmin() || $pengaduan->user_id === $user->id,
            ]);

        return response()->json($items);
    }

    /**
     * Store a newly created complaint together with its evidence file.
     */
    public function store(PengaduanRequest $request): JsonResponse
    {
        $data = $request->safe()->except('file_bukti');

        $file = $request->file('file_bukti');

        $data['user_id'] = $request->user()->id;

        $data['file_bukti'] = $file->store(
            'bukti',
            'public'
        );

        $data['nama_file'] = $file->getClientOriginalName();

        Pengaduan::create($data);

        return response()->json([
            'success' => 'Pengaduan berhasil dikirim.',
        ]);
    }

    /**
     * Return a single complaint for the edit form.
     * Only the owner can open it.
     */
    public function edit(Request $request, Pengaduan $pengaduan): JsonResponse
    {
        $this->ensureOwner($request, $pengaduan);

        return response()->json($pengaduan);
    }

    /**
     * Update the specified complaint.
     * The old file is replaced only when a new one is uploaded.
     */
    public function update(
        PengaduanRequest $request,
        Pengaduan $pengaduan
    ): JsonResponse {
        $this->ensureOwner($request, $pengaduan);

        $data = $request->safe()->except('file_bukti');

        if ($request->hasFile('file_bukti')) {
            if ($pengaduan->file_bukti) {
                Storage::disk('public')->delete($pengaduan->file_bukti);
            }

            $file = $request->file('file_bukti');

            $data['file_bukti'] = $file->store(
                'bukti',
                'public'
            );

            $data['nama_file'] = $file->getClientOriginalName();
        }

        $pengaduan->update($data);

        return response()->json([
            'success' => 'Pengaduan berhasil diperbarui.',
        ]);
    }

    /**
     * Remove the specified complaint and its evidence file.
     * Admin can remove any complaint, warga only their own.
     */
    public function destroy(Request $request, Pengaduan $pengaduan): JsonResponse
    {
        $user = $request->user();

        abort_unless(
            $user->isAdmin() || $pengaduan->user_id === $user->id,
            403,
            'Anda tidak berhak menghapus pengaduan ini.'
        );

        if ($pengaduan->file_bukti) {
            Storage::disk('public')->delete($pengaduan->file_bukti);
        }

        $pengaduan->delete();

        return response()->json([
            'success' => 'Pengaduan berhasil dihapus.',
        ]);
    }

    /**
     * Only the owner of a complaint is allowed to open or change it.
     */
    private function ensureOwner(Request $request, Pengaduan $pengaduan): void
    {
        abort_unless(
            $pengaduan->user_id === $request->user()->id,
            403,
            'Anda hanya dapat mengubah pengaduan milik sendiri.'
        );
    }
}
