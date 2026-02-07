{{-- resources/views/laporan/index.blade.php --}}
@extends('layouts.app')

@section('title', 'Laporan Keuangan')

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Laporan Keuangan</h1>
            </div>
        </div>
    </div>
</div>

<section class="content">
    <div class="container-fluid">
        <!-- Filter -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Filter Laporan</h3>
            </div>
            <div class="card-body">
                <!-- PERBAIKAN: Ganti route dengan dynamic role -->
                <form method="GET" action="{{ route(Auth::user()->role . '.laporan.index') }}" id="filterForm">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Tanggal Awal</label>
                                <input type="date" name="tanggal_awal" class="form-control" value="{{ request('tanggal_awal') }}">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Tanggal Akhir</label>
                                <input type="date" name="tanggal_akhir" class="form-control" value="{{ request('tanggal_akhir') }}">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>Kelas</label>
                                <select name="kelas" class="form-control">
                                    <option value="">Semua Kelas</option>
                                    @foreach($kelas as $k)
                                    <option value="{{ $k }}" {{ request('kelas') == $k ? 'selected' : '' }}>Kelas {{ $k }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>Jurusan</label>
                                <select name="jurusan" class="form-control">
                                    <option value="">Semua Jurusan</option>
                                    @foreach($jurusan as $j)
                                    <option value="{{ $j }}" {{ request('jurusan') == $j ? 'selected' : '' }}>{{ $j }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>Jenis Pembayaran</label>
                                <select name="jenis_pembayaran" class="form-control">
                                    <option value="">Semua Jenis</option>
                                    @foreach($jenisPembayaran as $jp)
                                    <option value="{{ $jp->id }}" {{ request('jenis_pembayaran') == $jp->id ? 'selected' : '' }}>{{ $jp->nama }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-filter"></i> Terapkan Filter
                            </button>
                            <!-- PERBAIKAN: Ganti route reset -->
                            <a href="{{ route(Auth::user()->role . '.laporan.index') }}" class="btn btn-secondary">
                                <i class="fas fa-refresh"></i> Reset
                            </a>
                            
                            @if($laporan->count() > 0)
                            <div class="btn-group float-right">
                                <button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown">
                                    <i class="fas fa-download"></i> Export Laporan
                                </button>
                                <div class="dropdown-menu">
                                    <!-- PERBAIKAN: Gunakan form untuk export -->
                                    <a href="{{ route(Auth::user()->role . '.laporan.pdf', request()->query()) }}" class="dropdown-item" target="_blank">
                                        <i class="fas fa-file-pdf text-danger"></i> Export PDF
                                    </a>
                                    <a href="{{ route(Auth::user()->role . '.laporan.excel', request()->query()) }}" class="dropdown-item" target="_blank">
                                        <i class="fas fa-file-excel text-success"></i> Export Excel
                                    </a>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Summary -->
        <div class="row">
            <div class="col-md-12">
                <div class="info-box bg-success">
                    <span class="info-box-icon"><i class="fas fa-money-bill-wave"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Total Pemasukan</span>
                        <span class="info-box-number">Rp {{ number_format($total, 0, ',', '.') }}</span>
                        <span class="progress-description">
                            {{ $laporan->count() }} transaksi ditemukan
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tabel Laporan -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Data Laporan</h3>
                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse">
                        <i class="fas fa-minus"></i>
                    </button>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-hover">
                        <thead class="thead-light">
                            <tr>
                                <th width="5%">No</th>
                                <th width="15%">Nama Siswa</th>
                                <th width="10%">NIS</th>
                                <th width="8%">Kelas</th>
                                <th width="8%">Jurusan</th>
                                <th width="15%">Jenis Pembayaran</th>
                                <th width="12%">Nominal</th>
                                <th width="10%">Tanggal Bayar</th>
                                <th width="8%">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($laporan as $key => $item)
                            <tr>
                                <td>{{ $key + 1 }}</td>
                                <td>
                                    {{ $item->user->name ?? 'N/A' }}
                                    @if(!$item->user)
                                        <small class="text-danger">(User tidak ditemukan)</small>
                                    @endif
                                </td>
                                <td>{{ $item->user->nis ?? 'N/A' }}</td>
                                <td>{{ $item->user->kelas ?? 'N/A' }}</td>
                                <td>{{ $item->user->jurusan ?? 'N/A' }}</td>
                                <td>
                                    {{ $item->jenisPembayaran->nama ?? 'N/A' }}
                                    @if(!$item->jenisPembayaran)
                                        <small class="text-danger">(Jenis pembayaran tidak ditemukan)</small>
                                    @endif
                                </td>
                                <td class="text-success font-weight-bold">
                                    Rp {{ number_format($item->jenisPembayaran->nominal ?? 0, 0, ',', '.') }}
                                </td>
                                <td>
                                    @if($item->tanggal_bayar)
                                        {{ $item->tanggal_bayar->format('d/m/Y') }}
                                    @else
                                        {{ $item->created_at->format('d/m/Y') }}
                                    @endif
                                </td>
                                <td>
                                    <span class="badge badge-success">DISETUJUI</span>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="9" class="text-center text-muted py-4">
                                    <i class="fas fa-inbox fa-2x mb-2"></i><br>
                                    Tidak ada data laporan untuk filter yang dipilih.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($laporan->count() > 0)
                <div class="mt-3">
                    <small class="text-muted">
                        Menampilkan {{ $laporan->count() }} data transaksi
                    </small>
                </div>
                @endif
            </div>
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Validasi tanggal
    $('#filterForm').submit(function() {
        const tanggalAwal = $('input[name="tanggal_awal"]').val();
        const tanggalAkhir = $('input[name="tanggal_akhir"]').val();
        
        if (tanggalAwal && tanggalAkhir) {
            if (new Date(tanggalAwal) > new Date(tanggalAkhir)) {
                alert('Tanggal awal tidak boleh lebih besar dari tanggal akhir');
                return false;
            }
        }
        return true;
    });

    // Copy filter parameters to export forms
    function updateExportForms() {
        const formData = new FormData(document.getElementById('filterForm'));
        
        // Update PDF form
        $('#exportPdfForm input[type="hidden"]').each(function() {
            const name = $(this).attr('name');
            if (formData.has(name)) {
                $(this).val(formData.get(name));
            }
        });
        
        // Update Excel form
        $('#exportExcelForm input[type="hidden"]').each(function() {
            const name = $(this).attr('name');
            if (formData.has(name)) {
                $(this).val(formData.get(name));
            }
        });
    }

    // Update export forms when filter changes
    $('#filterForm input, #filterForm select').on('change', function() {
        updateExportForms();
    });

    // Initialize export forms
    updateExportForms();
});
</script>
@endpush