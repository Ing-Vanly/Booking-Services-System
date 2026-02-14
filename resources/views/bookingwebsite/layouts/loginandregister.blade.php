<div class="position-relative px-3 u-header__login-form dropdown-connector-xl u-header__topbar-divider">
    @guest('customer')
        <!-- Show Sign In/Register when user is NOT logged in -->
        <a href="{{ route('customer.login') }}" class="text-white d-flex align-items-center py-3" style="font-size: 18px; color: #007bff;">
            <i class="flaticon-user mr-2 ml-1 font-size-24" style="color: #ffffff;"></i>
            <span class="d-inline-block font-size-18 mr-1" style="color: #ffffff;">{{__('Sign in or Register')}}</span>
        </a>
    @else
        <!-- Show Customer Name when user IS logged in -->
        <div class="dropdown">
            <a id="customerDropdownInvoker" href="#" 
               class="text-white d-flex align-items-center py-3 dropdown-toggle" 
               data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="font-size: 18px;">
                <i class="flaticon-user mr-2 ml-1 font-size-24" style="color: #ffffff;"></i>
                
                <span class="d-inline-block font-size-18 mr-1">{{ Auth::guard('customer')->user()->name }}</span>
            </a>

            <!-- Dropdown Menu -->
            <div class="dropdown-menu dropdown-menu-right custom-dropdown-menu shadow-lg" style="min-width: 320px; border-radius: 10px; padding: 20px; background: #fff;">
                <!-- Profile Section -->
                <div class="text-center">
                    <div class="mb-3">
                        @if (Auth::guard('customer')->user()->image && file_exists(public_path('uploads/customers/' . Auth::guard('customer')->user()->image)))
                            <img src="{{ asset('uploads/customers/' . Auth::guard('customer')->user()->image) }}" 
                                 alt="Profile Image" 
                                 class="rounded-circle profile_img_table" 
                                 width="80" height="80" style="object-fit: cover;">
                        @else
                            <img src="{{ asset('images/no_image_available.jpg') }}" 
                                 alt="Default Image" 
                                 class="rounded-circle profile_img_table" 
                                 width="80" height="80" style="object-fit: cover;">
                        @endif
                    </div>
                    <h5 class="mb-1" style="color: #333;">{{ Auth::guard('customer')->user()->first_name }}</h5>
                    <p class="text-muted small mb-3">{{ Auth::guard('customer')->user()->email }}</p>
                </div>

                <hr class="my-2">

                <!-- Profile & Logout Buttons -->
                <div class="d-flex justify-content-between">
                    <a class="btn btn-primary btn-sm px-3" href="{{ route('customer.profile.edit') }}" style="border-radius: 5px;">
                        <i class="fas fa-user-circle mr-1"></i> {{__('Profile')}}
                    </a>
                    {{-- <a class="btn btn-primary btn-sm px-3" href="{{ route('customer.profile.edit') }}" style="border-radius: 5px;">
                        <i class="fas fa-user-circle mr-1"></i> Update Profile
                    </a> --}}
                    
                    <a class="btn btn-danger btn-sm px-3" href="{{ route('customer.logout') }}" 
                       onclick="event.preventDefault(); document.getElementById('logout-form').submit();" 
                       style="border-radius: 5px;">
                        <i class="fas fa-sign-out-alt mr-1"></i> {{__('Sign out')}}
                    </a>
                </div>

                <!-- Hidden Logout Form -->
                <form id="logout-form" action="{{ route('customer.logout') }}" method="POST" style="display: none;">
                    @csrf
                </form>
            </div>
        </div>
    @endguest
</div>
