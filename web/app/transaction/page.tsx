'use client';
import React, { useState, useEffect } from 'react';
import { useRouter } from 'next/navigation';
import Navbar from '../../components/Navbar';

interface Transaction {
    id: string;
    date: string;
    order_list: Array<{
        product_id: string;
        name: string;
        quantity: number;
        price: number;
        totalPrice?: number;
    }>;
    total_order: number;
    status: 'Completed';
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
    const router = useRouter();

    const fetchTransactions = async () => {
        try {
            const token = localStorage.getItem('token');
            const userId = localStorage.getItem('userId');

            if (!token || !userId) {
                setError('Authentication required');
                return;
            }

            const response = await fetch(`http://localhost:8000/api/sales/${userId}`, {
                headers: {
                    'Authorization': `Bearer ${token}`,
                    'Accept': 'application/json'
                }
            });

            if (!response.ok) {
                throw new Error('Failed to fetch transactions');
            }

            const data = await response.json();
            console.log('Raw API response:', data); // Debug log

            // Check if data exists and has the expected structure
            if (!data || !Array.isArray(data)) {
                throw new Error('Invalid data structure received from API');
            }

            // Parse the order_list for each transaction
            const parsedTransactions = data.map(transaction => ({
                ...transaction,
                order_list: typeof transaction.order_list === 'string'
                    ? JSON.parse(transaction.order_list)
                    : transaction.order_list,
                status: 'Completed' // Add default status if not present
            }));

            console.log('Parsed transactions:', parsedTransactions); // Debug log
            setTransactions(parsedTransactions);
        } catch (err) {
            console.error('Fetch error:', err);
            setError(err instanceof Error ? err.message : 'Failed to load transactions');
        } finally {
            setLoading(false);
        }
    };

    useEffect(() => {
        fetchTransactions();
    }, []);

    const calculateTotalSales = () => {
        return transactions.reduce((total, trans) => {
            if (!Array.isArray(trans.order_list)) return total;
            return total + (trans.total_order || 0);
        }, 0);
    };

    const calculateTotalItems = () => {
        return transactions.reduce((total, trans) => {
            if (!Array.isArray(trans.order_list)) return total;
            return total + trans.order_list.reduce((sum, item) => sum + (item.quantity || 0), 0);
        }, 0);
    };

    const filteredTransactions = transactions
        .filter(trans => {
            if (!Array.isArray(trans.order_list)) return false;

            // Format the transaction date to match display format
            const transactionDate = new Date(trans.created_at).toLocaleString('en-US', {
                month: 'long',
                day: 'numeric',
                year: 'numeric',
                hour: '2-digit',
                minute: '2-digit',
                hour12: true
            });

            // Convert search term to lowercase for case-insensitive comparison
            const searchLower = searchTerm.toLowerCase();

            // Search by formatted date or product name
            return transactionDate.toLowerCase().includes(searchLower) ||
                trans.order_list.some(item =>
                    item && item.name && item.name.toLowerCase().includes(searchLower)
                );
        })
        .sort((a, b) => {
            const dateA = new Date(a.created_at).getTime();
            const dateB = new Date(b.created_at).getTime();
            return sortOrder === 'newest' ? dateB - dateA : dateA - dateB;
        });

