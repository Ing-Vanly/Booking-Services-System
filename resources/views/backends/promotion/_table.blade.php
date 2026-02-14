<div class="card-body p-0 table-wrapper table-responsive">
    <table class="table" style="white-space: nowrap">
        <thead>
            <tr class="text-capitalize">
                <th>#</th>
                <th>{{ __('Image') }}</th>
                <th>{{ __('Title') }}</th>
                <th>{{ __('Promotion Type') }}</th>
                <th>{{ __('Discount Type') }}</th>
                <th>{{ __('Discount Value') }}</th>
                <th>{{ __('Start Date') }}</th>
                <th>{{ __('End Date') }}</th>
                <th>{{ __('Action') }}</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($promotions as $promotion)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>
                        <img src="
                            @if ($promotion->image && file_exists(public_path('uploads/promotions/' . $promotion->image)))
                                {{ asset('uploads/promotions/' . $promotion->image) }}
                            @else
                                {{ asset('images/no_image_available.jpg') }}
                            @endif
                            " alt="Product Image" class="profile_img_table">
                    </td>
                    <td>{{ $promotion->title}}</td>
                    <td>{{ $promotion->promotion_type }}</td>
                    <td>{{ $promotion->discount_type }}</td>
                    <td>
                        @if ( $promotion->discount_type == 'fix' )
                            $ {{ $promotion->discount_value }}
                        @else
                            {{ $promotion->discount_value }} %
                        @endif
                    </td>
                    <td>{{ $promotion->start_date }}</td>
                    <td>{{ $promotion->end_date }}</td>
                    <td>
                        @can('promotion.edit')
                        <a href="{{ route('admin.promotion.edit', $promotion->id) }}" class="btn btn-info btn-sm btn-edit">
                            <i class="fas fa-pencil-alt"></i>
                            {{ __('Edit') }}
                        </a>
                        @endcan
                        @can('promotion.delete')
                        <form action="{{ route('admin.promotion.destroy', $promotion->id) }}"
                            class="d-inline-block form-delete-{{ $promotion->id }}">
                            @csrf
                            @method('DELETE')
                            <button type="submit" data-id="{{ $promotion->id }}"
                                data-href="{{ route('admin.promotion.destroy', $promotion->id) }}"
                                class="btn btn-danger btn-sm btn-delete">
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
                    {{ __('Showing') }} {{ $promotions->firstItem() }} {{ __('to') }} {{ $promotions->lastItem() }}
                    {{ __('of') }} {{ $promotions->total() }} {{ __('entries') }}
                </div>
                <div class="col-12 col-sm-6 pagination-nav pr-3">
                    {{ $promotions->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
