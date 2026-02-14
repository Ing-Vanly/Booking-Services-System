
{{-- @if (!empty($social_medias))
    <ul id="menu-social-media" class="list-inline mb-0 mr-2 pr-1">
        @foreach ($social_medias as $social)
            <li itemscope="itemscope" itemtype="https://www.schema.org/SiteNavigationElement"
                class="menu-item menu-item-type-custom menu-item-object-custom nav-item list-inline-item">
                <a title="{{ $social['title'] }}" 
                   href="{{ Str::startsWith($social['link'], 'http') ? $social['link'] : 'https://' . $social['link'] }}" 
                   class="btn btn-sm btn-icon btn-pill btn-soft-white btn-bg-transparent transition-3d-hover">
                    <img src="{{ $social['icon'] }}" alt="{{ $social['title'] }}" class="social-icon">
                    <span class="sr-only">{{ $social['title'] }}</span>
                </a>
            </li>
        @endforeach
    </ul>
@else
    <p>No social media links available.</p>
@endif --}}
<style>
   .social-icon {
    color: #000; /* Make the icons black */
}

</style>
<div>
    
@php
$filtered_socials = collect($social_medias)->reject(function ($social) {
    return $social['title'] === 'payment';
});
@endphp

@if ($filtered_socials->isNotEmpty())
<ul id="menu-social-media" class="list-inline mb-0 mr-2 pr-1" style="">
    @foreach ($filtered_socials as $social)
        <li itemscope="itemscope" itemtype="https://www.schema.org/SiteNavigationElement"
            class="menu-item menu-item-type-custom menu-item-object-custom nav-item list-inline-item">
            <a title="{{ $social['title'] }}" 
               href="{{ Str::startsWith($social['link'], 'http') ? $social['link'] : 'https://' . $social['link'] }}" 
               class="btn btn-sm btn-icon btn-pill btn-soft-black btn-bg-transparent transition-3d-hover">
                <img src="{{ $social['icon'] }}" alt="{{ $social['title'] }}" class="social-icon rounded-circle">
                <span class="sr-only">{{ $social['title'] }}</span>
            </a>
        </li>
    @endforeach
</ul>
@else
<p>No social media links available.</p>
@endif

</div>