import { useState, useContext } from "react";
import { formatCurrency } from "../../Helpers/helpers";
import PrimaryButton from "@/Components/PrimaryButton";
import { router, useForm } from "@inertiajs/react";
import CardProductSummary from "./CardProductSummary";
import { CheckoutContext } from "@/Components/Context/CheckoutProvider";

const OrderSummary = ({
    products,
    total,
    shippings,
    referral_discount,
    max_percentage,
}) => {
    const [selectedShipping, setSelectedShipping] = useState("");
    const [applyDiscount, setApplyDiscount] = useState(false);
    const { userForm } = useContext(CheckoutContext);
    const [isCashOnDeliveryLoading, setIsCashOnDeliveryLoading] =
        useState(false);
    const [isSSLCommerzLoading, setIsSSLCommerzLoading] = useState(false);

    const handleShippingChange = (e) => {
        const shippingValue = e.target.value;
        setSelectedShipping(shippingValue);
        router.post(route("set-shipping"), { shipping: shippingValue });
    };

    const handleSubmit = (e) => {
        e.preventDefault();
        setIsCashOnDeliveryLoading(true);
        userForm.post(route("purchase"), {
            preserveScroll: true,
            onSuccess: () => setIsCashOnDeliveryLoading(false),
            onError: () => setIsCashOnDeliveryLoading(false),
        });
    };

    const handleSubmitSSLCommerz = (e) => {
        e.preventDefault();
        setIsSSLCommerzLoading(true);
        userForm.post(route("ssl.purchase"), {
            preserveScroll: true,
            onSuccess: () => setIsSSLCommerzLoading(false),
            onError: () => setIsSSLCommerzLoading(false),
        });
    };

    const max_percentage_discount = Math.round(
        (total.sub_total * max_percentage) / 100
    );

    const applicable_discount = Math.min(
        referral_discount,
        max_percentage_discount
    );

    const handleDiscountChange = (e) => {
        const applyDiscount = e.target.checked;
        setApplyDiscount(applyDiscount);
        router.post(route("set-discount"), {
            referral_discount: applyDiscount ? applicable_discount : 0,
        });
    };

    const totalAfterDiscount =
        total.total - (applyDiscount ? applicable_discount : 0);

    return (
        <div>
            <div className="bg-gray-100 rounded-lg text-sm font-medium border divide-y">
                {products.map((item, index) => (
                    <CardProductSummary key={index} product={item} />
                ))}
                <div className="p-5 md:p-6 space-y-4 sm:space-y-4">
                    <div className="flex items-center justify-between">
                        <div className="text-gray-600">Subtotal</div>
                        <div>{formatCurrency(total.sub_total)}</div>
                    </div>
                    <div className="flex items-center justify-between">
                        <div className="text-gray-600">
                            <div className="text-xs text-gray-400">
                                *maximum applicable
                            </div>
                            Referral discount
                        </div>
                        <div className="flex items-center justify-between w-1/3">
                            <label className="inline-flex items-center cursor-pointer">
                                <input
                                    type="checkbox"
                                    value=""
                                    className="sr-only peer"
                                    checked={applyDiscount}
                                    onChange={handleDiscountChange}
                                />
                                <div className="relative w-11 h-6 bg-gray-200 rounded-full peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-primary"></div>
                                <span className="ms-3 text-sm font-medium text-gray-900 dark:text-gray-300">
                                    Apply
                                </span>
                            </label>
                            <div>-{formatCurrency(applicable_discount)}</div>
                        </div>
                    </div>
                    <div className="flex flex-col sm:flex-row items-start justify-between">
                        <div className="text-gray-600 mb-2 sm:mb-0">
                            Shipping
                        </div>
                        <div>
                            <select
                                className="bg-white rounded text-sm w-full"
                                value={selectedShipping}
                                onChange={handleShippingChange}
                            >
                                <option value="" disabled>
                                    Delivery options
                                </option>
                                {Object.entries(shippings).map(
                                    ([cost, location]) => (
                                        <option key={location} value={cost}>
                                            {location} ({formatCurrency(cost)})
                                        </option>
                                    )
                                )}
                            </select>
                        </div>
                    </div>
                </div>
                <div className="p-5 md:p-6 flex items-center justify-between pt-6 text-base border-t font-medium">
                    <div className="text-gray-600">Total</div>
                    <div>
                        {formatCurrency(
                            totalAfterDiscount +
                                parseFloat(selectedShipping || 0)
                        )}
                    </div>
                </div>
            </div>
            <div className="flex flex-row gap-2 sm:col-span-6">
                <PrimaryButton
                    className="w-1/2 mt-4"
                    onClick={handleSubmit}
                    isLoading={isCashOnDeliveryLoading}
                    disabled={
                        isCashOnDeliveryLoading ||
                        userForm.processing ||
                        !selectedShipping
                    }
                >
                    Cash on Delivery
                </PrimaryButton>
                <PrimaryButton
                    className="w-1/2 mt-4"
                    onClick={handleSubmitSSLCommerz}
                    isLoading={isSSLCommerzLoading}
                    disabled={
                        isSSLCommerzLoading ||
                        userForm.processing ||
                        !selectedShipping
                    }
                >
                    SSLCommerz
                </PrimaryButton>
            </div>
        </div>
    );
};

export default OrderSummary;
