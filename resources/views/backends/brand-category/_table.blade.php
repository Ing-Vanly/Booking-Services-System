<div class="card-body p-0 table-wrapper">
    <table class="table">
        <thead>
            <tr>
                <th>#</th>
                <th>{{ __('Name') }}</th>
                <th>{{ __('Category') }}</th>
                <th>{{ __('Created By') }}</th>
                <th>{{ __('Status') }}</th>
                <th>{{ __('Action') }}</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($brandCategories as $brand_category)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $brand_category->name }}</td>
                    <td>{{ $brand_category->category ? $brand_category->category->name : 'No category' }}</td>
                    <td>{{ $brand_category->createdBy->name }}</td>
                    <td>
                        <div class="custom-control custom-switch">
                            <input type="checkbox" class="custom-control-input switcher_input status" id="status_{{ $brand_category->id }}" data-id="{{ $brand_category->id }}" {{ $brand_category->status == 1 ? 'checked' : '' }} name="status">
                            <label class="custom-control-label" for="status_{{ $brand_category->id }}"></label>
                        </div>
                    </td>
                    <td>
                        @can('brand.edit')
                            <a href="#" data-href="{{ route('admin.brand-category.edit', $brand_category->id) }}" class="btn btn-info btn-sm btn-modal btn-edit" data-toggle="modal" data-container=".modal_form">
                                <i class="fas fa-pencil-alt"></i>
                                {{ __('Edit') }}
                            </a>
                        @endcan
                        @can('brand.delete')
                            <form action="{{ route('admin.brand-category.destroy', $brand_category->id) }}" class="d-inline-block form-delete-{{ $brand_category->id }}">
                                @csrf
                                @method('DELETE')
                                <button type="submit" data-id="{{ $brand_category->id }}" data-href="{{ route('admin.brand-category.destroy', $brand_category->id) }}" class="btn btn-danger btn-sm btn-delete">
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
                    {{ __('Showing') }} {{ $brandCategories->firstItem() }} {{ __('to') }} {{ $brandCategories->lastItem() }} {{ __('of') }} {{ $brandCategories->total() }} {{ __('entries') }}
                </div>
                <div class="col-12 col-sm-6 pagination-nav pr-3">
                    {{ $brandCategories->links() }}
                </div>
            </div>
        </div>
    </div>
</div>

