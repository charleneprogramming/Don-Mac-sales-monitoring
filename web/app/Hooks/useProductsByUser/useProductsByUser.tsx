import { useState, useEffect, useCallback } from 'react';

interface Product {
    product_id: string;
    product_name: string;
    description: string;
    product_price: number;
    product_stock: number;
    product_image: string;
}

export const useProductsByUser = (userId: string) => {
    const [products, setProducts] = useState<Product[]>([]);
    const [loading, setLoading] = useState(true);
    const [error, setError] = useState<string | null>(null);

    const fetchProducts = useCallback(async () => {
        if (!userId || userId === '') {
            setLoading(false);
            return;
        }

        try {
            const token = localStorage.getItem('token');

            if (!token) {
                setError('Authentication required');
                setLoading(false);
                return;
            }

            const response = await fetch(`http://localhost:8000/api/products/${userId}`, {
                headers: {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                    'Authorization': `Bearer ${token}`
                }
            });

            if (response.status === 401) {
                localStorage.removeItem('token');
                setError('Session expired. Please login again');
                setLoading(false);
                return;
            }

            if (!response.ok) {
                const text = await response.text();
                let errorMessage;
                try {
                    const errorData = JSON.parse(text);
                    errorMessage = errorData.message || errorData.exception || `HTTP error! status: ${response.status}`;
                } catch (e) {
                    errorMessage = `Server error (${response.status}): ${text}`;
                }
                throw new Error(errorMessage);
            }

            const text = await response.text();
            const data = JSON.parse(text);

            console.log(data);

            setProducts(data.products || []);
            setError(null);
        } catch (err) {
            console.error('Error fetching products:', err);
            setError(err instanceof Error ? err.message : 'An error occurred');
        } finally {
            setLoading(false);
        }
    }, [userId]);

    useEffect(() => {
        fetchProducts();
    }, [userId, fetchProducts]);

    return { products, loading, error, refreshProducts: fetchProducts };
};
