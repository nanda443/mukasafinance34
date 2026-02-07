@extends('layouts.app')

@section('title', 'Edit Penagihan - ' . $penagihan->judul)

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Edit Penagihan</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('bendahara.penagihan.index') }}">Penagihan</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('bendahara.penagihan.show', $penagihan->id) }}">Detail</a></li>
                    <li class="breadcrumb-item active">Edit</li>
                </ol>
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

        <div class="row">
            <div class="col-md-8">
                <div class="card card-primary">
                    <div class="card-header">
                        <h3 class="card-title">Form Edit Penagihan</h3>
                    </div>
                    <form action="{{ route('bendahara.penagihan.update', $penagihan->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Judul Penagihan *</label>
                                        <input type="text" name="judul" class="form-control @error('judul') is-invalid @enderror" 
                                               value="{{ old('judul', $penagihan->judul) }}" required placeholder="Contoh: SPP Bulan Januari">
                                        @error('judul')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Jenis *</label>
                                        <select name="jenis" class="form-control @error('jenis') is-invalid @enderror" required>
                                            <option value="">Pilih Jenis</option>
                                            <option value="bulanan" {{ old('jenis', $penagihan->jenis) == 'bulanan' ? 'selected' : '' }}>Bulanan</option>
                                            <option value="tahunan" {{ old('jenis', $penagihan->jenis) == 'tahunan' ? 'selected' : '' }}>Tahunan</option>
                                            <option value="bebas" {{ old('jenis', $penagihan->jenis) == 'bebas' ? 'selected' : '' }}>Bebas</option>
                                        </select>
                                        @error('jenis')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label>Deskripsi</label>
                                <textarea name="deskripsi" class="form-control @error('deskripsi') is-invalid @enderror" 
                                          rows="3" placeholder="Deskripsi penagihan...">{{ old('deskripsi', $penagihan->deskripsi) }}</textarea>
                                @error('deskripsi')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Nominal *</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">Rp</span>
                                            </div>
                                            <input type="number" name="nominal" class="form-control @error('nominal') is-invalid @enderror" 
                                                   value="{{ old('nominal', $penagihan->nominal) }}" min="0" required placeholder="50000">
                                        </div>
                                        @error('nominal')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Tenggat Waktu *</label>
                                        <input type="date" name="tenggat_waktu" class="form-control @error('tenggat_waktu') is-invalid @enderror" 
                                               value="{{ old('tenggat_waktu', \Carbon\Carbon::parse($penagihan->tenggat_waktu)->format('Y-m-d')) }}" required 
                                               min="{{ date('Y-m-d') }}">
                                        @error('tenggat_waktu')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Target *</label>
                                        <select name="target" class="form-control @error('target') is-invalid @enderror" id="targetSelect" required>
                                            <option value="">Pilih Target</option>
                                            <option value="massal" {{ old('target', $penagihan->target) == 'massal' ? 'selected' : '' }}>Massal</option>
                                            <option value="individu" {{ old('target', $penagihan->target) == 'individu' ? 'selected' : '' }}>Individu</option>
                                        </select>
                                        @error('target')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Status</label>
                                        <select name="status" class="form-control @error('status') is-invalid @enderror">
                                            <option value="1" {{ old('status', $penagihan->status) == 1 ? 'selected' : '' }}>Aktif</option>
                                            <option value="0" {{ old('status', $penagihan->status) == 0 ? 'selected' : '' }}>Non-Aktif</option>
                                        </select>
                                        @error('status')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <!-- Filter untuk Massal -->
                            <div id="massalFilter" style="display: {{ old('target', $penagihan->target) == 'massal' ? 'block' : 'none' }};">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Kelas</label>
                                            <select name="kelas" class="form-control @error('kelas') is-invalid @enderror">
                                                <option value="">Semua Kelas</option>
                                                @foreach($kelas as $k)
                                                    <option value="{{ $k }}" {{ old('kelas', $penagihan->kelas) == $k ? 'selected' : '' }}>Kelas {{ $k }}</option>
                                                @endforeach
                                            </select>
                                            @error('kelas')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Jurusan</label>
                                            <select name="jurusan" class="form-control @error('jurusan') is-invalid @enderror">
                                                <option value="">Semua Jurusan</option>
                                                @foreach($jurusan as $j)
                                                    <option value="{{ $j }}" {{ old('jurusan', $penagihan->jurusan) == $j ? 'selected' : '' }}>{{ $j }}</option>
                                                @endforeach
                                            </select>
                                            @error('jurusan')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Filter untuk Individu -->
                            <div id="individuFilter" style="display: {{ old('target', $penagihan->target) == 'individu' ? 'block' : 'none' }};">
                                <div class="form-group">
                                    <label>Pilih Siswa *</label>
                                    <select name="target_siswa[]" class="form-control select2 @error('target_siswa') is-invalid @enderror" multiple style="width: 100%;">
                                        @foreach($siswa as $s)
                                            <option value="{{ $s->id }}" 
                                                {{ in_array($s->id, old('target_siswa', $target_siswa ?? [])) ? 'selected' : '' }}>
                                                {{ $s->name }} - {{ $s->nis }} (Kelas {{ $s->kelas }} {{ $s->jurusan }})
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('target_siswa')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="card-footer">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Update Penagihan
                            </button>
                            <a href="{{ route('bendahara.penagihan.show', $penagihan->id) }}" class="btn btn-secondary">
                                <i class="fas fa-times"></i> Batal
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            <div class="col-md-4">
                <!-- Informasi Penagihan -->
                <div class="card card-info">
                    <div class="card-header">
                        <h3 class="card-title">Informasi Saat Ini</h3>
                    </div>
                    <div class="card-body">
                        <table class="table table-borderless">
                            <tr>
                                <td><strong>Judul:</strong></td>
                                <td>{{ $penagihan->judul }}</td>
                            </tr>
                            <tr>
                                <td><strong>Jenis:</strong></td>
                                <td><span class="badge badge-primary">{{ strtoupper($penagihan->jenis) }}</span></td>
                            </tr>
                            <tr>
                                <td><strong>Target:</strong></td>
                                <td><span class="badge badge-info">{{ strtoupper($penagihan->target) }}</span></td>
                            </tr>
                            <tr>
                                <td><strong>Nominal:</strong></td>
                                <td class="text-success">Rp {{ number_format($penagihan->nominal, 0, ',', '.') }}</td>
                            </tr>
                            <tr>
                                <td><strong>Tenggat:</strong></td>
                                <td>{{ \Carbon\Carbon::parse($penagihan->tenggat_waktu)->format('d/m/Y') }}</td>
                            </tr>
                            <tr>
                                <td><strong>Status:</strong></td>
                                <td>
                                    <span class="badge {{ $penagihan->status ? 'badge-success' : 'badge-secondary' }}">
                                        {{ $penagihan->status ? 'AKTIF' : 'NON-AKTIF' }}
                                    </span>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="card">
                    <div class="card-body text-center">
                        <a href="{{ route('bendahara.penagihan.show', $penagihan->id) }}" class="btn btn-info btn-block mb-2">
                            <i class="fas fa-eye"></i> Lihat Detail
                        </a>
                        <a href="{{ route('bendahara.penagihan.index') }}" class="btn btn-secondary btn-block">
                            <i class="fas fa-arrow-left"></i> Kembali ke Daftar
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@section('styles')
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />
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
</style>
@endsection

@section('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
<script>
$(document).ready(function() {
    // Initialize Select2
    $('.select2').select2({
        placeholder: "Pilih siswa...",
        allowClear: true,
        width: '100%'
    });

    // Toggle filter based on target selection
    $('#targetSelect').change(function() {
        var target = $(this).val();
        $('#massalFilter, #individuFilter').hide();
        
        if (target === 'individu') {
            $('#individuFilter').show();
        } else if (target === 'massal') {
            $('#massalFilter').show();
        }
    });

    // Set minimum date untuk input tanggal
    var today = new Date().toISOString().split('T')[0];
    $('input[type="date"]').attr('min', today);
});
</script>
@endsection