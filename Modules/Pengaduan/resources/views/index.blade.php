
@extends('layouts.app')

@section('title', 'Pengaduan')

@section('content')

    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-4">
        <div>
            <h4 class="mb-1">
                {{ auth()->user()->isAdmin() ? 'Semua Pengaduan' : 'Pengaduan Saya' }}
            </h4>
            <p class="text-muted mb-0 small">
                Kendala layanan SPBE yang dilaporkan warga. Data tersimpan tanpa memuat ulang halaman.
            </p>
        </div>

        {{-- Hanya warga yang dapat membuat pengaduan --}}
        @if (auth()->user()->isWarga())
            <button type="button" class="btn btn-primary" id="addBtn">
                <i class="bi bi-plus-lg me-1"></i> Buat Pengaduan
            </button>
        @endif
    </div>

    <div id="alert-container"></div>

    <div class="card">
        <div class="card-header d-flex flex-wrap justify-content-between align-items-center gap-2 py-3">
            <span class="fw-semibold">
                Daftar Pengaduan
                <span class="badge text-bg-light border ms-1" id="total-count">0</span>
            </span>

            <div class="input-group" style="max-width: 300px">
                <span class="input-group-text bg-white">
                    <i class="bi bi-search"></i>
                </span>

                <input
                    type="search"
                    id="search"
                    class="form-control border-start-0"
                    placeholder="Cari pengaduan..."
                >
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-hover" id="pengaduan-table">
                <thead>
                    <tr>
                        <th width="5%">No</th>

                        @if (auth()->user()->isAdmin())
                            <th>Pelapor</th>
                        @endif

                        <th>Layanan</th>
                        <th>Deskripsi Kendala</th>
                        <th>Bukti</th>
                        <th>Tanggal</th>
                        <th class="text-end">Aksi</th>
                    </tr>
                </thead>

                <tbody>
                    <tr>
                        <td colspan="{{ auth()->user()->isAdmin() ? 7 : 6 }}" class="text-center text-muted py-5">
                            <div class="spinner-border spinner-border-sm me-2" role="status"></div>
                            Memuat data...
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    {{-- Modal Form (khusus warga) --}}
    @if (auth()->user()->isWarga())
        <div
            class="modal fade"
            id="pengaduanModal"
            tabindex="-1"
            aria-labelledby="pengaduanModalLabel"
            aria-hidden="true"
        >
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">

                    <div class="modal-header">
                        <h5 class="modal-title" id="pengaduanModalLabel">
                            Buat Pengaduan
                        </h5>

                        <button
                            type="button"
                            class="btn-close"
                            data-bs-dismiss="modal"
                            aria-label="Tutup"
                        ></button>
                    </div>

                    <div class="modal-body">
                        <form id="pengaduanForm">
                            @csrf

                            <input type="hidden" name="id" id="id">

                            <div class="mb-3">
                                <label for="layanan_id" class="form-label">
                                    Layanan yang Bermasalah
                                    <span class="text-danger">*</span>
                                </label>

                                <select
                                    class="form-select"
                                    id="layanan_id"
                                    name="layanan_id"
                                    required
                                >
                                    <option value="">-- Pilih layanan --</option>

                                    @foreach ($layanans as $layanan)
                                        <option value="{{ $layanan->id }}">
                                            {{ $layanan->nama_layanan }}
                                        </option>
                                    @endforeach
                                </select>

                                <div class="invalid-feedback" id="layanan_id-error"></div>
                            </div>

                            <div class="mb-3">
                                <label for="deskripsi_pengaduan" class="form-label">
                                    Deskripsi Kendala
                                    <span class="text-danger">*</span>
                                </label>

                                <textarea
                                    class="form-control"
                                    id="deskripsi_pengaduan"
                                    name="deskripsi_pengaduan"
                                    rows="4"
                                    placeholder="Jelaskan kendala yang Anda alami..."
                                    required
                                ></textarea>

                                <div class="invalid-feedback" id="deskripsi_pengaduan-error"></div>
                            </div>

                            <div class="mb-3">
                                <label for="file_bukti" class="form-label">
                                    Bukti Pendukung
                                    <span class="text-danger" id="file-required">*</span>
                                </label>

                                <div class="small mb-2 d-none" id="file-current">
                                    Berkas saat ini:
                                    <a
                                        href="#"
                                        target="_blank"
                                        rel="noopener"
                                        id="file-current-link"
                                    ></a>
                                </div>

                                <input
                                    type="file"
                                    class="form-control"
                                    id="file_bukti"
                                    name="file_bukti"
                                    accept=".jpg,.jpeg,.png,.pdf,.doc,.docx"
                                >

                                <div class="invalid-feedback" id="file_bukti-error"></div>

                                <div class="form-text" id="file-help">
                                    Tangkapan layar atau dokumen. Format jpg, jpeg, png, pdf, doc, atau docx. Maksimal 2 MB.
                                </div>
                            </div>
                        </form>
                    </div>

                    <div class="modal-footer">
                        <button
                            type="button"
                            class="btn btn-light"
                            data-bs-dismiss="modal"
                        >
                            Batal
                        </button>

                        <button type="button" class="btn btn-primary" id="saveBtn">
                            <span
                                class="spinner-border spinner-border-sm me-1 d-none"
                                role="status"
                                aria-hidden="true"
                            ></span>

                            <span class="submit-text">Simpan</span>
                        </button>
                    </div>

                </div>
            </div>
        </div>
    @endif

