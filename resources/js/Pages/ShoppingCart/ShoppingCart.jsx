import SectionList from "@/Components/Sections/SectionList";
import Layout from "@/Layouts/Layout";
import CartProduct from "./CartProduct";
import PrimaryButton from "@/Components/PrimaryButton";
import { Head, Link, useForm } from "@inertiajs/react";
import { formatCurrency } from "@/Helpers/helpers";

const ShoppingCart = ({ products, total }) => {
    const { get, processing } = useForm();
    const handleClickCheckout = () => {
        get(route("checkout.add-shopping-cart"));
    };
    // console.log(total);
    return (
        <Layout>
            <Head title="My cart" />
            <div className="container relative">
                <div className="space-y-4 max-w-5xl mx-auto">
                    <SectionList title="My cart">
                        <div className=" divide-y divide-gray-200 ">
                            {products.map((product) => (
                                <CartProduct
                                    cardProduct={product}
                                    key={product.ref}
                                />
                            ))}
                        </div>
                        {products.length ? (
                            <>
                                <div className="py-6  text-right border-y border-t border-b border-gray-200">
                                    <div className="inline-block space-y-3">
                                        <div className=" grid grid-cols-2 gap-x-4">
                                            <div className="text-gray-600 ">
                                                Sub total
                                            </div>
                                            <div className=" font-medium">
                                                {formatCurrency(
                                                    total.sub_total
                                                )}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div className="text-right mt-6 ">
                                    <PrimaryButton
                                        onClick={handleClickCheckout}
                                        isLoading={processing}
                                        disabled={processing}
                                    >
                                        Buy now
                                    </PrimaryButton>
                                </div>
                            </>
                        ) : (
                            <span>Cart is empty</span>
                        )}
                    </SectionList>
                </div>
            </div>
        </Layout>
    );
};

export default ShoppingCart;
