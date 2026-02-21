{{-- resources/views/admin/user-management.blade.php --}}
@extends('layouts.app')

@section('title', 'Manajemen User')

@section('content')
<div class="user-management-page">
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Manajemen User (Admin & Bendahara)</h1>
            </div>
            <div class="col-sm-6">
                <button class="btn btn-primary float-right" data-toggle="modal" data-target="#modalTambah">
                    <i class="fas fa-plus"></i> Tambah User
                </button>
            </div>
        </div>
    </div>
</div>

<section class="content">
    <div class="container-fluid">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Daftar User</h3>
                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse">
                        <i class="fas fa-minus"></i>
                    </button>
                </div>
            </div>
            <div class="card-body">
                <!-- Search and Filter Section -->
                <div class="row mb-3">
                    <div class="col-md-6">
                        <div class="input-group">
                            <input type="text" class="form-control" id="searchInput" placeholder="Cari berdasarkan nama, email, NIS, atau role...">
                            <div class="input-group-append">
                                <button class="btn btn-outline-primary" type="button" id="searchButton">
                                    <i class="fas fa-search"></i>
                                </button>
                                <button class="btn btn-outline-secondary" type="button" id="resetSearch">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="row">
                            <div class="col-6">
                                <select class="form-control" id="filterRole">
                                    <option value="">Semua Role</option>
                                    <option value="admin">Admin</option>
                                    <option value="bendahara">Bendahara</option>
                                    <option value="siswa">Siswa</option>
                                </select>
                            </div>
                            <div class="col-6">
                                <button class="btn btn-outline-info w-100" id="btnRefresh">
                                    <i class="fas fa-sync-alt"></i> Refresh
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Active Filters -->
                <div class="row mb-3" id="activeFilters" style="display: none;">
                    <div class="col-12">
                        <div class="d-flex flex-wrap align-items-center">
                            <small class="text-muted mr-2">Filter aktif:</small>
                            <div id="filterTags"></div>
                            <button class="btn btn-sm btn-outline-danger ml-2" id="clearAllFilters">
                                <i class="fas fa-times"></i> Hapus Semua Filter
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Table -->
                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-hover w-100" id="userTable">
                        <thead class="thead-light">
                            <tr>
                                <th width="5%">No</th>
                                <th width="20%">Nama</th>
                                <th width="20%">Email</th>
                                <th width="15%">NIS</th>
                                <th width="15%">Role</th>
                                <th width="15%">Tanggal Dibuat</th>
                                <th width="10%">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Data akan diisi oleh DataTables -->
                        </tbody>
                    </table>
                </div>
            </div>
            <!-- Pagination Container -->
            <div class="card-footer">
                <div class="row align-items-center">
                    <div class="col-sm-12 col-md-6" id="infoContainer">
                        <!-- Info akan diisi oleh JavaScript -->
                    </div>
                    <div class="col-sm-12 col-md-6">
                        <nav aria-label="Page navigation" id="paginationContainer">
                            <!-- Pagination akan diisi oleh JavaScript -->
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Modal Tambah -->
<div class="modal fade" id="modalTambah">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Tambah User</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <form id="formTambah">
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
                                <label>Email *</label>
                                <input type="email" name="email" class="form-control" required>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>NIS *</label>
                                <input type="text" name="nis" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Role *</label>
                                <select name="role" class="form-control" required id="roleSelect">
                                    <option value="">Pilih Role</option>
                                    <option value="admin">Admin</option>
                                    <option value="bendahara">Bendahara</option>
                                    <option value="siswa">Siswa</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Fields khusus untuk siswa -->
                    <div class="row" id="siswaFields" style="display: none;">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Kelas</label>
                                <select name="kelas" class="form-control">
                                    <option value="">Pilih Kelas</option>
                                    <option value="10">Kelas 10</option>
                                    <option value="11">Kelas 11</option>
                                    <option value="12">Kelas 12</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Jurusan</label>
                                <select name="jurusan" class="form-control">
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
                                <input type="password" name="password" class="form-control" required minlength="6">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Konfirmasi Password *</label>
                                <input type="password" name="password_confirmation" class="form-control" required minlength="6">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Edit -->
