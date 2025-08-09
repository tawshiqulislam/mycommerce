import NavLink from "@/Components/NavLink";
import Layout from "@/Layouts/Layout";
import { Head, Link } from "@inertiajs/react";
import React from "react";
import Dashboard from "../Pages/Profile/Dashboard";
import BannerWithTitle from "@/Components/Carousel/BannerWithTitle";
import {
    ArrowRightOnRectangleIcon,
    HomeIcon,
    IdentificationIcon,
    LockClosedIcon,
    ShoppingBagIcon,
    ShareIcon,
    StarIcon,
} from "@heroicons/react/24/outline";
import SectionTitle from "@/Components/Sections/SectionTitle";
import Hero from "@/Components/Hero/Hero";
import Breadcrumb from "@/Components/Breadcrumb";

export default function Profile({ title, children, breadcrumb = [] }) {
    const links = [
        {
            title: "Dashboard",
            path: "profile.index",
            Icon: HomeIcon,
        },
        {
            title: "My Orders",
            path: "profile.orders",
            Icon: ShoppingBagIcon,
        },
        {
            title: "Referrals",
            path: "profile.referrals",
            Icon: ShareIcon,
        },
        {
            title: "Account details",
            path: "profile.account-details",
            Icon: IdentificationIcon,
        },
        {
            title: "Review",
            path: "profile.review",
            Icon: StarIcon,
        },
    ];

    return (
        <Layout>
            <Breadcrumb
                data={[
                    {
                        title: "Profile",
                    },
                    ...breadcrumb,
                ]}
            />
            <div className="container ">
                <div className="py-content">
                    <div className="grid grid-cols-12 md:gap-6 gap-y-10 ">
                        <div className="col-span-12 lg:col-span-3">
                            <div className="flex flex-col space-y-1">
                                {links.map((item) => (
                                    <Link
                                        preserveScroll
                                        key={item.path}
                                        href={route(item.path)}
                                        className={
                                            "block px-4 py-3 rounded-md " +
                                            (route().current(item.path)
                                                ? "bg-gray-100 font-medium"
                                                : "")
                                        }
                                    >
                                        <div className="flex items-center gap-3">
                                            <item.Icon className="w-6 h-6" />
                                            {item.title}
                                        </div>
                                    </Link>
                                ))}
                            </div>
                            <Link
                                method="post"
                                as="button"
                                href={route("logout")}
                                className={
                                    "block px-4 py-3 rounded-md border-t border-gray-100 mt-2"
                                }
                            >
                                <div className="flex items-center gap-3">
                                    <ArrowRightOnRectangleIcon className="w-6 h-6" />
                                    Logout
                                </div>
                            </Link>
                        </div>
                        <div className="col-span-12 lg:col-span-9 md:pl-10">
                            {title && (
                                <h3 className="title-section mb-8">{title}</h3>
                            )}

                            <div>{children}</div>
                        </div>
                    </div>
                </div>
            </div>
        </Layout>
    );
}
