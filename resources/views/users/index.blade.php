@extends('layouts.app')
@section('content')

@if(session('success'))
<script>
  document.addEventListener('DOMContentLoaded', function() {
    const notyf = new Notyf({
      position: {
        x: 'right',
        y: 'top'
      }
    });
    notyf.success("{{ session('success') }}");
  });
</script>
@endif

@if(session('error'))
<script>
  document.addEventListener('DOMContentLoaded', function() {
    const notyf = new Notyf({
      position: {
        x: 'right',
        y: 'top'
      }
    });
    notyf.error("{{ session('error') }}");
  });
</script>
@endif

<div class="container px-2 pt-2 d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center">
  <h1 class="mb-1 mt-2">Users</h1>
  <nav aria-label="breadcrumb" class="d-none d-md-inline-block">
    <ol class="breadcrumb breadcrumb-dark breadcrumb-transparent mb-0">
      <li class="breadcrumb-item">
        <a href="">
          <svg class="icon icon-xxs" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
          </svg>
        </a>
      </li>
      <li class="breadcrumb-item active" aria-current="page">Users</li>
    </ol>
  </nav>
</div>

<div class="bg-white rounded shadow p-4 mb-3 mt-3">
  <div class="d-flex justify-content-end gap-2">
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createUserModal">
      Add Data
    </button>
    <!-- Tombol Lihat Log -->
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#logModal">
      Log Aktivitas
    </button>

  </div>
  <hr class="mb-0">
  <div class="row">
    <div class="table-responsive">
      <table class="table table-centered table-nowrap mb-0 rounded">
        <thead>
          <tr>
            <th>Nama</th>
            <th>Email</th>
            <th>Aksi</th>
          </tr>
        </thead>
        <tbody>
          @foreach($data as $user)
          <tr>
            <td>{{ $user->name }}</td>
            <td>{{ $user->email }}</td>
            <td>
              @if ($user->email !== 'ikn@gmail.com')
              <!-- Tombol edit -->
              <button class="btn btn-sm btn-primary" data-bs-toggle="modal"
                data-bs-target="#editUserModal{{ $user->id }}">Edit</button>

              <!-- Tombol Hapus -->
              <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal"
                data-bs-target="#deleteUserModal{{ $user->id }}">Delete</button>
              @endif

              <!-- Modal Edit -->
              @if ($user->email !== 'ikn@gmail.com')
              <div class="modal fade" id="editUserModal{{ $user->id }}" tabindex="-1"
                aria-labelledby="editUserModalLabel{{ $user->id }}" aria-hidden="true">
                <div class="modal-dialog">
                  <div class="modal-content">
                    <form action="{{ route('users.update', $user->id) }}" method="POST">
                      @csrf
                      @method('PUT')
                      <div class="modal-header">
                        <h5 class="modal-title" id="editUserModalLabel{{ $user->id }}">Edit Data</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                      </div>
                      <div class="modal-body">
                        <div class="mb-3">
                          <label>Nama</label>
                          <input type="text" class="form-control" name="name" value="{{ $user->name }}" required>
                        </div>
                        <div class="mb-3">
                          <label>Email</label>
                          <input type="email" class="form-control" name="email" value="{{ $user->email }}" required>
                        </div>
                        <div class="mb-3">
                          <label>Password (isi jika ingin diganti)</label>
                          <input type="password" class="form-control" name="password">
                        </div>
                        <div class="mb-3">
                          <label>Ulangi Password</label>
                          <input type="password" class="form-control" name="password_confirmation">
                        </div>
                      </div>
                      <div class="modal-footer">
                        <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                      </div>
                    </form>
                  </div>
                </div>
              </div>
              @endif

              <!-- Modal Hapus -->
              @if ($user->email !== 'ikn@gmail.com')
              <div class="modal fade" id="deleteUserModal{{ $user->id }}" tabindex="-1"
                aria-labelledby="deleteUserModalLabel{{ $user->id }}" aria-hidden="true">
                <div class="modal-dialog">
                  <div class="modal-content">
                    <form action="{{ route('users.destroy', $user->id) }}" method="POST">
                      @csrf
                      @method('DELETE')
                      <div class="modal-header">
                        <h5 class="modal-title" id="deleteUserModalLabel{{ $user->id }}">Delete Data</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                      </div>
                      <div class="modal-body">
                        <p>Apakah Anda yakin ingin menghapus user <strong>{{ $user->name }}</strong>?</p>
                      </div>
                      <div class="modal-footer">
                        <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Yakin</button>
                      </div>
                    </form>
                  </div>
                </div>
              </div>
              @endif
            </td>

          </tr>
          @endforeach
        </tbody>
      </table>
    </div>
    <div class="mt-3">
      {{ $data->links('pagination::bootstrap-5') }}
    </div>
  </div>
</div>

<!-- Modal Tambah -->
<div class="modal fade" id="createUserModal" tabindex="-1" aria-labelledby="createUserModalLabel"
  aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form action="{{ route('users.store') }}" method="POST">
        @csrf
        <div class="modal-header">
          <h5 class="modal-title" id="createUserModalLabel">Add Data</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"
            aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="mb-3">
            <label>Nama</label>
            <input type="text" class="form-control" name="name" required>
          </div>
          <div class="mb-3">
            <label>Email</label>
            <input type="email" class="form-control" name="email" required>
          </div>
          <div class="mb-3">
            <label>Password</label>
            <input type="password" class="form-control" name="password" required>
          </div>
          <div class="mb-3">
            <label>Ulangi Password</label>
            <input type="password" class="form-control" name="password_confirmation" required>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Batal</button>
          <button type="submit" class="btn btn-primary">Tambah</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Modal Log -->
<div class="modal fade" id="logModal" tabindex="-1" aria-labelledby="logModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="logModalLabel">Log Aktivitas</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="log-box" style="max-height: 400px; overflow-y: auto; font-family: monospace;">
          <pre>{{ Illuminate\Support\Facades\File::exists(storage_path('logs/log.txt')) 
          ? Illuminate\Support\Facades\File::get(storage_path('logs/log.txt')) 
          : 'Belum ada log.' }}</pre>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Tutup</button>
      </div>
    </div>
  </div>
</div>

@endsection