@extends('Layout.app')
@section('title', 'Transactions')
@include('Components.NavBar.navbar')

@section('content')

    <div class="transaction container-fluid py-4 flex">
        {{-- <!-- Transaction Title -->
        <div class="transaction-title">
            <h2 style="font-size:2.5rem; font-weight:bold;">
                Transactions
            </h2>
            <p class="text-muted">View and manage all the users transactions here</p>
        </div> --}}

        <div class="container">
            <div id="transaction-summary" class="summary mb-5">
                <h4>Transaction Summary</h4>
                <div class="row mt-4">
                    <!-- Total Sold -->
                    <div class="col-md-4">
                        <div class="card text-center shadow-sm">
                            <div class="card-body">
                                <i class="fa-solid fa-dollar-sign fa-2x text-success mb-3"></i>
                                <h5 class="card-title">Total Beverage Sold</h5>
                                <p class="card-text" id="totalSold">₱0.00</p>
                            </div>
                        </div>
                    </div>

                    <!-- Total Transactions -->
                    <div class="col-md-4">
                        <div class="card text-center shadow-sm">
                            <div class="card-body">
                                <i class="fa-solid fa-receipt fa-2x text-primary mb-3"></i>
                                <h5 class="card-title">Total Transactions</h5>
                                <p class="card-text" id="totalTransactions">0</p>
                            </div>
                        </div>
                    </div>

                    <!-- Total Users -->
                    <div class="col-md-4">
                        <div class="card text-center shadow-sm">
                            <div class="card-body">
                                <i class="fa-solid fa-users fa-2x text-info mb-3"></i>
                                <h5 class="card-title">Total Users</h5>
                                <p class="card-text" id="totalUsers">0</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="box"
                style="border: rgba(96, 92, 92, 0.259) 1px solid; padding:10px; border-radius:10px; padding:20px;">
                <div id="all-transaction" class="userstransactions">
                    <div class="rowing d-flex justify-content-between align-items-center">
                        <!-- Title on the Left -->
                        <div class="text">
                            <h3>All Transactions<br>
                                <h4>Today</h4>
                            </h3>
                        </div>

                        <div class="d-flex align-items-center mb-2">

                            <div class="input-group me-3">
                                <span class="input-group-text">
                                    <i class="fa-solid fa-calendar"></i>
                                </span>
                                <input type="date" id="filterDate" class="form-control"
                                    onchange="filterTransactionsByDate()">
                            </div>

                            <!-- Filter Dropdown -->
                            <div class="dropdown">
                                <button class="btn btn-secondary dropdown-toggle" type="button" id="filterDropdown"
                                    data-bs-toggle="dropdown" aria-expanded="false">
                                    Sort: Newest to Oldest
                                </button>
                                <ul class="dropdown-menu" aria-labelledby="filterDropdown">
                                    <li><a class="dropdown-item" href="#" onclick="sortTransactions('newest')">Newest
                                            to Oldest</a></li>
                                    <li><a class="dropdown-item" href="#" onclick="sortTransactions('oldest')">Oldest
                                            to Newest</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="d-flex justify-content-end mb-3">
                        <div class="input-group" style="width: 40vh;">
                            <span class="input-group-text">
                                <i class="fa-solid fa-magnifying-glass"></i>
                            </span>
                            <input type="text" id="searchName" class="form-control"
                                placeholder="Search for a transaction by name or username" onkeyup="filterTransactions()">
                        </div>
                    </div>

                    <div class="action-button d-flex justify-content-start gap-3 mb-3">
                        <!-- Completed Transactions Button -->
                        <button class="btn btn-success" id="completedButton">
                            Completed (0)
                        </button>

                        <!-- Cancelled Transactions Button -->
                        <button class="btn btn-danger" id="cancelledButton">
                            Cancelled (0)
                        </button>
                    </div>

                    <table class="table table-hover" style="font-size:12px;">
                        <thead>
                            <tr>
                                <th>Transaction ID</th>
                                <th>Name</th>
                                <th>Username</th>
                                <th>Contact number</th>
                                <th>Order date</th>
                                <th>Delivery method</th>
                                <th>Merchant fee</th>
                                <th>Status</th>
                                <th>Total amount</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody id="transactionsTableBody">
                            @foreach ($transactions as $transaction)
                                <tr>
                                    <td>{{ $transaction->id }}</td>
                                    <td>{{ $transaction->name }}</td>
                                    <td>{{ $transaction->username }}</td>
                                    <td>{{ $transaction->contact }}</td>
                                    <td>{{ $transaction->order_date }}</td>
                                    <td>{{ $transaction->delivery_method ? 'For Delivery' : 'For Pick Up' }}</td>
                                    <td>₱{{ $transaction->merchant_fee ? '25.00' : '0.00' }}</td>
                                    <td>{{ $transaction->status ? 'Completed' : 'Cancelled' }}</td>
                                    <td>₱{{ number_format($transaction->total, 2) }}</td>
                                    <td>
                                        <button class="btn btn-info btn-sm"
                                            onclick="showTransactionDetails({{ $transaction->id }})">
                                            <i class="fa-regular fa-eye"></i> View
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                </div>
            </div>

            {{-- <!-- List Users Section -->
            <div id="list-users-section" class="col-md-4" style="margin-top:-15vh;">
                <div id="list-users">
                    <div class="d-flex justify-content-end">
                        <div class="input-group mb-3 flex" style="width:45vh;">
                            <span class="input-group-text search-icon">
                                <i class="fa-solid fa-magnifying-glass"></i>
                            </span>
                            <input type="text" id="searchUser" class="form-control"
                                placeholder="Search for a user by name or email" onkeyup="filterUsers()">
                        </div>
                    </div>
                    <h4 style="background-color: #f0f0f0; padding:2vh;">List of Users</h4>
                    <table class="table table-hover" style="font-size:12px;">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Username</th>
                                <th>Contact</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody id="usersTableBody">
                            @foreach ($users as $user)
                                <tr>
                                    <td>{{ $user->name }}</td>
                                    <td>{{ $user->username }}</td>
                                    <td>{{ $user->contact }}</td>
                                    <td>
                                        <button class="btn btn-primary btn-sm"
                                            onclick="showUserTransactions({{ $user->id }})">
                                            <i class="fa-regular fa-eye"></i>
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div> --}}
        </div>
    </div>
    </div>


    </div>


    <!-- Transaction Details Modal -->
    <div class="modal fade" id="transactionDetailsModal" tabindex="-1" aria-labelledby="transactionDetailsModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="transactionDetailsModalLabel">Order Items</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="transaction-details-section">
                    <!-- Transaction details will be dynamically loaded here -->
                    <p class="text-muted">Loading transaction details...</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        function showAllTransactions() {
            // Hide the user-transactions section and show the all-transaction section
            document.getElementById('transaction-users-section').classList.add('d-none');
            document.getElementById('all-transaction').classList.remove('d-none');
        }

        function filterTransactions() {
            // Get the search input value
            const searchValue = document.getElementById('searchName').value.toLowerCase();

            // Get all rows in the transactions table
            const rows = document.querySelectorAll('#transactionsTableBody tr');

            // Loop through the rows and filter based on the search value
            rows.forEach(row => {
                const nameCell = row.querySelector('td:nth-child(2)'); // Get the second cell (Name column)
                const usernameCell = row.querySelector('td:nth-child(3)'); // Get the third cell (Username column)

                const name = nameCell ? nameCell.textContent.toLowerCase() : '';
                const username = usernameCell ? usernameCell.textContent.toLowerCase() : '';

                // Show or hide the row based on the search value
                if (name.includes(searchValue) || username.includes(searchValue)) {
                    row.style.display = ''; // Show the row
                } else {
                    row.style.display = 'none'; // Hide the row
                }
            });
        }
        // Function to show user transactions
        function showUserTransactions(userId) {
            // Fetch user transactions via AJAX
            fetch(`/transactions/${userId}`)
                .then(response => response.json())
                .then(data => {
                    const tableBody = document.getElementById('userTransactionTableBody');
                    tableBody.innerHTML = data.transactions.map(transaction => `
                    <tr>
                        <td>${transaction.id}</td>
                        <td>${transaction.date}</td>
                        <td>${transaction.time}</td>
                        <td>${transaction.delivery_method ? 'For Delivery' : 'For Pick Up'}</td>
                           <span class="${transaction.status ? 'text-success' : 'text-danger'}">
                                ${transaction.status ? 'Completed' : 'Cancelled'}
                            </span>
                        <td>₱${transaction.total.toFixed(2)}</td>
                        <td>
                            <button class="btn btn-sm btn-info" onclick="showTransactionDetails(${transaction.id})">
                                View Details
                            </button>
                        </td>
                    </tr>
                `).join('');

                    // Hide the all-transaction section and show the user-transactions section
                    document.getElementById('all-transaction').classList.add('d-none');
                    document.getElementById('transaction-users-section').classList.remove('d-none');
                })
                .catch(error => {
                    console.error('Error fetching user transactions:', error);
                    document.getElementById('userTransactionTableBody').innerHTML =
                        '<tr><td colspan="7" class="text-danger">Failed to load user transactions.</td></tr>';
                });
        }

        function showListUsers() {
            // Hide the transaction-users-section
            document.getElementById('transaction-users-section').classList.add('d-none');

            // Show the list-users-section
            document.getElementById('list-users-section').classList.remove('d-none');
        }

        // Function to show transaction details
        function showTransactionDetails(transactionId) {
            // Fetch transaction details via AJAX
            fetch(`/transaction-details/${transactionId}`)
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Failed to fetch transaction details');
                    }
                    return response.json();
                })
                .then(data => {
                    // Build the transaction details content
                    const transactionContent = `
                      
                        <p><strong>Beverages:</strong></p>
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Beverage name</th>
                                    <th>Price</th>
                                    <th>Quantity</th>
                                    <th>Merchant Fee</th>
                                    <th>Total amount</th>
                                </tr>
                            </thead>
                            <tbody>
                               ${data.beverages.map(item => {
                                    const itemTotal = (item.quantity * item.price) + (data.merchant_fee || 0);
                                    return `
                                                                <tr>
                                                                    <td>${item.name}</td>
                                                                    <td>₱${item.price.toFixed(2)}</td>
                                                                    <td>${item.quantity}</td>
                                                                    <td>₱${(data.merchant_fee || 0).toFixed(2)}</td>
                                                                    <td>₱${itemTotal.toFixed(2)}</td>
                                                                </tr>
                                                            `;
                                }).join('')}
                            </tbody>
                        </table>
                    `;

                    // Populate the modal with transaction details
                    document.getElementById('transaction-details-section').innerHTML = transactionContent;

                    // Show the modal
                    const transactionDetailsModal = new bootstrap.Modal(document.getElementById(
                        'transactionDetailsModal'));
                    transactionDetailsModal.show();
                })
                .catch(error => {
                    console.error('Error fetching transaction details:', error);
                    document.getElementById('transaction-details-section').innerHTML =
                        '<p class="text-danger">Failed to load transaction details.</p>';
                });
        }
    </script>

    <style>
        .status-completed {
            border: 1px solid green;
            padding: 5px;
            border-radius: 5px;
            color: green;
            background-color: #ffffff86;
            font-weight: bold;
        }

        .status-cancelled {
            border: 1px solid red;
            padding: 5px;
            border-radius: 5px;
            color: red;
            background-color: #ffffff86;
            font-weight: bold;
        }

        .container-name {
            border: 1px solid #6f6e6e96;
            border-radius: 10px;
            padding: 10px;
        }



        .transaction-details-section {
            max-height: 70vh;
            overflow-y: scroll;
        }
    </style>

@endsection
