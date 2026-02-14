<aside class="main-sidebar elevation-4 sidebar-light-primary" style="">
    <!-- Brand Logo -->
    @php
        $web_header_logo = \App\Models\BusinessSetting::where('type', 'web_header_logo')->first()->value;
    @endphp
    <a href="{{ route('admin.dashboard') }}" class="brand-link" style="">
        <img src="@if ($web_header_logo && file_exists('uploads/business_settings/' . $web_header_logo)) {{ asset('uploads/business_settings/' . $web_header_logo) }}
        @else
        {{ asset('images/no_image_available.jpg') }} @endif"
            alt="AdminLTE Logo" class="brand-image"
            style="width: 100%;
      object-fit: contain;margin-left: 0; height: 100px;max-height: 72px;">
        {{-- <span class="brand-text font-weight-light">ONLINE SHOP</span> --}}
    </a>



    <!-- Sidebar -->
    <div class="sidebar os-theme-dark">
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image">
                {{-- <img src="{{ Session::get('current_user')->profile_photo_path }}" class="img-circle elevation-2"
                    alt="User Image"> --}}
            </div>
            <div class="info">
                {{-- <a href="#" class="d-block">{{ Session::get('current_user')->name }}</a> --}}
            </div>
        </div>
        <!-- SidebarSearch Form -->
        {{-- <div class="form-inline">
            <div class="input-group" data-widget="sidebar-search">
                <input style="background-color: #012e5a;" class="form-control form-control-sidebar" type="search"
                    placeholder="Search" aria-label="Search">
                <div class="input-group-append">
                    <button class="btn btn-sidebar">
                        <i class="fas fa-search fa-fw"></i>
                    </button>
                </div>
            </div>
        </div> --}}

        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu"
                data-accordion="false">
                <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->
                <li class="nav-item">
                    <a href="{{ route('admin.dashboard') }}"
                        class="nav-link @if (request()->routeIs('admin.dashboard')) active @endif">
                        {{-- <i class="nav-icon fas fa-tachometer-alt"></i> --}}
                        @include('svgs.dashboard')
                        <p>
                            {{ __('Dashboard') }}

                        </p>
                    </a>
                </li>
                @if (auth()->user()->can('user.view') || auth()->user()->can('role.view'))
                    <li class="nav-item @if (request()->routeIs('admin.user*', 'admin.roles*')) menu-is-opening menu-open @endif">
                        <a href="#" class="nav-link @if (request()->routeIs('admin.user*', 'admin.roles*')) active @endif">
                            {{-- <i class="nav-icon fa fa-users"></i> --}}
                            @include('svgs.users')
                            <p>
                                {{ __('User Management') }}
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            @if (auth()->user()->can('user.view'))
                                <li class="nav-item">
                                    <a href="{{ route('admin.user.index') }}"
                                        class="nav-link @if (request()->routeIs('admin.user*')) active @endif">
                                        <i class="fa-solid fa-circle nav-icon"></i>
                                        <p>
                                            {{ __('Users') }}
                                        </p>
                                    </a>
                                </li>
                            @endif
                            @if (auth()->user()->can('role.view'))
                                <li class="nav-item">
                                    <a href="{{ route('admin.roles.index') }}"
                                        class="nav-link @if (request()->routeIs('admin.roles*')) active @endif">
                                        <i class="fa-solid fa-circle nav-icon"></i>
                                        <p>
                                            {{ __('Role') }}
                                        </p>
                                    </a>
                                </li>
                            @endif
                        </ul>
                    </li>
                @endif
                @if (auth()->user()->can('product.view'))
                    <li class="nav-item @if (request()->routeIs('admin.product*', 'admin.brand-category*')) menu-is-opening menu-open @endif">
                        {{-- menu-open --}}
                        <a href="#" class="nav-link @if (request()->routeIs('admin.product*', 'admin.brand-category*')) active @endif">
                            <i class="nav-icon fa fa-boxes"></i>
                            <p>
                                {{ __('Product Setup') }}
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="{{ route('admin.product.index') }}"
                                    class="nav-link @if (request()->routeIs('admin.product.*')) active @endif">
                                    <i class="fa-solid fa-circle nav-icon"></i>
                                    <p>{{ __('Product') }}</p>
                                </a>
                            </li>

                            @if (auth()->user()->can('category.view'))
                                <li class="nav-item">
                                    <a href="{{ route('admin.product-category.index') }}"
                                        class="nav-link @if (request()->routeIs('admin.product-category*')) active @endif">
                                        <i class="fa-solid fa-circle nav-icon"></i>
                                        <p>{{ __('Category') }}</p>
                                    </a>
                                </li>
                            @endif
                            @if (auth()->user()->can('brand.view'))
                                <li class="nav-item">
                                    <a href="{{ route('admin.brand-category.index') }}"
                                        class="nav-link @if (request()->routeIs('admin.brand-category*')) active @endif">
                                        <i class="fa-solid fa-circle nav-icon"></i>
                                        <p>{{ __('Model') }}</p>
                                    </a>
                                </li>
                            @endif
                        </ul>
                    </li>
                @endif

                @if (auth()->user()->can('promotion.view'))
                    <li class="nav-item">
                        <a href="{{ route('admin.promotion.index') }}"
                            class="nav-link @if (request()->routeIs('admin.promotion*')) active @endif">
                            @include('svgs.slider')
                            <p>
                                {{ __('Promotion') }}
                            </p>
                        </a>
                    </li>
                @endif

                @if (auth()->user()->can('transaction.view'))
                    <li class="nav-item">
                        <a href="{{ route('admin.transaction.index') }}"
                            class="nav-link @if (request()->routeIs('admin.transaction*')) active @endif">
                            @include('svgs.report')
                            <p>
                                {{ __('Transaction') }}
                            </p>
                        </a>
                    </li>
                @endif

                @if (auth()->user()->can('slider.view'))
                    <li class="nav-item">
                        <a href="{{ route('admin.slider.index') }}"
                            class="nav-link @if (request()->routeIs('admin.slider.index')) active @endif">
                            {{-- <i class="nav-icon fas fa-tachometer-alt"></i> --}}
                            @include('svgs.slider')
                            <p>
                                {{ __('Slider') }}

                            </p>
                        </a>
                    </li>
                @endif
                
                @if (auth()->user()->can('customer.view'))
                    <!---Customer--------->
                    <li class="nav-item">
                        <a href="{{ route('admin.customer.index') }}"
                            class="nav-link @if (request()->routeIs('admin.customer*')) active @endif">
                            {{-- <i class="fas fa-users"></i> --}}
                            @include('svgs.customer')
                            <p>
                                {{ __('Customer') }}
                            </p>
                        </a>
                    </li>
                @endif

                @if (auth()->user()->can('contacts.view'))
                <!---Customer--------->
                <li class="nav-item">
                    <a href="{{ route('admin.contacts.index') }}"
                        class="nav-link @if (request()->routeIs('admin.contacts*')) active @endif">
                        {{-- <i class="fa-solid fa-address-book"></i> --}}
                        @include('svgs.contact')
                        <p>
                            {{ __('Contact') }}
                        </p>
                    </a>
                </li>
            @endif
                <!---  booking --------->
                {{-- <li class="nav-item">
                    <a href="{{ route('booking.index') }}"
                        class="nav-link @if (request()->routeIs('admin.booking*')) active @endif">
                        <i class="fas fa-calendar-check"></i>
                        <p>
                            {{ __('Booking') }}
                        </p>
                    </a>
                </li> --}}
                @if (auth()->user()->can('setting.view'))
                    <li class="nav-item">
                        <a href="{{ route('admin.setting.index') }}"
                            class="nav-link @if (request()->routeIs('admin.setting*')) active @endif">
                            {{-- <i class="nav-icon fas fa-cog"></i> --}}
                            @include('svgs.setting')
                            <p>
                                {{ __('Setting') }}
                            </p>
                        </a>
                    </li>
                @endif
            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>
