import { formatCurrency } from "@/Helpers/helpers";
import React from "react";

const ProductPriceOffer = ({ price, old_price, offer, color }) => {
    return (
        <div className="w-full">
            <div className="flex items-center text-xs text-gray-500">
                <span className="line-through">
                    {formatCurrency(old_price)}
                </span>
                <span className="ml-auto">{color}</span>
            </div>
            <div className="flex items-center">
                <div className="text-md inline-block mr-2 font-semibold">
                    {formatCurrency(price)}
                </div>
                {offer > 0 && (
                    <div className="inline-block text-green-500 text-xs font-semibold">
                        {offer}%
                    </div>
                )}
            </div>
        </div>
    );
};

export default ProductPriceOffer;
