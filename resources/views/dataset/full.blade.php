@extends('layouts.app')

@section('content')

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

@if(session('success'))
<div class="alert alert-success mt-2">
 {{ session('success') }}
</div>
@endif

<div class="bg-white rounded shadow p-4 mb-3 mt-3">

 <form action="{{ route('dataset.deleteAll') }}" method="POST" class="d-flex justify-content-end" onsubmit="return confirm('Yakin ingin menghapus semua data?')">
  @csrf
  @method('DELETE')
  <button type="submit" class="btn btn-sm btn-gray-800 d-inline-flex align-items-center px-3">
   Delete All Data
  </button>
 </form>

 <hr class="mb-0">
 <div class="row">
  <div class="table-responsive">
   <table class="table table-centered table-nowrap mb-0 rounded">
    <thead>
     <tr>
      <th>Conversation ID</th>
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
</div>

@endsection