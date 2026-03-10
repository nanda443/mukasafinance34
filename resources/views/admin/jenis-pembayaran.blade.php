{{-- resources/views/admin/jenis-pembayaran.blade.php --}}
@extends('layouts.app')

@section('title', 'Jenis Pembayaran')

@section('content')

<script type="text/javascript">
// Definisikan fungsi SEBELUM digunakan
function editJenisPembayaran(id, nama, nominal, kategori, status, keterangan) {
    console.log('Edit function called with:', {id, nama, nominal, kategori, status, keterangan});
    
    // Set action URL
    var url = "{{ route('admin.jenis-pembayaran.index') }}" + "/" + id;
    document.getElementById('formEdit').action = url;
    
    // Set values
    document.getElementById('editNama').value = nama;
    document.getElementById('editNominal').value = nominal;
    document.getElementById('editKategori').value = kategori;
    document.getElementById('editKeterangan').value = keterangan || '';
    
    // Set checkbox
    if(status == 1 || status == true || status == '1') {
        document.getElementById('editStatus').checked = true;
    } else {
        document.getElementById('editStatus').checked = false;
    }
    
    // Show modal
    $('#modalEdit').modal('show');
}

// Fungsi Delete
function deleteJenisPembayaran(id, nama) {
    console.log('Delete function called with:', {id, nama});
    
    // Set action URL
    var url = "{{ route('admin.jenis-pembayaran.index') }}" + "/" + id;
    document.getElementById('formDelete').action = url;
    
    // Set nama
    document.getElementById('deleteNama').textContent = nama;
    
    // Show modal
    $('#modalDelete').modal('show');
}

// Fungsi untuk menampilkan alert sukses
function showSuccessAlert(message) {
    // Hapus alert existing
    $('.alert-dismissible').remove();
    
    // Buat alert baru
    var alertHtml = `
        <div class="alert alert-success alert-dismissible fade show">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            <i class="fas fa-check-circle"></i> ${message}
        </div>
    `;
    
    $('.content').prepend(alertHtml);
    
    // Auto hide setelah 3 detik
    setTimeout(function() {
        $('.alert-dismissible').fadeOut('slow');
    }, 3000);
}

// Fungsi untuk menampilkan alert error
function showErrorAlert(message) {
    // Hapus alert existing
    $('.alert-dismissible').remove();
    
    // Buat alert baru
    var alertHtml = `
        <div class="alert alert-danger alert-dismissible fade show">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            <i class="fas fa-exclamation-triangle"></i> ${message}
        </div>
    `;
    
    $('.content').prepend(alertHtml);
}
</script>

<div class="jenis-pembayaran-page">
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Jenis Pembayaran</h1>
            </div>
            <div class="col-sm-6">
                <button class="btn btn-primary float-right" data-toggle="modal" data-target="#modalTambah">
                    <i class="fas fa-plus"></i> Tambah Jenis Pembayaran
                </button>
            </div>
        </div>
    </div>
</div>

