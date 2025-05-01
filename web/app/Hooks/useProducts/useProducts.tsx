import { useState, useEffect, useCallback } from 'react';

interface Product {
    product_id: string;
    product_name: string;
    description: string;
    product_price: number;
    product_stock: number;
    product_image: string;
}

export const useProducts = () => {
    const [products, setProducts] = useState<Product[]>([]);
    const [loading, setLoading] = useState(true);
    const [error, setError] = useState<string | null>(null);

    const fetchProducts = useCallback(async () => {
        setLoading(true);
        try {
            const token = localStorage.getItem('token');

            const response = await fetch(`http://localhost:8000/api/products`, {
                headers: {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                    ...(token && { 'Authorization': `Bearer ${token}` })
                }
            });

            if (response.status === 401) {
                localStorage.removeItem('token');
                throw new Error('Session expired. Please login again');
            }

            if (!response.ok) {
                const text = await response.text();
                try {
                    const errorData = JSON.parse(text);
                    throw new Error(errorData.message || errorData.exception || `HTTP error! status: ${response.status}`);
                } catch {
                    throw new Error(`Server error (${response.status}): ${text}`);
                }
            }

            const { products } = await response.json();
            setProducts(products || []);
            setError(null);
        } catch (err) {
            console.error('Error fetching products:', err);
            setError(err instanceof Error ? err.message : 'An unknown error occurred');
        } finally {
            setLoading(false);
        }
    }, []);

    useEffect(() => {
        fetchProducts();
    }, [fetchProducts]);

    return { products, loading, error, refreshProducts: fetchProducts };
};
