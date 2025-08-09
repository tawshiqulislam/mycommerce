import Dropdown from "@/Components/Dropdown";
import { Menu, Transition } from "@headlessui/react";
import {
    AdjustmentsHorizontalIcon,
    ArrowLeftOnRectangleIcon,
    ArrowRightOnRectangleIcon,
    ShoppingBagIcon,
    UserCircleIcon,
    SparklesIcon,
    StarIcon,
} from "@heroicons/react/24/outline";

import { Link, usePage } from "@inertiajs/react";
import { Fragment } from "react";

export default function ProfileDropdown({ children }) {
    const navigation_profile = [
        {
            name: "Profile",
            href: route("profile.index"),
            current: route().current("profile.index"),
            icon: UserCircleIcon,
        },
        {
            name: "My orders",
            href: route("profile.orders"),
            current: route().current("profile.orders"),
            icon: ShoppingBagIcon,
        },
        {
            name: "Review",
            href: route("profile.review"),
            current: route().current("profile.review"),
            icon: StarIcon,
        },
        {
            name: "Rewards",
            href: route("profile.referrals"),
            current: route().current("profile.referrals"),
            icon: SparklesIcon,
        },
    ];
    const navigation_sing = [
        {
            name: "Login",
            href: route("login"),
            current: route().current("login"),
            icon: ArrowRightOnRectangleIcon,
        },
        {
            name: "Register",
            href: route("register"),
            current: route().current("register"),
            icon: ArrowLeftOnRectangleIcon,
        },
    ];

    const { auth } = usePage().props;
    return (
        <>
            <Dropdown>
                <Dropdown.Trigger>{children}</Dropdown.Trigger>
                <Dropdown.Content>
                    {auth.user ? (
                        <>
                            {navigation_profile.map((item) => (
                                <Dropdown.Link href={item.href} key={item.name}>
                                    <div className="flex items-center">
                                        <item.icon className="h-5 w-5 mr-2 text-primary-600" />
                                        <span>{item.name}</span>
                                    </div>
                                </Dropdown.Link>
                            ))}
                            {/*{(auth.user.role == 'admin') && (

                                 <a className="dropdown-link" target='_blank' href={route('dashboard.home')}  >
                                    <div className="flex items-center">
                                        <AdjustmentsHorizontalIcon className="h-5 w-5 mr-2 text-primary-600" />
                                        <span>Dashboard</span>
                                    </div>
                                </a>
                            )}*/}
                            <Dropdown.Link
                                href={route("logout")}
                                method="post"
                                className="border-t"
                            >
                                Logout
                            </Dropdown.Link>
                        </>
                    ) : (
                        navigation_sing.map((item) => (
                            <Dropdown.Link href={item.href} key={item.name}>
                                <div className="flex items-center">
                                    <item.icon className="h-5 w-5 mr-2 text-gray-700" />
                                    <span>{item.name}</span>
                                </div>
                            </Dropdown.Link>
                        ))
                    )}
                </Dropdown.Content>
            </Dropdown>
        </>
    );
}
