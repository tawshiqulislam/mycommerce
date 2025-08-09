import React, { useState } from "react";
import { Head, useForm, usePage } from "@inertiajs/react";
import LayoutProfile from "../../Layouts/LayoutProfile";
import TextInput from "@/Components/Form/TextInput";
import TextArea from "@/Components/Form/Textarea";
import PrimaryButton from "@/Components/PrimaryButton";
import InputLabel from "@/Components/Form/InputLabel";
import InputError from "@/Components/Form/InputError";
import { FormGrid } from "@/Components/Form/FormGrid";
import StarRating from "@/Components/Form/StarRating";

const Referrals = ({ review }) => {
    const { auth } = usePage().props;
    const { data, setData, post, patch, processing, errors } = useForm({
        name: review?.name || "",
        company: review?.company || "",
        rating: review?.rating || 5,
        review: review?.review || "",
    });

    const handleSubmit = (e) => {
        e.preventDefault();
        if (review) {
            patch(route("profile.review.update", review.id), {
                data,
                preserveScroll: true,
            });
        } else {
            post(route("profile.review.store"), {
                data,
                preserveScroll: true,
            });
        }
    };

    return (
        <LayoutProfile
            title="Review"
            breadcrumb={[
                {
                    title: "Review",
                    path: route("profile.review"),
                },
            ]}
        >
            <Head title="Review" />
            <h4 className="mb-4">
                Please leave a review of your experience. We may feature it on
                our homepage!
            </h4>
            <div className="space-y-2">
                <form onSubmit={handleSubmit} encType="multipart/form-data">
                    <FormGrid className="max-w-2xl">
                        <div className="sm:col-span-3">
                            <InputLabel>Name *</InputLabel>
                            <TextInput
                                className="w-full mt-2"
                                onChange={(e) =>
                                    setData("name", e.target.value)
                                }
                                name="name"
                                value={data.name}
                                placeholder="Name"
                            />
                            <InputError message={errors.name} />
                        </div>
                        <div className="sm:col-span-3">
                            <InputLabel>
                                Company/Affiliation (Optional)
                            </InputLabel>
                            <TextInput
                                className="w-full mt-2"
                                onChange={(e) =>
                                    setData("company", e.target.value)
                                }
                                name="company"
                                value={data.company}
                                placeholder="Affiliation"
                            />
                            <InputError message={errors.company} />
                        </div>
                        <div className="sm:col-span-6">
                            <InputLabel>Rating *</InputLabel>
                            <StarRating
                                rating={data.rating}
                                setRating={(value) => setData("rating", value)}
                            />
                            <InputError message={errors.rating} />
                        </div>
                        <div className="sm:col-span-6">
                            <InputLabel>Review *</InputLabel>
                            <TextArea
                                className="w-full mt-2"
                                onChange={(e) =>
                                    setData("review", e.target.value)
                                }
                                name="review"
                                value={data.review}
                                placeholder="Review"
                                rows="4"
                            ></TextArea>
                            <InputError message={errors.review} />
                        </div>

                        <div className="text-right sm:col-span-6">
                            <PrimaryButton
                                disabled={processing}
                                isLoading={processing}
                            >
                                Submit
                            </PrimaryButton>
                        </div>
                    </FormGrid>
                </form>
            </div>
        </LayoutProfile>
    );
};

export default Referrals;
