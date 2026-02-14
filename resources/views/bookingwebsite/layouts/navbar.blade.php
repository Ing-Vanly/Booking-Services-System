{{-- <a href="https://mytravel.madrasthemes.com/my-account/"
    class="text-white d-xl-none font-size-20 scroll-icon order-2 mr-2 mr-lg-3 ml-auto">
    <i class="flaticon-user"></i>
</a>

<a href="https://mytravel.madrasthemes.com/"
    class="navbar-brand u-header__navbar-brand-default u-header__navbar-brand-center mr-0 mr-xl-5 u-header__navbar-brand-text-white "
    rel="home"><img width="55" height="53" src="{{ asset('uploads/business_settings/' . $web_header_logo) }}"
        class="attachment-full size-full" alt=""><span class="u-header__navbar-brand-text" style="color: #007bff;">{{ $company_name }}</span></a>
<a href="https://mytravel.madrasthemes.com/"
    class="navbar-brand u-header__navbar-brand u-header__navbar-brand-center u-header__navbar-brand-on-scroll "
    rel="home"><img width="58" height="54" src="{{ asset($web_scroll_logo) }}"
        class="attachment-full size-full" alt=""><span class="u-header__navbar-brand-text" style="color: #007bff;">{{ $company_name }}</span></a>
<button type="button" class="navbar-toggler btn u-hamburger u-hamburger--white order-2 ml-3"
    aria-label="Toggle navigation" aria-expanded="false" aria-controls="navBar" data-toggle="collapse"
    data-target="#navBar">
    <span id="hamburgerTrigger" class="u-hamburger__box">
        <span class="u-hamburger__inner"></span>
    </span>
</button>

<div id="navBar"
    class="navbar-collapse u-header__navbar-collapse collapse order-2 order-xl-0 pt-4 p-xl-0 position-relative">
    <ul id="menu-primary-menu" class="navbar-nav u-header__navbar-nav flex-wrap" >
      
         <nav class="navbar navbar navbar-expand-lg" style="background-color: #ffffff; width: 800px; height: 80px; border-radius: 20px;">
        
            <div class="container" >
                <a class="navbar-brand" href="{{ url('/') }}">Home</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav ml-auto">
                        @foreach($categories as $category)
                            <li class="nav-item dropdown">
                                <a class="navbar-brand" href="#" id="navbarDropdown{{ $category->id }}" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    {{ $category->name }}
                                </a>
                               
                                <ul class="dropdown-menu border-0 shadow-sm" style="width: 300px; height: 100px;" aria-labelledby="navbarDropdown{{ $category->id }}">
                                    @foreach($category->brandCategories as $brandCategory)
                                        <li><a class="dropdown-item"  style="font-size: 1.25rem;" href="{{ route('brandCategory.products', $brandCategory->id) }}">{{ $brandCategory->name }}</a></li>
                                    @endforeach
                                </ul>
                               
                            </li>
                        @endforeach
                    </ul>
                </div>
                <a class="navbar-brand" href="{{ url('/contact') }}">Contact</a>
                <a class="navbar-brand" href="{{ url('/about') }}">About us</a>
            </div>

            @include('bookingwebsite.layouts.cart')
        </nav>


        
    </ul>



    

</div>  

 --}}



 <nav class="navbar navbar-expand-lg navbar-light bg-primary shadow-sm">
   
        <!-- Logo -->
        <a class="navbar-brand d-flex align-items-center" href="{{ url('/') }}">
            <img src="{{ asset('uploads/business_settings/' . $web_header_logo) }}" width="55" height="53" alt="Logo">
            {{-- <span class="ml-2 font-weight-bold text-primary">{{ $company_name }}</span> --}}
        </a>
        
        <!-- Toggler Button -->
        <button class="navbar-toggler bg-white" type="button" data-bs-toggle="collapse" data-bs-target="#navBar" 
            aria-controls="navBar" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        
        <!-- Navbar Links -->
        <div class="collapse navbar-collapse" id="navBar">
            <ul class="navbar-nav mx-auto p-2  bg-primary">
                <li class="nav-item">
                    <a class="nav-link" href="{{ url('/') }}">{{__('Home')}}</a>
                </li>
                
                @foreach($categories as $category)
                    <li class="nav-item dropdown" >
                        <a class="nav-link  " href="#"  id="navbarDropdown{{ $category->id }}" 
                            role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" >
                            {{ $category->name }}
                        </a>
                        <ul class="dropdown-menu border-0 shadow" aria-labelledby="navbarDropdown{{ $category->id }}">
                            @foreach($category->brandCategories as $brandCategory)
                                <li><a class="dropdown-item" href="{{ route('brandCategory.products', $brandCategory->id) }}">
                                    {{ $brandCategory->name }}</a>
                                </li>
                            @endforeach
                        </ul>
                    </li>
                @endforeach
                
                <li class="nav-item">
                    <a class="nav-link" href="{{ url('/contact') }}">{{__('Contact')}}</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ url('/about') }}">{{__('About Us')}}</a>
                </li>
            </ul>
        </div>
        
        <!-- User & Cart Icons -->
      
            {{-- <a href="https://mytravel.madrasthemes.com/my-account/" class="text-dark mx-2">
                <i class="flaticon-user"></i>
            </a> --}}
            @include('bookingwebsite.layouts.cart')
            @include('bookingwebsite.layouts.loginandregister')
            @include('bookingwebsite.layouts.language')
    
 
</nav>

