import Layout from "@/Layouts/Layout";
import { Head, Link } from "@inertiajs/react";
import React from "react";

const ErrorPage = ({ status }) => {
    const title = {
        503: "503: Service Unavailable",
        500: "500: Server Error",
        404: "404: Page Not Found",
        403: "403: Forbidden",
    }[status];

    const description = {
        503: "Sorry, we are undergoing maintenance. Please check back soon.",
        500: "Oops, something went wrong on our servers.",
        404: "Sorry, the page you are looking for could not be found.",
        403: "Sorry, you are forbidden from accessing this page.",
    }[status];

    return (
        <main className="grid h-screen px-6 py-24 bg-white place-items-center sm:py-32 lg:px-8">
            <Head title="ERROR" />
            <div className="text-center">
                <p className="text-base font-semibold text-primary-600">{status}</p>
                <h1 className="mt-4 text-3xl font-bold tracking-tight text-gray-900 sm:text-5xl">
                    {title}
                </h1>
                <p className="mt-6 text-base leading-7 text-gray-600">
                    {description}
                </p>
                <div className="flex items-center justify-center mt-10 gap-x-6">
                    <Link
                        href={route("home")}
                        className="rounded-md bg-primary-600 px-3.5 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-primary-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary-600"
                    >
                        Go back home
                    </Link>
                    <Link
                        href={route("contact")}
                        className="text-sm font-semibold text-gray-900"
                    >
                        Contact support
                        <span aria-hidden="true">&rarr;</span>
                    </Link>
                </div>
            </div>
        </main>
    );
};

export default ErrorPage;