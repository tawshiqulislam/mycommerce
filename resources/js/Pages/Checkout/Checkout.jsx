import Layout from "@/Layouts/Layout";
import { Head, useForm } from "@inertiajs/react";
import ShippingAddress from "./ShippingAddress";
import OrderSummary from "./OrderSummary";
import CheckoutProvider from "@/Components/Context/CheckoutProvider";

const Checkout = ({
    products,
    total,
    shippings,
    referral_discount,
    max_percentage,
}) => {
    return (
        <Layout>
            <Head title="Checkout" />
            <CheckoutProvider>
                <div className="py-content container">
                    <div className="lg:flex lg:gap-x-16 ">
                        <div className="w-full lg:w-6/12 xl:w-6/12 2xl:w-7/12">
                            <div className="max-w-2xl mx-auto">
                                <form className="divide-y">
                                    <div className="pb-8">
                                        <div>
                                            <ShippingAddress />
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <div className="w-full lg:w-6/12 xl:w-6/12 2xl:w-5/12">
                            <h3 className="block  font-medium  text-lg mb-4">
                                Order summary
                            </h3>
                            <div>
                                <OrderSummary
                                    products={products}
                                    total={total}
                                    shippings={shippings}
                                    referral_discount={referral_discount}
                                    max_percentage={max_percentage}
                                />
                            </div>
                        </div>
                    </div>
                </div>
            </CheckoutProvider>
        </Layout>
    );
};

export default Checkout;
