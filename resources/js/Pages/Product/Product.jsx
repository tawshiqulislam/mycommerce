import Layout from "@/Layouts/Layout";
import { Head, Link } from "@inertiajs/react";
import React from "react";
import ImagesProduct from "./ImagesProduct";
import Description from "./Description";
import CarouselProduct from "./CarouselProduct";
import Breadcrumb from "@/Components/Breadcrumb";
import SectionTitle from "@/Components/Sections/SectionTitle";
import TitlePrice from "./TitlePrice";
import { formatCurrency } from "@/Helpers/helpers";
import Presentations from "./Variants/VariantsProduct";
import VariantsProduct from "./Variants/VariantsProduct";
import ColorVariants from "./Variants/ColorVariants";
import MetaTag from "@/Components/MetaTag";

export default function Product({ product, variants, relatedProducts }) {
    let breadcrumb = [
        {
            title: product.department.name,
            path: route("search", { "departments[]": product.department.id }),
        },
        {
            title: product.category.name,
            path: route("search", {
                "categories[]": product.category.id,
                "departments[]": product.department.id,
            }),
        },
        {
            title: product.name,
        },
    ];
    return (
        <Layout>
            <Head title={product.name}></Head>
            <Breadcrumb data={breadcrumb} />
            <div className="container ">
                <div className="flex flex-col-reverse lg:flex-row py-content gap-10">
                    <div className=" w-full lg:w-7/12">
                        <ImagesProduct product={product} />
                    </div>
                    <div className="w-full lg:w-5/12 ">
                        <TitlePrice product={product} />
                        <div className="space-y-6">
                            <VariantsProduct />
                        </div>
                    </div>
                </div>
                <div className="w-full lg:w-9/12">
                    <Description product={product} />
                </div>
                {relatedProducts && relatedProducts.length > 0 && (
                    <div className="py-content">
                        <SectionTitle title="Related products" />
                        <div className="mt-5">
                            <CarouselProduct products={relatedProducts} />
                        </div>
                    </div>
                )}
            </div>
        </Layout>
    );
}
