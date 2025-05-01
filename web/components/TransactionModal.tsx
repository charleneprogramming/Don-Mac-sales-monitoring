import React from 'react';

interface OrderItem {
    product_id: string;
    name: string;
    quantity: number;
    price: number;
    totalPrice?: number;
}

interface TransactionModalProps {
    isOpen: boolean;
    onClose: () => void;
    transaction: {
        id: string;
        order_list: OrderItem[] | null;
        total_order: string;
        status?: string;
        delivery_method: string | boolean;
        created_at: string;
        date?: string; // Add support for direct date string
        merchant_fee?: number;
    } | null;
}

const TransactionModal: React.FC<TransactionModalProps> = ({ isOpen, onClose, transaction }) => {
    if (!isOpen || !transaction) return null;

    // Parse order_list if it's a string
    const orderList = typeof transaction.order_list === 'string'
        ? JSON.parse(transaction.order_list)
        : transaction.order_list;

    // Calculate subtotal
    const subtotal = orderList?.reduce((sum: number, item: OrderItem) => {
        return sum + (Number(item.price) * Number(item.quantity));
    }, 0) || 0;

    // Determine delivery method and merchant fee
    const isDelivery = typeof transaction.delivery_method === 'string'
        ? transaction.delivery_method === 'For Delivery'
        : Boolean(transaction.delivery_method);

    // Calculate merchant fee based on delivery method
    const merchantFee = transaction.merchant_fee !== undefined
        ? transaction.merchant_fee
        : (isDelivery ? 25 : 0);

    // Calculate total amount
    const totalAmount = subtotal + merchantFee;

    // Format date
    const formatDate = () => {
        try {
            if (transaction.date) {
                return transaction.date;
            }
            const date = new Date(transaction.created_at);
            if (isNaN(date.getTime())) {
                return new Date().toLocaleString(); // Fallback to current date if invalid
            }
            return date.toLocaleString();
        } catch (error) {
            return new Date().toLocaleString(); // Fallback to current date if error
        }
    };

    const handlePrint = () => {
        const printContent = document.getElementById('printable-content');
        if (printContent) {
            const originalContents = document.body.innerHTML;
            document.body.innerHTML = printContent.innerHTML;
            window.print();
            document.body.innerHTML = originalContents;
            window.location.reload(); // Reload to restore React functionality
        }
    };

    return (
        <div className="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
            <div className="bg-white rounded-lg w-full max-w-2xl max-h-[90vh] overflow-y-auto">
                <div id="printable-content">
                    <div className="sticky top-0 bg-[#6b4226] text-white px-6 py-4 rounded-t-lg flex justify-between items-center">
                        <h2 className="text-xl font-semibold">Transaction Details</h2>
                        <div className="flex gap-2">
                            <button
                                onClick={handlePrint}
                                className="text-white hover:text-gray-200 transition-colors px-4 py-2 bg-blue-500 rounded"
                            >
                                Print
                            </button>
                            <button
                                onClick={onClose}
                                className="text-white hover:text-gray-200 transition-colors"
                            >
                                <svg className="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>
                    </div>

                    <div className="p-6">
                        <div className="mb-6">
                            <div className="flex justify-between items-center mb-4">
                                <span className="text-gray-600">Transaction ID:</span>
                                <span className="font-semibold">{transaction.id}</span>
                            </div>
                            <div className="flex justify-between items-center mb-4">
                                <span className="text-gray-600">Date:</span>
                                <span className="font-semibold">{formatDate()}</span>
                            </div>
                            {transaction.status && (
                                <div className="flex justify-between items-center mb-4">
                                    <span className="text-gray-600">Status:</span>
                                    <span className={`px-3 py-1 rounded-full text-sm font-semibold ${transaction.status === 'Completed'
                                        ? 'bg-green-100 text-green-800'
                                        : 'bg-red-100 text-red-800'
                                        }`}>
                                        {transaction.status}
                                    </span>
                                </div>
                            )}
                            <div className="flex justify-between items-center mb-4">
                                <span className="text-gray-600">Delivery Method:</span>
                                <span className="font-semibold">
                                    {typeof transaction.delivery_method === 'string'
                                        ? transaction.delivery_method
                                        : isDelivery ? 'For Delivery' : 'For Pick Up'}
                                </span>
                            </div>
                        </div>

                        <div className="border-t pt-6">
                            <h3 className="text-lg font-semibold mb-4">Order Details</h3>
                            {orderList && orderList.length > 0 ? (
                                <div className="bg-[#e5f6fd] rounded-lg overflow-hidden">
                                    <table className="w-full">
                                        <thead>
                                            <tr className="bg-[#cceefb]">
                                                <th className="px-4 py-3 text-left">Product Name</th>
                                                <th className="px-4 py-3 text-right">Price</th>
                                                <th className="px-4 py-3 text-right">Quantity</th>
                                                <th className="px-4 py-3 text-right">Subtotal</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            {orderList.map((item: OrderItem, index: number) => {
                                                const price = Number(item.price);
                                                const quantity = Number(item.quantity);
                                                const total = price * quantity;

                                                return (
                                                    <tr key={index} className="border-b border-[#cceefb]">
                                                        <td className="px-4 py-3">{item.name}</td>
                                                        <td className="px-4 py-3 text-right">₱{price.toFixed(2)}</td>
                                                        <td className="px-4 py-3 text-right">{quantity}</td>
                                                        <td className="px-4 py-3 text-right">₱{total.toFixed(2)}</td>
                                                    </tr>
                                                );
                                            })}
                                            <tr className="border-t border-[#cceefb]">
                                                <td colSpan={3} className="px-4 py-3 text-right font-semibold">Subtotal:</td>
                                                <td className="px-4 py-3 text-right font-semibold">₱{subtotal.toFixed(2)}</td>
                                            </tr>
                                            <tr>
                                                <td colSpan={3} className="px-4 py-3 text-right">
                                                    Merchant Fee: {!isDelivery && '(No charge for pick up)'}
                                                </td>
                                                <td className="px-4 py-3 text-right">₱{merchantFee.toFixed(2)}</td>
                                            </tr>
                                            <tr className="bg-[#cceefb]">
                                                <td colSpan={3} className="px-4 py-3 text-right font-bold">Total Amount:</td>
                                                <td className="px-4 py-3 text-right font-bold">₱{totalAmount.toFixed(2)}</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            ) : (
                                <div className="text-center text-gray-500 py-8 bg-gray-50 rounded-lg">
                                    <svg
                                        className="w-12 h-12 mx-auto mb-4 text-gray-400"
                                        fill="none"
                                        stroke="currentColor"
                                        viewBox="0 0 24 24"
                                    >
                                        <path
                                            strokeLinecap="round"
                                            strokeLinejoin="round"
                                            strokeWidth={2}
                                            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"
                                        />
                                    </svg>
                                    <p className="text-lg">No order details available</p>
                                </div>
                            )}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    );
};

export default TransactionModal; 