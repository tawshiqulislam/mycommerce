import React from "react";

function SimpleCard({ name, img }) {
    return (
        <div className="flex flex-col md:flex-row p-2 items-center move-up">
            <div className="border border-gray-100 flex flex-col w-52 max-w-full first-line:w-52 bg-gray-50 flex aspect-square rounded-xl shadow-md">
                <img
                    src={img}
                    className="max-w-full object-cover rounded-xl"
                    alt={name}
                />
                <h2 className="p-2 text-center text-gray-600 text-xs">
                    {name}
                </h2>
            </div>
        </div>
    );
}

export default SimpleCard;
