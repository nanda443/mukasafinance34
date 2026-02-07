@extends('layouts.app')

@section('title', 'Profile Saya')

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Profile Saya</h1>
            </div>
        </div>
    </div>
</div>

<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-4">
                <!-- Profile Image -->
                <div class="card card-primary card-outline">
                    <div class="card-body box-profile">
                        <div class="text-center">
                            @if($user->foto)
                                <img class="profile-user-img img-fluid img-circle"
                                     src="{{ asset('storage/' . $user->foto) }}"
                                     alt="User profile picture"
                                     style="width: 150px; height: 150px; object-fit: cover;">
                            @else
                                <i class="fas fa-user-circle fa-5x text-secondary"></i>
                            @endif
                        </div>

                        <h3 class="profile-username text-center mt-3">{{ $user->name }}</h3>
                        <p class="text-muted text-center">{{ ucfirst($user->role) }}</p>

                        <ul class="list-group list-group-unbordered mb-3">
                            <li class="list-group-item">
                                <b>NIS</b> <a class="float-right">{{ $user->nis ?? '-' }}</a>
                            </li>
                            <li class="list-group-item">
                                <b>Kelas</b> <a class="float-right">{{ $user->kelas ?? '-' }}</a>
                            </li>
                            <li class="list-group-item">
                                <b>Jurusan</b> <a class="float-right">{{ $user->jurusan ?? '-' }}</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="col-md-8">
                <div class="card">
                    <div class="card-header p-2">
                        <h3 class="card-title">Edit Profile</h3>
                    </div>
                    <div class="card-body">
                        @if(session('success'))
                            <div class="alert alert-success alert-dismissible">
                                <button type="button" class="close" data-dismiss="alert">&times;</button>
                                {{ session('success') }}
                            </div>
                        @endif

                        @if($errors->any())
                            <div class="alert alert-danger alert-dismissible">
                                <button type="button" class="close" data-dismiss="alert">&times;</button>
                                <ul>
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form class="form-horizontal" method="POST" action="{{ route('siswa.profile.update') }}" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            
                            <div class="form-group row">
                                <label for="foto" class="col-sm-2 col-form-label">Foto Profil</label>
                                <div class="col-sm-10">
                                    <div class="custom-file">
                                        <input type="file" class="custom-file-input" id="foto" name="foto" accept="image/*">
                                        <label class="custom-file-label" for="foto">Pilih file...</label>
                                    </div>
                                    <small class="form-text text-muted">Format: JPG, PNG, GIF (Maks. 2MB)</small>
                                </div>
                            </div>
                            
                            <div class="form-group row">
                                <label for="name" class="col-sm-2 col-form-label">Nama</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $user->name) }}">
                                </div>
                            </div>
                            
                            <div class="form-group row">
                                <label for="email" class="col-sm-2 col-form-label">Email</label>
                                <div class="col-sm-10">
                                    <input type="email" class="form-control" id="email" name="email" value="{{ old('email', $user->email) }}">
                                </div>
                            </div>
                            
                            <div class="form-group row">
                                <label for="no_telp" class="col-sm-2 col-form-label">No. Telp</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" id="no_telp" name="no_telp" value="{{ old('no_telp', $user->no_telp) }}">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="alamat" class="col-sm-2 col-form-label">Alamat</label>
                                <div class="col-sm-10">
                                    <textarea class="form-control" id="alamat" name="alamat" rows="3">{{ old('alamat', $user->alamat) }}</textarea>
                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="col-sm-12">
                                    <hr>
                                    <p class="text-muted">Kosongkan jika tidak ingin mengubah password</p>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="password" class="col-sm-2 col-form-label">Password Baru</label>
                                <div class="col-sm-10">
                                    <input type="password" class="form-control" id="password" name="password">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="password_confirmation" class="col-sm-2 col-form-label">Konfirmasi Password</label>
                                <div class="col-sm-10">
                                    <input type="password" class="form-control" id="password_confirmation" name="password_confirmation">
                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="offset-sm-2 col-sm-10">
                                    <button type="submit" class="btn btn-primary"><i class="fas fa-save mr-1"></i> Simpan Perubahan</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@section('scripts')
<script>
    $('.custom-file-input').on('change', function() {
        let fileName = $(this).val().split('\\').pop();
        $(this).next('.custom-file-label').addClass("selected").html(fileName);
    });
</script>
@endsection
