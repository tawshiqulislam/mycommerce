import React from "react";

const GridProduct = ({ children }) => {
    return (
        <div className="grid grid-cols-2 gap-4 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-7 xl:grid-cols-7">
            {children}
        </div>
    );
};

export default GridProduct;
