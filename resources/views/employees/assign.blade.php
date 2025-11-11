@extends('template.app')

@section('content')
<div class="ms-4">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0">Employees Work</h4>

            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="javascript: void(0);">Pages</a></li>
                    <li class="breadcrumb-item active">Employees</li>
                    <li class="breadcrumb-item active">Assignment</li>
                </ol>
            </div>
        </div>
    </div>
</div>
<div class="container d-flex justify-content-center">
    <div class="card shadow-sm p-4" style="width: 800px; border-radius: 10px;">
        <div class="text-center mb-4">
            <img class="rounded-circle" src="{{ asset('tamplate/assets/images/users/avatar-3.jpg') }}" 
                 alt="avatar" width="100" class="mb-3">
        </div>

        <form action="{{ route('employees')}}" method="POST" enctype="multipart/form-data">
            @csrf
            
            <input type="hidden" name="user_id" value="{{ $employee->id ?? 'ID_KARYAWAN_YANG_DITUGASKAN' }}">
            
            <div class="mb-3">
                <label for="assignment" class="form-label fw-bold">Assignment (Judul Tugas)</label>
                <input type="text" name="assignment" id="assignment" class="form-control" placeholder="Masukkan judul tugas" required>
            </div>

            <div class="mb-3">
                <label for="description" class="form-label fw-bold">Description (Deskripsi Tugas)</label>
                <textarea name="description" id="description" class="form-control" rows="3" placeholder="Jelaskan detail tugas" required></textarea>
            </div>

            <div class="mb-3">
                <label for="priority" class="form-label fw-bold">Priority</label>
                <select name="priority" id="priority" class="form-control" required>
                    <option value="">Pilih Prioritas</option>
                    <option value="low">Low</option>
                    <option value="medium">Medium</option>
                    <option value="high">High</option>
                </select>
            </div>

            <div class="mb-3">
                <label for="file" class="form-label fw-bold">Pilih File (Opsional)</label>
                <div class="input-group">
                    <input type="file" name="file" id="file" class="form-control">
                </div>
            </div>

            <div class="mb-3">
                <label for="noted" class="form-label fw-bold">Noted (Catatan)</label>
                <input type="text" name="noted" id="noted" class="form-control" placeholder="Masukkan catatan tambahan">
            </div>

            <div class="mb-3">
                <label for="deadline" class="form-label fw-bold">Deadline</label>
                <input type="date" name="deadline" id="deadline" class="form-control" required>
            </div>

            <div class="text-center mt-4">
                <button type="submit" class="btn btn-primary px-5">Send Task</button>
            </div>

        </form>
    </div>
</div>
@endsection