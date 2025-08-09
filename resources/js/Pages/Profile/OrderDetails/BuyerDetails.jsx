import { formatDate } from "@/Helpers/helpers";
import React from "react";

function BuyerDetails({ order }) {
    return (
        <div className="grid lg:grid-cols-4 gap-6 ">
            <OrderDetailsList title="Client name">
                <div className="block ">{order.user.name}</div>
            </OrderDetailsList>

            <OrderDetailsList title="Order code">{order.code}</OrderDetailsList>

            {/* <OrderDetailsList title="Moneda" >
						USD - US Dollar
					</OrderDetailsList> */}

            <OrderDetailsList title="Order date">
                {formatDate(order.created_at)}
                <span className="text-gray-500 block">
                    {order.createdAtRelative}
                </span>
            </OrderDetailsList>

            <OrderDetailsList title="Payment method">
                {order.payment.method}
            </OrderDetailsList>

            <OrderDetailsList
                title="Shipping address"
                className="lg:col-span-2"
            >
                {/* <span className='block'>{order.user.email}</span>
                <span className='block'>{order.user.phone}</span> */}
                <span className="block">{order.user.city}</span>
                {/* <span className='block'>{order.user.postalCode}</span> */}
                <address className="not-italic font-normal">
                    {order.user.address}
                </address>
            </OrderDetailsList>

            <OrderDetailsList title="Deliver to" className="lg:col-span-2">
                <span className="inline-block mr-2">{order.user.name}</span>
                <span className="inline-block">+{order.user.phone}</span>
                <span className="block">{order.user.email}</span>
                {/* <span className='block'>{order.user.postalCode}</span> */}
            </OrderDetailsList>

            {order.user.note && (
                <OrderDetailsList
                    title="Aditional note"
                    className="lg:col-span-full"
                >
                    {order.user.note}
                </OrderDetailsList>
            )}
        </div>
    );
}

const OrderDetailsList = ({ title, children, ...props }) => {
    return (
        <dl {...props}>
            <dt className=" text-sm font-medium text-gray-800">{title}:</dt>
            <dd className="text-gray-600 mt-1 text-sm">{children}</dd>
        </dl>
    );
};

export default BuyerDetails;
