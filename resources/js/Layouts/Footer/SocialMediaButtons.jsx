import React, { useState } from "react";

const SocialMediaButtons = ({ handleClickMessenger, handleClickWhatsapp }) => {
    const [isVisible, setIsVisible] = useState(false);

    const toggleVisibility = () => {
        setIsVisible(!isVisible);
    };

    return (
        <div className="flex flex-col fixed bottom-20 right-5 gap-2 z-40">
            <div
                className={`transform transition-transform duration-300 ${
                    isVisible
                        ? "translate-y-0 opacity-100"
                        : "translate-y-4 opacity-0"
                }`}
                onClick={handleClickMessenger}
            >
                <img
                    src="/img/contact-us/messenger.png"
                    alt="Messenger"
                    className="cursor-pointer opacity-75 hover:opacity-100 h-8 w-8"
                />
            </div>
            <div
                className={`transform transition-transform duration-300 ${
                    isVisible
                        ? "translate-y-0 opacity-100"
                        : "translate-y-4 opacity-0"
                }`}
                onClick={handleClickWhatsapp}
            >
                <img
                    src="/img/contact-us/whatsapp.png"
                    alt="WhatsApp"
                    className="cursor-pointer opacity-75 hover:opacity-100 h-8 w-8"
                />
            </div>
            <div
                className="transition-opacity z-40 opacity-100 h-8 w-8"
                onClick={toggleVisibility}
            >
                <img
                    src="/img/contact-us/chat.png"
                    alt="Chat"
                    className="cursor-pointer opacity-75 hover:opacity-100 h-8 w-8"
                />
            </div>
        </div>
    );
};

export default SocialMediaButtons;
