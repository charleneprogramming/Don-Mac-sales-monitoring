import { useState, useEffect } from 'react';
import { TransactionDetail } from '@/context/transactioncontext';

export function useTransactions() {
    const [transactions, setTransactions] = useState<TransactionDetail[]>([]);
    const [loading, setLoading] = useState(true);
    const [error, setError] = useState<string | null>(null);

    useEffect(() => {
        const fetchTransactions = async () => {
            try {
                const token = localStorage.getItem('token');
                const userId = localStorage.getItem('userId');

                if (!token || !userId) {
                    throw new Error('Authentication required');
                }

                const response = await fetch(`http://localhost:8000/api/sales/transactions/${userId}`, {
                    headers: {
                        'Accept': 'application/json',
                        'Content-Type': 'application/json',
                        'Authorization': `Bearer ${token}`
                    }
                });

                if (!response.ok) {
                    throw new Error('Failed to fetch transactions');
                }

                const data = await response.json();
                setTransactions(data);
                setLoading(false);
            } catch (err) {
                setError(err instanceof Error ? err.message : 'Failed to fetch transactions');
                setLoading(false);
            }
        };

        fetchTransactions();
    }, []);

    return { transactions, loading, error };
}
