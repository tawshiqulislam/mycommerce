import React, { useState, useEffect } from "react";
import { toast } from "react-hot-toast";
import { Inertia } from "@inertiajs/inertia";

export default function OrderForm({
    buyerPhone,
    setBuyerPhone,
    orderProducts,
    setOrderProducts,
    setShowForm,
    vat,
    vat_negation,
}) {
    const [grandTotal, setGrandTotal] = useState(0);

    useEffect(() => {
        const total = orderProducts.reduce(
            (sum, product) => sum + product.total,
            0
        );
        setGrandTotal(total);
    }, [orderProducts]);

    const handleQuantityChange = (index, quantity) => {
        const updatedProducts = orderProducts.map((product, i) =>
            i === index
                ? {
                      ...product,
                      quantity: parseInt(quantity),
                      total: product.price * quantity,
                  }
                : product
        );
        setOrderProducts(updatedProducts);
    };

    const handleRemoveProduct = (index) => {
        const updatedProducts = orderProducts.filter(
            (product, i) => i !== index
        );
        setOrderProducts(updatedProducts);
    };

    const handleSubmit = (event) => {
        toast.dismiss();
        event.preventDefault();
        if (orderProducts.length === 0) {
            toast.error("Please add at least one product.");
            return;
        }
        const data = {
            buyer_phone: buyerPhone,
            products: orderProducts.map(
                ({ name, color, quantity, price, total }) => ({
                    name,
                    color,
                    quantity,
                    price,
                    total,
                })
            ),
        };
        Inertia.post(route("pos.store"), data);
    };

    return (
        <form className="mt-4" onSubmit={handleSubmit}>
            <div className="mb-4">
                <div className="flex w-full justify-between text-xs text-gray-500">
                    <h3>Total:</h3> <span>৳{grandTotal.toFixed(2)}</span>
                </div>
                <div className="flex w-full justify-between text-xs text-gray-500">
                    <h3>VAT (+{vat}%):</h3>{" "}
                    <span>৳{((grandTotal * vat) / 100).toFixed(2)}</span>
                </div>
                <div className="flex w-full justify-between text-xs text-gray-500">
                    <h3>Discount (-{vat_negation}%):</h3>{" "}
                    <span>
                        ৳{((grandTotal * vat_negation) / 100).toFixed(2)}
                    </span>
                </div>
                <div className="flex w-full justify-between">
                    <h3>Total Payable:</h3>{" "}
                    <span>
                        ৳
                        {(
                            grandTotal +
                            (grandTotal * vat) / 100 -
                            (grandTotal * vat_negation) / 100
                        ).toFixed(2)}
                    </span>
                </div>
            </div>
            <input
                type="text"
                value={buyerPhone}
                onChange={(e) => setBuyerPhone(e.target.value)}
                placeholder="Enter buyer phone"
                className="w-full mb-4 rounded border border-gray-300 px-3 py-2"
                required
            />
            {orderProducts.map((product, index) => (
                <div key={index} className="mb-4">
                    <p>{product.name}</p>
                    <input
                        type="number"
                        value={product.quantity}
                        onChange={(e) =>
                            handleQuantityChange(index, e.target.value)
                        }
                        className="w-full rounded border border-gray-300 px-3 py-2 mb-2"
                        min="1"
                        required
                    />
                    <div className="flex justify-between items-center">
                        <p className="mr-4">Total: {product.total}</p>
                        <button
                            type="button"
                            className="btn text-cart hover:bg-gray-50 border border-gray-100"
                            onClick={() => handleRemoveProduct(index)}
                        >
                            Remove
                        </button>
                    </div>
                </div>
            ))}
            <div className="flex justify-between items-center">
                <button type="submit" className="btn btn-primary">
                    Submit
                </button>
                <button
                    type="button"
                    className="btn btn-secondary"
                    onClick={() => setShowForm(false)}
                >
                    Cancel
                </button>
            </div>
        </form>
    );
}
