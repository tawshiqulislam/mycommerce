import { useEffect, useState } from "react";
import GuestLayout from "@/Layouts/GuestLayout";
import InputError from "@/Components/Form/InputError";
import InputLabel from "@/Components/Form/InputLabel";
import PrimaryButton from "@/Components/PrimaryButton";
import TextInput from "@/Components/Form/TextInput";
import { Head, Link, useForm } from "@inertiajs/react";
import axios from "axios";

export default function Register() {
    const { data, setData, post, processing, errors, reset } = useForm({
        phone: "",
        otp: "",
        referrer_code: "",
    });

    const [otpSent, setOtpSent] = useState(false);
    const [isReferralDisabled, setIsReferralDisabled] = useState(false);

    useEffect(() => {
        const params = new URLSearchParams(window.location.search);
        const referral = params.get("referral");
        if (referral) {
            setData("referrer_code", referral);
            setIsReferralDisabled(true);
        }
    }, [setData]);

    useEffect(() => {
        if (errors.phone || errors.otp) {
            setOtpSent(false);
        }
    }, [errors]);

    const sendOtp = async (e) => {
        e.preventDefault();
        try {
            const fullPhoneNumber = data.phone;
            await axios.post(route("send-register"), { phone: fullPhoneNumber });
            setOtpSent(true);
        } catch (error) {
            console.error("Error sending OTP:", error);
        }
    };

    const submit = async (e) => {
        e.preventDefault();
        const fullPhoneNumber = data.phone; 
        setData("phone", fullPhoneNumber);
        await post(route("register"), data);
    };

    return (
        <GuestLayout title="Register">
            <Head title="Register" />
            <form onSubmit={otpSent ? submit : sendOtp}>
                <div>
                    <InputLabel htmlFor="phone" value="Phone Number" />
                    <div className="flex items-center mt-1">
                        {/* Fixed +88 prefix */}
                        <div className="bg-gray-100 p-2 mt-1 border border-gray-300 rounded-md text-sm">
                            +88
                        </div>
                        <TextInput
                            id="phone"
                            type="text"
                            name="phone"
                            value={data.phone}
                            className="mt-1 block w-full"
                            autoComplete="phone"
                            isFocused={true}
                            onChange={(e) => setData("phone", e.target.value)}
                        />
                    </div>
                    <InputError message={errors.phone} className="mt-2" />
                </div>
                <div className="mt-4">
                    <div className="flex gap-1">
                        <InputLabel
                            htmlFor="referrer_code"
                            value="Referral Code"
                        />
                        <div className="text-xs text-gray-400">*optional</div>
                    </div>
                    <TextInput
                        id="referrer_code"
                        name="referrer_code"
                        value={data.referrer_code}
                        className="mt-1 block w-full"
                        autoComplete="off"
                        disabled={isReferralDisabled}
                        onChange={(e) =>
                            setData("referrer_code", e.target.value)
                        }
                    />
                    <InputError
                        message={errors.referrer_code}
                        className="mt-2"
                    />
                </div>
                {otpSent && (
                    <div className="mt-4">
                        <InputLabel htmlFor="otp" value="OTP" />
                        <TextInput
                            id="otp"
                            type="text"
                            name="otp"
                            value={data.otp}
                            className="mt-1 block w-full"
                            onChange={(e) => setData("otp", e.target.value)}
                        />
                        <InputError message={errors.otp} className="mt-2" />
                    </div>
                )}
                <div className="flex items-center justify-end mt-4">
                    <Link
                        href={route("login")}
                        className="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 dark:focus:ring-offset-gray-800"
                    >
                        Have an account already?
                    </Link>
                    {otpSent && (
                        <PrimaryButton className="ml-4" disabled={processing}>
                            Register
                        </PrimaryButton>
                    )}
                    {!otpSent && (
                        <PrimaryButton
                            className="ml-4"
                            disabled={processing}
                            isLoading={processing}
                        >
                            Send OTP
                        </PrimaryButton>
                    )}
                </div>
            </form>
        </GuestLayout>
    );
}
