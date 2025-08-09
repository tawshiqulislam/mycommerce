import CardProduct from "@/Components/Cards/CardProduct";
import GridProduct from "@/Components/Grids/GridProduct";
import SectionList from "@/Components/Sections/SectionList";
import Layout from "@/Layouts/Layout";
import { Head, Link } from "@inertiajs/react";
import React from "react";
import CarouselProduct from "../Product/CarouselProduct";
import CarouselSection from "../Home/CarouselSection";
import BannerText from "@/Components/Carousel/BannerText";
import Breadcrumb from "@/Components/Breadcrumb";
import SectionTitle from "@/Components/Sections/SectionTitle";
import MetaTag from "@/Components/MetaTag";
import SearchBar from "../../Layouts/Navbar/SearchBar";
import CarouselTop from "./CarouselTop";
import Banner from "@/Components/Carousel/Banner";

function Department({
    department,
    offertProducts,
    bestSellersProducts,
    categories,
    bannersMedium,
}) {
    // console.log(offertProducts[0])
    return (
        <Layout>
            <MetaTag metaTag={department.metaTag} />
            {/* <div className="container">
                <BannerText
                    title={department.name}
                    img={department.img}
                    entry={department.entry}
                />
            </div> */}
            <div className="container">
                <div className="col-span-1 md:col-span-2">
                    <CarouselTop image={department.img} />
                </div>
                <div className="lg:hidden">
                    <SearchBar />
                </div>
            </div>
            <Breadcrumb
                data={[
                    {
                        title: department.name,
                    },
                ]}
            />
            <div className="container">
                <SectionList title="Top Offers">
                    <CarouselProduct products={offertProducts} />
                </SectionList>
                {/* {bestSellersProducts.length > 0 && (
                    <SectionList title="Los mas vendidos">
                        <CarouselProduct productVariants={bestSellersProducts} />
                    </SectionList>
                )} */}
                <div className="space-y-10">
                    {categories.map((category) => (
                        <div key={category.id}>
                            <SectionTitle title={category.name} />
                            <div className="mt-6">
                                <GridProduct>
                                    {category.products.map((product) => (
                                        <CardProduct
                                            key={product.ref}
                                            product={product}
                                            productNew={true}
                                        />
                                    ))}
                                </GridProduct>
                            </div>
                        </div>
                    ))}
                </div>
                <div className="flex justify-center mt-10">
                    <Link
                        className="btn btn-secondary"
                        href={route("search", {
                            "departments[]": department.id,
                        })}
                    >
                        See all
                    </Link>
                </div>
                {bannersMedium.length > 0 && (
                    <div className="py-content ">
                        <Banner image={bannersMedium[0]} />
                    </div>
                )}
            </div>
        </Layout>
    );
}

export default Department;