<div class="modal fade" id="modalEdit">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Edit User</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <form id="formEdit">
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
                                <label>Email *</label>
                                <input type="email" name="email" id="edit_email" class="form-control" required>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>NIS *</label>
                                <input type="text" name="nis" id="edit_nis" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Role *</label>
                                <select name="role" id="edit_role" class="form-control" required>
                                    <option value="admin">Admin</option>
                                    <option value="bendahara">Bendahara</option>
                                    <option value="siswa">Siswa</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Fields khusus untuk siswa -->
                    <div class="row" id="editSiswaFields" style="display: none;">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Kelas</label>
                                <select name="kelas" id="edit_kelas" class="form-control">
                                    <option value="">Pilih Kelas</option>
                                    <option value="10">Kelas 10</option>
                                    <option value="11">Kelas 11</option>
                                    <option value="12">Kelas 12</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Jurusan</label>
                                <select name="jurusan" id="edit_jurusan" class="form-control">
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
                                <label>Password (Kosongkan jika tidak ingin mengubah)</label>
                                <input type="password" name="password" class="form-control" minlength="6">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Konfirmasi Password</label>
                                <input type="password" name="password_confirmation" class="form-control" minlength="6">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>
</div>
@endsection

@section('styles')
<style>
    .user-management-page .table td,
    .user-management-page .table th {
        vertical-align: middle;
    }

    .table-responsive {
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
    }
    
    #userTable {
        width: 100% !important;
        table-layout: fixed;
    }
    
    #userTable th:nth-child(1) { width: 5%; }
    #userTable th:nth-child(2) { width: 20%; }
    #userTable th:nth-child(3) { width: 20%; }
    #userTable th:nth-child(4) { width: 15%; }
    #userTable th:nth-child(5) { width: 15%; }
    #userTable th:nth-child(6) { width: 15%; }
    #userTable th:nth-child(7) { width: 10%; }
    
    #userTable td {
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        vertical-align: middle;
    }
    
    #userTable td:last-child {
        white-space: normal;
        overflow: visible;
    }
    
    .custom-pagination .page-item.active .page-link {
        background-color: #007bff;
        border-color: #007bff;
    }
    
    .custom-pagination .page-link {
        color: #007bff;
        padding: 0.5rem 0.75rem;
    }
    
    .filter-tag {
        background-color: #e9ecef;
        border: 1px solid #dee2e6;
        border-radius: 15px;
        padding: 0.25rem 0.75rem;
        margin: 0.25rem;
        font-size: 0.875rem;
        display: inline-flex;
        align-items: center;
    }
    
    .filter-tag .close {
        margin-left: 0.5rem;
        font-size: 1rem;
    }
    
    .role-badge {
        font-size: 0.75rem;
        padding: 0.25rem 0.5rem;
    }
    
    @media (max-width: 768px) {
        .user-management-page .content-header h1 {
            font-size: 1.2rem;
        }

        .user-management-page .content-header .btn.float-right {
            float: none !important;
            width: 100%;
            margin-top: .65rem;
        }

        .user-management-page .card-body {
            padding: .85rem;
        }

        .user-management-page .input-group,
        .user-management-page .row.mb-3 .col-md-6 {
            margin-bottom: .5rem;
        }

        .user-management-page #activeFilters .d-flex {
            align-items: flex-start !important;
            flex-direction: column;
            gap: .4rem;
        }

        .user-management-page #clearAllFilters {
            margin-left: 0 !important;
        }

        #userTable th:nth-child(1) { width: 8%; }
        #userTable th:nth-child(2) { width: 22%; }
        #userTable th:nth-child(3) { width: 20%; }
        #userTable th:nth-child(4) { width: 15%; }
        #userTable th:nth-child(5) { width: 15%; }
        #userTable th:nth-child(6) { width: 10%; }
        #userTable th:nth-child(7) { width: 10%; }
        
        .card-footer .row {
            text-align: center;
        }
        
        #infoContainer {
            margin-bottom: 10px;
        }
        
        .btn-group-sm > .btn {
            padding: 0.25rem 0.4rem;
            font-size: 0.7rem;
        }

        .user-management-page .modal-footer {
            display: flex;
            flex-direction: column-reverse;
            gap: .5rem;
        }

        .user-management-page .modal-footer .btn {
            width: 100%;
            margin: 0 !important;
        }
    }
