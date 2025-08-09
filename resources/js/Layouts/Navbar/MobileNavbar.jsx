import React from "react";
import { Link, useForm, usePage } from "@inertiajs/react";
import { ChevronUpDownIcon } from "@heroicons/react/24/outline";
import ApplicationLogo from "@/Components/ApplicationLogo";
import ProfileDropdown from "./ProfileDropdown";
import DepartmentDropdown from "./DepartmentDropdown";
import FullMenu from "./FullMenu";
import { formatCurrency } from "@/Helpers/helpers";
import verifiedIcon from "../../../../public/img/icons/verified.png";

export default function DesktopNavbar({ navigation }) {
    const { auth, filters, departments, settings } = usePage().props;
    const { data, setData, get, processing, errors, reset } = useForm({
        q: filters?.q || null,
    });

    function handleSubmit(e) {
        e.preventDefault();
        get("/search", {
            preserveScroll: true,
        });
    }

    return (
        <nav className="border-b lg:hidden bg-nav">
            <div className="container text-neutral-700 text-sm">
                <div className="flex items-center justify-between">
                    <div className="col-span-3 flex flex-row space-x-2">
                        <FullMenu
                            navigation={navigation}
                            departments={departments}
                        />
                        <ApplicationLogo />
                    </div>
                    <div>
                        {auth.user ? (
                            <ProfileDropdown>
                                <button className="inline-flex items-center">
                                    {Boolean(auth.user.verified) && (
                                        <img
                                            src={verifiedIcon}
                                            alt="Verified"
                                            className="w-5 h-5 mr-1"
                                        />
                                    )}
                                    {auth.user.name}
                                    <ChevronUpDownIcon
                                        className="w-5 h-5 ml-1 -mr-1"
                                        aria-hidden="true"
                                    />
                                </button>
                            </ProfileDropdown>
                        ) : (
                            <div className="flex lg:flex-1 lg:items-center lg:justify-end lg:space-x-2">
                                <Link href={route("login")} className="hover:">
                                    Login
                                </Link>
                                <span
                                    className="h-4 w-px bg-neutral-400"
                                    aria-hidden="true"
                                ></span>
                                <Link
                                    href={route("register")}
                                    className="hover:"
                                >
                                    Register
                                </Link>
                            </div>
                        )}
                    </div>
                </div>
            </div>
        </nav>
    );
}
