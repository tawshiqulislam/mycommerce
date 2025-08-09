import { Link, usePage } from "@inertiajs/react";
import Suscribe from "./Suscribe";
import ApplicationLogo from "@/Components/ApplicationLogo";
import SocialMediaButtons from "./SocialMediaButtons";
import SubscribeNewsletter from "./SubscribeNewsletter";
import { useState, useEffect } from "react";

const Footer = () => {
    const { settings, departments } = usePage().props;
    const footerItems = [
        {
            name: "Contact",
            links: [
                {
                    title: settings.company.email,
                },
                {
                    title: settings.company.address,
                },
                {
                    title: settings.company.phone,
                },
            ],
        },
        {
            name: "Info",
            links: [
                {
                    title: "Shipping",
                    path: "/shipping-delivery",
                },
                {
                    title: "Return",
                    path: "/return-exchanges",
                },
                {
                    title: "FAQ",
                    path: "/faq",
                },
            ],
        },
        {
            name: "Categories",
            links: departments.map((department) => ({
                title: department.name,
                path: route("department", department.slug),
            })),
        },
    ];

    const [isScrollTopVisible, setIsScrollTopVisible] = useState(false);
    const [isSocialMediaVisible, setIsSocialMediaVisible] = useState(false);

    useEffect(() => {
        const handleScroll = () => {
            if (window.scrollY > 300) {
                setIsScrollTopVisible(true);
            } else {
                setIsScrollTopVisible(false);
            }
            if (window.scrollY > 10) {
                setIsSocialMediaVisible(true);
            } else {
                setIsSocialMediaVisible(false);
            }
        };

        window.addEventListener("scroll", handleScroll);

        return () => {
            window.removeEventListener("scroll", handleScroll);
        };
    }, []);

    const handleClickWhatsapp = () => {
        window.open(
            "https://api.whatsapp.com/send?phone=" + settings.company.phone,
            "_blank"
        );
    };

    const handleClickMessenger = () => {
        window.open(settings.social.ws, "_blank");
    };

    const scrollToTop = () => {
        window.scrollTo({
            top: 0,
            behavior: "smooth",
        });
    };

    return (
        <>
            {/* <div className="container py-content">
                <Suscribe />
            </div> */}

            <footer className="pt-content">
                <div className="border-t bg-gray-100">
                    <div className="container text-sm">
                        <div className="py-8 md:py-10 lg:py-12 xl:py-14 grid grid-cols-2 lg:grid-cols-6 gap-8">
                            {/* <div className="col-span-2 ">
                            <div className="flex-shrink-0 flex items-center text-primary-600">
                                <ApplicationLogo bgIcon="bg-white" colorIcon="text-primary-600" textColor="text-white" />
                            </div>
                            <p className="leading-6 mt-2 lg:mt-5 opacity-80">
                                {settings.company.entry}
                            </p>
                        </div> */}
                            {footerItems.map((item, key) => (
                                <ItemFooter key={key} title={item.name}>
                                    <ul className="space-y-3">
                                        {item.links.map((link, key) => (
                                            <li key={key}>
                                                {link.path ? (
                                                    <Link
                                                        href={link.path}
                                                        className="block hover:opacity-100 opacity-90"
                                                    >
                                                        {link.title}
                                                    </Link>
                                                ) : (
                                                    link.title
                                                )}
                                            </li>
                                        ))}
                                    </ul>
                                </ItemFooter>
                            ))}
                            <ItemFooter
                                title="Subscribe to our newsletter"
                                className="col-span-2 "
                            >
                                <div className="space-y-4">
                                    <p className="text-gray-500">
                                        Get the best deals and discounts by
                                        subscribing to our newsletter!
                                    </p>
                                    <SubscribeNewsletter />
                                </div>
                            </ItemFooter>
                        </div>
                    </div>
                    <div className="py-4 text-xs bg-[#BAEBFF]">
                        <div className="container flex items-center justify-between text-gray-500 ">
                            <p>
                                © 2025 {settings.company.name}. All rights
                                reserved.
                            </p>
                            {/* <SocilaMediaIcon /> */}
                        </div>
                    </div>
                </div>
                <div
                    className={`fixed bottom-5 right-5 transition-opacity z-40 ${
                        isScrollTopVisible ? "opacity-100" : "opacity-0"
                    }`}
                    onClick={scrollToTop}
                >
                    <div className="bg-primary text-white cursor-pointer opacity-75 hover:opacity-100 flex items-center justify-center rounded-full w-8 h-8">
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            className="h-6 w-6"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="currentColor"
                        >
                            <path
                                strokeLinecap="round"
                                strokeLinejoin="round"
                                strokeWidth={2}
                                d="M5 15l7-7 7 7"
                            />
                        </svg>
                    </div>
                </div>
                {isSocialMediaVisible && (
                    <SocialMediaButtons
                        handleClickMessenger={handleClickMessenger}
                        handleClickWhatsapp={handleClickWhatsapp}
                    />
                )}
            </footer>
        </>
    );
};

export const ItemFooter = ({ title, children, className }) => {
    return (
        <div className={className}>
            <h4 className="mt-2 font-medium">{title}</h4>
            <div className="mt-2 lg:mt-5">{children}</div>
        </div>
    );
};

export default Footer;
