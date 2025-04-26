import { useState, useEffect } from 'react';

interface Product {
    product_id: string;
    product_name: string;
    product_price: number;
    product_stock: number;
    product_image: string;
    quantity: number;
}

export const useSales = () => {
    const [products, setProducts] = useState<Product[]>([]);
    const [cart, setCart] = useState<Product[]>([]);
    const [loading, setLoading] = useState(true);
    const [error, setError] = useState<string | null>(null);
    const [showReceipt, setShowReceipt] = useState(false);
    const [currentTransaction, setCurrentTransaction] = useState<any>(null);

    const fetchProducts = async () => {
        try {
            const token = localStorage.getItem('token');
            const userId = localStorage.getItem('userId');

            if (!token || !userId) {
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

            if (!response.ok) {
                throw new Error('Failed to fetch products');
            }

            const data = await response.json();
            console.log('API Response:', data);

            setProducts(data);
            setError(null);
        } catch (err) {
            console.error('Error details:', err);
            setError(err instanceof Error ? err.message : 'An error occurred');
        } finally {
            setLoading(false);
        }
    };

    const addToCart = (product: Product) => {
        const existingItem = cart.find(item => item.product_id === product.product_id);

        const currentQuantity = existingItem ? existingItem.quantity : 0;
        if (currentQuantity + 1 > product.product_stock) {
            alert('Not enough stock available!');
            return;
        }

        if (existingItem) {
            setCart(cart.map(item =>
                item.product_id === product.product_id
                    ? { ...item, quantity: item.quantity + 1 }
                    : item
            ));
        } else {
            setCart([...cart, { ...product, quantity: 1 }]);
        }
    };

    const handleCheckout = async () => {
        try {
            const token = localStorage.getItem('token');
            const userId = localStorage.getItem('userId');

            if (!token || !userId) {
                setError('Authentication required');
                return;
            }

            const orderData = {
                user_id: userId,
                order_list: cart.map(item => ({
                    product_id: item.product_id,
                    name: item.product_name,
                    quantity: item.quantity,
                    price: item.product_price
                })),
                total_order: cart.reduce((total, item) => total + (item.product_price * item.quantity), 0),
                quantity: cart.reduce((total, item) => total + item.quantity, 0)
            };

            const response = await fetch('http://localhost:8000/api/createSales', {
                method: 'POST',
                headers: {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                    'Authorization': `Bearer ${token}`
                },
                body: JSON.stringify(orderData)
            });

            if (!response.ok) {
                throw new Error('Failed to create transaction');
            }

            const data = await response.json();
            setCurrentTransaction({
                ...orderData,
                date: new Date().toLocaleString(),
                message: "Thank you for choosing Don Macchiatos!",
                footer: "Please come again"
            });
            setShowReceipt(true);
            setCart([]);
            await fetchProducts(); // Refresh products to update stock
        } catch (err) {
            console.error('Checkout error:', err);
            setError(err instanceof Error ? err.message : 'An error occurred during checkout');
        }
    };

    useEffect(() => {
        fetchProducts();
    }, []);

    return {
        products: products || [],
        cart,
        setCart,
        loading,
        error,
        addToCart,
        handleCheckout,
        showReceipt,
        setShowReceipt,
        currentTransaction
    };
};
