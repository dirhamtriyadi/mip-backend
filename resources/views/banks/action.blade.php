<div class="d-flex justify-content-center">
    <a href="{{ route('banks.edit', $value->id) }}" class="btn btn-sm btn-warning"><i class="fas fa-edit"></i> Edit</a>
    <form action="{{ route('banks.destroy', $value->id) }}" class="ml-1" method="post">
        @csrf
        @method('delete')
        <button type="submit" class="btn btn-sm btn-danger"><i class="fas fa-trash"></i> Delete</button>
    </form>
</div>
