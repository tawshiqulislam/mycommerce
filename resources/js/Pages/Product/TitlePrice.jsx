import { formatCurrency } from "@/Helpers/helpers";
import React from "react";

const TitlePrice = ({ product }) => {
    return (
        <>
            <span className="text-sm text-gray-400">
                New | {product.stock} Available
            </span>
            <h2 className="font-bold text-xl sm:text-3xl mt-2">
                {product.name}
            </h2>
            <h2 className="text-md sm:text-xl mt-2">{product.color.name}</h2>

            <span className="text-gray-400 block mt-2 text-sm ">
                REF {product.ref}
            </span>

            <div className="mt-6 tracking-tight">
                <span className="text-xs text-gray-400 font-medium line-through">
                    {formatCurrency(product.old_price)}
                </span>

                <div className="flex items-center font-medium">
                    <div className="text-3xl inline-block mr-2 font-semibold">
                        {formatCurrency(product.price)}
                    </div>

                    <div className="inline-block text-green-500  ">
                        {product.offer}%
                    </div>
                </div>
            </div>
            <p className="mt-4 mb-10">{product.entry}</p>
        </>
    );
};

export default TitlePrice;
