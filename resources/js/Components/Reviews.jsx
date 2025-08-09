import React from "react";
import { Swiper, SwiperSlide } from "swiper/react";
import { Autoplay, Pagination } from "swiper/modules";
import "swiper/css";
import "swiper/css/bundle";

const Reviews = ({ reviews }) => {
    return (
        <div>
            <h2 className="text-lg lg:text-2xl font-black md:max-w-[50%] mx-auto mb-4 text-center">
                Hear from our happy clients
            </h2>
            <Swiper
                modules={[Autoplay, Pagination]}
                pagination={{ clickable: true }}
                autoplay={{ delay: 5000 }}
                className="md:w-[50%] w-[80%]"
                style={{ "--swiper-pagination-color": "#228b22" }}
            >
                {reviews.map((review, index) => (
                    <SwiperSlide key={index} className="pb-3 sm:pb-10">
                        <div className="relative flex flex-col items-start justify-center p-4">
                            <div className="flex items-center mb-2">
                                <div>
                                    <p className="font-bold text-sm">
                                        {review.name}
                                    </p>
                                    <p className="text-sm text-gray-600">
                                        {review.company}
                                    </p>
                                </div>
                            </div>
                            <div className="absolute top-5 right-4 flex">
                                <img
                                    src="img/reviews/quote.png"
                                    alt="quote"
                                    className="w-3 h-5"
                                />
                                <img
                                    src="img/reviews/quote.png"
                                    alt="quote"
                                    className="w-3 h-5 ml-1"
                                />
                            </div>
                            <div className="flex justify-center mb-2">
                                {[...Array(review.rating)].map((_, i) => (
                                    <img
                                        key={i}
                                        src="img/reviews/star.png"
                                        alt="star"
                                        className="w-4 h-4 mx-1 mb-2"
                                    />
                                ))}
                            </div>
                            <p className="text-sm text-justify whitespace-pre-line">
                                "{review.review}"
                            </p>
                        </div>
                    </SwiperSlide>
                ))}
            </Swiper>
        </div>
    );
};

export default Reviews;
