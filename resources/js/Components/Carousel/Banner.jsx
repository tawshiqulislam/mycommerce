import { Link } from "@inertiajs/react";
import React from "react";

export default function Banner({ image }) {
    return (
        <a href={image.link} target="_blank">
            <div className="w-full">
                <img className="w-full" src={image.img} alt={image.alt} />
            </div>
        </a>
    );
}