    return (
        <div className="min-h-screen bg-[#faf2e9]">
            {/* Navbar always visible */}
            <Navbar />

            {/* Loading State */}
            {loading ? (
                <div className="flex items-center justify-center h-screen">
                    <div className="text-xl text-[#4b3025]">Loading transactions...</div>
                </div>
            ) : error ? (
                <div className="flex items-center justify-center h-screen">
                    <div className="text-xl text-red-500">{error}</div>
                </div>
            ) : (
                // Main Content
                <div className="container mx-auto px-4 py-8">
                      <div className="container mx-auto">
                {/* Add Back Button */}
                <div className="mb-6">
                    <button
                        onClick={() => router.push('/homepage')}
                        className="bg-[#6b4226] text-white px-4 py-2 rounded-lg hover:bg-[#3a2117] transition-colors flex items-center gap-2"
                    >
                        ← Back to Homepage
                    </button>
                </div>

                <div className="flex flex-col lg:flex-row gap-8">
                    {/* Transaction List Section */}
                    <div className="lg:w-2/3">
                        <div className="bg-white rounded-lg shadow-lg p-6">
                            <div className="flex justify-between items-center mb-6">
                                <h2 className="text-2xl font-bold text-[#4b3025]">Transaction History</h2>
                                <div className="flex gap-4">
                                    <input
                                        type="text"
                                        placeholder="Search by product name or date (e.g., December 13, 2024)"
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

                            {filteredTransactions.map((transaction) => (
                                <div key={transaction.id} className="bg-white rounded-lg shadow-lg p-6 mb-6 hover:shadow-xl transition-shadow">
                                    <div className="flex justify-between items-center border-b pb-4">
                                        <div className="flex flex-col">
                                            <span className="bg-green-500 text-white px-4 py-1 rounded-full text-sm inline-block mb-2 w-fit">
                                                {transaction.status}
                                            </span>
                                            <span className="text-gray-600 text-sm">
                                                {new Date(transaction.created_at).toLocaleString('en-PH', {
                                                    year: 'numeric',
                                                    month: 'long',
                                                    day: 'numeric',
                                                    hour: '2-digit',
                                                    minute: '2-digit',
                                                    hour12: true,
                                                    timeZone: 'Asia/Manila'
                                                })}
                                            </span>
                                        </div>
                                        <div className="text-right">
                                            <div className="text-sm text-gray-600 mb-1">Total Amount</div>
                                            <div className="text-2xl font-bold text-[#4b3025]">
                                                ₱{transaction.total_order.toFixed(2)}
                                            </div>
                                        </div>
                                    </div>

                                    <div className="mt-4">
                                        <h4 className="font-semibold mb-3 text-[#6b4226]">Items Purchased</h4>
                                        <div className="space-y-3">
                                            {transaction.order_list.map((item, index) => (
                                                <div key={index} className="flex justify-between items-center bg-[#fff8e7] p-3 rounded-lg">
                                                    <div className="flex-1">
                                                        <div className="font-medium">{item.name}</div>
                                                        <div className="text-sm text-gray-600">
                                                            Qty: {item.quantity} × ₱{item.price.toFixed(2)}
                                                        </div>
                                                    </div>
                                                    <div className="font-semibold text-[#4b3025]">
                                                        ₱{(item.quantity * item.price).toFixed(2)}
                                                    </div>
                                                </div>
                                            ))}
                                        </div>
                                        <div className="mt-4 text-right text-gray-600">
                                            Total Items: {transaction.order_list.reduce((sum, item) => sum + item.quantity, 0)}
                                        </div>
                                    </div>
                                </div>
                            ))}
                        </div>
                    </div>

                    {/* Transaction Summary Section */}
                    <div className="lg:w-1/3">
                        <div className="bg-white rounded-lg shadow-lg">
                            <div className="bg-[#6b4226] text-white p-4 rounded-t-lg">
                                <h3 className="text-xl font-bold">Transaction Summary</h3>
                            </div>
                            <div className="p-4">
                                <div className="bg-[#fff8e7] rounded-lg p-4 mb-4">
                                    <h4 className="text-[#6b4226] text-lg mb-2">Total Transactions</h4>
                                    <p className="text-2xl font-bold">{transactions.length}</p>
                                </div>

                                <div className="bg-[#fff8e7] rounded-lg p-4 mb-4">
                                    <h4 className="text-[#6b4226] text-lg mb-2">Total Revenue</h4>
                                    <p className="text-2xl font-bold">₱{calculateTotalSales().toFixed(2)}</p>
                                </div>

                                <div className="bg-[#fff8e7] rounded-lg p-4">
                                    <h4 className="text-[#6b4226] text-lg mb-2">Total Items Sold</h4>
                                    <p className="text-2xl font-bold">{calculateTotalItems()}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                </div>
                </div>
            )}
        </div>
    );
}