</style>
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

    // Variabel untuk menyimpan state filter
    var currentSearch = '';
    var currentRole = '';

    // Initialize DataTable - PERBAIKAN: Gunakan route yang benar
    var table = $('#userTable').DataTable({
        processing: true,
        serverSide: true,
        responsive: true,
        autoWidth: false,
        scrollX: true,
        searching: false,
        paging: true,
        pageLength: 10,
        lengthChange: false,
        info: false,
        dom: 'rt',
        language: {
            processing: '<i class="fas fa-spinner fa-spin fa-2x"></i> Memuat data...',
            emptyTable: 'Tidak ada data user',
            zeroRecords: 'Tidak ada data yang sesuai dengan pencarian'
        },
        ajax: {
            url: "{{ route('admin.user-management.index') }}", // PERBAIKAN: Gunakan route yang benar
            type: "GET",
            data: function (d) {
                // Tambahkan parameter custom untuk search dan filter
                d.global_search = currentSearch;
                d.role = currentRole;
            },
            error: function(xhr, error, thrown) {
                console.log('Error:', error);
                Swal.fire('Error', 'Gagal memuat data', 'error');
            }
        },
        columns: [
            { 
                data: 'DT_RowIndex', 
                name: 'DT_RowIndex', 
                orderable: false, 
                searchable: false,
                className: 'text-center'
            },
            { 
                data: 'name', 
                name: 'name',
                render: function(data, type, row) {
                    return '<strong>' + data + '</strong>';
                }
            },
            { 
                data: 'email', 
                name: 'email',
                render: function(data, type, row) {
                    return '<span class="text-primary">' + data + '</span>';
                }
            },
            { 
                data: 'nis', 
                name: 'nis',
                render: function(data, type, row) {
                    return data ? '<span class="text-monospace">' + data + '</span>' : '-';
                }
            },
            { 
                data: 'role_badge', 
                name: 'role',
                orderable: false, 
                searchable: false,
                className: 'text-center',
                render: function(data, type, row) {
                    return data;
                }
            },
            { 
                data: 'created_at', 
                name: 'created_at',
                render: function(data, type, row) {
                    return new Date(data).toLocaleDateString('id-ID', {
                        day: '2-digit',
                        month: '2-digit',
                        year: 'numeric'
                    });
                }
            },
            { 
                data: 'action', 
                name: 'action', 
                orderable: false, 
                searchable: false,
                className: 'text-center',
                render: function(data, type, row) {
                    return `
                        <div class="btn-group btn-group-sm" role="group">
                            <button class="btn btn-warning edit" data-id="${row.id}" title="Edit">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class="btn btn-danger delete" data-id="${row.id}" title="Hapus">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    `;
                }
            }
        ],
        initComplete: function() {
            updatePaginationAndInfo();
            updateActiveFilters();
        },
        drawCallback: function() {
            updatePaginationAndInfo();
            table.columns.adjust();
        }
    });

    // Toggle fields untuk siswa
    $('#roleSelect').change(function() {
        if ($(this).val() === 'siswa') {
            $('#siswaFields').show();
        } else {
            $('#siswaFields').hide();
        }
    });

    $('#edit_role').change(function() {
        if ($(this).val() === 'siswa') {
            $('#editSiswaFields').show();
        } else {
            $('#editSiswaFields').hide();
        }
    });

    // Fungsi untuk update pagination dan info
    function updatePaginationAndInfo() {
        var info = table.page.info();
        
        // Update info text
        var infoText = `Menampilkan ${info.start + 1} sampai ${info.end} dari ${info.recordsTotal} user`;
        if (info.recordsDisplay !== info.recordsTotal) {
            infoText = `Menampilkan ${info.start + 1} sampai ${info.end} dari ${info.recordsDisplay} user (disaring dari ${info.recordsTotal} total)`;
        }
        $('#infoContainer').html(infoText);
        
        // Build custom pagination
        var paginationHtml = '<ul class="pagination custom-pagination justify-content-end mb-0">';
        
        // Previous button
        if (table.page() > 0) {
            paginationHtml += `<li class="page-item">
                <a class="page-link" href="#" data-page="${table.page() - 1}">Sebelumnya</a>
            </li>`;
        } else {
            paginationHtml += `<li class="page-item disabled">
                <span class="page-link">Sebelumnya</span>
            </li>`;
        }
        
        // Page numbers
        var startPage = Math.max(0, table.page() - 2);
        var endPage = Math.min(info.pages, startPage + 5);
        
        for (var i = startPage; i < endPage; i++) {
            if (i === table.page()) {
                paginationHtml += `<li class="page-item active">
                    <span class="page-link">${i + 1}</span>
                </li>`;
            } else {
                paginationHtml += `<li class="page-item">
                    <a class="page-link" href="#" data-page="${i}">${i + 1}</a>
                </li>`;
            }
        }
        
        // Next button
        if (table.page() < info.pages - 1) {
            paginationHtml += `<li class="page-item">
                <a class="page-link" href="#" data-page="${table.page() + 1}">Berikutnya</a>
            </li>`;
        } else {
            paginationHtml += `<li class="page-item disabled">
                <span class="page-link">Berikutnya</span>
            </li>`;
        }
        
        paginationHtml += '</ul>';
        $('#paginationContainer').html(paginationHtml);
    }

    // Fungsi untuk update active filters display
    function updateActiveFilters() {
        var filterTags = '';
        var hasActiveFilter = false;

        if (currentSearch) {
            filterTags += `<span class="filter-tag">
                Pencarian: "${currentSearch}"
                <button type="button" class="close" data-filter="search">&times;</button>
            </span>`;
            hasActiveFilter = true;
        }

        if (currentRole) {
            var roleText = currentRole;
            switch(currentRole) {
                case 'admin': roleText = 'Admin'; break;
                case 'bendahara': roleText = 'Bendahara'; break;
                case 'siswa': roleText = 'Siswa'; break;
            }
            filterTags += `<span class="filter-tag">
                Role: ${roleText}
                <button type="button" class="close" data-filter="role">&times;</button>
            </span>`;
            hasActiveFilter = true;
        }

        $('#filterTags').html(filterTags);
        
        if (hasActiveFilter) {
            $('#activeFilters').show();
        } else {
            $('#activeFilters').hide();
        }
    }

    // Fungsi untuk apply search dan filter
    function applyFilters() {
        currentSearch = $('#searchInput').val().trim();
        currentRole = $('#filterRole').val();
        
        table.ajax.reload();
        updateActiveFilters();
    }

    // Event handler untuk custom pagination
    $(document).on('click', '.page-link', function(e) {
        e.preventDefault();
        if (!$(this).parent().hasClass('disabled') && !$(this).parent().hasClass('active')) {
            var page = $(this).data('page');
            table.page(page).draw('page');
        }
    });

    // Search functionality
    $('#searchButton').click(function() {
        applyFilters();
    });

    $('#searchInput').keypress(function(e) {
        if (e.which === 13) {
            applyFilters();
        }
    });

    $('#resetSearch').click(function() {
        $('#searchInput').val('');
        applyFilters();
    });

    // Filter functionality
    $('#filterRole').change(function() {
        applyFilters();
    });

    // Refresh button
    $('#btnRefresh').click(function() {
        table.ajax.reload();
        Swal.fire({
            icon: 'success',
            title: 'Data diperbarui',
            timer: 1000,
            showConfirmButton: false
        });
    });

    // Clear individual filter
    $(document).on('click', '.filter-tag .close', function() {
        var filterType = $(this).data('filter');
        
        switch(filterType) {
            case 'search':
                $('#searchInput').val('');
                break;
            case 'role':
                $('#filterRole').val('');
                break;
        }
        
        applyFilters();
    });

    // Clear all filters
    $('#clearAllFilters').click(function() {
        $('#searchInput').val('');
        $('#filterRole').val('');
        applyFilters();
    });

    // Form Tambah
    $('#formTambah').submit(function(e) {
        e.preventDefault();
        var formData = $(this).serialize();
        
        $.ajax({
            url: "{{ route('admin.user-management.store') }}", // PERBAIKAN: Gunakan route yang benar
            method: "POST",
            data: formData,
            beforeSend: function() {
                $('#modalTambah').find('button[type="submit"]').prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Menyimpan...');
            },
            success: function(response) {
                $('#modalTambah').modal('hide');
                table.ajax.reload(null, false);
                Swal.fire({
                    icon: 'success',
                    title: 'Sukses',
                    text: response.success,
                    timer: 2000,
                    showConfirmButton: false
                });
                $('#formTambah')[0].reset();
                $('#siswaFields').hide();
            },
            error: function(xhr) {
                var errorMessage = 'Terjadi kesalahan';
                if (xhr.responseJSON && xhr.responseJSON.errors) {
                    errorMessage = '';
                    $.each(xhr.responseJSON.errors, function(key, value) {
                        errorMessage += value + '<br>';
                    });
                }
                Swal.fire('Error', errorMessage, 'error');
            },
            complete: function() {
                $('#modalTambah').find('button[type="submit"]').prop('disabled', false).html('Simpan');
            }
        });
    });

    // Edit
    $(document).on('click', '.edit', function() {
        var id = $(this).data('id');
        $.ajax({
            url: "{{ url('admin/user-management') }}/" + id + "/edit", // PERBAIKAN: Gunakan route yang benar
            method: "GET",
            success: function(response) {
                $('#edit_id').val(response.id);
                $('#edit_name').val(response.name);
                $('#edit_email').val(response.email);
                $('#edit_nis').val(response.nis);
                $('#edit_role').val(response.role);
                
                // Tampilkan fields untuk siswa jika role siswa
                if (response.role === 'siswa') {
                    $('#editSiswaFields').show();
                    $('#edit_kelas').val(response.kelas);
                    $('#edit_jurusan').val(response.jurusan);
                } else {
                    $('#editSiswaFields').hide();
                }
                
                $('#modalEdit').modal('show');
            },
            error: function(xhr) {
                Swal.fire('Error', 'Gagal memuat data user', 'error');
            }
        });
    });

    // Update
    $('#formEdit').submit(function(e) {
        e.preventDefault();
        var id = $('#edit_id').val();
        
        $.ajax({
            url: "{{ url('admin/user-management') }}/" + id, // PERBAIKAN: Gunakan route yang benar
            method: "POST",
            data: $(this).serialize() + '&_method=PUT',
            beforeSend: function() {
                $('#modalEdit').find('button[type="submit"]').prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Mengupdate...');
            },
            success: function(response) {
                $('#modalEdit').modal('hide');
                table.ajax.reload(null, false);
                Swal.fire({
                    icon: 'success',
                    title: 'Sukses',
                    text: response.success,
                    timer: 2000,
                    showConfirmButton: false
                });
            },
            error: function(xhr) {
                var errorMessage = 'Terjadi kesalahan';
                if (xhr.responseJSON && xhr.responseJSON.errors) {
                    errorMessage = '';
                    $.each(xhr.responseJSON.errors, function(key, value) {
                        errorMessage += value + '<br>';
                    });
                }
                Swal.fire('Error', errorMessage, 'error');
            },
            complete: function() {
                $('#modalEdit').find('button[type="submit"]').prop('disabled', false).html('Update');
            }
        });
    });

    // Delete
    $(document).on('click', '.delete', function() {
        var id = $(this).data('id');
        
        Swal.fire({
            title: 'Hapus User?',
            text: "Data user akan dihapus permanen!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: "{{ url('admin/user-management') }}/" + id, // PERBAIKAN: Gunakan route yang benar
                    method: "DELETE",
                    success: function(response) {
                        table.ajax.reload(null, false);
                        Swal.fire('Sukses', response.success, 'success');
                    },
                    error: function(xhr) {
                        if (xhr.status === 422) {
                            Swal.fire('Error', xhr.responseJSON.error, 'error');
                        } else {
                            Swal.fire('Error', 'Terjadi kesalahan', 'error');
                        }
                    }
                });
            }
        });
    });

    // Reset modal ketika ditutup
    $('#modalTambah').on('hidden.bs.modal', function() {
        $('#formTambah')[0].reset();
        $('#siswaFields').hide();
    });

    $('#modalEdit').on('hidden.bs.modal', function() {
        $('#formEdit')[0].reset();
        $('#editSiswaFields').hide();
    });
});
</script>
@endsection