@endsection

@push('scripts')
<script>
    $(document).ready(function () {

        const isAdmin = @json(auth()->user()->isAdmin());
        const columnCount = isAdmin ? 7 : 6;

        const urls = {
            data: "{{ route('pengaduan.data') }}",
            store: "{{ route('pengaduan.store') }}",
            edit: "{{ route('pengaduan.edit', ':id') }}",
            update: "{{ route('pengaduan.update', ':id') }}",
            destroy: "{{ route('pengaduan.destroy', ':id') }}"
        };

        const defaultHelp = 'Tangkapan layar atau dokumen. Format jpg, jpeg, png, pdf, doc, atau docx. Maksimal 2 MB.';

        let records = [];

        // Set CSRF token untuk semua request AJAX
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                'Accept': 'application/json'
            }
        });

        // Escape karakter HTML untuk mencegah XSS
        function escapeHtml(value) {
            return String(value ?? '')
                .replace(/&/g, '&amp;')
                .replace(/</g, '&lt;')
                .replace(/>/g, '&gt;')
                .replace(/"/g, '&quot;')
                .replace(/'/g, '&#039;');
        }

        // Link untuk membuka bukti di tab baru
        function fileLink(item) {
            if (!item.file_url) {
                return '<span class="text-muted">-</span>';
            }

            const icon = item.is_image
                ? 'bi-file-earmark-image text-primary'
                : 'bi-file-earmark-text text-danger';

            return `
                <a
                    href="${escapeHtml(item.file_url)}"
                    target="_blank"
                    rel="noopener"
                    class="small text-decoration-none"
                >
                    <i class="bi ${icon} me-1"></i>
                    ${escapeHtml(item.nama_file || 'Lihat berkas')}
                </a>
            `;
        }

        // Mode input berkas: wajib saat tambah, opsional saat edit
        function setFileMode(item = null) {
            $('#file_bukti').val('');

            $('#file-required').toggleClass('d-none', item !== null);

            $('#file-current').toggleClass(
                'd-none',
                !item?.file_url
            );

            $('#file-help').text(
                item
                    ? 'Kosongkan jika tidak ingin mengganti berkas. ' + defaultHelp
                    : defaultHelp
            );

            if (item?.file_url) {
                $('#file-current-link')
                    .attr('href', item.file_url)
                    .text(item.nama_file || 'Lihat berkas');
            }
        }

        function showAlert(message, type = 'success') {
            const icon = type === 'success'
                ? 'bi-check-circle'
                : 'bi-exclamation-triangle';

            const alert = $(`
                <div class="alert alert-${type} alert-dismissible fade show d-flex align-items-center gap-2" role="alert">
                    <i class="bi ${icon}"></i>
                    <span>${escapeHtml(message)}</span>
                    <button
                        type="button"
                        class="btn-close"
                        data-bs-dismiss="alert"
                        aria-label="Tutup"
                    ></button>
                </div>
            `);

            $('#alert-container').html(alert);

            setTimeout(() => {
                bootstrap.Alert.getOrCreateInstance(alert[0]).close();
            }, 4000);
        }

        function clearErrors() {
            $('#pengaduanForm .invalid-feedback').text('');
            $('#pengaduanForm .is-invalid').removeClass('is-invalid');
        }

        function emptyRow(icon, text) {
            return `
                <tr>
                    <td colspan="${columnCount}">
                        <div class="empty-state">
                            <i class="bi ${icon}"></i>
                            <p class="mt-2 mb-0">${text}</p>
                        </div>
                    </td>
                </tr>
            `;
        }

        // Tampilkan data ke tabel sesuai kata kunci pencarian
        function renderTable() {
            const keyword = $('#search').val().trim().toLowerCase();

            const rows = records.filter((item) =>
                [
                    item.pelapor,
                    item.layanan,
                    item.deskripsi_pengaduan,
                    item.nama_file
                ].some((value) =>
                    (value || '').toLowerCase().includes(keyword)
                )
            );

            $('#total-count').text(records.length);

            if (records.length === 0) {
                $('#pengaduan-table tbody').html(
                    emptyRow(
                        'bi-inbox',
                        isAdmin
                            ? 'Belum ada pengaduan.'
                            : 'Belum ada pengaduan. Klik <strong>Buat Pengaduan</strong> untuk memulai.'
                    )
                );
                return;
            }

            if (rows.length === 0) {
                $('#pengaduan-table tbody').html(
                    emptyRow(
                        'bi-search',
                        'Tidak ada pengaduan yang cocok dengan pencarian.'
                    )
                );
                return;
            }

            const html = rows.map((item, index) => `
                <tr>
                    <td class="text-muted">${index + 1}</td>

                    ${isAdmin ? `<td class="fw-medium">${escapeHtml(item.pelapor)}</td>` : ''}

                    <td>
                        <span class="badge text-bg-light border">
                            ${escapeHtml(item.layanan)}
                        </span>
                    </td>

                    <td class="small">${escapeHtml(item.deskripsi_pengaduan)}</td>

                    <td>${fileLink(item)}</td>

                    <td class="small text-muted">${escapeHtml(item.tanggal)}</td>

                    <td class="text-end text-nowrap">
                        ${item.can_edit ? `
                            <button
                                data-id="${item.id}"
                                class="edit btn btn-sm btn-outline-primary"
                            >
                                <i class="bi bi-pencil"></i> Edit
                            </button>
                        ` : ''}

                        ${item.can_delete ? `
                            <button
                                data-id="${item.id}"
                                data-nama="${escapeHtml(item.layanan)}"
                                class="delete btn btn-sm btn-outline-danger"
                            >
                                <i class="bi bi-trash"></i> Hapus
                            </button>
                        ` : ''}
                    </td>
                </tr>
            `).join('');

            $('#pengaduan-table tbody').html(html);
        }

        // Muat data dari server
        function loadData() {
            $.ajax({
                url: urls.data,
                type: 'GET',
                dataType: 'json',

                success: function (data) {
                    records = data;
                    renderTable();
                },

                error: function (xhr) {
                    console.error('Error:', xhr.responseText);

                    $('#pengaduan-table tbody').html(
                        emptyRow(
                            'bi-wifi-off',
                            'Gagal memuat data. Silakan refresh halaman.'
                        )
                    );
                }
            });
        }

        loadData();

        $('#search').on('input', renderTable);

        // Form tambah / ubah hanya tersedia untuk warga
        const modalElement = document.getElementById('pengaduanModal');

        if (modalElement) {

            const pengaduanModal = new bootstrap.Modal(modalElement);

            $('#pengaduanModal').on('shown.bs.modal', function () {
                $('#layanan_id').trigger('focus');
            });

            $('#pengaduanModal').on('hidden.bs.modal', function () {
                $('#pengaduanForm').trigger('reset');
                $('#id').val('');
                clearErrors();
                setFileMode();
            });

            // Tambah data
            $('#addBtn').click(function () {
                $('#pengaduanModalLabel').text('Buat Pengaduan');
                setFileMode();
                pengaduanModal.show();
            });

            // Simpan data (create / update)
            $('#saveBtn').click(function () {
                const btn = $(this);
                const id = $('#id').val();

                btn.prop('disabled', true);
                btn.find('.submit-text').text('Menyimpan...');
                btn.find('.spinner-border').removeClass('d-none');

                clearErrors();

                // FormData digunakan agar file ikut terkirim
                const formData = new FormData(
                    document.getElementById('pengaduanForm')
                );

                if (id) {
                    formData.append('_method', 'PUT');
                }

                $.ajax({
                    url: id
                        ? urls.update.replace(':id', id)
                        : urls.store,

                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    dataType: 'json',

                    success: function (response) {
                        pengaduanModal.hide();
                        loadData();
                        showAlert(response.success);
                    },

                    error: function (xhr) {
                        if (xhr.status === 422) {
                            $.each(xhr.responseJSON.errors, function (key, value) {
                                $(`#${key}`).addClass('is-invalid');
                                $(`#${key}-error`).text(value[0]);
                            });

                            $('#pengaduanForm .is-invalid:first').focus();

                        } else {
                            console.error('Error:', xhr.responseText);

                            pengaduanModal.hide();

                            showAlert(
                                (xhr.responseJSON && xhr.responseJSON.message)
                                    || 'Terjadi kesalahan. Silakan coba lagi.',
                                'danger'
                            );
                        }
                    },

                    complete: function () {
                        btn.prop('disabled', false);
                        btn.find('.submit-text').text('Simpan');
                        btn.find('.spinner-border').addClass('d-none');
                    }
                });
            });

            // Tekan Enter di form = simpan
            $('#pengaduanForm').on('submit', function (e) {
                e.preventDefault();
                $('#saveBtn').trigger('click');
            });

            // Edit data
            $(document).on('click', '.edit', function () {
                const id = $(this).data('id');

                $.get(urls.edit.replace(':id', id), function (data) {
                    $('#pengaduanModalLabel').text('Ubah Pengaduan');

                    $('#id').val(data.id);
                    $('#layanan_id').val(data.layanan_id);
                    $('#deskripsi_pengaduan').val(data.deskripsi_pengaduan);

                    setFileMode({
                        file_url: data.file_url,
                        nama_file: data.nama_file
                    });

                    pengaduanModal.show();

                }).fail(function () {
                    showAlert(
                        'Gagal memuat data untuk diedit.',
                        'danger'
                    );
                });
            });
        }

        // Hapus data
        $(document).on('click', '.delete', function () {
            const id = $(this).data('id');
            const nama = $(this).data('nama');

            confirmDelete(
                `Pengaduan untuk layanan "${nama}" akan dihapus dan tidak bisa dikembalikan.`
            ).then(function (confirmed) {

                if (!confirmed) {
                    return;
                }

                $.ajax({
                    type: 'DELETE',
                    url: urls.destroy.replace(':id', id),
                    dataType: 'json',

                    success: function (response) {
                        loadData();
                        showAlert(response.success);
                    },

                    error: function (xhr) {
                        showAlert(
                            (xhr.responseJSON && xhr.responseJSON.message)
                                || 'Gagal menghapus data. Silakan coba lagi.',
                            'danger'
                        );
                    }
                });
            });
        });

    });
</script>
@endpush
