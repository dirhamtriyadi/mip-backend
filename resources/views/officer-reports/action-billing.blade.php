<div class="d-flex justify-content-center">
    <form action="{{ route('officer-reports.destroyBillingFollowupByUser', $value->id) }}" method="post">
        @csrf
        @method('delete')
        <button type="submit" class="btn btn-sm btn-danger">
            <i class="fas fa-trash"></i>
        </button>
    </form>
</div>
