{{-- <div class="d-flex justify-content-center">
    <form action="{{ route('billings.reset', $value->id) }}" method="post">
        @csrf
        @method('put')
        <button type="submit" class="btn btn-sm btn-info"><i class="fas fa-redo-alt"></i> Reset</button>
    </form>
</div> --}}

<div class="d-flex justify-content-center">
    <button id="btn-detail" type="button" class="btn btn-sm btn-info" data-toggle="modal"
        data-target="#modal-xl" data-attendance="{{ $value }}">
        <i class="fas fa-eye"></i>
    </button>
    <button class="btn btn-sm btn-primary ml-1">
        <i class="fas fa-download"></i>
    </button>
</div>