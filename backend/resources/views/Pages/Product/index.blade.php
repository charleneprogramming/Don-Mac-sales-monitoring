@extends('Layout.app')

@section('title', 'Beverages')

@include('Components.NavBar.navbar')

@section('content')

    <div style="padding: 20px; background-color: #ffffff; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;">

        <div style="display: flex; justify-content:space-between; gap: 10px;">
            <button onclick="showAddProductModal()"
                style="padding: 10px; background-color: #9a4c2e; color: white; border: none; border-radius: 10vh; cursor: pointer; margin-bottom: 20px;">
                <i class="fa-solid fa-plus"></i><i class="fa-solid fa-whiskey-glass "></i>
            </button>
            <a class="button-style" href="{{ route('product.archive') }}"
                style="padding: 10px; color: #6b4226; font-size:1.5rem; text-decoration: none; border-radius: 8px; margin-bottom: 20px;">
                <i class="fa-solid fa-trash"></i>
            </a>
        </div>
        <h1 style="text-align: center; margin-bottom: 2rem; font-size: 2.5rem; color: #6b4226; font-weight: bold;">
            Coffee Menu
        </h1>

        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div id="product-container" style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 10px; padding: 20px;">
            @if (count($products) > 0)
                @foreach ($products as $product)
                <div style="background-color: white; padding: 20px; border: 1px solid #9a4c2e; display: flex; flex-direction: column; height: 100%;"
                         onclick="showProductModal({{ json_encode($product) }})">
                        {{-- <div
                            style="margin-bottom: 15px; height: 500px; border-radius: 12px; overflow: hidden; display: flex; align-items: center; justify-content: center; background-color: #f8f8f8;">
                            <img src="{{ asset('images/' . ($product['product_image'] ?? 'default.jpg')) }}"
                                alt="{{ $product['product_name'] }}"
                                style="max-width: 100%; max-height: 100%; object-fit: contain; width: auto; height: auto;"
                                onerror="this.src='{{ asset('images/default.jpg') }}'">
                        </div> --}}
                        <div
                            style="margin-bottom: 15px; height: 150px; border-radius: 12px; overflow: hidden; display: flex; align-items: center; justify-content: center;">
                            <!-- Add loading="lazy" and cache control -->
                            <img src="{{ asset('images/' . ($product['product_image'] ?? 'default.jpg')) }}"
                                alt="{{ $product['product_name'] }}"
                                style="max-width: 100%; max-height: 100%; object-fit: contain; width: auto; height: auto;"
                                loading="lazy" onerror="this.src='{{ asset('images/default.jpg') }}'; this.onerror=null;"
                                data-product-id="{{ $product['product_id'] }}">
                        </div>
                        <div style="flex-grow: 1; display: flex; flex-direction: column;">
                            <h2 style="font-size: 1.5rem; color: #4b3025; margin-bottom: 10px;">
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
                            {{-- <p style="color: #666; margin-bottom: 15px;">{{ $product['description'] }}</p> --}}

                            <div style="margin-top: auto;">
                                <div style="display: flex; gap: 10px;">
                                    <button onclick="event.stopPropagation(); editProduct({{ $loop->index }})"
                                        style="flex: 1; padding: 8px; background-color: #fc8c06; color: white; border: none; border-radius: 8px; cursor: pointer; font-weight: bold;">
                                        Edit
                                    </button>
                                    <button onclick="event.stopPropagation(); showDeleteModal('{{ $product['product_id'] }}')"
                                        style="flex: 1; padding: 8px; background-color: #9a4c2e; color: white; border: none; border-radius: 8px; cursor: pointer; font-weight: bold;">
                                        Delete
                                    </button>
                                    <form id="delete-form-{{ $product['product_id'] }}"
                                        action="{{ route('product.delete', $product['product_id']) }}" method="POST"
                                        style="display: none;">
                                        @csrf
                                        @method('DELETE')
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            @else
                <div style="text-align: center; grid-column: span 4; padding: 20px;">
                    <p style="color: #666;">No products available.</p>
                </div>
            @endif
        </div>

        {{-- card-details  --}}
        <div class="detail-product modal fade" id="productModal" tabindex="-1" aria-labelledby="productModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-body d-flex">
                    <!-- Image Section -->
                    <div class="col-md-6 d-flex align-items-center justify-content-center"
                        style="background-color: #f8f8f8;">
                        <img id="modalProductImage" src="" alt="Product Image"
                            style="max-width: 100%; max-height: 100%; object-fit: contain;">
                    </div>
                    <!-- Text Section -->
                    <div class="col-md-6 p-4">
                        <h2 id="modalProductName" style="color: #9a4c2e;"></h2>
                        <p id="modalProductPrice" style="color: #6b4226; font-weight: bold;"></p>
                        <p id="modalProductStock"></p>
                        <p id="modalProductDescription" style="color: #666;"></p>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

        {{-- id="edit-modal-form" --}}
        <div id="edit-modal-form" class="modal"
            style="display: none; position: fixed; top: 50%; left: 50%; transform: translate(-50%, -50%); z-index: 9999; width: 500px; margin-top: 60px;">
            <form action="{{ route('product.update') }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('POST')
                <input type="hidden" id="edit-product-id" name="productID">
                <div class="modal-content"
                    style="background-color: white; padding: 20px; border-radius: 8px; box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);">
                    <h2 style="text-align: center;">Edit Beverage</h2>
                    <input type="hidden" name="user_id" value="{{ Auth::id() }}"><br>
                    <label for="edit-product-name-update" style="font-weight:bold;">Name</label>
                    <input type="text" id="edit-product-name-update"
                        style="width: 100%; padding: 8px; margin-bottom: 10px;" name="productName">

                    <label for="edit-product-description-update" style="font-weight:bold;">Description</label>
                    <textarea id="edit-product-description-update" style="width: 100%; padding: 8px; margin-bottom: 10px; height: 100px;"
                        name="productDescription"></textarea>

                    <label for="edit-product-price-update" style="font-weight:bold;">Price</label>
                    <input type="number" id="edit-product-price-update"
                        style="width: 100%; padding: 8px; margin-bottom: 10px;" name="productPrice">

                    <label for="edit-product-stock-update" style="font-weight:bold;">Stock</label>
                    <input type="number" id="edit-product-stock-update"
                        style="width: 100%; padding: 8px; margin-bottom: 10px;" name="productStock">

                    <label for="edit-product-image-update" style="font-weight:bold;">Image</label>
                    <input type="file" id="edit-product-image-update"
                        style="width: 100%; padding: 8px; margin-bottom: 10px;" name="productImage">

                    <div style="display: flex; justify-content: space-between; margin-top: 20px;">
                        <button type="submit"
                            style="padding: 10px 20px; background-color: #4CAF50; color: white; border: none; border-radius: 8px; cursor: pointer; font-weight: bold;">
                            Update Beverage
                        </button>
                        <button type="button" onclick="closeEditModalForm()"
                            style="padding: 10px 20px; background-color: #f44336; color: white; border: none; border-radius: 8px; cursor: pointer; font-weight: bold;">
                            Cancel
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <!-- Toast Notification -->
        <div id="success-toast"
            style="position: fixed; bottom: 20px; right: 20px; background-color: #4CAF50; color: white; padding: 15px 20px; border-radius: 8px; box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2); display: none; z-index: 9999; font-weight: bold; transform: translateX(100%); opacity: 0; transition: transform 0.5s ease, opacity 0.5s ease;">
        </div>


        <div id="edit-modal" class="modal"
            style="display: none; position: fixed; top: 50%; left: 50%; transform: translate(-50%, -50%); z-index: 9999; width: 500px; margin-top:60px;">
            <form id="addProductForm" action="{{ route('product.create') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-content"
                    style="background-color: white; padding: 20px; border-radius: 8px; box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);">
                    <h2 style="text-align: center;">Add Beverage</h2>

                    <div id="validation-errors"
                        style="display: none; background-color: #fee2e2; border: 1px solid #ef4444; padding: 10px; border-radius: 8px; margin-bottom: 15px;">
                        <ul style="list-style: none; margin: 0; padding: 0;">

                        </ul>
                    </div>
                    <input type="hidden" name="user_id" value="{{ Auth::id() }}"><br>

                    <label for="edit-product-name" style="font-weight:bold;">Name</label>
                    <input type="text" id="edit-product-name" style="width: 100%; padding: 8px; margin-bottom: 10px;"
                        name="product_name" required>

                    <label for="edit-product-description" style="font-weight:bold;">Description</label>
                    <textarea id="edit-product-description" style="width: 100%; padding: 8px; margin-bottom: 10px; height: 100px;"
                        name="productDescription" required></textarea>

                    <label for="edit-product-price" style="font-weight:bold;">Price</label>
                    <input type="number" id="edit-product-price" style="width: 100%; padding: 8px; margin-bottom: 10px;"
                        name="productPrice" required step="0.01" min="0">

                    <label for="edit-product-stock" style="font-weight:bold;">Stock</label>
                    <input type="number" id="edit-product-stock" style="width: 100%; padding: 8px; margin-bottom: 10px;"
                        name="productStock" required min="0">

                    <label for="edit-product-image" style="font-weight:bold;">Image</label>
                    <input type="file" id="edit-product-image" style="width: 100%; padding: 8px; margin-bottom: 10px;"
                        name="productImage" accept="image/*">

                    <div style="display: flex; justify-content: space-between;
                        margin-top: 20px;">
                        <button type="submit"
                            style="padding: 10px 20px; background-color: #4CAF50; color: white; border: none; border-radius: 8px; cursor: pointer; font-weight: bold;">
                            Add Beverage
                        </button>
                        <button type="submit" onclick="closeEditModal(event)"
                            style="padding: 10px 20px; background-color: #f44336; color: white; border: none; border-radius: 8px; cursor: pointer; font-weight: bold;">
                            Cancel
                        </button>

                    </div>
                </div>
            </form>
        </div>
        <div id="overlay"
            style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background-color: rgba(0, 0, 0, 0.5); z-index: 999;">
        </div>
        <div id="delete-modal" class="modal"
        style="display: none; position: fixed; top: 50%; left: 50%; transform: translate(-50%, -50%); z-index: 9999; width: 500px; margin-top:35vh;">
        <div class="modal-content"
            style="background-color: white; padding: 20px; border-radius: 8px; box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);">
            <h2 style="color: #dc3545; margin-bottom: 20px; text-align: center;">Confirmation Delete</h2>
            <p style="color: #666; margin-bottom: 20px; text-align: center;">Are you sure you want to delete this beverage?</p>
            <input type="hidden" id="delete-product-id">
            <div style="display: flex; justify-content: center; gap: 10px;">
                <button onclick="confirmDelete()"
                    style="padding: 10px 20px; background-color: #dc3545; color: white; border: none; border-radius: 8px; cursor: pointer; font-weight: bold;">
                    Delete
                </button>
                <button onclick="closeDeleteModal()"
                    style="padding: 10px 20px; background-color: #6c757d; color: white; border: none; border-radius: 8px; cursor: pointer; font-weight: bold;">
                    Cancel
                </button>
            </div>
        </div>
    </div>
    </div>

    <!-- Modal -->
    <div class="detail-product modal fade" id="productModal" tabindex="-1" aria-labelledby="productModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-body d-flex">
                    <!-- Image Section -->
                    <div class="col-md-6 d-flex align-items-center justify-content-center"
                        style="background-color: #f8f8f8;">
                        <img id="modalProductImage" src="" alt="Product Image"
                            style="max-width: 100%; max-height: 100%; object-fit: contain;">
                    </div>
                    <!-- Text Section -->
                    <div class="col-md-6 p-4">
                        <h2 id="modalProductName" style="color: #9a4c2e;"></h2>
                        <p id="modalProductPrice" style="color: #6b4226; font-weight: bold;"></p>
                        <p id="modalProductStock"></p>
                        <p id="modalProductDescription" style="color: #666;"></p>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        // Edit product function
        function editProduct(index) {
            const products = @json($products);
            if (products && products[index]) {
                const product = products[index];
                document.getElementById('edit-product-id').value = product.product_id;
                document.getElementById('edit-product-name-update').value = product.product_name;
                document.getElementById('edit-product-description-update').value = product.description;
                document.getElementById('edit-product-price-update').value = product.product_price;
                document.getElementById('edit-product-stock-update').value = product.product_stock;
                document.getElementById('edit-modal-form').style.display = 'block';
                document.getElementById('overlay').style.display = 'block';
            }
        }

        // Show the modal
        function showModal() {
            document.getElementById('edit-modal').style.display = 'block';
            document.getElementById('overlay').style.display = 'block';
        }

        // Close the modal
        function closeEditModal(event) {
            if (event) {
                event.preventDefault();
            }
            document.getElementById('edit-modal').style.display = 'none';
            document.getElementById('overlay').style.display = 'none';
        }

        // Show the modal for adding a product
        function showAddProductModal() {
            // Clear the input fields for a new product
            document.getElementById('edit-product-name').value = '';
            document.getElementById('edit-product-description').value = '';
            document.getElementById('edit-product-price').value = '';
            document.getElementById('edit-product-stock').value = '';
            document.getElementById('edit-product-image').value = ''; // Clear the image input field
            document.getElementById('edit-modal').setAttribute('data-index', 'new'); // Indicate new product
            showModal();
        }

        function closeEditModalForm() {
            document.getElementById('edit-modal-form').style.display = 'none';
            document.getElementById('overlay').style.display = 'none';
        }

        function showDeleteModal(productId) {
            document.getElementById('delete-product-id').value = productId;
            document.getElementById('delete-modal').style.display = 'block';
            document.getElementById('overlay').style.display = 'block';
        }

        function closeDeleteModal() {
            document.getElementById('delete-modal').style.display = 'none';
            document.getElementById('overlay').style.display = 'none';
        }

        function confirmDelete() {
            const productId = document.getElementById('delete-product-id').value;
            document.getElementById(`delete-form-${productId}`).submit();
        }

        function showProductModal(product) {
            // Populate modal content
            document.getElementById('modalProductImage').src = `/images/${product.product_image || 'default.jpg'}`;
            document.getElementById('modalProductName').textContent = product.product_name;
            document.getElementById('modalProductPrice').textContent = `₱${product.product_price}`;
            document.getElementById('modalProductStock').innerHTML = `Stock: ${product.product_stock} ${
                product.product_stock < 50 ? '<span style="color: #dc3545; font-weight: bold;">(Low Stock)</span>' : ''
            }`;
            document.getElementById('modalProductDescription').textContent = product.description ||
                'No description available.';

            // Show the modal
            const productModal = new bootstrap.Modal(document.getElementById('productModal'));
            productModal.show();
        }

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
        .button-style i:hover{
            color:#ae7b59;
        }

        button {
            transition: transform 0.3s ease, box-shadow 0.3s ease;

        }

        button:hover {
            transform: translateY(-3.2px);
            box-shadow: 0 6px 25px rgba(107, 66, 38, 0.15);
        }

        .detail-product {
            margin-top: 20vh;
        }
    </style>
@endsection
