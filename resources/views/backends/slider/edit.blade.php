<div class="modal-dialog modal-md modal-dialog-centered">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title">{{ __('Edit Silder') }}</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
            </button>
        </div>
        <form method="POST" action="{{ route('admin.slider.update', $record->id) }}" class="submit-form" enctype="multipart/form-data">
            <div class="modal-body">
                @csrf
                @method('PUT')
                <div class="row">
                    <div class="col-12">
                        <div class="form-group">
                            <label class="required_label">{{ __('Title') }}</label>
                            <input type="text" class="form-control @error('title') is-invalid @enderror" value="{{ old('title', $record->title) }}" name="title" placeholder="{{ __('Enter Title') }}">
                            @error('title')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="card no_translate_wrapper">
                    <div class="card-header">
                        <h3 class="card-title">{{ __('General Info') }}</h3>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="form-group col-md-12">
                                <label for="exampleInputFile">{{__('Image')}}</label>
                                <div class="input-group">
                                    <div class="custom-file">
                                        <input type="file" class="custom-file-input" id="exampleInputFile" name="image">
                                        <label class="custom-file-label" for="exampleInputFile">{{ $record->image ?? __('Choose file') }}</label>
                                    </div>
                                </div>
                                <div class="preview text-center border rounded mt-2" style="height: 150px">
                                    <img src="
                                        @if ($record->image && file_exists(public_path('uploads/images/' . $record->image)))
                                            {{ asset('uploads/images/'. $record->image) }}
                                        @else
                                            {{ asset('images/no_image_available.jpg') }}
                                        @endif
                                        " alt="" height="100%">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('Close') }}</button>
                <button type="submit" class="btn btn-primary submit">{{ __('Save') }}</button>
            </div>
        </form>
    </div>
</div>
<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>


<script>
    $(document).on('change', '.custom-file-input', function (e) {
        var file = e.target.files[0];
        var reader = new FileReader();
        var preview = $(this).closest('.form-group').find('.preview img');

        if (file) {
            reader.onload = function(e) {
                preview.attr('src', e.target.result).show();
            }
            reader.readAsDataURL(file);

            // Update file label
            $(this).next('.custom-file-label').text(file.name);
        }
    });
</script>
