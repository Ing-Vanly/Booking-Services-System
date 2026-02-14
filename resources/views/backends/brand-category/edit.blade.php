
@push('css')
@endpush
<div class="modal-dialog modal-md modal-dialog-centered">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title">{{ __('Edit Model') }}</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
            </button>
        </div>
        <form method="POST" action="{{ route('admin.brand-category.update', $brandCategory->id) }}" class="submit-form" enctype="multipart/form-data">
            <div class="modal-body">
                @csrf
                @method('PUT')
                <div class="row">
                    <div class="col-12">
                        <ul class="nav nav-tabs" id="custom-content-below-tab" role="tablist">
                            @foreach (json_decode($language, true) as $lang)
                                @if ($lang['status'] == 1)
                                    <li class="nav-item">
                                        <a class="nav-link text-capitalize {{ $lang['code'] == $default_lang ? 'active' : '' }}" id="lang_{{ $lang['code'] }}-tab" data-toggle="pill" href="#lang_{{ $lang['code'] }}" data-lang="{{ $lang['code'] }}" role="tab" aria-controls="lang_{{ $lang['code'] }}" aria-selected="false">{{ \App\helpers\AppHelper::get_language_name($lang['code']) . '(' . strtoupper($lang['code']) . ')' }}</a>
                                    </li>
                                @endif
                            @endforeach
                        </ul>
                        <div class="tab-content" id="custom-content-below-tabContent">
                            @foreach (json_decode($language, true) as $lang)
                                @if ($lang['status'] == 1)
                                    <?php
                                        if (count($brandCategory['translations'])) {
                                            $translate = [];
                                            foreach ($brandCategory['translations'] as $t) {
                                                if ($t->locale == $lang['code'] && $t->key == "name") {
                                                    $translate[$lang['code']]['name'] = $t->value;
                                                }
                                                if ($t->locale == $lang['code'] && $t->key == "description") {
                                                    $translate[$lang['code']]['description'] = $t->value;
                                                }
                                            }
                                        }
                                    ?>
                                    <div class="tab-pane fade {{ $lang['code'] == $default_lang ? 'show active' : '' }} mt-3" id="lang_{{ $lang['code'] }}" role="tabpanel" aria-labelledby="lang_{{ $lang['code'] }}-tab">
                                        <div class="form-group">
                                            <input type="hidden" name="lang[]" value="{{ $lang['code'] }}">
                                            <label for="name_{{ $lang['code'] }}">{{ __('Name') }}({{ strtoupper($lang['code']) }})</label>
                                            <input type="text" id="name_{{ $lang['code'] }}" class="form-control"
                                                name="name[]" placeholder="{{__('Enter Name')}}" value="{{ $translate[$lang['code']]['name'] ?? $brandCategory['name'] }}" {{ $lang['code'] == 'en' ? 'required' : '' }}>
                                        </div>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    </div>
                </div>
                <div class="card no_translate_wrapper">
                    <div class="card-header">
                        <h3 class="card-title">{{ __('General Info') }}</h3>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <!-- Category -->
                            <div class="form-group col-md-12">
                                <label class="required_lable" for="category">{{ __('Category') }}</label>
                                <select name="category" id="category"
                                    class="form-control select2 @error('category') is-invalid @enderror">
                                    <option value="">{{ __('Please Select') }}</option>
                                    @foreach ($categories as $id => $name)
                                        <option value="{{ $id }}"
                                            {{ $id == $brandCategory->category_id ? 'selected' : '' }}>
                                            {{ $name }}</option>
                                    @endforeach
                                </select>
                                @error('category')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
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
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>


@push('js')
    <script>
        $('.custom-file-input').change(function (e) {
            var reader = new FileReader();
            var preview = $(this).closest('.form-group').find('.preview img');
            reader.onload = function(e) {
                preview.attr('src', e.target.result).show();
            }
            reader.readAsDataURL(this.files[0]);
        });

        $(document).on('click', '.nav-tabs .nav-link', function (e) {
            if ($(this).data('lang') != 'en') {
                $('.no_translate_wrapper').addClass('d-none');
            } else {
                $('.no_translate_wrapper').removeClass('d-none');
            }
        });
    </script>
@endpush
