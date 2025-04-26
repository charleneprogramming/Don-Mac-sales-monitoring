@extends('Layout.app')

@section('title', 'Archive')

@include('Components.NavBar.navbar')

@section('content')
    <div style="padding: 20px; background-color: #ffffff; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;">
        <div style="text-align:start; margin-top: 20px;">
            <a href="{{ route('product.index', ['user_id' => $userId]) }}" class="back-arrow"
                style="display: inline-block; padding: 10px 20px;  color: #6b4226; font-weight:bold; font-size:1.5rem;">
                <i class="fa-solid fa-arrow-left"></i>
            </a>
        </div>
        <h1 style="text-align: center; margin-bottom: 2rem; font-size: 2.5rem; color: #6b4226; font-weight: bold;">
            Archive Beverages
        </h1>

        <div id="product-container" style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 30px; padding: 20px;">

            @if (count($archivedProducts) > 0)
                @foreach ($archivedProducts as $product)
                    @if ($product['user_id'] === $userId)
                        <div style="background-color: white; padding: 20px; border: 1px solid #9a4c2e; display: flex; flex-direction: column; height: 100%;"
                            onclick="showProductModal({{ json_encode($product) }})">
                            <div
                                style="margin-bottom: 15px; height: 150px; border-radius: 12px; overflow: hidden; display: flex; align-items: center; justify-content: center;">
                                <img src="{{ asset('images/' . ($product['product_image'] ?? 'default.jpg')) }}"
                                    alt="{{ $product['product_name'] }}"
                                    style="max-width: 100%; max-height: 100%; object-fit: contain; width: auto; height: auto;"
                                    loading="lazy"
                                    onerror="this.src='{{ asset('images/default.jpg') }}'; this.onerror=null;"
                                    data-product-id="{{ $product['product_id'] }}">
                            </div>
                            <div style="flex-grow: 1; display: flex; flex-direction: column;">
                                <h2 style="font-size: 1.5rem; color: #9a4c2e; margin-bottom: 10px;">
                                    {{ $product['product_name'] }}</h2>
                                <p style="color: #6b4226; font-weight: bold; margin-bottom: 8px;">
                                    ₱{{ $product['product_price'] }}</p>
                                <p
                                    style="color: {{ $product['product_stock'] < 50 ? '#dc3545' : '#666' }}; margin-bottom: 8px;">
                                    Stock: {{ $product['product_stock'] }}
                                    @if ($product['product_stock'] < 50)
                                        <span style="font-weight: bold; color: #dc3545;"> (Low Stock)</span>
                                    @endif
                                </p>
                            </div>
                            <form action="{{ route('product.restore', $product['product_id']) }}" method="POST">
                                @csrf
                                <button type="submit"
                                    style="width: 100%; padding: 10px; background-color: #fc8c06; color: white; border: none; border-radius: 8px; cursor: pointer; font-weight: bold;">
                                    Restore Product
                                </button>
                            </form>
                        </div>
                    @endif
                @endforeach
            @else
                <div style="text-align: center; grid-column: span 4; padding: 20px;">
                    <p style="color: #666;">No archived beverages</p>
                </div>
            @endif
        </div>

        <div id="success-toast"
            style="position: fixed; bottom: 20px; right: 20px; background-color: #4CAF50; color: white; padding: 15px 20px; border-radius: 8px; box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2); display: none; z-index: 9999; font-weight: bold; transform: translateX(100%); opacity: 0; transition: transform 0.5s ease, opacity 0.5s ease;">
        </div>



    </div>
    <script>
        function showToast(message) {
            const toast = document.getElementById('success-toast');
            toast.textContent = message;
            toast.style.display = 'block';

            // Start with hidden position
            toast.style.transform = 'translateX(100%)';
            toast.style.opacity = '0';

            // Slide in after a short delay
            setTimeout(() => {
                toast.style.transform = 'translateX(0)';
                toast.style.opacity = '1';
            }, 10);

            // Slide out after 5 seconds
            setTimeout(() => {
                toast.style.transform = 'translateX(100%)';
                toast.style.opacity = '0';
            }, 5000);

            // Hide completely after transition ends
            setTimeout(() => {
                toast.style.display = 'none';
            }, 5500);
        }

        @if (session('success'))
            showToast("{{ session('success') }}");
        @endif
    </script>

    <style>
        .back-arrow i:hover {
            color: #ae7b59;
        }
    </style>
@endsection
