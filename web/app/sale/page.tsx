'use client';
import React, { useState } from 'react';
import { useRouter } from 'next/navigation';
import { useSales } from '../Hooks/useSales/useSales';
import Navbar from '../../components/Navbar';
import Image from 'next/image';
import TransactionModal from '../../components/TransactionModal';

export default function Sales(): JSX.Element {
    const { products, cart, loading, error, addToCart, setCart, handleCheckout, showReceipt, setShowReceipt, currentTransaction } = useSales();

    console.log('Products:', products);

    const calculateSubtotal = () => {
        return cart.reduce((total, item) => total + (item.product_price * item.quantity), 0);
    };

    const calculateTotal = () => {
        const subtotal = calculateSubtotal();
        const merchantFee = enabled ? 25.00 : 0.00; // 25 pesos for delivery, 0 for pickup
        return subtotal + merchantFee;
    };

    const updateQuantity = (productId: string, newQuantity: number) => {
        const product = products.find(p => p.product_id === productId);
        if (!product) return;

        if (newQuantity > product.product_stock) {
            alert('Not enough stock available!');
            return;
        }

        if (newQuantity <= 0) {
            setCart(cart.filter(item => item.product_id !== productId));
            return;
        }

        setCart(cart.map(item =>
            item.product_id === productId
                ? { ...item, quantity: newQuantity }
                : item
        ));
        console.log(products);
    };

    const removeFromCart = (productId: string) => {
        setCart(cart.filter(item => item.product_id !== productId));
    };

    const [enabled, setEnabled] = useState(false);

    return (
        <div className="min-h-screen bg-[#faf2e9] py-4">
            {/* Navbar always visible */}
            <Navbar />

            {/* Loading State */}
            {loading ? (
                <div className="flex justify-center items-center h-screen">Loading Beverages...</div>
            ) : error ? (
                <div className="flex justify-center items-center h-screen flex-col gap-4">
                    <div className="text-red-500">Error: {error}</div>
                </div>
            ) : (
                // Main Content
                <div className="container mx-auto mt-20">
                    <div className="flex flex-col lg:flex-row justify-between items-start">
                        {/* Debug Info (Left Side) */}
                        <div className="flex-[2]">
                            <h1 className="text-3xl font-bold text-center text-['#9a4c2e'] mb-6">SALES INTERFACE</h1>
                            <div className="grid grid-cols-3 gap-[5px]">
                                {products && products.map((product) => (
                                    <div key={product.product_id}
                                        className="p-[20px] border border-[#9a4c2e] flex flex-col h-full"
                                    >
                                        <div className="relative w-[200px] h-[200px] border-b border-gray-200 mx-auto">
                                            <Image
                                                src={`http://localhost:8000/images/${product.product_image}`}
                                                alt={product.product_name}
                                                fill
                                                className="object-cover"
                                                sizes="(max-width: 768px) 50vw, (max-width: 1200px) 25vw, 20vw"
                                            />
                                        </div>
                                        <div className="p-4 text-center">
                                            <h2 className="text-lg font-bold text-[#4b3025] mb-2">{product.product_name}</h2>
                                            <p className="text-[#6b4226] text-md font-bold mb-3">₱{product.product_price?.toFixed(2)}</p>
                                            <button
                                                onClick={() => addToCart(product)}
                                                className="w-full bg-[#6b4226] text-white py-2 rounded hover:bg-[#4b3025] transition-colors"
                                            >
                                                <i className="fa-solid fa-cart-plus"></i> Add to cart
                                            </button>
                                        </div>
                                    </div>
                                ))}
                            </div>
                        </div>

                        {/* Add To Cart (Right Side) */}
                        <div className="flex-1 rounded-lg shadow-lg p-6">
                            <div className="justify-between flex">
                                <h2 className="text-2xl font-bold text-[#4b3025] mb-4">Your Cart</h2>
                                <div className="flex items-center space-x-4">
                                    <span className="text-[#4b3025] font-semibold">
                                        {enabled ? 'For Delivery' : 'For Pickup'}
                                    </span>
                                    <button
                                        onClick={() => setEnabled(!enabled)}
                                        className={`w-12 h-6 flex items-center rounded-full p-1 transition duration-300 ${enabled ? 'bg-yellow-400' : 'bg-gray-300'
                                            }`}
                                    >
                                        <div
                                            className={`bg-white w-4 h-4 rounded-full shadow-md transform transition-transform duration-300 ${enabled ? 'translate-x-6' : 'translate-x-0'
                                                }`}
                                        ></div>
                                    </button>
                                </div>
                            </div>
                            <div className="overflow-x-auto">
                                <table className="w-full mb-4">
                                    <thead>
                                        <tr>
                                            <th className="text-left text-[#4b3025]">Product</th>
                                            <th className="text-left text-[#4b3025]">Quantity</th>
                                            <th className="text-left text-[#4b3025]">Price</th>
                                            <th className="text-left text-[#4b3025]">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        {cart.map((item) => (
                                            <tr key={item.product_id}>
                                                <td className="text-[#4b3025]">{item.product_name}</td>
                                                <td className="text-[#4b3025]">
                                                    <input
                                                        type="number"
                                                        value={item.quantity}
                                                        onChange={(e) => updateQuantity(item.product_id, parseInt(e.target.value))}
                                                        className="w-16 border border-gray-300 rounded px-2 py-1"
                                                    />
                                                </td>
                                                <td className="text-[#4b3025]">₱{(item.product_price * item.quantity).toFixed(2)}</td>
                                                <td>
                                                    <button
                                                        onClick={() => removeFromCart(item.product_id)}
                                                        className="text-red-500 hover:underline"
                                                    >
                                                        Remove
                                                    </button>
                                                </td>
                                            </tr>
                                        ))}
                                    </tbody>
                                </table>
                                <div className="text-right">
                                    <p className="text-lg font-semibold text-[#4b3025] mb-2">Subtotal: ₱{calculateSubtotal().toFixed(2)}</p>
                                    <p className="text-md text-[#4b3025] mb-2">
                                        Merchant Fee: ₱{(enabled ? 25.00 : 0.00).toFixed(2)}
                                        <span className="text-sm text-gray-500 ml-2">
                                            ({enabled ? 'Delivery charge' : 'No charge for pickup'})
                                        </span>
                                    </p>
                                    <p className="text-xl font-bold text-[#4b3025] mb-4">
                                        Total: ₱{calculateTotal().toFixed(2)}
                                    </p>
                                    <button
                                        onClick={() => handleCheckout(enabled)}
                                        className="mt-4 bg-[#6b4226] text-white px-4 py-2 rounded hover:bg-[#4b3025] transition-colors"
                                        disabled={cart.length === 0}
                                    >
                                        Checkout
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            )}
            {showReceipt && currentTransaction && (
                <TransactionModal
                    isOpen={showReceipt}
                    onClose={() => setShowReceipt(false)}
                    transaction={currentTransaction}
                />
            )}
        </div>
    );
}
