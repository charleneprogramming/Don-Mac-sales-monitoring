'use client';

import { useDashboard } from '../Hooks/useDashboard/useDashboard';
import { Line } from 'react-chartjs-2';
import {
    Chart as ChartJS,
    CategoryScale,
    LinearScale,
    PointElement,
    LineElement,
    Title,
    Tooltip,
    Legend,
    Filler,
} from 'chart.js';
import Link from 'next/link';
import { BiArrowBack } from 'react-icons/bi';
import { BsGraphUp } from 'react-icons/bs';
import { AiOutlineClockCircle, AiOutlineCalendar } from 'react-icons/ai';
import { FiCalendar } from 'react-icons/fi';
import { HiOutlineChartBar } from 'react-icons/hi';
import { HiOutlineDownload } from 'react-icons/hi';


ChartJS.register(
    CategoryScale,
    LinearScale,
    PointElement,
    LineElement,
    Title,
    Tooltip,
    Legend,
    Filler
);

export default function Dashboard() {
    const { dashboardData, loading, error } = useDashboard();

    if (loading) {
        return (
            <div className="flex justify-center items-center min-h-screen bg-gradient-to-br from-[#fff8e7] to-[#fff2d6]">
                <div className="animate-spin rounded-full h-12 w-12 border-t-2 border-b-2 border-[#6b4226]"></div>
            </div>
        );
    }

    if (error) {
        return (
            <div className="flex justify-center items-center min-h-screen bg-gradient-to-br from-[#fff8e7] to-[#fff2d6]">
                <div className="text-[#6b4226]">Error loading dashboard data</div>
            </div>
        );
    }

    return (
        <div className="min-h-screen bg-gradient-to-br from-[#fff8e7] to-[#fff2d6] p-8">
            {/* Back Button */}
            <Link
                href="/homepage"
                className="inline-flex items-center text-[#8B5E34] hover:text-[#6b4226] mb-6 transition-colors"
            >
                <BiArrowBack className="mr-2" size={24} />
                Back to Home
            </Link>

            {/* Header Section */}
            <div className="text-center mb-12">
                <h2 className="text-[#6b4226] text-4xl font-bold mb-3 flex items-center justify-center">
                    <BsGraphUp className="mr-3 text-[#8B5E34]" size={24} />
                    Sales Analytics Dashboard
                </h2>
                <p className="text-gray-600 text-lg">Track your business performance and sales trends</p>
            </div>

            {/* Stats Cards Row */}
            <div className="flex flex-wrap justify-center gap-6 mb-12">
                <StatCard
                    title="Today's Sales"
                    amount={Number(dashboardData?.dailySales[0]?.total_sales || 0)}
                    icon="clock"
                    iconComponent={<AiOutlineClockCircle size={24} className="text-[#8B5E34]" />}
                />
                <StatCard
                    title="Weekly Sales"
                    amount={dashboardData?.weeklySales?.reduce((acc, curr) => acc + Number(curr.total_sales), 0) || 0}
                    icon="calendar-week"
                    iconComponent={<AiOutlineCalendar size={24} className="text-[#8B5E34]" />}
                />
                <StatCard
                    title="Monthly Sales"
                    amount={dashboardData?.monthlySales?.reduce((acc, curr) => acc + Number(curr.total_sales), 0) || 0}
                    icon="calendar-alt"
                    iconComponent={<FiCalendar size={24} className="text-[#8B5E34]" />}
                />
                <StatCard
                    title="Yearly Sales"
                    amount={dashboardData?.yearlySales?.reduce((acc, curr) => acc + Number(curr.total_sales), 0) || 0}
                    icon="chart-bar"
                    iconComponent={<HiOutlineChartBar size={24} className="text-[#8B5E34]" />}
                />
            </div>

            {/* Charts Layout */}
            <div className="max-w-[1800px] mx-auto space-y-8">
                {/* First Row */}
                <div className="flex gap-8">
                    <div className="flex-1">
                        <ChartCard
                            title="Daily Sales Trend"
                            subtitle=""
                            data={{
                                labels: dashboardData?.dailySales?.map(sale => sale.date) ?? [],
                                datasets: [{
                                    label: 'Daily Sales',
                                    data: dashboardData?.dailySales?.map(sale => sale.total_sales) ?? [],
                                    borderColor: '#8B5E34',
                                    backgroundColor: 'rgba(139, 94, 52, 0.1)',
                                    fill: true,
                                    tension: 0.4
                                }]
                            }}
                            options={chartOptions}
                        />
                    </div>
                    <div className="flex-1">
                        <ChartCard
                            title="Weekly Sales Trend"
                            subtitle=""
                            data={{
                                labels: dashboardData?.weeklySales?.map(sale => sale.date) ?? [],
                                datasets: [{
                                    label: 'Weekly Sales',
                                    data: dashboardData?.weeklySales?.map(sale => sale.total_sales) ?? [],
                                    borderColor: '#8B5E34',
                                    backgroundColor: 'rgba(139, 94, 52, 0.1)',
                                    fill: true,
                                    tension: 0.4
                                }]
                            }}
                            options={chartOptions}
                        />
                    </div>
                </div>

                {/* Second Row */}
                <div className="flex gap-8">
                    <div className="flex-1">
                        <ChartCard
                            title="Monthly Sales Trend"
                            subtitle=""
                            data={{
                                labels: dashboardData?.monthlySales?.map(sale => sale.date) ?? [],
                                datasets: [{
                                    label: 'Monthly Sales',
                                    data: dashboardData?.monthlySales?.map(sale => sale.total_sales) ?? [],
                                    borderColor: '#8B5E34',
                                    backgroundColor: 'rgba(139, 94, 52, 0.1)',
                                    fill: true,
                                    tension: 0.4
                                }]
                            }}
                            options={chartOptions}
                        />
                    </div>
                    <div className="flex-1">
                        <ChartCard
                            title="Yearly Sales Trend"
                            subtitle=""
                            data={{
                                labels: dashboardData?.yearlySales?.map(sale => sale.date) ?? [],
                                datasets: [{
                                    label: 'Yearly Sales',
                                    data: dashboardData?.yearlySales?.map(sale => sale.total_sales) ?? [],
                                    borderColor: '#8B5E34',
                                    backgroundColor: 'rgba(139, 94, 52, 0.1)',
                                    fill: true,
                                    tension: 0.4
                                }]
                            }}
                            options={chartOptions}
                        />
                    </div>
                </div>
            </div>
        </div>
    );
}
const chartOptions = {
    responsive: true,
    plugins: {
        tooltip: {
            mode: 'index',
            intersect: false,
            backgroundColor: 'white',
            titleColor: '#2d1810',
            bodyColor: '#2d1810',
            borderColor: '#e5e7eb',
            borderWidth: 1,
            padding: 8,
            displayColors: false
        }
    },
    scales: {
        y: {
            beginAtZero: true,
            grid: {
                color: 'rgba(0, 0, 0, 0.05)'
            },
            ticks: {
                color: '#6b7280'
            }
        },
        x: {
            grid: {
                display: false
            },
            ticks: {
                color: '#6b7280'
            }
        }
    }
};

