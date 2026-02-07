@extends('layouts.app')

@section('title', 'Manajemen Penagihan')

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Manajemen Penagihan</h1>
            </div>
            <div class="col-sm-6">
                <button type="button" class="btn btn-success float-right" data-toggle="modal" data-target="#createPenagihanModal">
                    <i class="fas fa-plus"></i> Buat Penagihan Baru
                </button>
            </div>
        </div>
    </div>
</div>

<section class="content">
    <div class="container-fluid">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                <i class="icon fas fa-check"></i> {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                <i class="icon fas fa-ban"></i> {{ session('error') }}
            </div>
        @endif

        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Daftar Penagihan</h3>
                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse">
                        <i class="fas fa-minus"></i>
                    </button>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-hover w-100" id="penagihanTable">
                        <thead class="thead-light">
                            <tr>
                                <th width="5%">No</th>
                                <th width="20%">Judul</th>
                                <th width="10%">Jenis</th>
                                <th width="15%">Target</th>
                                <th width="15%">Nominal</th>
                                <th width="15%">Tenggat Waktu</th>
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

<!-- Modal Create -->
<div class="modal fade" id="createPenagihanModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Buat Penagihan Baru</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <form id="createPenagihanForm" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Judul Penagihan <span class="text-danger">*</span></label>
                                <input type="text" name="judul" class="form-control" required placeholder="Contoh: SPP Bulan Januari">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Jenis <span class="text-danger">*</span></label>
                                <select name="jenis" class="form-control" required>
                                    <option value="">Pilih Jenis</option>
                                    <option value="bulanan">Bulanan</option>
                                    <option value="tahunan">Tahunan</option>
                                    <option value="bebas">Bebas</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Deskripsi</label>
                        <textarea name="deskripsi" class="form-control" rows="3" placeholder="Deskripsi penagihan..."></textarea>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Nominal <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">Rp</span>
                                    </div>
                                    <input type="number" name="nominal" class="form-control" min="1000" required placeholder="50000">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Tenggat Waktu <span class="text-danger">*</span></label>
                                <input type="date" name="tenggat_waktu" class="form-control" required>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Target <span class="text-danger">*</span></label>
                                <select name="target" class="form-control targetSelect" required>
                                    <option value="">Pilih Target</option>
                                    <option value="massal">Massal</option>
                                    <option value="individu">Individu</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Status</label>
                                <select name="status" class="form-control">
                                    <option value="1">Aktif</option>
                                    <option value="0">Non-Aktif</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Filter untuk Massal -->
                    <div id="massalFilter" style="display: none;">
                        <hr>
                        <h6>Filter Penagihan Massal</h6>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Kelas <span class="text-danger">*</span></label>
                                    <select name="kelas" class="form-control kelasSelect">
                                        <option value="">Pilih Kelas</option>
                                        @foreach($kelas as $k)
                                            <option value="{{ $k }}">Kelas {{ $k }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Jurusan <span class="text-danger">*</span></label>
                                    <select name="jurusan" class="form-control jurusanSelect">
                                        <option value="">Pilih Jurusan</option>
                                        @foreach($jurusan as $j)
                                            <option value="{{ $j }}">{{ $j }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Filter untuk Individu -->
                    <div id="individuFilter" style="display: none;">
                        <hr>
                        <h6>Pilih Siswa untuk Penagihan Individu</h6>
                        <div class="form-group">
                            <label>Pilih Siswa <span class="text-danger">*</span></label>
                            <select name="target_siswa[]" class="form-control siswaSelect" multiple style="width: 100%;">
                                @foreach($siswa as $s)
                                    <option value="{{ $s->id }}">{{ $s->name }} - {{ $s->nis }} (Kelas {{ $s->kelas }} {{ $s->jurusan }})</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary submitBtn" id="submitPenagihan">
                        <i class="fas fa-save"></i> Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('styles')
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />
<link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet" />
<style>
    .select2-container--default .select2-selection--multiple {
        border: 1px solid #ced4da;
        border-radius: 0.25rem;
        min-height: 38px;
    }
    .select2-container--default .select2-selection--multiple .select2-selection__choice {
        background-color: #007bff;
        border-color: #007bff;
        color: white;
    }
    .badge {
        font-size: 0.85em;
    }
    .table th {
        background-color: #f8f9fa;
        border-bottom: 2px solid #dee2e6;
    }
</style>
@endsection

@section('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
$(document).ready(function() {
    console.log('Document ready - Penagihan page loaded');

    // Set minimum date untuk input tanggal hari ini
    var today = new Date().toISOString().split('T')[0];
    $('input[name="tenggat_waktu"]').attr('min', today);

    // Initialize Select2
    $('.siswaSelect').select2({
        placeholder: "Pilih siswa...",
        allowClear: true,
        width: '100%'
    });

    // Setup CSRF token untuk AJAX
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    // Toggle filter berdasarkan target selection
    $(document).on('change', '.targetSelect', function() {
        console.log('Target changed to:', $(this).val());
        var target = $(this).val();
        
        $('#massalFilter, #individuFilter').hide();
        
        if (target === 'massal') {
            console.log('Showing massal filter');
            $('#massalFilter').show();
        } else if (target === 'individu') {
            console.log('Showing individu filter');
            $('#individuFilter').show();
        }
    });

    // Initialize DataTable
    var table = $('#penagihanTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: "{{ route('bendahara.penagihan.index') }}",
            type: "GET"
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
                data: 'judul', 
                name: 'judul'
            },
            { 
                data: 'jenis', 
                name: 'jenis',
                render: function(data) {
                    var badgeColor = {
                        'bulanan': 'primary',
                        'tahunan': 'success', 
                        'bebas': 'info'
                    };
                    return '<span class="badge badge-' + (badgeColor[data] || 'secondary') + '">' + data.toUpperCase() + '</span>';
                }
            },
            { 
                data: 'target_detail', 
                name: 'target_detail',
                orderable: false,
                searchable: false
            },
            { 
                data: 'nominal_formatted', 
                name: 'nominal',
                orderable: false,
                searchable: false
            },
            { 
                data: 'tenggat_formatted', 
                name: 'tenggat_waktu'
            },
            { 
                data: 'status_badge', 
                name: 'status',
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
            emptyTable: "Tidak ada data penagihan",
            info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
            infoEmpty: "Menampilkan 0 sampai 0 dari 0 data",
            infoFiltered: "(disaring dari _MAX_ total data)",
            lengthMenu: "Tampilkan _MENU_ data",
            loadingRecords: "Memuat...",
            processing: "Memproses...",
            search: "Cari:",
            zeroRecords: "Tidak ditemukan data yang sesuai",
            paginate: {
                first: "Pertama",
                last: "Terakhir",
                next: "Selanjutnya",
                previous: "Sebelumnya"
            }
        },
        order: [[1, 'asc']],
        pageLength: 10,
        lengthMenu: [[10, 25, 50, 100], [10, 25, 50, 100]],
        dom: '<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>rtip'
    });

    // Submit form penagihan
    $(document).on('submit', '#createPenagihanForm', function(e) {
        e.preventDefault();
        console.log('Form submitted!');
        
        var target = $('select[name="target"]').val();
        var kelas = $('select[name="kelas"]').val();
        var jurusan = $('select[name="jurusan"]').val();
        var siswaSelected = $('select[name="target_siswa[]"]').val();
        
        console.log('Form values:', {
            target: target,
            kelas: kelas,
            jurusan: jurusan,
            siswaSelected: siswaSelected
        });
        
        var isValid = true;
        var errorMessage = '';

        // Validasi manual
        if (!target) {
            isValid = false;
            errorMessage = 'Pilih target penagihan (Massal atau Individu)';
        } else if (target === 'massal') {
            if (!kelas || kelas === '') {
                isValid = false;
                errorMessage = 'Pilih kelas untuk penagihan massal';
            } else if (!jurusan || jurusan === '') {
                isValid = false;
                errorMessage = 'Pilih jurusan untuk penagihan massal';
            }
        } else if (target === 'individu') {
            if (!siswaSelected || siswaSelected.length === 0) {
                isValid = false;
                errorMessage = 'Pilih minimal satu siswa untuk penagihan individu';
            }
        }

        if (!isValid) {
            console.log('Validation error:', errorMessage);
            toastr.error(errorMessage);
            return false;
        }

        // Submit form
        var formData = $(this).serialize();
        console.log('Submitting form with data:', formData);
        
        var submitBtn = $(this).find('button[type="submit"]');
        submitBtn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Menyimpan...');

        $.ajax({
            url: "{{ route('bendahara.penagihan.store') }}",
            method: 'POST',
            data: formData,
            success: function(response) {
                console.log('Success:', response);
                if (response.success) {
                    $('#createPenagihanModal').modal('hide');
                    table.ajax.reload(null, false);
                    toastr.success(response.message || 'Penagihan berhasil dibuat!');
                    
                    // Reset form
                    $('#createPenagihanForm')[0].reset();
                    $('.siswaSelect').val(null).trigger('change');
                    $('#massalFilter, #individuFilter').hide();
                } else {
                    toastr.error(response.message || 'Gagal membuat penagihan');
                }
            },
            error: function(xhr, status, error) {
                console.log('Error response:', xhr);
                
                var errorMessage = 'Terjadi kesalahan saat membuat penagihan';
                
                if (xhr.status === 422 && xhr.responseJSON && xhr.responseJSON.errors) {
                    var errors = xhr.responseJSON.errors;
                    errorMessage = '';
                    for (var key in errors) {
                        if (errors.hasOwnProperty(key)) {
                            errorMessage += errors[key][0] + '\n';
                        }
                    }
                } else if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMessage = xhr.responseJSON.message;
                }
                
                console.log('Final error message:', errorMessage);
                toastr.error(errorMessage);
            },
            complete: function() {
                submitBtn.prop('disabled', false).html('<i class="fas fa-save"></i> Simpan');
            }
        });
    });

    // Delete penagihan
    $(document).on('click', '.delete-penagihan', function() {
        var id = $(this).data('id');
        var judul = $(this).closest('tr').find('td:eq(1)').text();
        
        Swal.fire({
            title: 'Hapus Penagihan?',
            html: `Anda akan menghapus penagihan: <strong>"${judul}"</strong><br><br>
                  <span class="text-danger"><i class="fas fa-exclamation-triangle"></i> Tindakan ini tidak dapat dibatalkan!</span>`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: '<i class="fas fa-trash"></i> Ya, Hapus!',
            cancelButtonText: '<i class="fas fa-times"></i> Batal',
            reverseButtons: true,
            width: '500px'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: "{{ url('bendahara/penagihan') }}/" + id,
                    method: 'DELETE',
                    beforeSend: function() {
                        Swal.fire({
                            title: 'Menghapus...',
                            text: 'Sedang menghapus penagihan',
                            allowOutsideClick: false,
                            didOpen: () => {
                                Swal.showLoading()
                            }
                        });
                    },
                    success: function(response) {
                        if (response.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Terhapus!',
                                text: response.message,
                                timer: 2000,
                                showConfirmButton: false
                            });
                            table.ajax.reload(null, false);
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: response.message || 'Gagal menghapus penagihan'
                            });
                        }
                    },
                    error: function(xhr) {
                        var errorMessage = 'Terjadi kesalahan saat menghapus penagihan';
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            errorMessage = xhr.responseJSON.message;
                        }
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: errorMessage
                        });
                    }
                });
            }
        });
    });

    // Reset form ketika modal ditutup
    $('#createPenagihanModal').on('hidden.bs.modal', function() {
        $('#createPenagihanForm')[0].reset();
        $('.siswaSelect').val(null).trigger('change');
        $('#massalFilter, #individuFilter').hide();
    });
});
</script>
@endsection