{{-- resources/views/admin/data-siswa.blade.php --}}
@extends('layouts.app')

@section('title', 'Data Siswa')

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Data Siswa</h1>
            </div>
            <div class="col-sm-6">
                <div class="float-right">
                    <button class="btn btn-success mr-2" data-toggle="modal" data-target="#importSiswaModal">
                        <i class="fas fa-file-excel"></i> Import Excel
                    </button>
                    <button class="btn btn-primary" data-toggle="modal" data-target="#createSiswaModal">
                        <i class="fas fa-plus"></i> Tambah Siswa
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<section class="content">
    <div class="container-fluid">
        <!-- Filter -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Filter Data Siswa</h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Kelas</label>
                            <select class="form-control" id="filterKelas">
                                <option value="">Semua Kelas</option>
                                @foreach($kelas as $k)
                                <option value="{{ $k }}">Kelas {{ $k }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Jurusan</label>
                            <select class="form-control" id="filterJurusan">
                                <option value="">Semua Jurusan</option>
                                @foreach($jurusan as $j)
                                <option value="{{ $j }}">{{ $j }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>&nbsp;</label>
                            <div class="d-flex">
                                <button class="btn btn-primary form-control mr-2" id="btnFilter">
                                    <i class="fas fa-filter"></i> Terapkan
                                </button>
                                <button class="btn btn-secondary form-control" id="btnReset">
                                    <i class="fas fa-refresh"></i> Reset
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tabel Data Siswa -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Daftar Siswa</h3>
                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse">
                        <i class="fas fa-minus"></i>
                    </button>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-hover w-100" id="siswaTable">
                        <thead class="thead-light">
                            <tr>
                                <th width="5%">No</th>
                                <th width="5%">Foto</th>
                                <th width="15%">Nama Siswa</th>
                                <th width="10%">NIS</th>
                                <th width="15%">Email</th>
                                <th width="8%">Kelas</th>
                                <th width="8%">Jurusan</th>
                                <th width="12%">Total Pembayaran</th>
                                <th width="12%">Total Tagihan</th>
                                <th width="10%">Status</th>
                                <th width="10%">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Data akan di-load via AJAX -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Modal Create Siswa -->
<div class="modal fade" id="createSiswaModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah Siswa Baru</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <form id="createSiswaForm">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Nama Lengkap *</label>
                                <input type="text" name="name" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>NIS *</label>
                                <input type="text" name="nis" class="form-control" required>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Email *</label>
                                <input type="email" name="email" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Kelas *</label>
                                <select name="kelas" class="form-control" required>
                                    <option value="">Pilih Kelas</option>
                                    <option value="10">Kelas 10</option>
                                    <option value="11">Kelas 11</option>
                                    <option value="12">Kelas 12</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Jurusan *</label>
                                <select name="jurusan" class="form-control" required>
                                    <option value="">Pilih Jurusan</option>
                                    <option value="IPA">IPA</option>
                                    <option value="IPS">IPS</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Password *</label>
                                <input type="password" name="password" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Konfirmasi Password *</label>
                                <input type="password" name="password_confirmation" class="form-control" required>
                            </div>
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

<!-- Modal Edit Siswa -->
<div class="modal fade" id="editSiswaModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Data Siswa</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <form id="editSiswaForm">
                @csrf
                @method('PUT')
                <input type="hidden" name="id" id="edit_id">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Nama Lengkap *</label>
                                <input type="text" name="name" id="edit_name" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>NIS *</label>
                                <input type="text" name="nis" id="edit_nis" class="form-control" required>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Email *</label>
                                <input type="email" name="email" id="edit_email" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Kelas *</label>
                                <select name="kelas" id="edit_kelas" class="form-control" required>
                                    <option value="">Pilih Kelas</option>
                                    <option value="10">Kelas 10</option>
                                    <option value="11">Kelas 11</option>
                                    <option value="12">Kelas 12</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Jurusan *</label>
                                <select name="jurusan" id="edit_jurusan" class="form-control" required>
                                    <option value="">Pilih Jurusan</option>
                                    <option value="IPA">IPA</option>
                                    <option value="IPS">IPS</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Password</label>
                                <input type="password" name="password" class="form-control" placeholder="Kosongkan jika tidak ingin mengubah">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Konfirmasi Password</label>
                                <input type="password" name="password_confirmation" class="form-control" placeholder="Kosongkan jika tidak ingin mengubah password">
                            </div>
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
<!-- Modal Import Siswa -->
<div class="modal fade" id="importSiswaModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Import Data Siswa</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <form id="importSiswaForm" enctype="multipart/form-data">
                <div class="modal-body">
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i> Gunakan template excel yang telah disediakan agar import berjalan lancar.
                        <br>
                        <a href="{{ route('admin.data-siswa.template') }}" class="btn btn-sm btn-info mt-2">
                            <i class="fas fa-download"></i> Download Template
                        </a>
                    </div>
                    
                    <div class="form-group">
                        <label>Pilih File Excel (.xlsx, .xls)</label>
                        <input type="file" name="file" class="form-control" required accept=".xlsx, .xls, .csv">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-upload"></i> Upload & Import
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
$(document).ready(function() {
    // Setup CSRF token untuk semua AJAX request
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    // Initialize DataTable - PERBAIKAN: Gunakan route yang benar
    var table = $('#siswaTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: "{{ route('admin.data-siswa.index') }}", // PERBAIKAN: Gunakan route yang benar
            type: "GET",
            data: function (d) {
                d.kelas = $('#filterKelas').val();
                d.jurusan = $('#filterJurusan').val();
            },
            error: function(xhr, error, thrown) {
                console.log('DataTables error:', xhr, error, thrown);
                if (xhr.status === 500) {
                    alert('Terjadi kesalahan server. Silakan refresh halaman.');
                }
            }
        },
        columns: [
            {
                data: null,
                name: 'no',
                orderable: false,
                searchable: false,
                render: function(data, type, row, meta) {
                    return meta.row + meta.settings._iDisplayStart + 1;
                }
            },
            {
                data: 'foto',
                name: 'foto',
                orderable: false,
                searchable: false
            },
            { 
                data: 'name', 
                name: 'name'
            },
            { 
                data: 'nis', 
                name: 'nis'
            },
            { 
                data: 'email', 
                name: 'email'
            },
            { 
                data: 'kelas', 
                name: 'kelas'
            },
            { 
                data: 'jurusan', 
                name: 'jurusan'
            },
            { 
                data: 'total_pembayaran', 
                name: 'total_pembayaran', 
                orderable: false, 
                searchable: false
            },
            { 
                data: 'total_tagihan', 
                name: 'total_tagihan', 
                orderable: false, 
                searchable: false
            },
            { 
                data: 'status_pembayaran', 
                name: 'status_pembayaran', 
                orderable: false, 
                searchable: false
            },
            { 
                data: 'action', 
                name: 'action', 
                orderable: false, 
                searchable: false
            }
        ],
        language: {
            url: "//cdn.datatables.net/plug-ins/1.13.1/i18n/id.json"
        },
        order: [[1, 'asc']],
        pageLength: 10,
        lengthMenu: [[10, 25, 50, 100], [10, 25, 50, 100]],
        dom: '<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>rtip',
        initComplete: function() {
            console.log('DataTable initialized');
        }
    });

    // Filter
    $('#btnFilter').click(function() {
        table.ajax.reload();
    });

    // Reset Filter
    $('#btnReset').click(function() {
        $('#filterKelas').val('');
        $('#filterJurusan').val('');
        table.ajax.reload();
    });

    // Create siswa form - PERBAIKAN: Gunakan route yang benar
    $('#createSiswaForm').submit(function(e) {
        e.preventDefault();
        
        var formData = $(this).serialize();
        
        $.ajax({
            url: "{{ route('admin.data-siswa.store') }}", // PERBAIKAN: Gunakan route yang benar
            method: 'POST',
            data: formData,
            beforeSend: function() {
                $('button[type="submit"]').prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Menyimpan...');
            },
            success: function(response) {
                if (response.success) {
                    $('#createSiswaModal').modal('hide');
                    table.ajax.reload(null, false);
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        text: response.message,
                        timer: 2000,
                        showConfirmButton: false
                    });
                    $('#createSiswaForm')[0].reset();
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: response.message || 'Terjadi kesalahan'
                    });
                }
            },
            error: function(xhr) {
                var errorMessage = 'Terjadi kesalahan saat membuat siswa';
                if (xhr.responseJSON && xhr.responseJSON.errors) {
                    var errors = xhr.responseJSON.errors;
                    errorMessage = '';
                    for (var key in errors) {
                        errorMessage += errors[key][0] + '\n';
                    }
                } else if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMessage = xhr.responseJSON.message;
                }
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: errorMessage
                });
            },
            complete: function() {
                $('button[type="submit"]').prop('disabled', false).html('<i class="fas fa-save"></i> Simpan');
            }
        });
    });

    // Edit siswa - Load data - PERBAIKAN: Gunakan route yang benar
    $(document).on('click', '.edit-siswa', function() {
        var id = $(this).data('id');
        
        $.ajax({
            url: "{{ url('admin/data-siswa') }}/" + id + "/edit", // PERBAIKAN: Gunakan route yang benar
            method: 'GET',
            success: function(response) {
                if (response.success) {
                    var siswa = response.siswa;
                    
                    // Isi form dengan data yang ada
                    $('#edit_id').val(siswa.id);
                    $('#edit_name').val(siswa.name);
                    $('#edit_nis').val(siswa.nis);
                    $('#edit_email').val(siswa.email);
                    $('#edit_kelas').val(siswa.kelas);
                    $('#edit_jurusan').val(siswa.jurusan);
                    
                    $('#editSiswaModal').modal('show');
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: response.message || 'Gagal memuat data siswa'
                    });
                }
            },
            error: function(xhr) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Terjadi kesalahan saat memuat data siswa'
                });
            }
        });
    });

    // Edit siswa - Submit form - PERBAIKAN: Gunakan route yang benar
    $('#editSiswaForm').submit(function(e) {
        e.preventDefault();
        
        var id = $('#edit_id').val();
        var formData = $(this).serialize();
        
        $.ajax({
            url: "{{ url('admin/data-siswa') }}/" + id, // PERBAIKAN: Gunakan route yang benar
            method: 'POST',
            data: formData + '&_method=PUT',
            beforeSend: function() {
                $('button[type="submit"]').prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Mengupdate...');
            },
            success: function(response) {
                if (response.success) {
                    $('#editSiswaModal').modal('hide');
                    table.ajax.reload(null, false);
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        text: response.message,
                        timer: 2000,
                        showConfirmButton: false
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: response.message || 'Terjadi kesalahan'
                    });
                }
            },
            error: function(xhr) {
                var errorMessage = 'Terjadi kesalahan saat mengupdate siswa';
                if (xhr.responseJSON && xhr.responseJSON.errors) {
                    var errors = xhr.responseJSON.errors;
                    errorMessage = '';
                    for (var key in errors) {
                        errorMessage += errors[key][0] + '\n';
                    }
                } else if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMessage = xhr.responseJSON.message;
                }
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: errorMessage
                });
            },
            complete: function() {
                $('button[type="submit"]').prop('disabled', false).html('<i class="fas fa-save"></i> Update');
            }
        });
    });

    // Delete siswa - PERBAIKAN: Gunakan route yang benar
    $(document).on('click', '.delete-siswa', function() {
        var id = $(this).data('id');
        var name = $(this).closest('tr').find('td:eq(1)').text();
        
        Swal.fire({
            title: 'Hapus Siswa?',
            html: `
                <p>Anda akan menghapus siswa: <strong>${name}</strong></p>
                <p class="text-danger">Data yang dihapus tidak dapat dikembalikan!</p>
            `,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: '<i class="fas fa-trash"></i> Ya, Hapus!',
            cancelButtonText: '<i class="fas fa-times"></i> Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: "{{ url('admin/data-siswa') }}/" + id, // PERBAIKAN: Gunakan route yang benar
                    method: 'DELETE',
                    success: function(response) {
                        if (response.success) {
                            table.ajax.reload(null, false);
                            Swal.fire({
                                icon: 'success',
                                title: 'Terhapus!',
                                text: response.message,
                                timer: 2000,
                                showConfirmButton: false
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: response.message
                            });
                        }
                    },
                    error: function(xhr) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Terjadi kesalahan saat menghapus siswa'
                        });
                    }
                });
            }
        });
    });

    // Reset form ketika modal ditutup
    $('#createSiswaModal').on('hidden.bs.modal', function() {
        $('#createSiswaForm')[0].reset();
    });

    $('#editSiswaModal').on('hidden.bs.modal', function() {
        $('#editSiswaForm')[0].reset();
    });

    // Import Siswa
    $('#importSiswaForm').submit(function(e) {
        e.preventDefault();
        
        var formData = new FormData(this);
        
        $.ajax({
            url: "{{ route('admin.data-siswa.import') }}",
            method: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            beforeSend: function() {
                var btn = $('#importSiswaForm button[type="submit"]');
                btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Mengupload...');
            },
            success: function(response) {
                if (response.success) {
                    $('#importSiswaModal').modal('hide');
                    table.ajax.reload(null, false);
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        text: response.message,
                        timer: 2000,
                        showConfirmButton: false
                    });
                    $('#importSiswaForm')[0].reset();
                }
            },
            error: function(xhr) {
                var errorMessage = 'Terjadi kesalahan saat import';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMessage = xhr.responseJSON.message;
                }
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal Import',
                    html: errorMessage
                });
            },
            complete: function() {
                var btn = $('#importSiswaForm button[type="submit"]');
                btn.prop('disabled', false).html('<i class="fas fa-upload"></i> Upload & Import');
            }
        });
    });
});
</script>
@endsection