import { useEffect, useState } from "react";
import Checkbox from "@/Components/Checkbox";
import GuestLayout from "@/Layouts/GuestLayout";
import InputError from "@/Components/Form/InputError";
import InputLabel from "@/Components/Form/InputLabel";
import PrimaryButton from "@/Components/PrimaryButton";
import TextInput from "@/Components/Form/TextInput";
import { Head, Link, useForm } from "@inertiajs/react";
import axios from "axios";

export default function Login({ status }) {
    const { data, setData, post, processing, errors, reset } = useForm({
        phone: "",
        otp: "",
        remember: false,
    });

    const [otpSent, setOtpSent] = useState(false);

    useEffect(() => {
        if (errors.phone) {
            setOtpSent(false);
        }
    }, [errors]);

    const sendOtp = async (e) => {
        e.preventDefault();
        try {
            const fullPhoneNumber = data.phone;
            await axios.post(route("send-login"), { phone: fullPhoneNumber });
            setOtpSent(true);
        } catch (error) {
            console.error("Error sending OTP:", error);
        }
    };

    const submit = async (e) => {
        e.preventDefault();
        const fullPhoneNumber = data.phone;
        await post(route("login"), { ...data, phone: fullPhoneNumber });
    };

    return (
        <GuestLayout title="Log into your account">
            <Head title="Login" />
            {status && (
                <div className="mb-4 font-medium text-sm text-green-600">
                    {status}
                </div>
            )}
            <div className="flex justify-center mb-4">
                <a
                    href={route("auth.google")}
                    className="flex justify-center items-center bg-white border border-gray-100 rounded-full w-12 h-12 shadow-lg cursor-pointer"
                >
                    <img src="/img/icons/google.webp" alt="Google" />
                </a>
                <a
                    href={route("auth.facebook")}
                    className="flex justify-center items-center bg-white border border-gray-100 rounded-full w-12 h-12 shadow-lg cursor-pointer"
                >
                    <img src="/img/icons/facebook.webp" alt="Facebook" />
                </a>
            </div>
            <form onSubmit={otpSent ? submit : sendOtp}>
                <div>
                    <InputLabel htmlFor="phone" value="Phone Number" />
                    <div className="flex items-center mt-1">
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
                <label className="flex items-center mt-2">
                    <Checkbox
                        name="remember"
                        checked={data.remember}
                        onChange={(e) => setData("remember", e.target.checked)}
                    />
                    <span className="ml-2 text-sm text-gray-600 dark:text-gray-400">
                        Remember me
                    </span>
                </label>
                <div className="flex items-center justify-between mt-4">
                    {otpSent && (
                        <PrimaryButton
                            className="w-full mt-4 flex justify-center"
                            disabled={processing}
                            isLoading={processing}
                        >
                            Login
                        </PrimaryButton>
                    )}
                </div>
                {!otpSent && (
                    <PrimaryButton
                        className="w-full mt-8 flex justify-center"
                        disabled={processing}
                        isLoading={processing}
                    >
                        Send OTP
                    </PrimaryButton>
                )}
            </form>
        </GuestLayout>
    );
}
