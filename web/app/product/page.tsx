"use client";

import { useProductsByUser } from '../Hooks/useProductsByUser/useProductsByUser';
import { useRouter } from 'next/navigation';
import Image from 'next/image';
import { useEffect, useState } from 'react';

export default function ProductPage() {
    const router = useRouter();
    const [userId, setUserId] = useState<string | null>(null);
    const [isLoading, setIsLoading] = useState(true);
    const [authError, setAuthError] = useState<string | null>(null);

    useEffect(() => {
        let mounted = true;

        const checkAuth = () => {
            try {
                const token = localStorage.getItem('token');
                const storedUserId = localStorage.getItem('userId');

                console.log('Auth check:', { hasToken: !!token, userId: storedUserId });

                if (!token || !storedUserId) {
                    console.log('Missing auth data');
                    setAuthError('Please log in to view products');
                    return;
                }

                if (mounted) {
                    setUserId(storedUserId);
                    setAuthError(null);
                }
            } catch (error) {
                console.error('Auth check failed:', error);
                setAuthError('Failed to check authentication');
            } finally {
                if (mounted) {
                    setIsLoading(false);
                }
            }
        };

        // Wait for next tick to ensure client-side execution
        Promise.resolve().then(checkAuth);

        return () => {
            mounted = false;
        };
    }, []);

    const { products, loading: productsLoading, error: productsError } = useProductsByUser(userId || '');

    // Show loading state while checking auth
    if (isLoading) {
        return <div className="flex justify-center items-center h-screen">Checking authentication...</div>;
    }

    // Show auth error if any
    if (authError) {
        return (
            <div className="flex justify-center items-center h-screen flex-col gap-4">
                <div className="text-red-500">{authError}</div>
                <button
                    onClick={() => router.push('/login')}
                    className="bg-[#6b4226] text-white px-4 py-2 rounded-lg hover:bg-[#3a2117] transition-colors"
                >
                    Go to Login
                </button>
            </div>
        );
    }

    // Show loading state while fetching products
    if (productsLoading) {
        return <div className="flex justify-center items-center h-screen">Loading products...</div>;
    }

    // Show products error if any
    if (productsError) {
        return (
            <div className="flex justify-center items-center h-screen flex-col gap-4">
                <div className="text-red-500">Error: {productsError}</div>
                <button
                    onClick={() => router.push('/homepage')}
                    className="bg-[#6b4226] text-white px-4 py-2 rounded-lg hover:bg-[#3a2117] transition-colors"
                >
                    Return to Homepage
                </button>
            </div>
        );
    }

    return (
        <div className="min-h-screen bg-[#f7f0e3] p-6">
            <div className="max-w-[1400px] mx-auto px-4">
                <div className="text-center mb-8">
                    <div className="relative flex justify-center items-center">
                        <button
                            onClick={() => router.push('/homepage')}
                            className="absolute left-0 bg-[#6b4226] text-white px-4 py-2 rounded-lg hover:bg-[#3a2117] transition-colors"
                        >
                            ← Back
                        </button>
                        <h1 className="text-3xl font-bold text-[#6b4226]">Products</h1>
                    </div>
                </div>

                {products.length === 0 ? (
                    <div className="text-center text-gray-600">No products found</div>
                ) : (
                    <div className="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                        {products.map((product) => (
                            <div key={product.product_id} className="bg-white rounded-lg shadow-lg overflow-hidden">
                                <div className="relative h-[200px] bg-[#fff8e7]">
                                    <Image
                                        src={`http://localhost:8000/images/${product.product_image}`}
                                        alt={product.product_name}
                                        sizes="100%"
                                        style={{ objectFit: 'contain' }}
                                        fill
                                        className="p-4"
                                    />
                                </div>
                                <div className="p-4">
                                    <h2 className="text-lg font-bold text-[#4b3025] mb-2">{product.product_name}</h2>
                                    <p className="text-gray-600 mb-2">{product.description}</p>
                                    <p className="text-[#6b4226] font-bold">₱{product.product_price.toFixed(2)}</p>
                                    <p className="text-gray-600">Stock: {product.product_stock}</p>
                                </div>
                            </div>
                        ))}
                    </div>
                )}
            </div>
        </div>
    );
}
