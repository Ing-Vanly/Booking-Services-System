<!-- resources/views/promotions/popup.blade.php -->
@if ($promotion)
    <div id="promotion-popup" class="slide-in"
        style="display: none; position: fixed; top: 50%; left: 50%; transform: translate(-50%, -50%); width: 100%; max-width: 600px; background-color: rgb(164, 152, 152); border: 1px solid #161212; box-shadow: 0 0 10px rgba(137, 110, 110, 0.1); z-index: 1000;">
        <div class="popup-content" style=" text-align: center;">
            <span class="close-button"
                style="position: absolute; top: 5px; right: 10px; cursor: pointer; font-size: 40px;">&times;</span>
            {{-- <h2 style="font-size: 18px; margin-bottom: 10px;">{{ $promotion->title }}</h2>
            <p style="font-size: 14px; margin-bottom: 10px;">{{ $promotion->description }}</p> --}}
            <div class="mt-3">
            @if ($promotion->promotion_type == 'category')
                <a href="{{ route('promotions.categories') }}">
                    <img src="
            @if ($promotion->image && file_exists(public_path('uploads/promotions/' . $promotion->image)))
                {{ asset('uploads/promotions/' . $promotion->image) }}
            @else
                {{ asset('images/no_image_available.jpg') }} @endif
            "
                    alt="Promotion Image" style="width: 100%; height: auto; margin-bottom: 10px;">
                </a>
            @else
                <a href="{{ route('promotions.products') }}">
                    <img src="
            @if ($promotion->image && file_exists(public_path('uploads/promotions/' . $promotion->image)))
                {{ asset('uploads/promotions/' . $promotion->image) }}
            @else
                {{ asset('images/no_image_available.jpg') }} @endif
            "
                    alt="Promotion Image" style="width: 100%; height: auto; margin-bottom: 10px;">
                </a>
            @endif
                
            </div>
            <!-- Add more promotion details as needed -->
            {{-- <a href="{{ route('promotions.products') }}" class="btn btn-primary btn-custom">{{__('View Promotion Products')}}</a>
            <a href="{{ route('promotions.categories') }}" class="btn btn-secondary btn-custom">{{__('View Promotion Categories')}}</a> --}}
        </div>
    </div>
@else
    <p>No active promotions at the moment.</p>
@endif

<script>
    document.addEventListener('DOMContentLoaded', function() {
        var popup = document.getElementById('promotion-popup');
        var closeButton = document.querySelector('.close-button');

        // Function to show the popup
        function showPopup() {
            popup.style.display = 'block';
            popup.classList.add('slide-in');
        }

        // Function to hide the popup
        function hidePopup() {
            popup.style.display = 'none';
            popup.classList.remove('slide-in');
        }

        // Show the popup initially
        showPopup();

        // Close the popup when the close button is clicked
        closeButton.addEventListener('click', function() {
            hidePopup();
        });
    });
</script>

<style>
    @keyframes slide-in {
        from {
            transform: translate(-50%, -100%);
        }

        to {
            transform: translate(-50%, -50%);
        }
    }

    .slide-in {
        animation: slide-in 0.5s ease-out;
    }

    @media (max-width: 768px) {
        #promotion-popup {
            width: 90%;
        }

        .popup-content {
            padding: 10px;
        }

        .close-button {
            top: 5px;
            right: 5px;
        }

        h2 {
            font-size: 16px;
        }

        p {
            font-size: 12px;
        }
    }

    @media (max-width: 480px) {
        #promotion-popup {
            width: 100%;
        }

        .popup-content {
            padding: 5px;
        }

        .close-button {
            top: 5px;
            right: 5px;
        }

        h2 {
            font-size: 14px;
        }

        p {
            font-size: 10px;
        }
    }
</style>
