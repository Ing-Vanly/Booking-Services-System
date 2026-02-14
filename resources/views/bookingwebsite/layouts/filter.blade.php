<style>
    .mytravel-elementor-date-picker {
        z-index: 900;
    }

    .col-12.col-lg-4.col-xl-2.pr-0.align-self-lg-end.text-md-right button {
        position: relative;
        z-index: 10;
        pointer-events: auto !important;
    }
      
        .js-select.selectpicker.dropdown-select {
        width: 100%; 
    }


    .mytravel-elementor-date-picker .flatpickr-input {
        width: 100%; 
    }

    .js-select.selectpicker.dropdown-select, 
    .mytravel-elementor-date-picker .flatpickr-input {
        min-width: 250px; 
    }
</style>
<div class="elementor-column elementor-col-50 elementor-top-column elementor-element elementor-element-076aa54 col-12 px-0"
    data-id="076aa54" data-element_type="column">
    <div class="p-0 mb-lg-n16 elementor-widget-wrap elementor-element-populated">
        <div class="elementor-section elementor-inner-section elementor-element elementor-element-1776308 elementor-section-full_width elementor-section-height-default elementor-section-height-default"
            data-id="1776308" data-element_type="section">
            <div class="elementor-container elementor-column-gap-default row-margin tab-content">
                <div class="elementor-column elementor-col-100 elementor-inner-column elementor-element elementor-element-82f82d7 col-12 tab-pane fade show active"
                    data-id="82f82d7" data-element_type="column" id="search-hotel">
                    <div class="p-0 elementor-widget-wrap elementor-element-populated">
                        <div class="elementor-element elementor-element-4a32277 elementor-widget elementor-widget-myt-search"
                            data-id="4a32277" data-element_type="widget"
                            data-settings="{&quot;skin&quot;:&quot;hotel&quot;}" data-widget_type="myt-search.default">
                            <div class="elementor-widget-container">
                                <div class="card border-0 tab-shadow">
                                    <div class="card-body">
                                        <form class="product_filters" method="get"
                                            action="{{ route('search.index') }}">
                                            <input type="hidden" name="post_type" value="product">
                                            <input type="hidden" name="product_format" value="product-format-hotel">

                                            <div
                                                class="search_vacancy row d-block nav-select d-lg-flex mb-lg-3 px-2 px-lg-3 align-items-end justify-content-center">
                                                <div class="col-12 col-lg-4 col-xl-3 mb-4 mb-lg-0">
                                                    <span
                                                        class="d-block text-gray-1 font-weight-normal text-left mb-0">{{__('Select Transport Type')}}</span>
                                                    <div
                                                        class="dropdown bootstrap-select js-select dropdown-select tab-dropdown col-12 pl-0 d-flex align-items-center text-primary font-weight-semi-bold">
                                                        <select title="Select Transport Type" data-style=""
                                                            data-live-search="true"
                                                            data-searchbox-classes="input-group-sm"
                                                            name="filter_locations" id="location-dropdown-hotel"
                                                            class="js-select selectpicker dropdown-select col-12 pl-0 flaticon-car d-flex align-items-center text-primary font-weight-semi-bold"
                                                            tabindex="-98">
                                                            <option value="" selected="selected">{{__('Which Transport do you want to book?')}}</option>
                                                            @foreach ($categories as $category)
                                                                <option value="{{ $category->slug }}">
                                                                    {{ $category->name }}</option>
                                                            @endforeach
                                                        </select>
                                                        <button type="button"
                                                            class="btn dropdown-toggle bs-placeholder"
                                                            data-toggle="dropdown" role="button"
                                                            data-id="location-dropdown-hotel"
                                                            title="Select Transport Type">
                                                            <div class="filter-option">
                                                                <div class="filter-option-inner">
                                                                    <div class="filter-option-inner-inner">
                                                                        {{__('Select Transport Type')}}
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </button>
                                                        <div class="dropdown-menu" role="combobox">
                                                            <div class="bs-searchbox"><input type="text"
                                                                    class="form-control" autocomplete="off"
                                                                    role="textbox" aria-label="Search"></div>
                                                            <div class="inner show" role="listbox" aria-expanded="false"
                                                                tabindex="-1">
                                                                <ul class="dropdown-menu inner show"></ul>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- Flatpickr Styles -->

                                                <div
                                                    class="col-12 col-lg-4 col-xl-3 mb-4 mb-lg-0 mytravel-elementor-date-picker">
                                                    <span
                                                        class="d-block text-gray-1 font-weight-normal text-left mb-0">{{__('Check In - Out')}}</span>
                                                    <div class="border-bottom border-width-2 border-color-1"
                                                        style="z-index: 1000;">
                                                        <div id="datepickerWrapperPick-67c75650731a6"
                                                            class="u-datepicker input-group">
                                                            <div class="input-group-prepend">
                                                                <span
                                                                    class="d-flex align-items-center mr-2 font-size-20">
                                                                    <i
                                                                        class="flaticon-calendar text-primary font-weight-semi-bold"></i>
                                                                </span>
                                                                <input
                                                                    class="js-range-datepicker font-size-lg-16 shadow-none font-weight-bold form-control hero-form bg-transparent border-0 flatpickr-input pl-2"
                                                                    type="text"
                                                                    placeholder="{{__('Select Check-in / Check-out Date')}}"
                                                                    aria-label="Select Date" style="width: 100%;" />
                                                                <input type="hidden" name="start_date_submit"
                                                                    value="">
                                                                <input type="hidden" name="end_date_submit"
                                                                    value="">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-12 col-lg-4 col-xl-2 pr-0 align-self-lg-end text-md-right">
                                                    <button type="submit" href="#"
                                                        class="btn-primary btn transition-3d-hover w-100 border-radius-3 mb-xl-0 mb-lg-1">
                                                        <i class="flaticon-magnifying-glass font-size-20 mr-1"></i>
                                                        {{__('Search')}}
                                                    </button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Flatpickr Script -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script src="https://cdn.jsdelivr.net/npm/flatpickr@4.6.13"></script>
<script>
    $(document).ready(function() {
        $(".js-range-datepicker").each(function() {
            $(this).flatpickr({
                mode: "range",
                dateFormat: "Y-m-d",
                minDate: "today",
                allowInput: true,
                onChange: function(selectedDates, dateStr, instance) {
                    if (selectedDates.length === 2) {
                        $('input[name="start_date_submit"]').val(flatpickr.formatDate(
                            selectedDates[0], "Y-m-d"));
                        $('input[name="end_date_submit"]').val(flatpickr.formatDate(
                            selectedDates[1], "Y-m-d"));
                    }
                }
            });
        });
    });
</script>
