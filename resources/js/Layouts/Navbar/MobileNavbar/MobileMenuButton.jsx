import { Disclosure } from "@headlessui/react";
import { Bars3Icon, XMarkIcon } from "@heroicons/react/24/solid";
import React from "react";

export default function MobileMenuButton({ open }) {
    return (
        <Disclosure.Button
            className={
                "inline-flex items-center justify-center p-2 " +
                (open ? "bg-primary-500" : "")
            }
        >
            <span className="sr-only">Open main menu</span>
            {open ? (
                <XMarkIcon className="block h-6 w-6" />
            ) : (
                <Bars3Icon className="block h-6 w-6" />
            )}
        </Disclosure.Button>
    );
}
