import React, { useState } from "react";
import { router } from "@inertiajs/react";
import { formatCurrency } from "@/Helpers/helpers";

function OrderItemsList({ order }) {

    const [showPopup, setShowPopup] = useState(false);
    const [refundStatus, setRefundStatus] = useState(null);
    const [selectedProductId, setSelectedProductId] = useState(null);

    // Check if order is defined
    if (!order || !order.products) {
        return <div>Loading...</div>; // Or any fallback UI
    }

    const handleRefundClick = (productId, e) => {
        e.preventDefault(); // Prevent the default link behavior

        setSelectedProductId(productId);
        setShowPopup(true); // Show the confirmation popup
    };

    const handleRefundConfirm = () => {
        // Construct the URL with order code and product ID
        const refundUrl = `/profile/refund/${order.code}/${selectedProductId}`;
        console.log(order.code, selectedProductId);
        // Proceed with refund request
        router.get(refundUrl, {
            onSuccess: () => {
                setRefundStatus("Refund requested successfully!");
                setShowPopup(false);
            },
            onError: (errors) => {
                setRefundStatus("Refund request failed. Please try again.");
                setShowPopup(false);
            },
        });
    };


    const closePopup = () => {
        setShowPopup(false);
        setRefundStatus(null);
        setSelectedProductId(null);
    };

    return (
        <div>
            {/* Popup for refund confirmation */}
            {showPopup && (
                <div className="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50">
                    <div className="p-6 bg-white rounded-lg shadow-lg">
                        <p>Are you sure you want to request a refund for this product?</p>
                        <div className="flex mt-4 gap-x-3">
                            <button
                                className="px-4 py-2 text-white bg-red-500 rounded hover:bg-red-600"
                                onClick={handleRefundConfirm}
                            >
                                Confirm
                            </button>
                            <button
                                className="px-4 py-2 text-white bg-gray-500 rounded hover:bg-gray-600"
                                onClick={closePopup}
                            >
                                Cancel
                            </button>
                        </div>
                    </div>
                </div>
            )}

            {/* Popup for refund status */}
            {refundStatus && (
                <div className="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50">
                    <div className="p-6 bg-white rounded-lg shadow-lg">
                        <p>{refundStatus}</p>
                        <button
                            className="px-4 py-2 mt-4 text-white bg-blue-500 rounded hover:bg-blue-600"
                            onClick={closePopup}
                        >
                            Close
                        </button>
                    </div>
                </div>
            )}

            <table className="table-list">
                <thead>
                    <tr>
                        <th>Image</th>
                        <th>Item</th>
                        <th>Price</th>
                        <th>Quantity</th>
                        <th>Amount</th>
                        {order.status === "Successful" && (
                            <th>Action</th>
                        )}
                    </tr>
                </thead>
                <tbody>
                    {order.products.map((product, index) => (
                        <tr key={index}>
                            <td className="whitespace-nowrap">
                                <img
                                    className="h-16 max-w-full rounded"
                                    src={product.thumb}
                                    alt={product.name}
                                />
                            </td>
                            <td className="align-top">
                                {product.name}
                                <div
                                    key={index}
                                    className="flex gap-x-1.5 text-xs mt-1 text-gray-500"
                                >
                                    <div>{product.color}</div>
                                </div>
                            </td>
                            <td className="whitespace-nowrap">
                                <PriceOffer
                                    price={product.price}
                                    old_price={product.old_price}
                                    offer={product.offer}
                                />
                            </td>
                            <td>{product.quantity}</td>
                            <td className="whitespace-nowrap">
                                {formatCurrency(product.total)}
                            </td>
                            <td>
                                {order.status === "Successful" && product.product_details.featured === 1 &&
                                    (() => {
                                        // Find the refund related to this product
                                        const refund = order.refunds.find((r) => r.product_id === product.id);

                                        if (!refund) {
                                            // No refund exists, show the refund button
                                            return (
                                                <button
                                                    className="px-3 py-1 text-white bg-red-500 rounded hover:bg-red-600"
                                                    onClick={(e) => handleRefundClick(product.id, e)}
                                                >
                                                    Refund
                                                </button>
                                            );
                                        } else {
                                            // Refund exists, check status
                                            if (refund.status === 0) {
                                                return <span className="text-yellow-500 text-wrap">Refund Requested</span>;
                                            } else if (refund.status === 1) {
                                                return <span className="text-red-500 text-wrap">Refund Rejected</span>;
                                            } else if (refund.status === 2) {
                                                return <span className="text-green-500 text-wrap">Refund Approved</span>;
                                            }
                                        }
                                    })()}
                            </td>
                        </tr>
                    ))}
                </tbody>
            </table>
        </div>
    );
}

function PriceOffer({ price, old_price, offer }) {
    return (
        <div className="text-sm">
            <span>{formatCurrency(price)}</span>
            {offer > 0 && (
                <div className="flex gap-x-1">
                    <div className="inline-block text-xs font-semibold text-green-500">
                        -{offer}%
                    </div>
                    <div className="text-xs text-gray-400 line-through">
                        {formatCurrency(old_price)}
                    </div>
                </div>
            )}
        </div>
    );
}

export default OrderItemsList;
