@php
    use App\Enums\ProspectiveCustomerStatusEnum;
@endphp

<div class="d-flex justify-content-center">
    @if ($value->status->value === ProspectiveCustomerStatusEnum::Pending->value)
        <button type="button" class="btn btn-sm btn-primary" data-toggle="modal" data-target="#modal-proccess-customer"
            data-prospectiveCustomer="{{ json_encode($value) }}">
            <i class="fas fa-spinner"></i> Proses
        </button>
    @endif
    <a href="{{ route('prospective-customers.edit', $value->id) }}" class="btn btn-sm btn-warning ml-1"><i
            class="fas fa-edit"></i> Edit</a>
    <form action="{{ route('prospective-customers.destroy', $value->id) }}" class="ml-1" method="post">
        @csrf
        @method('delete')
        <button type="submit" class="btn btn-sm btn-danger"><i class="fas fa-trash"></i> Delete</button>
    </form>
</div>
