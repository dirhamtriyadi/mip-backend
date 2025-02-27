<div class="d-flex justify-content-center">
    <form action="{{ route('attendance-reports.show', $value->id) }}" method="get">
        <input type="hidden" class="form-control" name="start_date" placeholder="Masukan Tanggal" value="{{ $start_date }}">
        <input type="hidden" class="form-control" name="end_date" placeholder="Masukan Tanggal" value="{{ $end_date }}">
        <button type="submit" class="btn btn-sm btn-info">
            <i class="fas fa-eye"></i>
        </button>
    </form>
    <form action="{{ route('attendance-reports.exportByUser') }}" method="get">
        <input type="hidden" class="form-control" name="start_date" placeholder="Masukan Tanggal" value="{{ $start_date }}">
        <input type="hidden" class="form-control" name="end_date" placeholder="Masukan Tanggal" value="{{ $end_date }}">
        <input type="hidden" class="form-control" name="user_id" placeholder="Masukan Tanggal" value="{{ $value->id }}">
        <button type="submit" class="btn btn-sm btn-primary ml-1">
            <i class="fas fa-download"></i>
        </button>
    </form>
</div>
{{-- <td class="d-flex justify-content-center">
    <a href="{{ route('users.edit', $value->id) }}"
        class="btn btn-sm btn-warning"><i class="fas fa-edit"></i>
        Edit</a>
    <form action="{{ route('users.destroy', $value->id) }}"
        method="post">
        @csrf
        @method('delete')
        <button type="submit" class="btn btn-sm btn-danger"><i
                class="fas fa-trash"></i> Delete</button>
    </form>
</td> --}}
