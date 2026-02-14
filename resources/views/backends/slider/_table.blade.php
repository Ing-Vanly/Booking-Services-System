<div class="card-body p-0 table-wrapper">
    <table class="table">
        <thead>
            <tr>
                <th>#</th>
                <th>{{ __('Title') }}</th>
                <th>{{ __('Image') }}</th>
                <th>{{ __('Action') }}</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($sliders as $slider)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>
                        <span class="ml-2">
                            {{ $slider->title }}
                        </span>
                    </td>
                    <td>
                        <img src="
                        @if ($slider->image && file_exists(public_path('uploads/images/' . $slider->image)))
                            {{ asset('uploads/images/'. $slider->image) }}
                        @else
                            {{ asset('images/no_image_available.jpg') }}
                        @endif
                        " alt="" class="profile_img_table">
                    </td>
                    <td>
                        @can('brand.edit')
                            <a href="#" data-href="{{ route('admin.slider.edit', $slider->id) }}" class="btn btn-info btn-sm btn-modal btn-edit" data-toggle="modal" data-container=".modal_form">
                                <i class="fas fa-pencil-alt"></i>
                                {{ __('Edit') }}
                            </a>
                        @endcan
                        @can('slider.destroy')

                        <form action="{{ route('admin.slider.destroy', $slider->id) }}" class="d-inline-block form-delete-{{ $slider->id }}">
                            @csrf
                            @method('DELETE')
                            <button type="submit" data-id="{{ $slider->id }}" data-href="{{ route('admin.slider.destroy', $slider->id) }}" class="btn btn-danger btn-sm btn-delete">
                                <i class="fa fa-trash-alt"></i> {{ __('Delete') }}
                            </button>
                        </form>
                        @endcan
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="text-center">{{ __('No sliders found') }}</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="row">
        <div class="col-12 d-flex flex-row flex-wrap">
            <div class="row" style="width: -webkit-fill-available;">
                <div class="col-12 col-sm-6 text-center text-sm-left pl-3" style="margin-block: 20px">
                    {{ __('Showing') }} {{ $sliders->firstItem() }} {{ __('to') }} {{ $sliders->lastItem() }} {{ __('of') }} {{ $sliders->total() }} {{ __('entries') }}
                </div>
                <div class="col-12 col-sm-6 pagination-nav pr-3"> {{ $sliders->links() }}</div>
            </div>
        </div>
    </div>
</div>
