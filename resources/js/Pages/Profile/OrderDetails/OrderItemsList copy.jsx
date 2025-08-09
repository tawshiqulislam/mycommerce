import { formatCurrency } from "@/Helpers/helpers";
import React from "react";

function OrderItemsList({ order }) {
    return (
        <div>
            <table className="table-list">
                <thead>
                    <tr>
                        <th>Image</th>
                        <th>Item</th>
                        <th>Price</th>
                        <th>Quantity</th>
                        <th>Amount</th>
                        <th>Action</th> {/* // for refund product */}
                    </tr>
                </thead>
                <tbody>
                    {order.products.map((product, index) => (
                        <tr key={index}>
                            <td className="whitespace-nowrap">
                                <img
                                    className="h-16 max-w-full rounded "
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
                                {/* {formatCurrency(product.price)} */}
                            </td>
                            <td>{product.quantity}</td>
                            <td className="whitespace-nowrap">
                                {formatCurrency(product.total)}
                            </td>
                            <td>
                                {order.status === "Successful" && (
                                    <button
                                        className="bg-red-500 text-white px-3 py-1 rounded hover:bg-red-600"
                                        onClick={() => handleRefund(product.id)}
                                    >
                                        Refund
                                    </button>
                                )}
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
                    <div className="inline-block text-green-500 text-xs font-semibold">
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
