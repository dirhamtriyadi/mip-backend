<div class="d-flex justify-content-center">
    <form action="{{ route('billings.reset', $value->id) }}" method="post">
        @csrf
        @method('put')
        <button type="submit" class="btn btn-sm btn-info"><i class="fas fa-redo-alt"></i> Reset</button>
    </form>
</div>
