<div class="swiper">
    <div class="swiper-wrapper">
        @foreach ($sliders as $slider)
            <div class="swiper-slide">
                <img class="sliderimg" src="
                @if ($slider->image && file_exists(public_path('uploads/images/' . $slider->image)))
                    {{ asset('uploads/images/'. $slider->image) }}
                @else
                    {{ asset('images/no_image_available.jpg') }}
                @endif
                " alt="{{ $slider->title }}" style="width: 2227px; height: 742px; object-fit: cover;">
                
                <h2>{{ $slider->title }}</h2>
                <p>{{ $slider->description }}</p>
            </div>
        @endforeach
    </div>

    <!-- Pagination & Navigation -->
    <div class="swiper-pagination"></div>
    <div class="swiper-button-next"></div>
    <div class="swiper-button-prev"></div>
</div>

<!-- Swiper JS -->
<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
<script>
    const swiper = new Swiper('.swiper', {
        loop: true,  // Infinite looping
        autoplay: {
            delay: 3000, // Change slides every 3 seconds
            disableOnInteraction: false, // Keep autoplay even after manual navigation
        },
        pagination: {
            el: '.swiper-pagination',
            clickable: true,
        },
        navigation: {
            nextEl: '.swiper-button-next',
            prevEl: '.swiper-button-prev',
        },
        speed: 800, // Smooth transition speed
    });
</script>

<!-- Responsive CSS -->
<style>
    .swiper {
        width: 100%;
        height: auto;
    }

    @media (max-width: 768px) {
        .swiper {
            margin-top: 100px; /* Move down by 100px */
        }
    }

    @media (max-width: 480px) {
        .swiper {
            margin-top: 100px; /* Move down by 100px */
        }
    }

    .swiper-slide {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
    }

    .swiper-slide img {
        width: 100%;
        height: auto;
        object-fit: cover;
    }

    .swiper-slide h2, .swiper-slide p {
        text-align: center;
        padding: 0 10px;
    }
</style>