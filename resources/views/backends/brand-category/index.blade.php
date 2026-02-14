@extends('backends.master')

@section('contents')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>{{ __('Brand Category') }}</h1>
                </div>
                <div class="col-sm-6">
                </div>
            </div>
        </div>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="row align-items-center">
                                <div class="col-sm-6">
                                    <h3 class="card-title">{{ __('Brand Category List') }}</h3>
                                </div>
                                <div class="col-sm-6">
                                    @can('brand.create')
                                    <a class="btn btn-primary btn-modal float-right" href="#" data-href="{{ route('admin.brand-category.create') }}" data-toggle="modal" data-container=".modal_form">
                                        <i class=" fa fa-plus-circle"></i>
                                        {{ __('Add New') }}
                                    </a>
                                    @endcan
                                </div>
                            </div>
                        </div>

                        @include('backends.brand-category._table')
                    </div>
                </div>
            </div>
        </div>
    </section>
    <div class="modal fade modal_form" tabindex="-1" role="dialog" aria-labelledby="gridSystemModalLabel"></div>

@endsection

@push('js')
<script>
    $('.btn_add').click(function (e) {
        var tbody = $('.tbody');
        var numRows = tbody.find("tr").length;
        $.ajax({
            type: "get",
            url: window.location.href,
            data: {
                "key" : numRows
            },
            dataType: "json",
            success: function (response) {
                $(tbody).append(response.tr);
            }
        });
    });

    $(document).on('click', '.btn-edit', function(){
        $("div.modal_form").load($(this).data('href'), function(){

            $(this).modal('show');

        });
    });

    $(document).on('click', '.btn-delete', function (e) {
        e.preventDefault();

        const Confirmation = Swal.mixin({
            customClass: {
                confirmButton: 'btn btn-success',
                cancelButton: 'btn btn-danger'
            },
            buttonsStyling: false
        });

        Confirmation.fire({
            title: '{{ __('Are you sure?') }}',
            text: @json(__('You won\'t be able to revert this!')),
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: '{{ __('Yes, delete it!') }}',
            cancelButtonText: '{{ __('No, cancel!') }}',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                var data = $(`.form-delete-${$(this).data('id')}`).serialize();
                $.ajax({
                    type: "post",
                    url: $(this).data('href'),
                    data: data,
                    success: function (response) {
                        if (response.status == 1) {
                            $('.table-wrapper').replaceWith(response.view);
                            toastr.success(response.msg);
                        } else {
                            toastr.error(response.msg)
                        }
                    }
                });
            }
        });
    });

    $('input.status').on('change', function () {
        $.ajax({
            type: "get",
            url: "{{ route('admin.brand-category.update_status') }}",
            data: { "id" : $(this).data('id') },
            dataType: "json",
            success: function (response) {
                if (response.status == 1) {
                    toastr.success(response.msg);
                } else {
                    toastr.error(response.msg);
                }
            }
        });
    });
</script>
@endpush
