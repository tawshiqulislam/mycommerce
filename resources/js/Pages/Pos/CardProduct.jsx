import React from "react";
import { formatCurrency } from "@/Helpers/helpers";
import ProductPriceOffer from "@/Components/ProductPriceOffer";

const CardProduct = ({ product, onAddProduct }) => {
    return (
        <div
            className="w-full relative block max-w-md mx-auto group h-full overflow-hidden rounded-lg move-up hover:shadow border border-product_border cursor-pointer"
            onClick={() => onAddProduct(product)}
        >
            <div className="h-full flex flex-col">
                <div className="flex justify-center">
                    <img
                        src={product.thumb}
                        alt={product.slug}
                        className="w-full object-cover object-top group-hover:rounded-none"
                    />
                </div>
                <div className="h-full flex flex-col p-4 space-y-4 bg-product">
                    <h2
                        className="text-heading text-sm md:text-sm line-clamp-2"
                        alt={product.name}
                        title={product.name}
                    >
                        {product.name}
                    </h2>
                    <div className="flex items-end w-full">
                        <ProductPriceOffer
                            color={product.color}
                            price={product.price}
                            old_price={product.old_price}
                            offer={product.offer}
                        />
                    </div>
                </div>
            </div>
        </div>
    );
};

export default CardProduct;
