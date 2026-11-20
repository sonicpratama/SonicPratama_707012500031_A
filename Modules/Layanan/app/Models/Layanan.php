<?php

namespace Modules\Layanan\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Modules\Layanan\Database\Factories\LayananFactory;
use Modules\Pengaduan\Models\Pengaduan;

class Layanan extends Model
{
    /** @use HasFactory<LayananFactory> */
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'layanan';

    /**
     * Allowed service categories (key => label).
     *
     * @var array<string, string>
     */
    public const KATEGORI = [
        'administrasi' => 'Administrasi',
        'kependudukan' => 'Kependudukan',
        'perizinan' => 'Perizinan',
        'kesehatan' => 'Kesehatan',
        'pendidikan' => 'Pendidikan',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'nama_layanan',
        'kategori_layanan',
        'perangkat_daerah',
        'deskripsi',
        'tingkat_kematangan',
    ];

    /**
     * Complaints reported against this service.
     */
    public function pengaduan(): HasMany
    {
        return $this->hasMany(Pengaduan::class, 'layanan_id');
    }

    /**
     * Search by name, responsible agency, or description.
     */
    public function scopeCari(Builder $query, ?string $keyword): Builder
    {
        if (blank($keyword)) {
            return $query;
        }

        return $query->where(function (Builder $query) use ($keyword) {
            $query->where('nama_layanan', 'like', "%{$keyword}%")
                ->orWhere('perangkat_daerah', 'like', "%{$keyword}%")
                ->orWhere('deskripsi', 'like', "%{$keyword}%");
        });
    }

    /**
     * Create a new factory instance for the model.
     */
    protected static function newFactory(): LayananFactory
    {
        return LayananFactory::new();
    }
}
