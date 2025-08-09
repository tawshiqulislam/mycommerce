import React, { useState, useEffect } from "react";
import { toast } from "react-hot-toast";
import CardProduct from "./CardProduct";
import Pagination from "@/Components/Pagination";
import LayoutPos from "@/Layouts/LayoutPos";
import { Head, usePage } from "@inertiajs/react";
import SearchBar from "./SearchBar";
import OrderForm from "./OrderForm";

export default function Pos({ products, pdf_url, vat, vat_negation }) {
    const [filteredProducts, setFilteredProducts] = useState(products.data);
    const [showForm, setShowForm] = useState(false);
    const [buyerPhone, setBuyerPhone] = useState("");
    const [orderProducts, setOrderProducts] = useState([]);

    const handleSearch = (searchTerm) => {
        if (!searchTerm) {
            setFilteredProducts(products.data);
        } else {
            const filtered = products.data.filter((product) =>
                product.name.toLowerCase().includes(searchTerm.toLowerCase())
            );
            setFilteredProducts(filtered);
        }
    };

    const handleAddProduct = (product) => {
        toast.dismiss();
        if (!showForm) {
            toast("Please click 'New Order' first.");
            return;
        }
        const existingProduct = orderProducts.find((p) => p.id === product.id);
        if (existingProduct) {
            toast("Already added.");
            return;
        }
        const newProduct = {
            id: product.id,
            name: product.name,
            color: product.color,
            price: product.price,
            quantity: 1,
            total: product.price,
        };
        setOrderProducts([...orderProducts, newProduct]);
    };

    useEffect(() => {
        if (pdf_url) {
            printPdf(pdf_url);
        }
    }, [pdf_url]);

    return (
        <LayoutPos>
            <Head title="POS" />
            <div className="container py-content">
                <div className="flex lg:flex-row flex-col-reverse lg:gap-x-10">
                    <div className="w-full lg:w-9/12 xl:w-10/12 2xl:w-10/12">
                        <div className="relative">
                            <div className="flex items-start justify-between mb-4">
                                <h2 className="font-bold text-2xl">
                                    Search
                                    <label className="text-xs block font-normal whitespace-nowrap w-full mt-1">
                                        {products.meta.total} items
                                    </label>
                                </h2>
                            </div>
                            <SearchBar onSearch={handleSearch} />
                            <div className="relative mt-9">
                                {filteredProducts.length ? (
                                    <div className="relative">
                                        <>
                                            <div className="grid grid-cols-2 gap-6 md:grid-cols-3 lg:grid-cols-5 xl:grid-cols-6 2xl:grid-cols-7 md:gap-x-6 md:gap-y-6">
                                                {filteredProducts.map(
                                                    (item) => (
                                                        <CardProduct
                                                            key={item.id}
                                                            product={item}
                                                            onAddProduct={
                                                                handleAddProduct
                                                            }
                                                        />
                                                    )
                                                )}
                                            </div>
                                            {products.meta.total >
                                                products.meta.per_page && (
                                                <div className="mt-10">
                                                    <Pagination
                                                        paginator={
                                                            products.meta
                                                        }
                                                    />
                                                </div>
                                            )}
                                        </>
                                    </div>
                                ) : (
                                    <div className="text-center mt-10 pt-10 durac">
                                        No records found
                                    </div>
                                )}
                            </div>
                        </div>
                    </div>
                    <div className="w-full lg:w-3/12 xl:w-2/12 2xl:w-2/12">
                        <button
                            className="btn btn-primary"
                            onClick={() => setShowForm(!showForm)}
                        >
                            New Order
                        </button>
                        {showForm && (
                            <OrderForm
                                buyerPhone={buyerPhone}
                                setBuyerPhone={setBuyerPhone}
                                orderProducts={orderProducts}
                                setOrderProducts={setOrderProducts}
                                setShowForm={setShowForm}
                                vat={vat}
                                vat_negation={vat_negation}
                            />
                        )}
                    </div>
                </div>
            </div>
        </LayoutPos>
    );
}

function printPdf(url) {
    const iframe = document.createElement("iframe");
    iframe.src = url;
    iframe.style.display = "none";
    document.body.appendChild(iframe);
    iframe.onload = () => {
        iframe.contentWindow.focus();
        iframe.contentWindow.print();
    };
}
