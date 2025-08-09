import { useState } from "react";
import { formatCurrency, formatDate } from "../../Helpers/helpers";

import LayoutProfile from "../../Layouts/LayoutProfile";
import OrderStatuBadges from "@/Components/OrderStatuBadges";
import { Head, Link } from "@inertiajs/react";
import Pagination from "@/Components/Pagination";
import Badge from "@/Components/Badge";

const Order = ({ orders }) => {
    const [page, setPage] = useState(1);
    // console.log(orders);
    const handleClickChangePage = (number) => {
        setPage(number);
    };

    return (
        <LayoutProfile
            title="Orders"
            breadcrumb={[
                {
                    title: "Orders",
                    path: route("profile.orders"),
                },
            ]}
        >
            <Head title="Orders" />

            <div className="space-y-2">
                <table className="table-list">
                    <thead>
                        <tr>
                            <th>Code</th>
                            <th>Status</th>
                            <th>Products</th>
                            <th>Total</th>
                            <th>Date</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        {orders.data.map((item, key) => (
                            <tr key={key}>
                                <td>
                                    <span className="font-medium">
                                        #{item.code}
                                    </span>
                                </td>

                                <td>
                                    <Badge color={item.status_color}>
                                        {item.status}
                                    </Badge>
                                </td>
                                <td>{item.quantity}</td>

                                <td>
                                    <span className="font-medium">
                                        {formatCurrency(item.total)}
                                    </span>
                                </td>
                                <td>
                                    {formatDate(item.created_at)}
                                    <span className="text-gray-500 block text-xs    ">
                                        {item.createdAtRelative}
                                    </span>
                                </td>
                                <td className="px-4  text-start">
                                    <Link
                                        preserveScroll
                                        className="font-medium text-indigo-600"
                                        href={route("profile.order", item.code)}
                                    >
                                        Details
                                    </Link>
                                </td>
                            </tr>
                        ))}
                    </tbody>
                </table>
                <div>
                    <div className="mt-8">
                        {orders.meta.total > orders.meta.per_page && (
                            <div className="mt-8">
                                <Pagination paginator={orders.meta} />
                            </div>
                        )}
                    </div>
                </div>
            </div>
        </LayoutProfile>
    );
};

export default Order;