interface StatCardProps {
    title: string;
    amount: number;
    icon: string;
    iconComponent: React.ReactNode;
    trend?: number;
}

function StatCard({ title, amount, icon, iconComponent, trend }: StatCardProps) {
    return (
        <div className="bg-white rounded-xl shadow-sm hover:shadow-md transition-all duration-300 min-w-[280px] transform hover:-translate-y-1">
            <div className="p-6">
                <div className="flex items-center justify-between mb-4">
                    <div className="bg-[#f8f3ee] w-12 h-12 rounded-xl flex items-center justify-center">
                        {iconComponent}
                    </div>
                    {typeof trend === 'number' && (
                        <span className="text-green-600 text-sm font-medium bg-green-50 px-3 py-1 rounded-full">
                            <i className="fas fa-arrow-up mr-1" />
                            {trend}
                        </span>
                    )}
                </div>
                <h3 className="text-[#2d1810] text-3xl font-bold mb-2">₱{amount.toFixed(2)}</h3>
                <h6 className="text-[#8B5E34] text-base font-semibold">{title}</h6>
            </div>
        </div>
    );
}


function ChartCard({ title, subtitle, data, options }: {
    title: string;
    subtitle: string;
    data: any;
    options: any;
}) {
    return (
        <div className="bg-white rounded-xl shadow-sm hover:shadow-md transition-all duration-300 p-6 w-full min-h-[400px] flex flex-col">
            <div className="flex justify-between items-center mb-4">
                <div>
                    <h5 className="text-[#2d1810] font-bold text-xl mb-2">{title}</h5>
                    <p className="text-gray-500 text-base">{subtitle}</p>
                </div>
                <button className="text-gray-400 hover:text-[#8B5E34] transition-colors p-2.5 rounded-lg hover:bg-[#f8f3ee]">
                    <i className="fas fa-download" />
                </button>
            </div>
            <div className="flex-1 w-full h-[240px]">
                <Line
                    data={data}
                    options={{
                        ...options,
                        maintainAspectRatio: false,
                    }}
                />
            </div>
        </div>
    );
}