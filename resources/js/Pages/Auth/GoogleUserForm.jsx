import React, { useState } from "react";
import { Inertia } from "@inertiajs/inertia";
import { Head } from "@inertiajs/react";
import GuestLayout from "@/Layouts/GuestLayout";
import InputLabel from "@/Components/Form/InputLabel";
import TextInput from "@/Components/Form/TextInput";

const GoogleUserForm = ({ name, email, google_id }) => {
    const [phone, setPhone] = useState("");
    const [referrer_code, setReferrerCode] = useState("");

    const handleSubmit = (event) => {
        event.preventDefault();
        Inertia.post("/store-google-user", {
            name,
            email,
            google_id,
            phone,
            referrer_code,
        });
    };

    return (
        <GuestLayout title="Finish creating your account">
            <Head title="Login" />
            <form onSubmit={handleSubmit}>
                <div>
                    <InputLabel htmlFor="phone" value="Phone Number" />
                    <TextInput
                        type="text"
                        name="phone"
                        value={phone}
                        className="mt-1 block w-full"
                        isFocused={true}
                        onChange={(e) => setPhone(e.target.value)}
                        required
                    />
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
                        type="text"
                        name="referrer_code"
                        value={referrer_code}
                        className="mt-1 block w-full"
                        onChange={(e) => setReferrerCode(e.target.value)}
                    />
                </div>
                <button
                    className="mt-4 btn btn-primary w-full flex justify-center"
                    type="submit"
                >
                    Submit
                </button>
            </form>
        </GuestLayout>
    );
};

export default GoogleUserForm;
