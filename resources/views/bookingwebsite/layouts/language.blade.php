<link href="https://cdnjs.cloudflare.com/ajax/libs/flag-icon-css/3.5.0/css/flag-icon.min.css" rel="stylesheet">

    <a class="nav-link" href="#" id="languageDropdown" data-bs-toggle="dropdown">
        <i class="flag-icon flag-icon-{{ $current_locale == 'en' ? 'gb' : $current_locale }}"></i>
    </a>
    <div class="dropdown-menu dropdown-menu-right " aria-labelledby="languageDropdown">
        @foreach ($available_locales as $locale_name => $available_locale)
            <a href="{{ route('change_language', $available_locale) }}" class="dropdown-item text-capitalize 
                {{ $available_locale === $current_locale ? 'active' : '' }}">
                <i class="flag-icon flag-icon-{{ $available_locale == 'en' ? 'gb' : $available_locale }}"></i>
                {{ $locale_name }}
            </a>
        @endforeach
    </div>