<?php

namespace Modules\Layanan\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Layanan\Models\Layanan;

class LayananDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $layanan = [
            ['Pembuatan KTP Elektronik Online', 'kependudukan', 'Dinas Kependudukan dan Pencatatan Sipil', 'Pengajuan dan pemantauan status KTP-el secara daring.', 4],
            ['Pengajuan Kartu Keluarga', 'kependudukan', 'Dinas Kependudukan dan Pencatatan Sipil', 'Layanan pembuatan dan perubahan data Kartu Keluarga.', 4],
            ['Surat Keterangan Domisili', 'administrasi', 'Dinas Komunikasi dan Informatika', 'Penerbitan surat keterangan domisili secara online.', 3],
            ['Izin Mendirikan Bangunan (PBG)', 'perizinan', 'Dinas Penanaman Modal dan PTSP', 'Pengajuan Persetujuan Bangunan Gedung.', 3],
            ['Izin Usaha Mikro dan Kecil', 'perizinan', 'Dinas Penanaman Modal dan PTSP', 'Pendaftaran NIB dan izin usaha UMK.', 5],
            ['Pendaftaran Online Puskesmas', 'kesehatan', 'Dinas Kesehatan', 'Antrean dan pendaftaran pasien Puskesmas.', 4],
            ['Informasi Ketersediaan Tempat Tidur RS', 'kesehatan', 'Dinas Kesehatan', 'Informasi real-time ketersediaan tempat tidur rumah sakit.', 3],
            ['Penerimaan Peserta Didik Baru (PPDB)', 'pendidikan', 'Dinas Pendidikan', 'Pendaftaran dan seleksi siswa baru secara daring.', 5],
            ['Beasiswa Pendidikan Daerah', 'pendidikan', 'Dinas Pendidikan', 'Pengajuan beasiswa bagi pelajar berprestasi dan kurang mampu.', 2],
            ['Layanan Pengaduan Masyarakat', 'administrasi', 'Dinas Komunikasi dan Informatika', 'Kanal pengaduan layanan publik terpadu.', 3],
            ['Pelaporan Pajak Daerah', 'administrasi', 'Badan Pendapatan Daerah', 'Pelaporan dan pembayaran pajak daerah online.', 4],
            ['Pencatatan Akta Kelahiran', 'kependudukan', 'Dinas Kependudukan dan Pencatatan Sipil', 'Pengajuan akta kelahiran secara daring.', 2],
        ];

        foreach ($layanan as [$nama, $kategori, $perangkat, $deskripsi, $level]) {
            Layanan::firstOrCreate(
                ['nama_layanan' => $nama],
                [
                    'kategori_layanan' => $kategori,
                    'perangkat_daerah' => $perangkat,
                    'deskripsi' => $deskripsi,
                    'tingkat_kematangan' => $level,
                ]
            );
        }
    }
}
