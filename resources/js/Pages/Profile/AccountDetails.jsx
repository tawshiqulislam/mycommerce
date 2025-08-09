import TextInput from "@/Components/Form/TextInput";
import PrimaryButton from "@/Components/PrimaryButton";
import LayoutProfile from "../../Layouts/LayoutProfile";
import { Head, useForm, usePage } from "@inertiajs/react";
import { useState } from "react";
import InputLabel from "@/Components/Form/InputLabel";
import InputError from "@/Components/Form/InputError";
import SectionTitle from "@/Components/Sections/SectionTitle";
import { FormGrid } from "@/Components/Form/FormGrid";

const AccountDetails = () => {
    const { auth } = usePage().props;

    const [notification, setNotifications] = useState({});
    const { data, setData, patch, processing, errors } = useForm({
        name: auth.user.name,
        phone: auth.user.phone,
        email: auth.user.email,
        city: auth.user.city,
        country: auth.user.country,
        address: auth.user.address,
    });

    const handleSubmit = (e) => {
        e.preventDefault();
        patch(route("profile.account-details.update"), {
            preserveScroll: true,
        });
    };
    return (
        <LayoutProfile
            title="Account details"
            breadcrumb={[
                {
                    title: "Account details",
                    path: route("profile.account-details"),
                },
            ]}
        >
            <Head title="Account details" />
            <div className="space-y-2">
                <form onSubmit={handleSubmit}>
                    <FormGrid className="max-w-2xl">
                        <div className="sm:col-span-3">
                            <InputLabel>Full name *</InputLabel>
                            <TextInput
                                className="w-full mt-2"
                                onChange={(e) =>
                                    setData("name", e.target.value)
                                }
                                name="name"
                                value={data.name}
                                placeholder={"Full name"}
                            />
                            <InputError message={errors.name} />
                        </div>
                        <div className=" sm:col-span-3">
                            <InputLabel>Phone *</InputLabel>
                            <div className="flex items-center">
                            <div className="bg-gray-100 p-2 mt-1 border border-gray-300 rounded-md text-sm">
                                +88
                            </div>
                            <TextInput
                                className="w-full mt-1"
                                onChange={(e) =>
                                    setData("phone", e.target.value)
                                }
                                name="phone"
                                value={data.phone}
                                placeholder={"Phone"}
                            />
                            </div>
                            <InputError message={errors.phone} />
                        </div>
                        <div className="sm:col-span-3">
                            <InputLabel>Email *</InputLabel>
                            <TextInput
                                className="w-full mt-2"
                                type="email"
                                onChange={(e) =>
                                    setData("email", e.target.value)
                                }
                                name="email"
                                value={data.email}
                                placeholder={"Email"}
                            />
                            <InputError message={errors.email} />
                        </div>
                        <div className="sm:col-span-3">
                            <InputLabel>Address</InputLabel>
                            <TextInput
                                className="w-full mt-2
							"
                                onChange={(e) =>
                                    setData("address", e.target.value)
                                }
                                value={data.address}
                                name="address"
                                placeholder={"Address"}
                            />
                        </div>
                        <div className="sm:col-span-3">
                            <InputLabel>City</InputLabel>
                            <TextInput
                                className="w-full mt-2"
                                onChange={(e) =>
                                    setData("city", e.target.value)
                                }
                                name="city"
                                value={data.city}
                                placeholder={"City"}
                            />
                            <InputError message={errors.city} />
                        </div>
                        <div className="sm:col-span-3">
                            <InputLabel>Country</InputLabel>
                            <TextInput
                                className="w-full mt-2"
                                onChange={(e) =>
                                    setData("country", e.target.value)
                                }
                                name="country"
                                value={data.country}
                                placeholder={"Country"}
                            />
                            <InputError message={errors.country} />
                        </div>
                        <div className="text-right sm:col-span-6">
                            <PrimaryButton
                                disabled={processing}
                                isLoading={processing}
                            >
                                Update
                            </PrimaryButton>
                        </div>
                    </FormGrid>
                </form>
            </div>
        </LayoutProfile>
    );
};

export default AccountDetails;
