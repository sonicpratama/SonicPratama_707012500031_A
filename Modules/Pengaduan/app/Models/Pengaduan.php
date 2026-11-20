<?php

namespace Modules\Pengaduan\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\Layanan\Models\Layanan;
use Modules\Pengaduan\Database\Factories\PengaduanFactory;

class Pengaduan extends Model
{
    /** @use HasFactory<PengaduanFactory> */
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'pengaduan';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'user_id',
        'layanan_id',
        'deskripsi_pengaduan',
        'file_bukti',
        'nama_file',
    ];

    /**
     * The accessors to append to the model's array and JSON form.
     *
     * @var list<string>
     */
    protected $appends = [
        'file_url',
        'is_image',
    ];

    /**
     * The citizen who sent the complaint.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * The service the complaint is about.
     */
    public function layanan(): BelongsTo
    {
        return $this->belongsTo(Layanan::class, 'layanan_id');
    }

    /**
     * Public URL of the uploaded evidence, or null when there is no file.
     */
    protected function fileUrl(): Attribute
    {
        return Attribute::get(
            fn (): ?string => $this->file_bukti
                ? asset('storage/' . $this->file_bukti)
                : null
        );
    }

    /**
     * Whether the uploaded evidence is an image (jpg, jpeg, png).
     */
    protected function isImage(): Attribute
    {
        return Attribute::get(
            fn (): bool => $this->file_bukti !== null
                && in_array(
                    strtolower(pathinfo($this->file_bukti, PATHINFO_EXTENSION)),
                    ['jpg', 'jpeg', 'png'],
                    true
                )
        );
    }

    /**
     * Create a new factory instance for the model.
     */
    protected static function newFactory(): PengaduanFactory
    {
        return PengaduanFactory::new();
    }
}
