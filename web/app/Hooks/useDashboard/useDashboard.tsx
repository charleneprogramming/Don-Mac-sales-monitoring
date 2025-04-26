import { useState, useEffect } from 'react';

interface DashboardData {
    dailySales: {
        date: string;
        total_sales: number;
        transaction_count: number;
    }[];
    weeklySales: {
        date: string;
        total_sales: number;
        transaction_count: number;
    }[];
    monthlySales: {
        date: string;
        total_sales: number;
        transaction_count: number;
    }[];
    yearlySales: {
        date: string;
        total_sales: number;
        transaction_count: number;
    }[];
}

export const useDashboard = () => {
    const [dashboardData, setDashboardData] = useState<DashboardData | null>(null);
    const [loading, setLoading] = useState(true);
    const [error, setError] = useState<string | null>(null);

    const fetchDashboardData = async () => {
        try {
            const response = await fetch('http://localhost:8000/api/dashboard');
            if (!response.ok) {
                throw new Error('Failed to fetch dashboard data');
            }
            const data = await response.json();
            setDashboardData(data);
            setLoading(false);
        } catch (err) {
            setError(err instanceof Error ? err.message : 'An error occurred');
            setLoading(false);
        }
    };

    useEffect(() => {
        fetchDashboardData();
    }, []);

    return { dashboardData, loading, error, refreshDashboard: fetchDashboardData };
};
