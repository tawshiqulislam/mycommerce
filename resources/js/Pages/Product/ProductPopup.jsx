import React, { createContext, useEffect, useState } from "react";
import { Link, useForm, usePage } from "@inertiajs/react";
import ImagesProduct from "./ImagesProduct";
import Description from "./Description";
import TitlePrice from "./TitlePrice";
import SelectSkuSize from "@/Pages/Product/Variants/SelectSkuSize";
import SelectQuantity from "@/Pages/Product/Variants/SelectQuantity";
import ButtonsProcessing from "@/Pages/Product/Variants/ButtonsProcessing";

const ProductPopup = ({ data, onClose }) => {
    const [selectedSkuSize, setSelectedSkuSize] = useState(null);
    const form = useForm({
        quantity: 1,
        skuId: null,
    });
    useEffect(() => {
        let newSelectedSkuSize = data.skus.find((sku) => {
            return sku.stock > 0;
        });

        if (newSelectedSkuSize) {
            setSelectedSkuSize(newSelectedSkuSize);
        }
    }, []);
    useEffect(() => {
        if (selectedSkuSize) {
            form.setData((data) => ({
                ...data,
                quantity: 1,
                skuId: selectedSkuSize.id,
            }));
        } else {
            form.setData((data) => ({
                ...data,
                quantity: 1,
                skuId: null,
            }));
        }
    }, [selectedSkuSize]);

    return (
        <div className="fixed top-0 left-0 w-full h-full flex items-center justify-center bg-gray-800 bg-opacity-75 z-50">
            <div className="bg-white p-6 rounded-lg shadow-lg max-w-2xl w-full relative overflow-y-auto max-h-100">
                <button
                    onClick={onClose}
                    className="absolute top-2 right-2 text-2xl"
                >
                    &times;
                </button>
                <div className="w-full">
                    <div className="flex">
                        <div className="w-[70%]">
                            <TitlePrice product={data} />
                        </div>
                        <div className="w-[30%] mt-2">
                            <img src={data.img} alt="" />
                        </div>
                    </div>
                    <div className="space-y-6">
                        {data.skus.length > 1 && (
                            <SelectSkuSize
                                skuSizes={data.skus}
                                selectedSkuSize={selectedSkuSize}
                                setSelectedSkuSize={setSelectedSkuSize}
                            />
                        )}
                        <SelectQuantity
                            maxQuantity={data.max_quantity}
                            selectedSkuSize={selectedSkuSize}
                            form={form}
                        />
                        <ButtonsProcessing form={form} />
                    </div>
                </div>
            </div>
        </div>
    );
};

export default ProductPopup;
