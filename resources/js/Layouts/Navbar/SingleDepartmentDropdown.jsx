import React from "react";
import { Link } from "@inertiajs/react";
import { Popover, PopoverButton, PopoverPanel } from "@headlessui/react";
import { ChevronRightIcon } from "@heroicons/react/24/solid";

function SingleDepartmentDropdown({ department }) {
    return (
        <Popover className="relative">
            <PopoverButton className="focus:ring-0 focus:border-none focus:outline-none">
                <div className="flex items-center">
                    <span>{department.name}</span>
                    <ChevronRightIcon className="h-4 w-4 ml-1" />
                </div>
            </PopoverButton>
            <PopoverPanel
                transition
                className="absolute z-20 transition duration-200 ease-in-out w-[150%] md:w-[400%] lg:w-[800%]"
                style={{ left: "100%", top: "0%" }}
            >
                {({ close }) => (
                    <div className="overflow-hidden rounded-lg shadow-lg ring-1 ring-black ring-opacity-5 bg-white">
                        <div className="p-4 lg:p-7 grid grid-cols-1">
                            <div className="flex items-start text-sm">
                                <div className="grow">
                                    <ul className="mt-2">
                                        {department.categories.map(
                                            (category) => (
                                                <li key={category.id}>
                                                    <Link
                                                        href={route("search", {
                                                            "categories[]":
                                                                category.id,
                                                            "departments[]":
                                                                department.id,
                                                        })}
                                                        onClick={close}
                                                        className="text-left flex py-1 items-center text-gray-500 hover:text-primary-600"
                                                    >
                                                        {category.name}
                                                    </Link>
                                                </li>
                                            )
                                        )}
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div className="p-4 lg:p-5 bg-neutral-50 transition duration-150 ease-in-out hover:bg-neutral-100 focus:outline-none focus-visible:ring">
                            <div className="text-right">
                                <Link href={route("search")}>
                                    <span className="text-sm font-medium">
                                        View All
                                    </span>
                                </Link>
                            </div>
                        </div>
                    </div>
                )}
            </PopoverPanel>
        </Popover>
    );
}

export default SingleDepartmentDropdown;
