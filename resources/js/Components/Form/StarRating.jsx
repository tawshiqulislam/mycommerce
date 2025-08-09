import React, { useState } from "react";

const StarRating = ({ rating, setRating }) => {
    const [hover, setHover] = useState(0);

    return (
        <div className="flex">
            {[...Array(5)].map((star, index) => {
                index += 1;
                return (
                    <button
                        type="button"
                        key={index}
                        className={
                            index <= (hover || rating)
                                ? "text-yellow-500"
                                : "text-gray-400"
                        }
                        onClick={() => setRating(index)}
                        onMouseEnter={() => setHover(index)}
                        onMouseLeave={() => setHover(rating)}
                    >
                        <svg
                            className="w-6 h-6"
                            fill="currentColor"
                            viewBox="0 0 20 20"
                            xmlns="http://www.w3.org/2000/svg"
                        >
                            <path d="M9.049.865a1 1 0 011.902 0l1.36 4.155a1 1 0 00.95.69h4.372a1 1 0 01.593 1.807l-3.548 2.585a1 1 0 00-.364 1.118l1.36 4.155a1 1 0 01-1.54 1.118L10 13.347a1 1 0 00-1.172 0l-3.548 2.586a1 1 0 01-1.54-1.118l1.36-4.155a1 1 0 00-.364-1.118L1.297 7.517a1 1 0 01.593-1.807h4.372a1 1 0 00.95-.69L8.573.865a1 1 0 01.476-.865z" />
                        </svg>
                    </button>
                );
            })}
        </div>
    );
};

export default StarRating;
