<div class="d-flex justify-content-center">
    {{-- <form action="{{ route('billings.reset', $value->id) }}" method="post">
        @csrf
        @method('put')
        <button type="submit" class="btn btn-sm btn-info"><i class="fas fa-redo-alt"></i> Reset</button>
    </form> --}}
    <a href="{{ route('prospective-customer-surveys.edit', $value->id) }}" class="btn btn-sm btn-warning"><i
            class="fas fa-edit"></i> Edit</a>
    <form action="{{ route('prospective-customer-surveys.destroy', $value->id) }}" method="post">
        @csrf
        @method('delete')
        <button type="submit" class="btn btn-sm btn-danger ml-1"><i class="fas fa-trash"></i> Delete</button>
    </form>
    <a href="{{ route('prospective-customer-surveys.exportPdfByCustomer', $value->id) }}"
        class="btn btn-sm btn-success ml-1"><i class="fas fa-file-pdf"></i> Export PDF</a>
</div>
