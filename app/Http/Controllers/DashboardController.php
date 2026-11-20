<?php

namespace App\Http\Controllers;

use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Modules\Layanan\Models\Layanan;
use Modules\Pengaduan\Models\Pengaduan;

class DashboardController extends Controller
{
    public function __invoke(Request $request): View
    {
        $user = $request->user();

        $pengaduanQuery = Pengaduan::query()
            ->with(['user:id,name', 'layanan:id,nama_layanan'])
            ->when(! $user->isAdmin(), fn ($query) => $query->where('user_id', $user->id));

        return view('dashboard', [
            'totalLayanan' => Layanan::count(),

            'totalPengaduan' => (clone $pengaduanQuery)->count(),

            'pengaduanTerbaru' => $pengaduanQuery->latest()->take(5)->get(),

            'totalWarga' => $user->isAdmin()
                ? User::where('role', UserRole::Warga)->count()
                : null,

            'rataKematangan' => $user->isAdmin()
                ? round((float) Layanan::avg('tingkat_kematangan'), 1)
                : null,

            'perKategori' => $user->isAdmin()
                ? Layanan::selectRaw('kategori_layanan, COUNT(*) as total')
                    ->groupBy('kategori_layanan')
                    ->pluck('total', 'kategori_layanan')
                : collect(),
        ]);
    }
}
