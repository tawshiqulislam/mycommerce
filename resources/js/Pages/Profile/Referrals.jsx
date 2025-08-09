import React from "react";
import { Head, Link } from "@inertiajs/react";
import { FaCopy } from "react-icons/fa";
import { toast } from "react-hot-toast";
import LayoutProfile from "../../Layouts/LayoutProfile";
import FacebookIcon from "../../../../public/img/footer/facebook-icon.png";
import InstagramIcon from "../../../../public/img/footer/instagram-icon.png";
import TwitterIcon from "../../../../public/img/footer/twt-icon.png";
import WhatsAppIcon from "../../../../public/img/footer/ws-icon.png";

const Referrals = ({
    referrals,
    referral_code,
    referral_reward,
    used_points,
    usable_points,
}) => {
    const appUrl = window.location.origin;
    const referralLink = `${appUrl}/register?referral=${referral_code}`;

    const copyToClipboard = () => {
        navigator.clipboard.writeText(referralLink).then(() => {
            toast.success("Link copied to clipboard!");
        });
    };

    const shareOnFacebook = () => {
        const facebookShareUrl = `https://www.facebook.com/sharer/sharer.php?u=${encodeURIComponent(
            referralLink
        )}`;
        window.open(facebookShareUrl, "_blank");
    };

    const shareOnTwitter = () => {
        const twitterShareUrl = `https://twitter.com/intent/tweet?url=${encodeURIComponent(
            referralLink
        )}`;
        window.open(twitterShareUrl, "_blank");
    };

    const shareOnWhatsApp = () => {
        const whatsappShareUrl = `https://api.whatsapp.com/send?text=${encodeURIComponent(
            referralLink
        )}`;
        window.open(whatsappShareUrl, "_blank");
    };

    return (
        <LayoutProfile
            title="Referral programs"
            breadcrumb={[
                {
                    title: "Referrals",
                    path: route("profile.referrals"),
                },
            ]}
        >
            <Head title="Referrals" />
            <div className="space-y-2">
                <p>Your referral link</p>
                <div className="flex items-center space-x-2">
                    <p
                        className="font-black underline"
                        style={{
                            textUnderlineOffset: "6px",
                        }}
                    >
                        {referralLink}
                    </p>
                    <FaCopy
                        className="cursor-pointer text-gray-600 hover:text-gray-800"
                        onClick={copyToClipboard}
                        title="Copy referral link"
                    />
                </div>
                <div className="flex items-center space-x-2 mt-2">
                    <img
                        src={FacebookIcon}
                        alt="Facebook"
                        className="cursor-pointer w-6 h-6 hover:scale-110 transition-transform duration-200"
                        onClick={shareOnFacebook}
                    />
                    <img
                        src={TwitterIcon}
                        alt="Twitter"
                        className="cursor-pointer w-6 h-6 hover:scale-110 transition-transform duration-200"
                        onClick={shareOnTwitter}
                    />
                    <img
                        src={WhatsAppIcon}
                        alt="WhatsApp"
                        className="cursor-pointer w-6 h-6 hover:scale-110 transition-transform duration-200"
                        onClick={shareOnWhatsApp}
                    />
                </div>
                <div className="space-y-2 pt-4">
                    <p className="font-bold">Referral reward details</p>
                    {referrals.length > 0 ? (
                        <div className="flex flex-col lg:w-2/3">
                            <table className="bg-white">
                                <thead>
                                    <tr className="bg-gray-200 text-left">
                                        <th className="py-2 px-4">Account</th>
                                        <th className="py-2 px-4">
                                            Referral Purchases
                                        </th>
                                        <th className="py-2 px-4">
                                            Points Earned
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    {referrals.map((referral, index) => (
                                        <tr key={index}>
                                            <td className="py-2 px-4">
                                                {referral.phone}
                                            </td>
                                            <td className="py-2 px-4">
                                                ৳
                                                {(
                                                    referral.referrer_points /
                                                    0.02
                                                ).toFixed(2)}
                                            </td>
                                            <td className="py-2 px-4">
                                                {referral.referrer_points}
                                            </td>
                                        </tr>
                                    ))}
                                    <tr className="bg-gray-200 text-left">
                                        <td className="py-2 px-4">Total</td>
                                        <td className="py-2 px-4">
                                            ৳
                                            {referrals
                                                .reduce(
                                                    (total, referral) =>
                                                        total +
                                                        referral.referrer_points /
                                                            0.02,
                                                    0
                                                )
                                                .toFixed(2)}
                                        </td>
                                        <td className="py-2 px-4">
                                            {referrals.reduce(
                                                (total, referral) =>
                                                    total +
                                                    referral.referrer_points,
                                                0
                                            )}
                                        </td>
                                    </tr>
                                    <tr className="bg-gray-200 text-left">
                                        <td className="py-2 px-4">
                                            Available Points
                                        </td>
                                        <td className="py-2 px-4">
                                            (Redeemed: {used_points})
                                        </td>
                                        <td className="py-2 px-4">
                                            {usable_points}
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                            <div className="p-2 bg-reward mt-4">
                                <p>
                                    Referral Rewards Total: ৳
                                    {referral_reward.toFixed(2)}
                                </p>
                            </div>
                        </div>
                    ) : (
                        <p>No referrals found.</p>
                    )}
                </div>
            </div>
        </LayoutProfile>
    );
};

export default Referrals;
