import React from 'react';

interface ReceiptProps {
    transaction: any;
    onClose: () => void;
    onPrint: () => void;
}

export const Receipt: React.FC<ReceiptProps> = ({ transaction, onClose, onPrint }) => {
    return (
        <div className="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
            <div className="bg-white rounded-lg shadow-xl max-w-md w-full mx-4">
                <div className="p-6">
                    <div className="flex justify-between items-center mb-4">
                        <h2 className="text-2xl font-bold text-[#4b3025]">Transaction Receipt</h2>
                        <span className="text-2xl text-[#6b4226]">🧾</span>
                    </div>

                    <div className="mb-4">
                        <h3 className="text-xl font-bold text-[#4b3025]">Don Macchiatos</h3>
                        <p className="text-gray-600">Fuel your day, one cup at a time</p>
                        <p className="text-sm text-gray-500">{transaction.date}</p>
                    </div>

                    <div className="border-t border-b py-4 my-4">
                        <table className="w-full">
                            <thead>
                                <tr className="text-left">
                                    <th>Item</th>
                                    <th>Qty</th>
                                    <th>Price</th>
                                    <th>Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                {transaction.order_list.map((item: any) => (
                                    <tr key={item.product_id}>
                                        <td>{item.name}</td>
                                        <td>{item.quantity}</td>
                                        <td>₱{item.price.toFixed(2)}</td>
                                        <td>₱{(item.price * item.quantity).toFixed(2)}</td>
                                    </tr>
                                ))}
                            </tbody>
                        </table>
                    </div>

                    <div className="text-right mb-6">
                        <p className="text-lg font-bold">Total Amount: ₱{transaction.total_order.toFixed(2)}</p>
                    </div>

                    <div className="text-center text-gray-600">
                        <p>{transaction.message}</p>
                        <p>{transaction.footer}</p>
                    </div>

                    <div className="flex justify-between mt-6">
                        <button
                            onClick={onPrint}
                            className="bg-blue-500 text-white px-6 py-2 rounded hover:bg-blue-600"
                        >
                            Print Receipt
                        </button>
                        <button
                            onClick={onClose}
                            className="bg-red-500 text-white px-6 py-2 rounded hover:bg-red-600"
                        >
                            Close
                        </button>
                    </div>
                </div>
            </div>
        </div>
    );
};