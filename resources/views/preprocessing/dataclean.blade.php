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
    <h1 class="mb-1 mt-2">Data Clean</h1>
    <nav aria-label="breadcrumb" class="d-none d-md-inline-block">
        <ol class="breadcrumb breadcrumb-dark breadcrumb-transparent mb-0">
            <li class="breadcrumb-item">
                <a href="">
                    <svg class="icon icon-xxs" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                    </svg>
                </a>
            </li>
            <li class="breadcrumb-item"><a href="#">Preprocessing</a></li>
            <li class="breadcrumb-item active" aria-current="page">Data Clean</li>
        </ol>
    </nav>
</div>

<div class="bg-white rounded shadow p-4 mb-3 mt-3">

    <div class="row">
        <div class="table-responsive">
            <table class="table table-centered table-nowrap mb-0 rounded">
                <thead>
                    <tr>
                        <th>Data Clean</th>
                        <th>Sentiment</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($data as $row)
                    <tr>
                        <td style="font-size: 10px;">{{ $row->data_clean }}</td>
                        <td style="font-size: 10px;">{{ ucfirst($row->sentiment) }}</td>
                        <td>
                            <!-- Tombol untuk buka modal edit -->
                            <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#editSentimenModal{{ $row->id }}">
                                Edit
                            </button>

                            <!-- Modal Edit Sentimen -->
                            <div class="modal fade" id="editSentimenModal{{ $row->id }}" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <form action="{{ route('preprocessing.updateSentimen', $row->id) }}" method="POST">
                                            @csrf
                                            @method('PUT')
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="editModalLabel">Edit Data</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="mb-3">
                                                    <label for="sentiment" class="form-label">Sentiment</label>
                                                    <select class="form-select" name="sentiment" required>
                                                        <option value="positif" {{ $row->sentiment == 'positif' ? 'selected' : '' }}>Positif</option>
                                                        <option value="negatif" {{ $row->sentiment == 'negatif' ? 'selected' : '' }}>Negatif</option>
                                                    </select>
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

@endsection