@extends('Layout.app')
@section('title', 'Transactions')
@include('Components.NavBar.navbar')

@section('content')
    <!-- Add CSRF Token meta tag -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <div class="transaction container-fluid py-4 flex">
        <div class="container">
            <div class="box"
                style="border: rgba(96, 92, 92, 0.259) 1px solid; padding:10px; border-radius:10px; padding:20px;">
                <div id="all-transaction" class="userstransactions">
                    <div class="rowing d-flex justify-content-between align-items-center">
                        <!-- Title on the Left -->
                        <div class="text">
                            <h3>All Transactions<br>
                            </h3>
                        </div>

                        <div class="d-flex align-items-center mb-2">
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
                        <button class="btn btn-success" id="completedButton" onclick="filterTransactionsByStatus(true)">
                            Completed ({{ $completedCount }})
                        </button>

                        <!-- Cancelled Transactions Button -->
                        <button class="btn btn-danger" id="cancelledButton" onclick="filterTransactionsByStatus(false)">
                            Cancelled ({{ $cancelledCount }})
                        </button>

                        <!-- Show All Button -->
                        <button class="btn btn-secondary" id="showAllButton" onclick="showAllTransactions()">
                            Show All
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
                                    <td>
                                        <span class="{{ $transaction->status ? 'status-completed' : 'status-cancelled' }}">
                                            {{ $transaction->status ? 'Completed' : 'Cancelled' }}
                                        </span>
                                    </td>
                                    <td>₱{{ number_format($transaction->total, 2) }}</td>
                                    <td>
                                        <button class="btn btn-info btn-sm"
                                            onclick="showTransactionDetails({{ $transaction->id }})">
                                            <i class="fa-regular fa-eye"></i> View
                                        </button>
                                        <button
                                            class="btn {{ $transaction->status ? 'btn-danger' : 'btn-success' }} btn-sm"
                                            onclick="showStatusConfirmation({{ $transaction->id }}, {{ $transaction->status }})">
                                            <i class="fa-solid {{ $transaction->status ? 'fa-ban' : 'fa-check' }}"></i>
                                            {{ $transaction->status ? 'Cancel' : 'Complete' }}
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Transaction Details Modal -->
    <div class="modal fade" id="transactionDetailsModal" tabindex="-1" aria-labelledby="transactionDetailsModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-info text-white">
                    <h5 class="modal-title" id="transactionDetailsModalLabel">
                        <i class="fa-solid fa-receipt me-2"></i>Transaction Details
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div id="transaction-details-section">
                        <div class="text-center">
                            <div class="spinner-border text-primary" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                            <p class="text-muted mt-2">Loading transaction details...</p>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Status Update Confirmation Modal -->
    <div class="modal fade" id="statusConfirmationModal" tabindex="-1" aria-labelledby="statusConfirmationModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="statusConfirmationModalLabel">Confirm Status Change</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="status-confirmation-message">
                    Are you sure you want to change this transaction's status?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">No, Cancel</button>
                    <button type="button" class="btn btn-danger" id="confirmStatusChange">Yes, Cancel
                        Transaction</button>
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
            const detailsSection = document.getElementById('transaction-details-section');

            // Show loading state
            detailsSection.innerHTML = `
                <div class="text-center">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <p class="text-muted mt-2">Loading transaction details...</p>
                </div>
            `;

            // Show the modal while loading
            const modal = new bootstrap.Modal(document.getElementById('transactionDetailsModal'));
            modal.show();

            // Function to format currency
            const formatCurrency = (number) => {
                return new Intl.NumberFormat('en-PH', {
                    style: 'currency',
                    currency: 'PHP',
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2
                }).format(number).replace('PHP', '₱');
            };

            // Fetch transaction details
            fetch(`/transactions/details/${transactionId}`)
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Failed to fetch transaction details');
                    }
                    return response.json();
                })
                .then(data => {
                    try {
                        // Calculate total amount
                        let totalAmount = 0;
                        data.beverages.forEach(item => {
                            totalAmount += Number(item.subtotal);
                        });

                        // Add merchant fee if applicable
                        if (data.merchant_fee) {
                            totalAmount += Number(data.merchant_fee);
                        }

                        // Build the transaction details content
                        const transactionContent = `
                            <div class="transaction-details">
                                <div class="row mb-4">
                                    <div class="col-md-6">
                                        <div class="card border-info">
                                            <div class="card-body">
                                                <h6 class="card-title text-info">
                                                    <i class="fa-solid fa-user me-2"></i>Customer Information
                                                </h6>
                                                <p class="card-text">
                                                    <strong>Name:</strong> ${data.user.name}<br>
                                                    <strong>Username:</strong> ${data.user.username}<br>
                                                    <strong>Contact:</strong> ${data.user.contact}<br>
                                                    <strong>Order Date:</strong> ${data.order_date}
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="card border-info">
                                            <div class="card-body">
                                                <h6 class="card-title text-info">
                                                    <i class="fa-solid fa-truck me-2"></i>Delivery Information
                                                </h6>
                                                <p class="card-text">
                                                    <strong>Method:</strong>
                                                    <span class="${data.delivery_method ? 'text-primary' : 'text-success'}">
                                                        ${data.delivery_method ? 'For Delivery' : 'For Pick Up'}
                                                    </span><br>
                                                    <strong>Merchant Fee:</strong>
                                                    <span class="${data.delivery_method ? 'text-primary' : 'text-success'}">
                                                        ${formatCurrency(data.merchant_fee)}
                                                        ${data.delivery_method ?
                                                            '<small class="text-muted">(Delivery charge)</small>' :
                                                            '<small class="text-muted">(No charge for pick up)</small>'}
                                                    </span><br>
                                                    <strong>Status:</strong>
                                                    <span class="badge ${data.status ? 'bg-success' : 'bg-danger'}">
                                                        ${data.status ? 'Completed' : 'Cancelled'}
                                                    </span>
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="table-responsive">
                        <table class="table table-hover">
                                        <thead class="table-info">
                                            <tr>
                                                <th>Product Name</th>
                                                <th class="text-end">Price</th>
                                                <th class="text-center">Quantity</th>
                                                <th class="text-end">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                            ${data.beverages.map(item => `
                                                                                <tr>
                                                                                    <td>${item.name}</td>
                                                                                    <td class="text-end">${formatCurrency(item.price)}</td>
                                                                                    <td class="text-center">${item.quantity}</td>
                                                                                    <td class="text-end">${formatCurrency(item.subtotal)}</td>
                                                                                </tr>
                                                                            `).join('')}
                            </tbody>
                                        <tfoot class="table-info">
                                            <tr>
                                                <td colspan="3" class="text-end"><strong>Subtotal:</strong></td>
                                                <td class="text-end">${formatCurrency(data.subtotal)}</td>
                                            </tr>
                                            <tr>
                                                <td colspan="3" class="text-end">
                                                    <strong>Merchant Fee:</strong>
                                                    <small class="text-muted">
                                                        (${data.delivery_method ? 'Delivery charge' : 'No charge for pick up'})
                                                    </small>
                                                </td>
                                                <td class="text-end">${formatCurrency(data.merchant_fee)}</td>
                                            </tr>
                                            <tr class="table-primary">
                                                <td colspan="3" class="text-end"><strong>Total Amount:</strong></td>
                                                <td class="text-end"><strong>${formatCurrency(data.total_with_fee)}</strong></td>
                                            </tr>
                                        </tfoot>
                        </table>
                                </div>
                            </div>
                        `;

                        // Update the modal content
                        detailsSection.innerHTML = transactionContent;
                    } catch (error) {
                        console.error('Error processing data:', error);
                        throw new Error('Error processing transaction details: ' + error.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    detailsSection.innerHTML = `
                        <div class="alert alert-danger">
                            <i class="fa-solid fa-circle-exclamation me-2"></i>
                            Failed to load transaction details. Please try again.
                            <br><small class="text-muted">${error.message}</small>
                        </div>
                    `;
                });
        }

        // Function to show status confirmation modal
        function showStatusConfirmation(transactionId, currentStatus) {
            const modal = new bootstrap.Modal(document.getElementById('statusConfirmationModal'));
            const messageElement = document.getElementById('status-confirmation-message');
            const confirmButton = document.getElementById('confirmStatusChange');

            // Update message based on current status
            messageElement.textContent = currentStatus ?
                'Are you sure you want to cancel this transaction?' :
                'Are you sure you want to mark this transaction as completed?';

            // Update confirm button style and text
            if (currentStatus) {
                confirmButton.className = 'btn btn-danger';
                confirmButton.textContent = 'Yes, Cancel Transaction';
            } else {
                confirmButton.className = 'btn btn-success';
                confirmButton.textContent = 'Yes, Complete Transaction';
            }

            // Remove any existing click event listeners
            confirmButton.replaceWith(confirmButton.cloneNode(true));

            // Get the fresh reference to the button after replacement
            const newConfirmButton = document.getElementById('confirmStatusChange');

            // Add click event listener
            newConfirmButton.addEventListener('click', function() {
                updateTransactionStatus(transactionId);
            });

            modal.show();
        }

        // Function to update transaction status
        function updateTransactionStatus(transactionId) {
            const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            fetch(`/transactions/update-status/${transactionId}`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': token,
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    }
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        // Close the modal
                        const modal = bootstrap.Modal.getInstance(document.getElementById('statusConfirmationModal'));
                        modal.hide();

                        // Find and update the specific row
                        const rows = document.querySelectorAll('#transactionsTableBody tr');
                        rows.forEach(row => {
                            const idCell = row.querySelector('td:first-child');
                            if (idCell && idCell.textContent.trim() === transactionId.toString()) {
                                // Update status cell
                                const statusCell = row.querySelector('td:nth-child(8) span');
                                if (statusCell) {
                                    statusCell.className = data.newStatus ? 'status-completed' :
                                        'status-cancelled';
                                    statusCell.textContent = data.newStatus ? 'Completed' : 'Cancelled';
                                }

                                // Update action button
                                const actionButton = row.querySelector('td:last-child button:last-child');
                                if (actionButton) {
                                    actionButton.className =
                                        `btn ${data.newStatus ? 'btn-danger' : 'btn-success'} btn-sm`;
                                    const icon = actionButton.querySelector('i');
                                    if (icon) {
                                        icon.className = `fa-solid ${data.newStatus ? 'fa-ban' : 'fa-check'}`;
                                    }
                                    actionButton.innerHTML = actionButton.innerHTML.replace(
                                        data.newStatus ? 'Complete' : 'Cancel',
                                        data.newStatus ? 'Cancel' : 'Complete'
                                    );
                                }
                            }
                        });

                        // Filter to show appropriate section
                        filterTransactionsByStatus(!data.newStatus);

                        // Update the counts
                        updateTransactionCounts();

                        // Show success message with status
                        const statusText = data.newStatus ? 'completed' : 'cancelled';
                        alert(`Transaction has been ${statusText} successfully`);
                    } else {
                        alert('Failed to update transaction status: ' + (data.message || 'Unknown error'));
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred while updating the transaction status. Please try again.');
                });
        }

        // Function to update transaction counts
        function updateTransactionCounts() {
            const rows = document.querySelectorAll('#transactionsTableBody tr');
            let completedCount = 0;
            let cancelledCount = 0;

            rows.forEach(row => {
                const statusCell = row.querySelector('td:nth-child(8) span');
                if (statusCell) {
                    if (statusCell.textContent.trim() === 'Completed') {
                        completedCount++;
                    } else {
                        cancelledCount++;
                    }
                }
            });

            // Update the button texts
            document.getElementById('completedButton').textContent = `Completed (${completedCount})`;
            document.getElementById('cancelledButton').textContent = `Cancelled (${cancelledCount})`;
        }

        // Function to filter transactions by status
        function filterTransactionsByStatus(status) {
            const rows = document.querySelectorAll('#transactionsTableBody tr');
            let visibleCount = 0;

            rows.forEach(row => {
                const statusCell = row.querySelector('td:nth-child(8) span');
                if (statusCell) {
                    const isCompleted = statusCell.textContent.trim() === 'Completed';
                    const shouldShow = (isCompleted === status);
                    row.style.display = shouldShow ? '' : 'none';
                    if (shouldShow) visibleCount++;
                }
            });

            // Update button states
            document.getElementById('completedButton').classList.toggle('active', status === true);
            document.getElementById('cancelledButton').classList.toggle('active', status === false);
            document.getElementById('showAllButton').classList.remove('active');

            // Show message if no transactions
            const noTransactionsMessage = document.getElementById('noTransactionsMessage') ||
                (() => {
                    const msg = document.createElement('div');
                    msg.id = 'noTransactionsMessage';
                    msg.className = 'alert alert-info text-center';
                    document.querySelector('.table').parentNode.appendChild(msg);
                    return msg;
                })();

            if (visibleCount === 0) {
                noTransactionsMessage.textContent = `No ${status ? 'completed' : 'cancelled'} transactions found`;
                noTransactionsMessage.style.display = '';
            } else {
                noTransactionsMessage.style.display = 'none';
            }
        }

        // Function to show all transactions
        function showAllTransactions() {
            const rows = document.querySelectorAll('#transactionsTableBody tr');
            rows.forEach(row => {
                row.style.display = '';
            });

            // Update button styles
            document.getElementById('completedButton').classList.remove('active');
            document.getElementById('cancelledButton').classList.remove('active');
            document.getElementById('showAllButton').classList.add('active');
        }

        // Function to sort transactions
        function sortTransactions(order) {
            const tbody = document.getElementById('transactionsTableBody');
            const rows = Array.from(tbody.getElementsByTagName('tr'));

            rows.sort((a, b) => {
                const dateA = new Date(a.cells[4].textContent); // Order date is in column 5 (index 4)
                const dateB = new Date(b.cells[4].textContent);

                return order === 'newest' ? dateB - dateA : dateA - dateB;
            });

            // Update dropdown button text
            document.getElementById('filterDropdown').textContent =
                `Sort: ${order === 'newest' ? 'Newest to Oldest' : 'Oldest to Newest'}`;

            // Clear the table body
            while (tbody.firstChild) {
                tbody.removeChild(tbody.firstChild);
            }

            // Add sorted rows back
            rows.forEach(row => tbody.appendChild(row));

            // Add visual feedback
            rows.forEach(row => {
                row.style.animation = 'none';
                row.offsetHeight; // Trigger reflow
                row.style.animation = 'highlightRow 1s ease-out';
            });
        }

        // Add CSS animation for row highlighting
        const style = document.createElement('style');
        style.textContent = `
            @keyframes highlightRow {
                0% { background-color: rgba(255, 255, 0, 0.2); }
                100% { background-color: transparent; }
            }

            #transactionsTableBody tr {
                transition: background-color 0.3s ease;
            }

            .dropdown-item:hover {
                background-color: #f8f9fa;
                cursor: pointer;
            }

            .dropdown-item.active {
                background-color: #e9ecef;
                color: #000;
            }
        `;
        document.head.appendChild(style);

        // Initialize with newest first
        document.addEventListener('DOMContentLoaded', function() {
            sortTransactions('newest');
        });
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
