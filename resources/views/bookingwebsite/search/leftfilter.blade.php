
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">

<div id="mytravel_products_filter-3" class="widget widget_mytravel_products_filter pb-4 mb-2">
    <div class="sidenav border border-color-8 rounded-xs p-4">
        <div class="product-filters mx-n4 my-n4">
            <div id="woocommerce_price_filter-2"
                class="widget woocommerce widget_price_filter accordion rounded-0 shadow-none border-bottom">
                <div class="border-0">
                    <div class="card-collapse" id="widgetHeading-woocommerce_price_filter-2">
                        <h3 class="widget-title mb-0">
                            <button type="button" class="btn btn-link btn-block card-btn py-2 px-5 text-lh-3" data-toggle="collapse"
                                data-target="#widget-collapse-woocommerce_price_filter-2" aria-expanded="true"
                                aria-controls="widget-collapse-woocommerce_price_filter-2">
                                <span class="row align-items-center"><span class="col-9"><span
                                            class="font-weight-bold font-size-17 text-dark mb-3">{{__('Price Range ($)')}}</span></span>
                                    <span class="col-3 text-right"><span class="card-btn-arrow"><span
                                                class="fas fa-chevron-up small"></span></span></span></span></button>
                        </h3>
                    </div>
                    <div id="widget-collapse-woocommerce_price_filter-2" class="collapse show"
                         aria-labelledby="widgetHeading-woocommerce_price_filter-2">
                        <div class="card-body pt-0 mt-1 px-5 pb-4">
                            <form method="get" action="{{ route('search.index') }}">
                                <div class="price_slider_wrapper">
                                    <div class="price_slider_amount" data-step="10">
                                        <input type="text" id="min_price" name="min_price" value="0"
                                               data-min="0" placeholder="Min price" style="display: none;" hidden>
                                        <input type="text" id="max_price" name="max_price" value="9990"
                                               placeholder="Max price" style="display: none;" hidden>

                                        <div class="price_label pb-3 mb-1 d-flex text-lh-1" style="">
                                            <span class="from border-0">$0</span>
                                            <span class="mx-0dot5">—</span> <span class="to border-0">$9,999</span>
                                        </div>
                                        <div class="price_slider ui-slider ui-corner-all ui-slider-horizontal ui-widget ui-widget-content"
                                            style="">
                                            <div class="ui-slider-range ui-corner-all ui-widget-header"
                                                 style="left: 0%; width: 100%;"></div>
                                            <span tabindex="0" class="ui-slider-handle ui-corner-all ui-state-default"
                                                  style="left: 0%;"></span><span tabindex="0"
                                                  class="ui-slider-handle ui-corner-all ui-state-default"
                                                  style="left: 100%;"></span>
                                        </div>
                                        {{-- <button type="submit" class="btn btn-primary btn-sm mt-3">Filter</button> --}}
                                        <input type="hidden" name="post_type" value="product"><input type="hidden"
                                            name="filter" value="pricerange">
                                        <div class="clear"></div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            @foreach ($categories as $category)
                <div id="mytravel_layered_nav-{{ $category->id }}"
                    class="widget mytravel widget_layered_nav mytravel-widget-layered-nav accordion rounded-0 shadow-none border-bottom">
                    <div class="border-0">
                        <div class="card-collapse" id="widgetHeading-mytravel_layered_nav-{{ $category->id }}">
                            <h3 class="widget-title mb-0">
                                <button type="button" class="btn btn-link btn-block card-btn py-2 px-5 text-lh-3 collapsed"
                                        data-toggle="collapse" data-target="#widget-collapse-mytravel_layered_nav-{{ $category->id }}"
                                        aria-expanded="false"
                                        aria-controls="widget-collapse-mytravel_layered_nav-{{ $category->id }}">
                                    <span class="row align-items-center">
                                        <span class="col-9">{{ $category->name }}</span>
                                        <span class="col-3 text-right">
                                            <span class="card-btn-arrow">
                                                <span class="fas fa-chevron-up small"></span>
                                            </span>
                                        </span>
                                    </span>
                                </button>
                            </h3>
                        </div>

                        <div id="widget-collapse-mytravel_layered_nav-{{ $category->id }}" class="collapse"
                             aria-labelledby="widgetHeading-mytravel_layered_nav-{{ $category->id }}">
                            <div class="card-body pt-0 mt-1 px-5 pb-4">
                                <form method="GET" action="{{ route('search.index') }}" id="filter-form">
                                    @csrf
                                    <div class="woocommerce-widget-layered-nav-list mytravel-widget-layered-nav-list">
                                        <label for="brandcategory-select-{{ $category->id }}"></label>
                                        @foreach ($category->brandcategories as $brandcategory)
                                            <div>
                                                <input type="checkbox" id="brandcategory-{{ $brandcategory->id }}" name="brandcategories[]" value="{{ $brandcategory->id }}"
                                                    @if (in_array($brandcategory->id, (array) request()->get('brandcategories', []))) checked @endif
                                                    onclick="this.form.submit()">
                                                <label for="brandcategory-{{ $brandcategory->id }}">{{ $brandcategory->name }}</label>
                                            </div>
                                        @endforeach
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>

<script>
   $(document).ready(function () {
    // Check if jQuery UI is loaded
    if (typeof $.ui === "undefined") {
        console.error("jQuery UI is not loaded. Make sure to include jQuery UI in your project.");
        return;
    }

    let $priceSlider = $(".price_slider");
    let $minPrice = $("#min_price");
    let $maxPrice = $("#max_price");
    let $fromLabel = $(".from");
    let $toLabel = $(".to");

    let minValue = parseInt($minPrice.val()) || 0;
    let maxValue = parseInt($maxPrice.val()) || 9999;

    // Initialize the slider
    $priceSlider.slider({
        range: true,
        min: 0,
        max: 9999,
        step: 10,
        values: [minValue, maxValue],
        create: function () {
            // Set initial values on page load
            $fromLabel.text(`$${minValue}`);
            $toLabel.text(`$${maxValue}`);
        },
        slide: function (event, ui) {
            $minPrice.val(ui.values[0]);
            $maxPrice.val(ui.values[1]);
            $fromLabel.text(`$${ui.values[0]}`);
            $toLabel.text(`$${ui.values[1]}`);
        },
        change: function (event, ui) {
            $minPrice.val(ui.values[0]);
            $maxPrice.val(ui.values[1]);
        }
    });

    // Set initial input values
    $minPrice.val(minValue);
    $maxPrice.val(maxValue);

    // Ensure input fields are visible
    $minPrice.show();
    $maxPrice.show();

    // Optional: Submit form when the slider stops moving
    $priceSlider.on("slidestop", function () {
        $(this).closest("form").submit();
    });
});

</script>