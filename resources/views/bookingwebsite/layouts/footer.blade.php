<div class="m-3">
    <div class="container">
        <div class="row d-block d-xl-flex align-items-md-center">
            {{-- <div class="col-xl-4 mb-4 mb-xl-0"><a href="https://mytravel.madrasthemes.com/"
                    class="footer-logo-link d-inline-flex align-items-center" rel="home"><img
                        width="58" height="54"
                        src="{{ asset('assets') }}/front/a_data/primary-logo.jpeg"
                        class="attachment-thumbnail size-thumbnail" alt="MyTravel"><span
                        class="brand brand-dark">MyTravel</span></a></div> --}}



            <div class="col-xl-4 mb-4 mb-xl-0"><a href="#"
                    class="footer-logo-link d-inline-flex align-items-center" rel="home"><img width="58"
                        height="54" src="{{ asset('uploads/business_settings/' . $web_header_logo) }}"
                        class="attachment-thumbnail size-thumbnail" alt="MyTravel">
                        {{-- <span class="brand brand-dark">{{ $company_name }}</span> --}}
                    </a></div>



            <div class="col-xl-8 d-block d-lg-flex justify-content-xl-end align-items-center">
                <div class="mb-4 mb-lg-0">
                    @if (isset($social_medias) && !empty($social_medias))
                        @php
                            $filtered_socials = collect($social_medias)->where('title', 'payment');
                        @endphp

                        @if ($filtered_socials->isNotEmpty())
                            <ul id="menu-social-media" class="list-inline mb-0 mr-2 pr-1">
                                @foreach ($filtered_socials as $social)
                                    <li itemscope="itemscope" itemtype="https://www.schema.org/SiteNavigationElement"
                                        class="menu-item menu-item-type-custom menu-item-object-custom nav-item list-inline-item">
                                        <a title="{{ $social['title'] }}"
                                            href="{{ Str::startsWith($social['link'], 'http') ? $social['link'] : 'https://' . $social['link'] }}"
                                            class="btn btn-sm btn-icon btn-pill btn-soft-white btn-bg-transparent transition-3d-hover">
                                            <img src="{{ $social['icon'] }}" alt="{{ $social['title'] }}"
                                                class="social-icon">
                                            <span class="sr-only">{{ $social['title'] }}</span>
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        @else
                            <p>No payment social media links available.</p>
                        @endif
                    @else
                        <p>No social media links available.</p>
                    @endif

                </div>
            </div>
        </div>
    </div>
</div>
<div class="site-credit border-t bg-primary text-white py-4">
    <div class="container">
        <div class="d-flex flex-wrap justify-content-between align-items-center">
            <p class="mb-3 mb-lg-0 text-white text-left">
                © 2022 {{ $company_name }}. All rights reserved
            </p>

            <ul id="menu-footer-navigation-menu" class="list-inline mb-0 d-flex flex-wrap gap-3">
                <li class="nav-item list-inline-item">
                    <a href="{{ url('/') }}" class="text-white hover:text-white text-decoration-none">
                        {{ __('Home') }}
                    </a>
                </li>

                @foreach ($categories as $category)
                    <li class="nav-item list-inline-item">
                        <a href="{{ route('category.products', $category->id) }}" class="text-white hover:text-white text-decoration-none">
                            {{ $category->name }}
                        </a>
                    </li>
                @endforeach

                <li class="nav-item list-inline-item">
                    <a href="{{ url('/about') }}" class="text-white hover:text-white text-decoration-none">
                        {{ __('About Us') }}
                    </a>
                </li>
            </ul>
        </div>
    </div>
</div>


