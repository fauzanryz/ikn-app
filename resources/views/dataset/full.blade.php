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
  <h1 class="mb-1 mt-2">Dataset Full</h1>
  <nav aria-label="breadcrumb" class="d-none d-md-inline-block">
    <ol class="breadcrumb breadcrumb-dark breadcrumb-transparent mb-0">
      <li class="breadcrumb-item">
        <a href="">
          <svg class="icon icon-xxs" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
          </svg>
        </a>
      </li>
      <li class="breadcrumb-item"><a href="#">Dataset</a></li>
      <li class="breadcrumb-item active" aria-current="page">Dataset Full</li>
    </ol>
  </nav>
</div>

<div class="bg-white rounded shadow p-4 mb-3 mt-3">

  <!-- Tombol untuk membuka modal -->
  <div class="d-flex justify-content-end gap-2">
    <button class="btn btn-sm btn-primary d-inline-flex align-items-center px-3" data-bs-toggle="modal" data-bs-target="#importModal">
      Add Data
    </button>
    <button class="btn btn-sm btn-primary d-inline-flex align-items-center px-3" data-bs-toggle="modal" data-bs-target="#deleteAllModal">
      Delete All Data
    </button>
    <a href="https://colab.research.google.com/drive/1Hc2PXsDc5SZR9e6Bb1p51SIMxoSBBQLR#scrollTo=6S00x_f6-GeD"
      target="_blank"
      class="btn btn-sm btn-primary d-inline-flex align-items-center px-3">
      Crawl Data
    </a>
  </div>

  <!-- Modal Konfirmasi Delete Semua Data -->
  <div class="modal fade" id="deleteAllModal" tabindex="-1" aria-labelledby="deleteAllModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <form action="{{ route('dataset.deleteAll') }}" method="POST">
          @csrf
          @method('DELETE')
          <div class="modal-header">
            <h5 class="modal-title" id="deleteAllModalLabel">Delete All Data</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            Apakah Anda yakin ingin menghapus <strong>semua data</strong>? Tindakan ini tidak dapat dibatalkan.
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Batal</button>
            <button type="submit" class="btn btn-primary">Yakin</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <!-- Modal Import CSV -->
  <div class="modal fade" id="importModal" tabindex="-1" aria-labelledby="importModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <form action="{{ route('dataset.import') }}" method="POST" enctype="multipart/form-data">
          @csrf
          <div class="modal-header">
            <h5 class="modal-title" id="importModalLabel">Add Data</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <div class="mb-3">
              <label for="csv_file" class="form-label">Pilih file CSV:</label>
              <input type="file" name="csv_file" id="csv_file" class="form-control" required accept=".csv">
            </div>
          </div>
          <div class="modal-footer">
            <button type="submit" class="btn btn-primary">Import</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <hr class="mb-0">
  <div class="row">
    <div class="table-responsive">
      <table class="table table-centered table-nowrap mb-0 rounded">
        <thead>
          <tr>
            <th>Conversation ID</th>
            <th>Created At</th>
            <th>Favorite Count</th>
            <th>Full Text</th>
            <th>ID Str</th>
            <th>Image URL</th>
            <th>In Reply To</th>
            <th>Lang</th>
            <th>Location</th>
            <th>Quote Count</th>
            <th>Reply Count</th>
            <th>Retweet Count</th>
            <th>Tweet URL</th>
            <th>User ID Str</th>
            <th>Username</th>
          </tr>
        </thead>
        <tbody>
          @foreach($data as $row)
          <tr>
            <td style="font-size: 10px;">{{ $row->conversation_id_str }}</td>
            <td style="font-size: 10px;">{{ $row->created_at }}</td>
            <td style="font-size: 10px;">{{ $row->favorite_count }}</td>
            <td style="font-size: 10px;">{{ $row->full_text }}</td>
            <td style="font-size: 10px;">{{ $row->id_str }}</td>
            <td style="font-size: 10px;">{{ $row->image_url }}</td>
            <td style="font-size: 10px;">{{ $row->in_reply_to_screen_name }}</td>
            <td style="font-size: 10px;">{{ $row->lang }}</td>
            <td style="font-size: 10px;">{{ $row->location }}</td>
            <td style="font-size: 10px;">{{ $row->quote_count }}</td>
            <td style="font-size: 10px;">{{ $row->reply_count }}</td>
            <td style="font-size: 10px;">{{ $row->retweet_count }}</td>
            <td style="font-size: 10px;">{{ $row->tweet_url }}</td>
            <td style="font-size: 10px;">{{ $row->user_id_str }}</td>
            <td style="font-size: 10px;">{{ $row->username }}</td>
          </tr>
          @endforeach
        </tbody>
      </table>
    </div>
    <div class="mt-3">
      {{ $data->links('pagination::bootstrap-5') }}
    </div>
  </div>
  <hr class="mb-3 mt-1">
  <div class="d-flex justify-content-end gap-2">
    @if ($latestBackup)
    <a href="{{ route('dataset.downloadBackup', basename($latestBackup)) }}"
      class="btn btn-sm btn-primary d-inline-flex align-items-center px-3">
      Download Backup Terakhir
    </a>
    @endif
  </div>
</div>

@endsection