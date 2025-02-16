<div class="d-flex justify-content-center">
    {{-- <form action="{{ route('billings.reset', $value->id) }}" method="post">
        @csrf
        @method('put')
        <button type="submit" class="btn btn-sm btn-info"><i class="fas fa-redo-alt"></i> Reset</button>
    </form> --}}
    <a href="{{ route('customer-billings.edit', $value->id) }}" class="btn btn-sm btn-warning ml-1"><i
            class="fas fa-edit"></i> Edit</a>
    <form action="{{ route('customer-billings.destroy', $value->id) }}" class="ml-1" method="post">
        @csrf
        @method('delete')
        <button type="submit" class="btn btn-sm btn-danger"><i class="fas fa-trash"></i> Delete</button>
    </form>
</div>
