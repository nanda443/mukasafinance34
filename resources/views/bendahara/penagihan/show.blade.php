@extends('layouts.app')

@section('title', 'Detail Penagihan - ' . $penagihan->judul)

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Detail Penagihan</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('bendahara.penagihan.index') }}">Penagihan</a></li>
                    <li class="breadcrumb-item active">Detail</li>
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
                        <h3 class="card-title">Informasi Penagihan</h3>
                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                <i class="fas fa-minus"></i>
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <table class="table table-borderless">
                                    <tr>
                                        <td width="40%"><strong>Judul</strong></td>
                                        <td>{{ $penagihan->judul }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Jenis</strong></td>
                                        <td>
                                            <span class="badge badge-primary">{{ strtoupper($penagihan->jenis) }}</span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><strong>Target</strong></td>
                                        <td>
                                            <span class="badge badge-info">{{ strtoupper($penagihan->target) }}</span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><strong>Nominal</strong></td>
                                        <td class="font-weight-bold text-success">Rp {{ number_format($penagihan->nominal, 0, ',', '.') }}</td>
                                    </tr>
                                </table>
                            </div>
                            <div class="col-md-6">
                                <table class="table table-borderless">
                                    <tr>
                                        <td width="40%"><strong>Tenggat Waktu</strong></td>
                                        <td>
                                            <span class="font-weight-bold {{ \Carbon\Carbon::parse($penagihan->tenggat_waktu)->lt(now()) ? 'text-danger' : 'text-primary' }}">
                                                {{ \Carbon\Carbon::parse($penagihan->tenggat_waktu)->format('d/m/Y') }}
                                                @if(\Carbon\Carbon::parse($penagihan->tenggat_waktu)->lt(now()))
                                                    <small class="text-danger">(LEWAT)</small>
                                                @endif
                                            </span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><strong>Status</strong></td>
                                        <td>
                                            <span class="badge {{ $penagihan->status ? 'badge-success' : 'badge-secondary' }}">
                                                {{ $penagihan->status ? 'AKTIF' : 'NON-AKTIF' }}
                                            </span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><strong>Jumlah Siswa</strong></td>
                                        <td>
                                            <span class="badge badge-primary">{{ $jumlah_siswa }} siswa</span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><strong>Dibuat</strong></td>
                                        <td>{{ $penagihan->created_at->format('d/m/Y H:i') }}</td>
                                    </tr>
                                </table>
                            </div>
                        </div>

                        @if($penagihan->deskripsi)
                        <div class="row mt-3">
                            <div class="col-12">
                                <strong>Deskripsi:</strong>
                                <div class="mt-1 p-3 bg-light rounded">
                                    {{ $penagihan->deskripsi }}
                                </div>
                            </div>
                        </div>
                        @endif

                        @if($penagihan->kelas || $penagihan->jurusan)
                        <div class="row mt-3">
                            <div class="col-12">
                                <strong>Filter Kelas/Jurusan:</strong>
                                <div class="mt-1">
                                    {{ $penagihan->kelas ? 'Kelas ' . $penagihan->kelas : '' }}
                                    {{ $penagihan->jurusan ? ' - ' . $penagihan->jurusan : '' }}
                                    {{ !$penagihan->kelas && !$penagihan->jurusan ? 'Semua Siswa' : '' }}
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Daftar Siswa -->
                <div class="card card-info">
                    <div class="card-header">
                        <h3 class="card-title">Daftar Siswa Target</h3>
                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                <i class="fas fa-minus"></i>
                            </button>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        @if($siswa_list->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-hover table-striped">
                                    <thead>
                                        <tr>
                                            <th width="5%">No</th>
                                            <th width="25%">Nama</th>
                                            <th width="15%">NIS</th>
                                            <th width="15%">Kelas</th>
                                            <th width="15%">Jurusan</th>
                                            <th width="25%">Status Pembayaran</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($siswa_list as $index => $siswa)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td>{{ $siswa->nama }}</td>
                                            <td>{{ $siswa->nis }}</td>
                                            <td>Kelas {{ $siswa->kelas }}</td>
                                            <td>{{ $siswa->jurusan }}</td>
                                            <td>
                                                @php
                                                    $pembayaran = \App\Models\Pembayaran::where('user_id', $siswa->id)
                                                        ->whereHas('jenisPembayaran', function($query) use ($penagihan) {
                                                            $query->where('nama', $penagihan->judul)
                                                                  ->where('nominal', $penagihan->nominal);
                                                        })
                                                        ->first();
                                                @endphp
                                                @if($pembayaran)
                                                    @if($pembayaran->status == 'approved')
                                                        <span class="badge badge-success">LUNAS</span>
                                                    @elseif($pembayaran->status == 'pending')
                                                        <span class="badge badge-warning">MENUNGGU</span>
                                                    @else
                                                        <span class="badge badge-danger">DITOLAK</span>
                                                    @endif
                                                @else
                                                    <span class="badge badge-secondary">BELUM BAYAR</span>
                                                @endif
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="text-center py-4">
                                <i class="fas fa-users fa-3x text-muted mb-3"></i>
                                <p class="text-muted">Tidak ada data siswa</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <!-- Statistik -->
                <div class="card card-success">
                    <div class="card-header">
                        <h3 class="card-title">Statistik Pembayaran</h3>
                    </div>
                    <div class="card-body">
                        @php
                            $lunas = 0;
                            $pending = 0;
                            $ditolak = 0;
                            $belum_bayar = 0;

                            foreach($siswa_list as $siswa) {
                                $pembayaran = \App\Models\Pembayaran::where('user_id', $siswa->id)
                                    ->whereHas('jenisPembayaran', function($query) use ($penagihan) {
                                        $query->where('nama', $penagihan->judul)
                                              ->where('nominal', $penagihan->nominal);
                                    })
                                    ->first();
                                
                                if($pembayaran) {
                                    if($pembayaran->status == 'approved') $lunas++;
                                    elseif($pembayaran->status == 'pending') $pending++;
                                    elseif($pembayaran->status == 'rejected') $ditolak++;
                                } else {
                                    $belum_bayar++;
                                }
                            }
                        @endphp
                        
                        <div class="text-center mb-3">
                            <div class="progress" style="height: 25px;">
                                @php
                                    $total = $jumlah_siswa;
                                    $lunas_percentage = $total > 0 ? ($lunas / $total) * 100 : 0;
                                    $pending_percentage = $total > 0 ? ($pending / $total) * 100 : 0;
                                    $ditolak_percentage = $total > 0 ? ($ditolak / $total) * 100 : 0;
                                    $belum_bayar_percentage = $total > 0 ? ($belum_bayar / $total) * 100 : 0;
                                @endphp
                                <div class="progress-bar bg-success" style="width: {{ $lunas_percentage }}%">
                                    {{ number_format($lunas_percentage, 1) }}%
                                </div>
                                <div class="progress-bar bg-warning" style="width: {{ $pending_percentage }}%">
                                    {{ number_format($pending_percentage, 1) }}%
                                </div>
                                <div class="progress-bar bg-danger" style="width: {{ $ditolak_percentage }}%">
                                    {{ number_format($ditolak_percentage, 1) }}%
                                </div>
                                <div class="progress-bar bg-secondary" style="width: {{ $belum_bayar_percentage }}%">
                                    {{ number_format($belum_bayar_percentage, 1) }}%
                                </div>
                            </div>
                        </div>

                        <div class="row text-center">
                            <div class="col-6 mb-3">
                                <div class="border-right">
                                    <h4 class="text-success">{{ $lunas }}</h4>
                                    <small class="text-muted">Lunas</small>
                                </div>
                            </div>
                            <div class="col-6 mb-3">
                                <h4 class="text-warning">{{ $pending }}</h4>
                                <small class="text-muted">Menunggu</small>
                            </div>
                            <div class="col-6">
                                <div class="border-right">
                                    <h4 class="text-danger">{{ $ditolak }}</h4>
                                    <small class="text-muted">Ditolak</small>
                                </div>
                            </div>
                            <div class="col-6">
                                <h4 class="text-secondary">{{ $belum_bayar }}</h4>
                                <small class="text-muted">Belum Bayar</small>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="card">
                    <div class="card-body text-center">
                        <a href="{{ route('bendahara.penagihan.index') }}" class="btn btn-secondary btn-block mb-2">
                            <i class="fas fa-arrow-left"></i> Kembali
                        </a>
                        <a href="{{ route('bendahara.penagihan.edit', $penagihan->id) }}" class="btn btn-warning btn-block mb-2">
                            <i class="fas fa-edit"></i> Edit Penagihan
                        </a>
                        <form action="{{ route('bendahara.penagihan.destroy', $penagihan->id) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-block" onclick="return confirm('Apakah Anda yakin ingin menghapus penagihan ini?')">
                                <i class="fas fa-trash"></i> Hapus Penagihan
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection