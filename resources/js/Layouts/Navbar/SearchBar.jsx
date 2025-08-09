import { MagnifyingGlassIcon } from "@heroicons/react/24/solid";
import { Link, useForm, usePage } from "@inertiajs/react";

export default function SearchBar() {
    const { auth, filters, departments, settings } = usePage().props;
    const { data, setData, get, processing, errors, reset } = useForm({
        q: filters?.q || null,
    });

    function handleSubmit(e) {
        e.preventDefault();
        get("/search", {
            preserveScroll: true,
            //onSuccess: () => reset('q'),
        });
    }
    return (
        <div>
            <form
                onSubmit={handleSubmit}
                className="overflow-hidden bg-white flex rounded-lg shadow"
            >
                <input
                    id="search-main"
                    type="text"
                    placeholder="Find what you're looking for (e.g., Rice, Onion, Banana)"
                    name="q"
                    onChange={(e) => setData("q", e.target.value)}
                    className="block w-full border-none focus:border-none ring-0 focus:ring-none focus:ring-0 text-sm"
                    autoComplete="search"
                    required
                />
                <button
                    type="submit"
                    className="inline-flex items-center px-3 text-sm text-gray-400 hover:text-gray-500"
                >
                    <MagnifyingGlassIcon className="w-6 h-6" />
                </button>
            </form>
        </div>
    );
}
