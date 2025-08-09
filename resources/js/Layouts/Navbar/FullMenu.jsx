import React from "react";
import { Link } from "@inertiajs/react";
import DepartmentDropdown from "./DepartmentDropdown";
import SingleDepartmentDropdown from "./SingleDepartmentDropdown";
import { Disclosure } from "@headlessui/react";
import { Bars3Icon, XMarkIcon } from "@heroicons/react/24/solid";

const colors = [
    "bg-primary",
    "bg-[#FC2D39]",
    "bg-[#50C878]",
    "bg-[#FF9505]",
    "bg-[#87CEEB]",
    "bg-[#FF6347]",
];

export default function FullMenu({ navigation, departments }) {
    return (
        <Disclosure as="div" className="relative inline-block text-left">
            {({ open }) => (
                <>
                    <div className="h-14 flex justify-between items-center">
                        <div>
                            <Disclosure.Button className="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-primary">
                                <span className="sr-only">Toggle menu</span>
                                {open ? (
                                    <XMarkIcon
                                        className="block h-6 w-6"
                                        aria-hidden="true"
                                    />
                                ) : (
                                    <Bars3Icon
                                        className="block h-6 w-6"
                                        aria-hidden="true"
                                    />
                                )}
                            </Disclosure.Button>
                        </div>
                    </div>
                    <Disclosure.Panel className="absolute mt-1 left-0 bg-white shadow-lg z-20 rounded-lg p-4">
                        <div className="flex flex-col items-start space-y-4 w-max">
                            <div className="grid grid-cols-2 gap-2">
                                {departments.map((item, index) => (
                                    <Link
                                        key={item.slug}
                                        href={route("department", item.slug)}
                                        className={`rounded-lg w-24 h-24 text-white font-black text-center ${
                                            colors[index % colors.length]
                                        }`}
                                    >
                                        <div className="w-full py-4">
                                            <img
                                                src={`/img/icons/${item.slug}.png`}
                                                alt={item.name}
                                                className="mx-auto mb-2 h-10 w-10 object-contain"
                                            />
                                            {item.name}
                                        </div>
                                    </Link>
                                ))}
                            </div>
                            <DepartmentDropdown />
                            {departments.map((department, index) => (
                                <SingleDepartmentDropdown
                                    key={index}
                                    department={department}
                                />
                            ))}
                            {navigation.map((item, key) => (
                                <LinkNavbar
                                    key={key}
                                    href={route(item.href)}
                                    active={route().current(item.href)}
                                >
                                    {item.name}
                                </LinkNavbar>
                            ))}
                        </div>
                    </Disclosure.Panel>
                </>
            )}
        </Disclosure>
    );
}

function LinkNavbar({ children, active, ...props }) {
    return (
        <Link
            {...props}
            className={
                (active
                    ? "border-primary-600/50 text-primary-600 font-medium border-b-2"
                    : "") + " whitespace-nowrap block "
            }
        >
            {children}
        </Link>
    );
}
