import React, { useState } from "react";
import { createPortal } from "react-dom";
import { formatCurrency } from "@/Helpers/helpers";
import { Link } from "@inertiajs/react";
import Badge from "../Badge";
import ProductPriceOffer from "../ProductPriceOffer";
import ProductPopup from "@/Pages/Product/ProductPopup";

const CardProduct = ({ product }) => {
    const [popupData, setPopupData] = useState(null);
    const fetchPopupData = () => {
        fetch(
            route("product_popup", {
                slug: product.slug,
                ref: product.ref,
            })
        )
            .then((response) => response.json())
            .then((data) => {
                setPopupData(data);
            })
            .catch((error) =>
                console.error("Error fetching product popup data:", error)
            );
    };
    const closePopup = () => {
        setPopupData(null);
    };

    return (
        <>
            <div className="w-full relative block max-w-md mx-auto group h-full overflow-hidden rounded-lg move-up hover:shadow border border-product_border">
                <div className="h-full flex flex-col">
                    <Link
                        className="flex justify-center"
                        href="#"
                        onClick={(e) => {
                            e.preventDefault();
                            fetchPopupData();
                        }}
                    >
                        <img
                            src={product.thumb}
                            alt={product.slug}
                            className="w-full object-cover object-top group-hover:rounded-none"
                        />
                    </Link>
                    <Link
                        className="h-full flex flex-col p-4 space-y-4 bg-product"
                        href={route("product", {
                            slug: product.slug,
                            ref: product.ref,
                        })}
                    >
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
                    </Link>
                </div>
            </div>
            {popupData &&
                createPortal(
                    <ProductPopup data={popupData} onClose={closePopup} />,
                    document.body
                )}
        </>
    );
};

export default CardProduct;
