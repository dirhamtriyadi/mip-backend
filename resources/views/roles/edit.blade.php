@extends('templates.main')

@push('styles')
@endpush

@section('content')
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Edit Role</h1>
                    </div>
                    <div class="col-sm-6">
                        {{ Breadcrumbs::render('roles.edit', $role->id) }}
                    </div>
                </div>
            </div><!-- /.container-fluid -->
        </section>

        <!-- Main content -->
        <section class="content">

            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <!-- Default box -->
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">Edit Role</h3>

                                <div class="card-tools">
                                    <button type="button" class="btn btn-tool" onclick="toggleCheckAll(this)"
                                        title="Toggle Check All">
                                        <i class="fas fa-check-square"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="d-flex justify-content-start">
                                    <a href="{{ route('roles.index') }}" class="btn btn-warning mb-3"><i class="fas fa-arrow-left"></i> Kembali</a>
                                </div>
                                <form action="{{ route('roles.update', $role->id) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <div class="form-group" style="margin-top: 10px;">
                                        <label for="name">Nama</label>
                                        <input type="text" class="form-control" id="name" name="name"
                                            placeholder="Masukkan Nama" value="{{ old('name', $role->name) }}">

                                        @error('name')
                                            <div class="alert alert-danger">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="form-group" style="margin-top: 10px;">
                                        <label for="permissions">Permissions</label>
                                        <div id="permissions" class="row">
                                            @foreach ($permissions as $group => $groupPermissions)
                                                <div class="permission-group col-md-3">
                                                    <div class="permission-group card card-primary">
                                                        <div class="card-header">
                                                            <h5 class="card-title">{{ $group }}</h5>

                                                            <div class="card-tools">
                                                                <button type="button" class="btn btn-tool"
                                                                    onclick="toggleCheckAll(this)"
                                                                    title="Toggle Check All">
                                                                    <i class="fas fa-check-square"></i>
                                                                </button>
                                                            </div>
                                                            <!-- /.card-tools -->
                                                        </div>
                                                        <!-- /.card-header -->
                                                        <div class="card-body">
                                                            @foreach ($groupPermissions as $permission)
                                                                <div class="form-check">
                                                                    <input class="form-check-input" type="checkbox"
                                                                        name="permissions[]"
                                                                        value="{{ $permission->name }}"
                                                                        id="permission{{ $permission->id }}"
                                                                        {{ in_array($permission->id, $rolePermissions) ? 'checked' : '' }}
                                                                        onclick="updateToggleIcon(this)">
                                                                    <label class="form-check-label"
                                                                        for="permission{{ $permission->id }}">
                                                                        {{ $permission->name }}
                                                                    </label>
                                                                </div>
                                                            @endforeach
                                                        </div>
                                                        <!-- /.card-body -->
                                                    </div>
                                                    <!-- /.card -->
                                                </div>
                                            @endforeach
                                        </div>

                                        @error('permissions')
                                            <div class="alert alert-danger">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mt-2 d-flex justify-content-center">
                                        <button type="submit" class="btn btn-primary"
                                            style="margin-top: 10px;">Update</button>
                                    </div>
                                </form>
                            </div>
                            <!-- /.card-body -->
                            <div class="card-footer">
                                Footer
                            </div>
                            <!-- /.card-footer-->
                        </div>
                        <!-- /.card -->
                    </div>
                </div>
            </div>
        </section>
        <!-- /.content -->
    </div>
@endsection

@push('scripts')
    <script>
        function toggleCheckAll(button) {
            const cardBody = button.closest('.card').querySelector('.card-body');
            const checkboxes = cardBody.querySelectorAll('input[type="checkbox"]');
            const allChecked = Array.from(checkboxes).every(checkbox => checkbox.checked);
            checkboxes.forEach(checkbox => checkbox.checked = !allChecked);

            // Toggle icon based on the new state
            updateToggleIcon(button);
        }

        function updateToggleIcon(element) {
            const card = element.closest('.card');
            const checkboxes = card.querySelectorAll('input[type="checkbox"]');
            const allChecked = Array.from(checkboxes).every(checkbox => checkbox.checked);
            const icon = card.querySelector('.btn-tool i');
            if (allChecked) {
                icon.classList.remove('fa-square');
                icon.classList.add('fa-check-square');
            } else {
                icon.classList.remove('fa-check-square');
                icon.classList.add('fa-square');
            }
        }

        // Update icons on page load
        document.addEventListener('DOMContentLoaded', () => {
            document.querySelectorAll('.card').forEach(card => {
                const checkboxes = card.querySelectorAll('input[type="checkbox"]');
                const allChecked = Array.from(checkboxes).every(checkbox => checkbox.checked);
                const icon = card.querySelector('.btn-tool i');
                if (allChecked) {
                    icon.classList.remove('fa-square');
                    icon.classList.add('fa-check-square');
                } else {
                    icon.classList.remove('fa-check-square');
                    icon.classList.add('fa-square');
                }
            });
        });
    </script>
@endpush
