<div class="card-body p-0 table-wrapper">
    <table class="table">
        <thead>
            <tr>
                <th>#</th>
                <th>{{ __('Customer Name') }}</th>
                <th>{{ __('Sub Total') }}</th>
                <th>{{ __('Final Total') }}</th>
                <th>{{ __('Start Date') }}</th>
                <th>{{ __('End Date') }}</th>
                <th>{{ __('Payment Status') }}</th>
                <th>{{ __('Transaction Status') }}</th>
                <th>{{ __('Created By') }}</th>
                <th>{{ __('Action') }}</th>
            </tr>
        </thead>
        <tbody>
            @foreach($transactions as $index => $transaction)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>
                        {{ $transaction->customer->first_name }} {{ $transaction->customer->last_name }}
                    </td>
                    <td>$ {{ number_format($transaction->sub_total,2) }}</td>
                    <td>$ {{ number_format($transaction->final_total,2) }}</td>
                    <td>{{ \Carbon\Carbon::parse($transaction->booking_date)->format('M d, Y') }}</td>
                    <td>{{ \Carbon\Carbon::parse($transaction->end_date)->format('M d, Y') }}</td>
                    <td>
                        <span class="badge badge-{{ $transaction->payment_status == 'paid' ? 'success' : 'danger' }}" style="padding: 0.4rem 0.8rem">
                            {{ ucfirst($transaction->payment_status) }}
                        </span>
                    </td>
                    <td>
                        <span class="badge badge-{{ $transaction->status == 'request' ? 'warning' : ($transaction->status == 'confirmed' ? 'primary' : ($transaction->status == 'cancel' ? 'danger' : 'secondary')) }}" style="padding: 0.4rem 0.8rem">
                            {{ ucfirst($transaction->status) }}
                        </span>
                    </td>
                    <td>
                        {{ $transaction->createdBy->name }}
                    </td>
                    <td>
                        @can('transaction.edit')
                        <a href="{{ route('admin.transaction.edit', $transaction->id) }}" class="btn btn-info btn-sm btn-edit">
                            <i class="fas fa-pencil-alt"></i>
                            {{ __('Edit') }}
                        </a>
                        @endcan
                        @can('transaction.destroy')
                        <form action="{{ route('admin.transaction.destroy', $transaction->id) }}" method="POST" class="d-inline-block form-delete-{{ $transaction->id }}">
                            @csrf
                            @method('DELETE')
                            <button type="submit" data-id="{{ $transaction->id }}" data-href="{{ route('admin.transaction.destroy', $transaction->id) }}" class="btn btn-danger btn-sm btn-delete">
                                <i class="fa fa-trash-alt"></i>
                                {{ __('Delete') }}
                            </button>
                        </form>
                        @endcan
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="row">
        <div class="col-12 d-flex flex-row flex-wrap">
            <div class="row" style="width: -webkit-fill-available;">
                <div class="col-12 col-sm-6 text-center text-sm-left pl-3" style="margin-block: 20px">
                    {{ __('Showing') }} {{ $transactions->firstItem() }} {{ __('to') }} {{ $transactions->lastItem() }} {{ __('of') }} {{ $transactions->total() }} {{ __('entries') }}
                </div>
                <div class="col-12 col-sm-6 pagination-nav pr-3">
                    {{ $transactions->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
