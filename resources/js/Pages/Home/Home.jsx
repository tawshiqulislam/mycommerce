import Banner from "@/Components/Carousel/Banner";
import CardProduct from "@/Components/Cards/CardProduct";
import CarouselBanner from "@/Components/Carousel/CarouselBanner";
import SectionList from "@/Components/Sections/SectionList";
import Layout from "@/Layouts/Layout";
import { Head, usePage } from "@inertiajs/react";
import CarouselTop from "./CarouselTop";
import GridProduct from "@/Components/Grids/GridProduct";
import CarouselSection from "./CarouselSection";
import MetaTag from "@/Components/MetaTag";
import SearchBar from "../../Layouts/Navbar/SearchBar";
import Reviews from "@/Components/Reviews";

export default function Home({
    page,
    carouselTop,
    bannersTop,
    productsBestSeller,
    bannersMedium,
    newProducts,
    bannersBottom,
    categoriesProductCount,
    reviews,
}) {
    return (
        <>
            <MetaTag metaTag={page.metaTag} />
            <Layout>
                <div className="container">
                    <div className="col-span-1 md:col-span-2">
                        <CarouselTop images={carouselTop} />
                    </div>
                    <div className="lg:hidden">
                        <SearchBar />
                    </div>
                    {/* <SectionList title={"Popular Categories"}> */}
                    <div className="py-content">
                        <h2 className="title-section-center mb-6">
                            Popular Categories
                        </h2>
                        <CarouselSection
                            items={categoriesProductCount}
                            searchType="categories[]"
                        />
                    </div>
                    {/* </SectionList> */}
                    <div className="py-content">
                        <h2 className="title-section-center mb-8">
                            Earn More with Referrals!
                        </h2>
                        <div className="grid grid-cols-1 gap-4 md:grid-cols-2 md:gap-8">
                            {bannersTop.map((item, index) => (
                                <div key={item.id} className="h-full">
                                    <a href={item.link} target="blank">
                                        <div
                                            className={`h-full mx-auto object-cover w-full rounded-xl overflow-hidden shadow-lg flex flex-col justify-between ${
                                                index % 2 === 0
                                                    ? "bg-banner1"
                                                    : "bg-banner2"
                                            }`}
                                        >
                                            <div className="p-4 w-[70%]">
                                                <h1 className="text-lg font-black">
                                                    {item.title}
                                                </h1>
                                                <h2 className="text-xs text-gray-700">
                                                    {item.alt}
                                                </h2>
                                            </div>
                                            <img
                                                src={item.img}
                                                alt={item.alt}
                                                className="w-full object-cover"
                                            />
                                        </div>
                                    </a>
                                </div>
                            ))}
                        </div>
                    </div>
                    {/* {bannersMedium.length > 0 && (
                        <div className="py-content ">
                            <Banner image={bannersMedium[0]} />
                        </div>
                    )} */}
                    {productsBestSeller.length > 0 && (
                        <SectionList title="Best selling">
                            <GridProduct>
                                {productsBestSeller.map((product) => (
                                    <CardProduct
                                        key={product.id}
                                        product={product}
                                    />
                                ))}
                            </GridProduct>
                        </SectionList>
                    )}
                    <SectionList title={"Recently added"}>
                        <div className="py-2 relative">
                            <GridProduct>
                                {newProducts.map((product) => (
                                    <CardProduct
                                        key={product.id}
                                        product={product}
                                        productNew={true}
                                    />
                                ))}
                            </GridProduct>
                        </div>
                    </SectionList>
                    {bannersBottom.length > 0 && (
                        <div className="py-content">
                            <CarouselBanner images={bannersBottom} />
                        </div>
                    )}
                    {reviews.length > 0 && <Reviews reviews={reviews} />}
                </div>
            </Layout>
        </>
    );
}
