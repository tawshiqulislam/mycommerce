import { Link } from "@inertiajs/react";

const Pagination = ({ paginator }) => {
    const prevPage = paginator.links[0].url;
    const netxPage = paginator.links.slice(-1)[0].url;
    return (
        paginator.total > paginator.per_page && (
            <div className="sm:flex-1 sm:flex sm:items-center sm:justify-between text-sm">
                <div className="hidden sm:block">
                    <p className="">
                        Showing
                        <span className="font-bold"> {paginator.from} </span>to
                        <span className="font-bold"> {paginator.to} </span>
                        of
                        <span className="font-bold"> {paginator.total} </span>
                        results
                    </p>
                </div>
                <div>
                    {paginator.total > paginator.per_page && (
                        <nav
                            role="navigation"
                            aria-label="Pagination Navigation"
                            className="flex justify-end space-x-2 "
                        >
                            {prevPage === null ? (
                                <span className=" px-4 py-2 font-semibold bg-gray-100 border border-gray-100 text-gray-300 cursor-default rounded-md">
                                    Previous
                                </span>
                            ) : (
                                <Link
                                    href={prevPage}
                                    rel="prev"
                                    className="px-4 py-2 border border-gray-300  font-semibold rounded-md  bg-white hover:bg-gray-50"
                                >
                                    Previous
                                </Link>
                            )}

                            {netxPage === null ? (
                                <span className=" px-4 py-2 font-semibold bg-gray-100 border border-gray-100 text-gray-300 cursor-default rounded-md">
                                    Next
                                </span>
                            ) : (
                                <Link
                                    href={netxPage}
                                    rel="next"
                                    className="px-4 py-2 border border-gray-300  font-semibold rounded-md  bg-white hover:bg-gray-50"
                                >
                                    Next
                                </Link>
                            )}
                        </nav>
                    )}
                </div>
            </div>
        )
    );
};

export default Pagination;
