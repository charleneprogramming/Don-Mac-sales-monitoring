'use client';
import React, { useState, useEffect } from 'react';
import { useRouter } from 'next/navigation';
import Navbar from '../../components/Navbar';
import TransactionModal from '../../components/TransactionModal';

interface Transaction {
    id: string;
    order_list: Array<{
        product_id: string;
        name: string;
        quantity: number;
        price: number;
        totalPrice?: number;
    }> | null;
    total_order: string;
    status: string;
    delivery_method: boolean;
    user_id: number;
    created_at: string;
    updated_at: string;
}

export default function TransactionPage() {
    const [transactions, setTransactions] = useState<Transaction[]>([]);
    const [loading, setLoading] = useState(true);
    const [error, setError] = useState<string | null>(null);
    const [searchTerm, setSearchTerm] = useState('');
    const [sortOrder, setSortOrder] = useState('newest');
    const [statusFilter, setStatusFilter] = useState<'all' | 'completed' | 'cancelled'>('all');
    const [selectedTransaction, setSelectedTransaction] = useState<Transaction | null>(null);
    const [isModalOpen, setIsModalOpen] = useState(false);
    const [isCancelModalOpen, setIsCancelModalOpen] = useState(false);
    const [transactionToCancel, setTransactionToCancel] = useState<string | null>(null);
    const router = useRouter();

    const fetchTransactions = async () => {
        try {
            const token = localStorage.getItem('token');
            const userId = localStorage.getItem('userId');

            if (!token || !userId) {
                router.push('/login');
                return;
            }

            const response = await fetch(`http://localhost:8000/api/sales/transactions/${userId}`, {
                method: 'GET',
                headers: {
                    'Authorization': `Bearer ${token}`,
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                }
            });

            if (!response.ok) {
                if (response.status === 401) {
                    localStorage.removeItem('token');
                    localStorage.removeItem('userId');
                    router.push('/login');
                    return;
                }
                throw new Error(`HTTP error! status: ${response.status}`);
            }

            const data = await response.json();
            console.log('Raw API response:', data);

            // Add detailed logging for the first transaction if available
            if (data && data.length > 0) {
                console.log('First transaction details:', {
                    id: data[0].id,
                    order_list: data[0].order_list,
                    order_list_type: typeof data[0].order_list,
                    total_order: data[0].total_order,
                    status: data[0].status
                });
            }

            if (!Array.isArray(data)) {
                throw new Error('Invalid response format: expected an array');
            }

            // Transform the data to ensure order_list is properly structured
            const transformedData = data.map(transaction => ({
                ...transaction,
                order_list: transaction.order_list ? (
                    typeof transaction.order_list === 'string'
                        ? JSON.parse(transaction.order_list)
                        : transaction.order_list
                ) : null
            }));

            console.log('Transformed transactions:', transformedData);
            setTransactions(transformedData);
            setError(null);
        } catch (err) {
            console.error('Fetch error:', err);
            setError(err instanceof Error ? err.message : 'An unexpected error occurred');
        } finally {
            setLoading(false);
        }
    };

    useEffect(() => {
        fetchTransactions();
    }, []);

    const calculateTotalSales = (): number => {
        try {
            const total = transactions
                .filter(trans => trans.status === 'Completed') // Only include completed transactions
                .reduce((total, trans) => {
                    const amount = Number(trans.total_order) || 0;
                    return total + amount;
                }, 0);
            return isNaN(total) ? 0 : total;
        } catch (error) {
            console.error('Error calculating total sales:', error);
            return 0;
        }
    };

    const calculateTotalItems = (): number => {
        try {
            return transactions.reduce((total, trans) => {
                if (!trans.order_list) return total;
                const itemCount = trans.order_list.reduce((sum, item) => {
                    const quantity = Number(item.quantity) || 0;
                    return sum + quantity;
                }, 0);
                return total + itemCount;
            }, 0);
        } catch (error) {
            console.error('Error calculating total items:', error);
            return 0;
        }
    };

    const getFilteredTransactions = () => {
        let filtered = [...transactions];

        // Apply status filter
        if (statusFilter !== 'all') {
            filtered = filtered.filter(trans =>
                statusFilter === 'completed' ?
                    trans.status === 'Completed' :
                    trans.status === 'Cancelled'
            );
        }

        // Apply search filter
        if (searchTerm) {
            filtered = filtered.filter(trans => {
                const transactionDate = new Date(trans.created_at).toLocaleString('en-US', {
                    month: 'long',
                    day: 'numeric',
                    year: 'numeric',
                    hour: '2-digit',
                    minute: '2-digit',
                    hour12: true
                });
                return transactionDate.toLowerCase().includes(searchTerm.toLowerCase());
            });
        }

        // Apply sorting
        return filtered.sort((a, b) => {
            const dateA = new Date(a.created_at).getTime();
            const dateB = new Date(b.created_at).getTime();
            return sortOrder === 'newest' ? dateB - dateA : dateA - dateB;
        });
    };

    const getCompletedCount = () => transactions.filter(t => t.status === 'Completed').length;
    const getCancelledCount = () => transactions.filter(t => t.status === 'Cancelled').length;

    const handleTransactionClick = (transaction: Transaction) => {
        console.log('Selected transaction:', transaction);
        console.log('Order list type:', typeof transaction.order_list);
        console.log('Order list value:', transaction.order_list);

        // Try parsing if it's a string
        if (typeof transaction.order_list === 'string') {
            try {
                const parsedOrderList = JSON.parse(transaction.order_list);
                console.log('Parsed order list:', parsedOrderList);
                // Update the transaction with parsed order list
                const updatedTransaction = {
                    ...transaction,
                    order_list: parsedOrderList
                };
                setSelectedTransaction(updatedTransaction);
            } catch (error) {
                console.error('Error parsing order list:', error);
                setSelectedTransaction(transaction);
            }
        } else {
            setSelectedTransaction(transaction);
        }
        setIsModalOpen(true);
    };

    const handleCancelClick = (transactionId: string, e: React.MouseEvent) => {
        e.stopPropagation();
        setTransactionToCancel(transactionId);
        setIsCancelModalOpen(true);
    };

    const handleCancelConfirm = async () => {
        if (!transactionToCancel) return;

        try {
            const token = localStorage.getItem('token');
            if (!token) {
                router.push('/login');
                return;
            }

            const response = await fetch(`http://localhost:8000/api/sales/cancel/${transactionToCancel}`, {
                method: 'PUT',
                headers: {
                    'Authorization': `Bearer ${token}`,
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                }
            });

            if (!response.ok) {
                throw new Error('Failed to cancel transaction');
            }

            // Show success message
            alert('Transaction cancelled successfully');

            // Close modal and refresh transactions
            setIsCancelModalOpen(false);
            setTransactionToCancel(null);
            await fetchTransactions();
        } catch (err) {
            console.error('Cancel error:', err);
            alert(err instanceof Error ? err.message : 'An error occurred while cancelling the transaction');
            setError(err instanceof Error ? err.message : 'An error occurred while cancelling the transaction');
        }
    };

    const handleCancelClose = () => {
        setIsCancelModalOpen(false);
        setTransactionToCancel(null);
    };

    return (
        <div className="min-h-screen bg-[#faf2e9]">
            <Navbar />

            {loading ? (
                <div className="flex items-center justify-center h-screen">
                    <div className="animate-spin rounded-full h-12 w-12 border-b-2 border-[#6b4226]"></div>
                </div>
            ) : error ? (
                <div className="flex items-center justify-center h-screen">
                    <div className="text-xl text-red-500">{error}</div>
                </div>
            ) : (
                <div className="container mx-auto px-4 py-8">
                    <div className="container mx-auto">
                        <div className="mb-6">
                            <button
                                onClick={() => router.push('/homepage')}
                                className="bg-[#6b4226] text-white px-4 py-2 rounded-lg hover:bg-[#3a2117] transition-colors flex items-center gap-2"
                            >
                                ← Back to Homepage
                            </button>
                        </div>

                        <div className="flex flex-col lg:flex-row gap-8">
                            <div className="lg:w-2/3">
                                <div className="bg-white rounded-lg shadow-lg p-6">
                                    <div className="flex justify-between items-center mb-6">
                                        <h2 className="text-2xl font-bold text-[#4b3025]">Transaction History</h2>
                                        <div className="flex gap-4">
                                            <input
                                                type="text"
                                                placeholder="Search by date"
                                                className="border rounded-lg px-4 py-2"
                                                value={searchTerm}
                                                onChange={(e) => setSearchTerm(e.target.value)}
                                            />
                                            <select
                                                className="border rounded-lg px-4 py-2"
                                                value={sortOrder}
                                                onChange={(e) => setSortOrder(e.target.value)}
                                            >
                                                <option value="newest">Newest First</option>
                                                <option value="oldest">Oldest First</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div className="action-button d-flex justify-content-start gap-3 mb-3">
                                        <button
                                            onClick={() => setStatusFilter('completed')}
                                            className={`px-4 py-2 rounded-lg ${statusFilter === 'completed'
                                                ? 'bg-green-500 text-white'
                                                : 'bg-gray-100 text-gray-700 hover:bg-gray-200'
                                                }`}
                                        >
                                            Completed ({getCompletedCount()})
                                        </button>
                                        <button
                                            onClick={() => setStatusFilter('cancelled')}
                                            className={`px-4 py-2 rounded-lg ${statusFilter === 'cancelled'
                                                ? 'bg-red-500 text-white'
                                                : 'bg-gray-100 text-gray-700 hover:bg-gray-200'
                                                }`}
                                        >
                                            Cancelled ({getCancelledCount()})
                                        </button>
                                        <button
                                            onClick={() => setStatusFilter('all')}
                                            className={`px-4 py-2 rounded-lg ${statusFilter === 'all'
                                                ? 'bg-[#6b4226] text-white'
                                                : 'bg-gray-100 text-gray-700 hover:bg-gray-200'
                                                }`}
                                        >
                                            Show All
                                        </button>
                                    </div>

                                    <div className="relative">
                                        <div className="overflow-x-auto">
                                            <div className="max-h-[60vh] overflow-y-auto custom-scrollbar">
                                                <table className="w-full">
                                                    <thead className="bg-gray-50 sticky top-0 z-10">
                                                        <tr>
                                                            <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider bg-gray-50">
                                                                Date
                                                            </th>
                                                            <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider bg-gray-50">
                                                                Status
                                                            </th>
                                                            <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider bg-gray-50">
                                                                Delivery Method
                                                            </th>
                                                            <th className="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider bg-gray-50">
                                                                Total Amount
                                                            </th>
                                                            <th className="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider bg-gray-50">
                                                                Actions
                                                            </th>
                                                        </tr>
                                                    </thead>
                                                    <tbody className="bg-white divide-y divide-gray-200">
                                                        {getFilteredTransactions().map((transaction) => (
                                                            <tr
                                                                key={transaction.id}
                                                                className="hover:bg-gray-50 cursor-pointer"
                                                                onClick={() => handleTransactionClick(transaction)}
                                                            >
                                                                <td className="px-6 py-4 whitespace-nowrap">
                                                                    {new Date(transaction.created_at).toLocaleString()}
                                                                </td>
                                                                <td className="px-6 py-4 whitespace-nowrap">
                                                                    <span className={`px-2 inline-flex text-xs leading-5 font-semibold rounded-full ${transaction.status === 'Completed'
                                                                        ? 'bg-green-100 text-green-800'
                                                                        : 'bg-red-100 text-red-800'
                                                                        }`}>
                                                                        {transaction.status}
                                                                    </span>
                                                                </td>
                                                                <td className="px-6 py-4 whitespace-nowrap">
                                                                    {transaction.delivery_method ? 'Delivery' : 'Pick-up'}
                                                                </td>
                                                                <td className="px-6 py-4 whitespace-nowrap text-right">
                                                                    ₱{transaction.total_order}
                                                                </td>
                                                                <td className="px-6 py-4 whitespace-nowrap text-center">
                                                                    <div className="flex justify-center space-x-2">
                                                                        <button
                                                                            className="text-[#6b4226] hover:text-[#3a2117]"
                                                                            onClick={(e) => {
                                                                                e.stopPropagation();
                                                                                handleTransactionClick(transaction);
                                                                            }}
                                                                        >
                                                                            View Details
                                                                        </button>
                                                                        {transaction.status === 'Completed' && (
                                                                            <button
                                                                                className="text-red-600 hover:text-red-800"
                                                                                onClick={(e) => handleCancelClick(transaction.id, e)}
                                                                            >
                                                                                Cancel
                                                                            </button>
                                                                        )}
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                        ))}
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div className="lg:w-1/3">
                                <div className="bg-white rounded-lg shadow-lg p-6">
                                    <h3 className="text-xl font-bold text-[#4b3025] mb-4">Summary</h3>
                                    <div className="space-y-4">
                                        <div className="flex justify-between items-center">
                                            <span className="text-gray-600">Total Sales:</span>
                                            <span className="font-bold text-[#6b4226]">
                                                ₱{calculateTotalSales().toFixed(2)}
                                            </span>
                                        </div>
                                        <div className="flex justify-between items-center">
                                            <span className="text-gray-600">Total Items Sold:</span>
                                            <span className="font-bold text-[#6b4226]">
                                                {calculateTotalItems()}
                                            </span>
                                        </div>
                                        <div className="flex justify-between items-center">
                                            <span className="text-gray-600">Completed Orders:</span>
                                            <span className="font-bold text-green-600">
                                                {getCompletedCount()}
                                            </span>
                                        </div>
                                        <div className="flex justify-between items-center">
                                            <span className="text-gray-600">Cancelled Orders:</span>
                                            <span className="font-bold text-red-600">
                                                {getCancelledCount()}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            )}

            <TransactionModal
                isOpen={isModalOpen}
                onClose={() => {
                    setIsModalOpen(false);
                    setSelectedTransaction(null);
                }}
                transaction={selectedTransaction}
            />

            {/* Cancel Confirmation Modal */}
            {isCancelModalOpen && (
                <div className="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
                    <div className="bg-white rounded-lg p-6 max-w-md w-full mx-4">
                        <h3 className="text-xl font-bold text-[#4b3025] mb-4">Cancel Transaction</h3>
                        <p className="text-gray-600 mb-6">
                            Are you sure you want to cancel this transaction? This action cannot be undone.
                        </p>
                        <div className="flex justify-end gap-4">
                            <button
                                onClick={handleCancelClose}
                                className="px-4 py-2 text-gray-600 hover:text-gray-800 transition-colors"
                            >
                                No, Keep Transaction
                            </button>
                            <button
                                onClick={handleCancelConfirm}
                                className="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors"
                            >
                                Yes, Cancel Transaction
                            </button>
                        </div>
                    </div>
                </div>
            )}

            <style jsx>{`
                .custom-scrollbar::-webkit-scrollbar {
                    width: 8px;
                    height: 8px;
                }

                .custom-scrollbar::-webkit-scrollbar-track {
                    background: #f1f1f1;
                    border-radius: 4px;
                }

                .custom-scrollbar::-webkit-scrollbar-thumb {
                    background: #888;
                    border-radius: 4px;
                }

                .custom-scrollbar::-webkit-scrollbar-thumb:hover {
                    background: #555;
                }

                .custom-scrollbar {
                    scrollbar-width: thin;
                    scrollbar-color: #888 #f1f1f1;
                }
            `}</style>
        </div>
    );
}
