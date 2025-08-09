import React from "react";

function BannerText({ title, img, entry }) {
    return (
        <div
            className="gradient-primary"
            style={{
                backgroundImage: `url(${img})`,
                backgroundSize: "cover",
                backgroundPosition: "center",
            }}
        >
            <div className="container">
                <div className="w-ful rounded-lg lg:flex lg:items-center lg:justify-around overflow-hidden h-96 py-10">
                    {/* <div className="lg:w-5/12 lg:px-8  ">
                        <h2 className="text-4xl font-bold tracking-tight text-white sm:text-6xl">
                            {img}
                        </h2>
                        <p className="mt-6 text-lg leading-7 text-white">
                            {entry}
                        </p>
                    </div> */}
                </div>
            </div>
        </div>
    );
}

export default BannerText;
