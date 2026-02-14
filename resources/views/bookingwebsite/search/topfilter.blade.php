<div class="sort-bar mb-5">
    <ul class="nav flex-nowrap border border-radius-3 tab-nav align-items-center py-2 px-0">
        <li class="nav-item d-flex align-items-center flex-shrink-0 flex-xl-shrink-1">
            <a href="{{ route('search.index', ['orderby' => 'popularity']) }}"
                class="nav-link font-weight-normal text-gray-1 text-lh-1dot6 py-1 px-4 px-wd-5 font-weight-normal font-size-15 ">{{__('Popularity')}}</a>
        </li>
        <li class="nav-item d-flex align-items-center flex-shrink-0 flex-xl-shrink-1 border-left">
            <a href="{{ route('search.index', ['orderby' => 'date']) }}"
                class="nav-link font-weight-normal text-gray-1 text-lh-1dot6 py-1 px-4 px-wd-5 font-weight-normal font-size-15 ">{{__('Latest')}}</a>
        </li>
        <li class="nav-item d-flex align-items-center flex-shrink-0 flex-xl-shrink-1 border-left">
            <a href="{{ route('search.index', ['orderby' => 'price']) }}"
                class="nav-link font-weight-normal text-gray-1 text-lh-1dot6 py-1 px-4 px-wd-5 font-weight-normal font-size-15 ">{{__('Price: low to high')}}</a>
        </li>
        <li class="nav-item d-flex align-items-center flex-shrink-0 flex-xl-shrink-1 border-left">
            <a href="{{ route('search.index', ['orderby' => 'price-desc']) }}"
                class="nav-link font-weight-normal text-gray-1 text-lh-1dot6 py-1 px-4 px-wd-5 font-weight-normal font-size-15 ">{{__('Price: high to low')}}</a>
        </li>
    </ul>
</div>
