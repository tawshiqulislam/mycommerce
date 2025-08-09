import TextInput from "@/Components/Form/TextInput";
import Textarea from "@/Components/Form/Textarea";
import { FormGrid } from "@/Components/Form/FormGrid";
import InputLabel from "@/Components/Form/InputLabel";
import { PaymentElement } from "@stripe/react-stripe-js";

import { useContext } from "react";
import { CheckoutContext } from "@/Components/Context/CheckoutProvider";
import { usePage } from "@inertiajs/react";
import InputError from "@/Components/Form/InputError";

const ShippingAddress = ({ handleSubmit }) => {
    const { userForm } = useContext(CheckoutContext);
    const { errors } = usePage().props;
    return (
        <>
            <div>
                <h3 className="text-lg font-medium mb-4">
                    Shipping information
                </h3>
                <FormGrid>
                    <div className="md:col-span-3">
                        <InputLabel>Name*</InputLabel>
                        <TextInput
                            name="name"
                            required
                            onChange={(e) =>
                                userForm.setData("name", e.target.value)
                            }
                            className="w-full"
                            value={userForm.data.name}
                        />
                        <InputError message={errors.name} className="mt-2" />
                    </div>
                    <div className="md:col-span-3">
                        <InputLabel>Phone*</InputLabel>
                        <div className="flex items-center">
                        {/* Fixed +88 prefix */}
                            <div className="bg-gray-100 p-2 border border-gray-300 rounded-md text-sm">
                                +88
                            </div>
                            <TextInput
                                name="phone"
                                required
                                onChange={(e) =>
                                    userForm.setData("phone", e.target.value)
                                }
                                className="w-full"
                                value={userForm.data.phone}
                            />
                        </div>
                        <InputError message={errors.phone} className="mt-2" />
                    </div>
                    <div className="md:col-span-full">
                        <InputLabel>Address*</InputLabel>
                        <TextInput
                            name="address"
                            required
                            onChange={(e) =>
                                userForm.setData("address", e.target.value)
                            }
                            className="w-full"
                            value={userForm.data.address}
                        />
                        <InputError message={errors.address} className="mt-2" />
                    </div>
                    <div className="md:col-span-3">
                        <InputLabel>Email*</InputLabel>
                        <TextInput
                            name="email"
                            required
                            onChange={(e) =>
                                userForm.setData("email", e.target.value)
                            }
                            className="w-full"
                            value={userForm.data.email}
                        />
                        <InputError message={errors.email} className="mt-2" />
                    </div>
                    <div className="md:col-span-3">
                        <InputLabel>City*</InputLabel>
                        <TextInput
                            name="city"
                            required
                            onChange={(e) =>
                                userForm.setData("city", e.target.value)
                            }
                            className="w-full"
                            value={userForm.data.city}
                        />
                        <InputError message={errors.city} className="mt-2" />
                    </div>
                    {/* <div className="md:col-span-3">
                        <InputLabel>Post code</InputLabel>
                        <TextInput
                            name="postalCode"
                            required
                            onChange={(e) =>
                                userForm.setData("postalCode", e.target.value)
                            }
                            className="w-full"
                            value={userForm.data.postalCode}
                        />
                        <InputError
                            message={errors.postalCode}
                            className="mt-2"
                        />
                    </div> */}

                    <div className="md:col-span-6">
                        <InputLabel>Additional note</InputLabel>
                        <Textarea
                            name="note"
                            label="Additional note"
                            onChange={(e) =>
                                userForm.setData("note", e.target.value)
                            }
                            rows="3"
                            value={userForm.data.note}
                        />
                        <InputError message={errors.note} className="mt-2" />
                    </div>
                </FormGrid>
            </div>
        </>
    );
};

export default ShippingAddress;