<section class="content">
    <div class="container-fluid">
        {{-- Alert Section --}}
        @if(session('success'))
            <div class="alert alert-success alert-dismissible">
                <button type="button" class="close" data-dismiss="alert">&times;</button>
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible">
                <button type="button" class="close" data-dismiss="alert">&times;</button>
                {{ session('error') }}
            </div>
        @endif

        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Daftar Jenis Pembayaran</h3>
                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse">
                        <i class="fas fa-minus"></i>
                    </button>
                </div>
            </div>
            <div class="card-body">
                @if(isset($jenisPembayaran) && $jenisPembayaran->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-hover" id="table-jenis-pembayaran">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Nama Pembayaran</th>
                                    <th>Nominal</th>
                                    <th>Kategori</th>
                                    <th>Status</th>
                                    <th>Jumlah Pembayar</th>
                                    <th>Total Pemasukan</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($jenisPembayaran as $index => $item)
                                    @php
                                        $pembayaranCount = $item->pembayarans_count ?? 0;
                                        $totalPemasukan = $pembayaranCount * $item->nominal;
                                    @endphp
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>
                                            <strong>{{ $item->nama }}</strong>
                                            @if($item->keterangan)
                                                <br><small class="text-muted">{{ $item->keterangan }}</small>
                                            @endif
                                        </td>
                                        <td>Rp {{ number_format($item->nominal, 0, ',', '.') }}</td>
                                        <td>
                                            <span class="badge badge-{{ $item->kategori == 'SPP' ? 'primary' : ($item->kategori == 'Gedung' ? 'success' : ($item->kategori == 'Praktikum' ? 'info' : ($item->kategori == 'Ujian' ? 'warning' : 'secondary'))) }}">
                                                {{ $item->kategori }}
                                            </span>
                                        </td>
                                        <td>
                                            @if($item->status)
                                                <span class="badge badge-success">Aktif</span>
                                            @else
                                                <span class="badge badge-danger">Nonaktif</span>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="badge badge-info">{{ $pembayaranCount }} Siswa</span>
                                        </td>
                                        <td>
                                            <span class="text-success font-weight-bold">
                                                Rp {{ number_format($totalPemasukan, 0, ',', '.') }}
                                            </span>
                                        </td>
                                        <td>
                                            <div class="btn-group">
                                                <a href="{{ route('admin.jenis-pembayaran.show', $item->id) }}" class="btn btn-info btn-sm" title="Detail">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <button class="btn btn-warning btn-sm" onclick="editJenisPembayaran({{ $item->id }}, '{{ addslashes($item->nama) }}', {{ $item->nominal }}, '{{ $item->kategori }}', {{ $item->status }}, '{{ addslashes($item->keterangan) }}')" title="Edit">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                                <button class="btn btn-danger btn-sm" onclick="deleteJenisPembayaran({{ $item->id }}, '{{ addslashes($item->nama) }}')" title="Hapus">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="alert alert-warning text-center">
                        <i class="fas fa-exclamation-triangle"></i> Belum ada jenis pembayaran yang ditambahkan.
                    </div>
                @endif
            </div>
        </div>
    </div>
</section>

<!-- Modal Tambah -->
<div class="modal fade" id="modalTambah">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Tambah Jenis Pembayaran</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <form action="{{ route('admin.jenis-pembayaran.store') }}" method="POST" id="formTambah">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label>Nama Pembayaran <span class="text-danger">*</span></label>
                        <input type="text" name="nama" class="form-control" required placeholder="Contoh: SPP Bulan Januari">
                    </div>
                    <div class="form-group">
                        <label>Nominal <span class="text-danger">*</span></label>
                        <input type="number" name="nominal" class="form-control" required min="1000" placeholder="Contoh: 500000">
                    </div>
                    <div class="form-group">
                        <label>Kategori <span class="text-danger">*</span></label>
                        <select name="kategori" class="form-control" required>
                            <option value="">Pilih Kategori</option>
                            <option value="SPP">SPP</option>
                            <option value="Gedung">Gedung</option>
                            <option value="Praktikum">Praktikum</option>
                            <option value="Ujian">Ujian</option>
                            <option value="Lainnya">Lainnya</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Keterangan (Opsional)</label>
                        <textarea name="keterangan" class="form-control" rows="2" placeholder="Tambahkan keterangan jika perlu"></textarea>
                    </div>
                    <div class="form-group">
                        <div class="custom-control custom-switch">
                            <input type="checkbox" class="custom-control-input" name="status" id="statusTambah" value="1" checked>
                            <label class="custom-control-label" for="statusTambah">Aktif</label>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Edit -->
<div class="modal fade" id="modalEdit">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Edit Jenis Pembayaran</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <form id="formEdit" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="form-group">
                        <label>Nama Pembayaran <span class="text-danger">*</span></label>
                        <input type="text" name="nama" class="form-control" id="editNama" required>
                    </div>
                    <div class="form-group">
                        <label>Nominal <span class="text-danger">*</span></label>
                        <input type="number" name="nominal" class="form-control" id="editNominal" required min="1000">
                    </div>
                    <div class="form-group">
                        <label>Kategori <span class="text-danger">*</span></label>
                        <select name="kategori" class="form-control" id="editKategori" required>
                            <option value="SPP">SPP</option>
                            <option value="Gedung">Gedung</option>
                            <option value="Praktikum">Praktikum</option>
                            <option value="Ujian">Ujian</option>
                            <option value="Lainnya">Lainnya</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Keterangan (Opsional)</label>
                        <textarea name="keterangan" class="form-control" id="editKeterangan" rows="2"></textarea>
                    </div>
                    <div class="form-group">
                        <div class="custom-control custom-switch">
                            <input type="checkbox" class="custom-control-input" name="status" id="editStatus" value="1">
                            <label class="custom-control-label" for="editStatus">Aktif</label>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Update
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Delete Confirmation -->
<div class="modal fade" id="modalDelete">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Konfirmasi Hapus</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <p>Apakah Anda yakin ingin menghapus jenis pembayaran <strong id="deleteNama"></strong>?</p>
                <p class="text-danger">
                    <i class="fas fa-exclamation-triangle"></i> 
                    <strong>Peringatan:</strong> Data yang dihapus tidak dapat dikembalikan!
                </p>
            </div>
            <div class="modal-footer">
                <form id="formDelete" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-trash"></i> Hapus
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

    </div>
@endsection

@push('styles')
<style>
    .jenis-pembayaran-page .table td,
    .jenis-pembayaran-page .table th {
        vertical-align: middle;
    }

    .table th {
        background-color: #f8f9fa;
        font-weight: 600;
    }
    .btn-group .btn {
        margin-right: 2px;
    }
    .badge {
        font-size: 0.8em;
    }

    @media (max-width: 767.98px) {
        .jenis-pembayaran-page .content-header h1 {
            font-size: 1.3rem;
        }

        .jenis-pembayaran-page .content-header .btn.float-right {
            float: none !important;
            width: 100%;
            margin-top: .65rem;
        }

        .jenis-pembayaran-page .card-body {
            padding: .85rem;
        }

        .jenis-pembayaran-page .table-responsive {
            font-size: .86rem;
        }

        .jenis-pembayaran-page .table td,
        .jenis-pembayaran-page .table th {
            white-space: nowrap;
        }

        .jenis-pembayaran-page .btn-group {
            display: inline-flex;
            flex-wrap: nowrap;
        }

        .jenis-pembayaran-page .modal-footer {
            display: flex;
            flex-direction: column-reverse;
            gap: .5rem;
        }

        .jenis-pembayaran-page .modal-footer .btn {
            width: 100%;
            margin: 0 !important;
        }
    }
</style>
@endpush

@push('scripts')
<script src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.10.25/js/dataTables.bootstrap4.min.js"></script>

<script type="text/javascript">
    window.disableAjaxSubmit = true;

$(document).ready(function() {
    // Initialize DataTable
    $('#table-jenis-pembayaran').DataTable({
        "paging": true,
        "lengthChange": true,
        "searching": true,
        "ordering": true,
        "info": true,
        "autoWidth": false,
        "responsive": true,
        "language": {
            "url": "//cdn.datatables.net/plug-ins/1.10.25/i18n/Indonesian.json"
        }
    });

    // Handle Edit Form dengan AJAX


    $('#formEdit').off('submit', function(e) {
         if (window.disableAjaxSubmit) return true; 

        e.preventDefault();
        
        var form = $(this);
        var url = form.attr('action');
        var formData = form.serialize();
        var submitBtn = form.find('button[type="submit"]');

        console.log('Edit URL:', url);
        console.log('Edit Data:', formData);

        // Show loading state
        submitBtn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Memproses...');

        $.ajax({
            url: url,
            type: 'POST',
            data: formData,
            success: function(response) {
                console.log('Edit success:', response);
                
                if (response.success) {
                    $('#modalEdit').modal('hide');
                    showSuccessAlert(response.message || 'Data berhasil diperbarui!');
                    
                    // Reload halaman setelah 1.5 detik
                    setTimeout(function() {
                        location.reload();
                    }, 1500);
                } else {
                    showErrorAlert(response.message || 'Gagal memperbarui data!');
                    submitBtn.prop('disabled', false).html('<i class="fas fa-save"></i> Update');
                }
            },
            error: function(xhr, status, error) {
                console.log('Edit error:', xhr.responseJSON);
                
                var response = xhr.responseJSON;
                var message = 'Terjadi kesalahan saat mengupdate data.';
                
                if (response && response.message) {
                    message = response.message;
                } else if (xhr.status === 422) {
                    message = 'Data yang dimasukkan tidak valid. Silakan periksa kembali.';
                }
                
                showErrorAlert(message);
                submitBtn.prop('disabled', false).html('<i class="fas fa-save"></i> Update');
            }
        });
    });

    // Handle Delete Form dengan AJAX
    $('#formDelete').off('submit', function(e) {
          if (window.disableAjaxSubmit) return true; 
        e.preventDefault();
        
        var form = $(this);
        var url = form.attr('action');
        var formData = form.serialize();
        var submitBtn = form.find('button[type="submit"]');

        console.log('Delete URL:', url);
        console.log('Delete Data:', formData);

        // Show loading state
        submitBtn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Menghapus...');

        $.ajax({
            url: url,
            type: 'POST',
            data: formData,
            success: function(response) {
                console.log('Delete success:', response);
                
                if (response.success) {
                    $('#modalDelete').modal('hide');
                    showSuccessAlert(response.message || 'Data berhasil dihapus!');
                    
                    // Reload halaman setelah 1.5 detik
                    setTimeout(function() {
                        location.reload();
                    }, 1500);
                } else {
                    $('#modalDelete').modal('hide');
                    showErrorAlert(response.message || 'Gagal menghapus data!');
                    submitBtn.prop('disabled', false).html('<i class="fas fa-trash"></i> Hapus');
                }
            },
            error: function(xhr, status, error) {
                console.log('Delete error:', xhr.responseJSON);
                
                var response = xhr.responseJSON;
                var message = 'Terjadi kesalahan saat menghapus data.';
                
                if (response && response.message) {
                    message = response.message;
                } else if (xhr.status === 404) {
                    message = 'Data tidak ditemukan.';
                }
                
                $('#modalDelete').modal('hide');
                showErrorAlert(message);
                submitBtn.prop('disabled', false).html('<i class="fas fa-trash"></i> Hapus');
            }
        });
    });

    // Handle Tambah Form - reload setelah submit berhasil
    $('#formTambah').on('submit', function() {
        // Form akan di-submit secara normal, kemudian redirect dari controller
        // Tambahkan loading state
        var submitBtn = $(this).find('button[type="submit"]');
        submitBtn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Menyimpan...');
    });

    // Auto-hide alerts setelah 5 detik
    setTimeout(function() {
        $('.alert').fadeOut('slow');
    }, 5000);

    // Reset form ketika modal ditutup
    $('#modalEdit').on('hidden.bs.modal', function() {
        $('#formEdit')[0].reset();
        $('#formEdit').find('button[type="submit"]').prop('disabled', false).html('<i class="fas fa-save"></i> Update');
    });

    $('#modalDelete').on('hidden.bs.modal', function() {
        $('#formDelete').find('button[type="submit"]').prop('disabled', false).html('<i class="fas fa-trash"></i> Hapus');
    });

    $('#modalTambah').on('hidden.bs.modal', function() {
        $('#formTambah')[0].reset();
        $('#formTambah').find('button[type="submit"]').prop('disabled', false).html('<i class="fas fa-save"></i> Simpan');
    });
});

// Fungsi untuk menampilkan alert sukses
function showSuccessAlert(message) {
    // Hapus alert existing
    $('.alert-dismissible').remove();
    
    // Buat alert baru
    var alertHtml = `
        <div class="alert alert-success alert-dismissible fade show">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            <i class="fas fa-check-circle"></i> ${message}
        </div>
    `;
    
    $('.content').prepend(alertHtml);
    
    // Auto hide setelah 3 detik
    setTimeout(function() {
        $('.alert-dismissible').fadeOut('slow');
    }, 3000);
}

// Fungsi untuk menampilkan alert error
function showErrorAlert(message) {
    // Hapus alert existing
    $('.alert-dismissible').remove();
    
    // Buat alert baru
    var alertHtml = `
        <div class="alert alert-danger alert-dismissible fade show">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            <i class="fas fa-exclamation-triangle"></i> ${message}
        </div>
    `;
    
    $('.content').prepend(alertHtml);
    
    // Auto hide setelah 5 detik
    setTimeout(function() {
        $('.alert-dismissible').fadeOut('slow');
    }, 5000);
}

document.addEventListener('DOMContentLoaded', function() {

    // FORM EDIT → SUBMIT NORMAL
    document.getElementById('formEdit').onsubmit = function() {
        return true; // submit ke Laravel langsung
    };

    // FORM DELETE → SUBMIT NORMAL
    document.getElementById('formDelete').onsubmit = function() {
        return confirm('Yakin ingin menghapus data ini?'); 
        // Laravel handle, otomatis redirect ke halaman ini
    };

});

</script>
@endpush