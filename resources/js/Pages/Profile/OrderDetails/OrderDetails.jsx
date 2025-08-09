import { CheckCircleIcon } from "@heroicons/react/24/solid";
import { Head, usePage } from "@inertiajs/react";
import LayoutProfile from "../../../Layouts/LayoutProfile";
import SectionTitle from "@/Components/Sections/SectionTitle";
import { ArrowDownTrayIcon } from "@heroicons/react/24/outline";
import BuyerDetails from "./BuyerDetails";
import OrderItemsList from "./OrderItemsList";
import OrderTotalPrice from "./OrderTotalPrice";
import Badge from "@/Components/Badge";

const OderDetails = ({ order }) => {
    const { flash } = usePage().props;

    // 🛑 Prevent errors by checking if `order` is defined
    if (!order) {
        return <div className="text-center py-4">Loading order details...</div>;
    }

    return (
        <LayoutProfile>
            <Head title={"Order #" + order.code} />

            <div className="space-y-8">
                <div className="flex items-center justify-between ">
                    <SectionTitle className="flex items-center">
                        <span>Order: # {order.code}</span>
                        <Badge className="ml-3" color={order.status_color}>
                            {order.status}
                        </Badge>
                    </SectionTitle>
                </div>

                {flash.success && (
                    <div className="bg-green-100 p-4 rounded-md">
                        <CheckCircleIcon className="h-6 w-6 text-green-400" />
                        <span className="text-green-700 font-semibold">
                            {flash.success}
                        </span>
                    </div>
                )}

                {flash.error && (
                    <div className="bg-red-100 p-4 rounded-md">
                        <span className="text-red-700 font-semibold">
                            {flash.error}
                        </span>
                    </div>
                )}

                <BuyerDetails order={order} />
                <OrderItemsList order={order} />
                <OrderTotalPrice order={order} />
            </div>
        </LayoutProfile>
    );
};

export default OderDetails;

