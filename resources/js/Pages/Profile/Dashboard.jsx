import { Head, Link, usePage } from "@inertiajs/react";
import LayoutProfile from "../../Layouts/LayoutProfile";

const Dashboard = () => {
    const { order_in_progress, total_order, total_purchase } = usePage().props;

    return (
        <LayoutProfile title="Dashboard" breadcrumb={[{ title: "Dashboard", path: route("profile.index") }]}>
            <Head title="Profile" />

            {/* Stats Section */}
            <div className="grid grid-cols-3 gap-4 text-center">
                <div className="p-4 bg-blue-100 rounded-lg shadow">
                    <h3 className="text-lg font-semibold">Order In Progress</h3>
                    <p className="text-xl font-bold text-blue-600">{order_in_progress}</p>
                </div>
                <div className="p-4 bg-green-100 rounded-lg shadow">
                    <h3 className="text-lg font-semibold">Total Orders</h3>
                    <p className="text-xl font-bold text-green-600">{total_order}</p>
                </div>
                <div className="p-4 bg-yellow-100 rounded-lg shadow">
                    <h3 className="text-lg font-semibold">Total Spent</h3>
                    <p className="text-xl font-bold text-yellow-600">BDT{total_purchase}</p>
                </div>
            </div>

            {/* Dashboard Info */}
            <p className="mt-6 text-gray-700">
                Manage your  
                <Link href={route("profile.orders")} className="font-bold text-blue-600 hover:underline mx-1">
                    orders
                </Link>,  
                <Link href={route("profile.account-details")} className="font-bold text-blue-600 hover:underline mx-1">
                    account details
                </Link>, and  
                <Link href={route("profile.referrals")} className="font-bold text-blue-600 hover:underline mx-1">
                    referral rewards
                </Link>.
            </p>
        </LayoutProfile>
    );
};

export default Dashboard;
