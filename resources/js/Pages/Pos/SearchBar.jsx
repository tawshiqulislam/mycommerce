import React, { useState } from "react";
import { useForm, usePage } from "@inertiajs/react";

export default function SearchBar({ onSearch }) {
    const [searchTerm, setSearchTerm] = useState("");

    const handleSearch = (e) => {
        setSearchTerm(e.target.value);
        onSearch(e.target.value);
    };

    return (
        <div>
            <form className="overflow-hidden bg-white flex rounded-lg shadow w-2/3">
                <input
                    id="search-input"
                    className="block w-full border-none focus:border-none ring-0 focus:ring-none focus:ring-0 text-sm"
                    value={searchTerm}
                    onChange={handleSearch}
                    placeholder="Search for products..."
                />
            </form>
        </div>
    );
}
