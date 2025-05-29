@extends('layouts.app')

@section('content')

<div class="container px-2 pt-2 d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center">
    <h1 class="mb-1 mt-2">Full Text</h1>
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
            <li class="breadcrumb-item active" aria-current="page">Full text</li>
        </ol>
    </nav>
</div>

<div class="bg-white rounded shadow p-4 mb-3 mt-3">

    <div class="row">
        <div class="table-responsive">
            <table class="table table-centered table-nowrap mb-0 rounded">
                <thead>
                    <tr>
                        <th scope="col">Full Text</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($data as $row)
                    <tr>
                        <td style="font-size: 10px;">{{ $row->full_text }}</td>
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