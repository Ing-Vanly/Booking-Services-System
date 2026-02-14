{{-- <ul id="menu-top-bar-left" class="list-inline u-header__topbar-nav-divider mb-0">
    <li itemscope="itemscope" itemtype="https://www.schema.org/SiteNavigationElement"
        id="menu-item-111"
        class="menu-item menu-item-type-custom menu-item-object-custom menu-item-111 nav-item list-inline-item mr-0">
        <a title="(000) 999 - 898 -999" href="tel:(000)999-898-999"
            class="u-header__navbar-link">(000) 999 – 898 -999</a></li>
    <li itemscope="itemscope" itemtype="https://www.schema.org/SiteNavigationElement"
        id="menu-item-112"
        class="menu-item menu-item-type-custom menu-item-object-custom menu-item-112 nav-item list-inline-item mr-0">
        <a title="info@mytravel.com" href="mailto:info@mytravel.com"
            class="u-header__navbar-link">info@mytravel.com</a></li>
</ul> --}}


<ul id="menu-top-bar-left" class="list-inline d-flex flex-wrap align-items-center mb-0">
    @if ($phone)
        <li itemscope="itemscope" itemtype="https://www.schema.org/SiteNavigationElement"
            class="menu-item nav-item list-inline-item px-2">
            <a title="{{ $phone }}" href="tel:{{ $phone }}" class="u-header__navbar-link text-black">
                📞 {{ $phone }}
            </a>
        </li>
    @endif

    @if ($email)
        <li itemscope="itemscope" itemtype="https://www.schema.org/SiteNavigationElement"
            class="menu-item nav-item list-inline-item px-2">
            <a title="{{ $email }}" href="mailto:{{ $email }}" class="u-header__navbar-link text-black">
                ✉️ {{ $email }}
            </a>
        </li>
    @endif
</ul>
