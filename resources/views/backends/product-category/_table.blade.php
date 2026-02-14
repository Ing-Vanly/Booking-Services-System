<div class="card-body p-0 table-wrapper">
    <table class="table">
        <thead>
            <tr>
                <th>#</th>
                <th>{{ __('Name') }}</th>
                <th>{{ __('Created By') }}</th>
                <th>{{ __('Short Description') }}</th>
                @can('category.edit')
                <th>{{ __('Status') }}</th>
                @endcan
                @can('category.edit' || 'category.delete')
                <th>{{ __('Action') }}</th>
                @endcan
            </tr>
        </thead>
        <tbody>
            @foreach ($categories as $category)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $category->name }}</td>
                    <td>{{ $category->createdBy->name }}</td>
                    <td>{{ $category->short_des }}</td>
                    @can('category.edit')
                    <td>
                        <div class="custom-control custom-switch">
                            <input type="checkbox" class="custom-control-input switcher_input status" id="status_{{ $category->id }}" data-id="{{ $category->id }}" {{ $category->status == 1 ? 'checked' : '' }} name="status">
                            <label class="custom-control-label" for="status_{{ $category->id }}"></label>
                        </div>
                    </td>
                    @endcan
                    <td>
                        @can('category.edit')
                            <a href="#" data-href="{{ route('admin.product-category.edit', $category->id) }}" class="btn btn-info btn-sm btn-modal btn-edit" data-toggle="modal" data-container=".modal_form">
                                <i class="fas fa-pencil-alt"></i>
                                {{ __('Edit') }}
                            </a>
                        @endcan

                        @can('category.delete')
                            <form action="{{ route('admin.product-category.destroy', $category->id) }}" class="d-inline-block form-delete-{{ $category->id }}">
                                @csrf
                                @method('DELETE')
                                <button type="submit" data-id="{{ $category->id }}" data-href="{{ route('admin.product-category.destroy', $category->id) }}" class="btn btn-danger btn-sm btn-delete">
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

    {{-- Pagination Section (if needed) --}}
    <div class="row">
        <div class="col-12 d-flex flex-row flex-wrap">
            <div class="row" style="width: -webkit-fill-available;">
                <div class="col-12 col-sm-6 text-center text-sm-left pl-3" style="margin-block: 20px">
                    {{ __('Showing') }} {{ $categories->firstItem() }} {{ __('to') }} {{ $categories->lastItem() }} {{ __('of') }} {{ $categories->total() }} {{ __('entries') }}
                </div>
                <div class="col-12 col-sm-6 pagination-nav pr-3"> {{ $categories->links() }}</div>
            </div>
        </div>
    </div>
</div>
