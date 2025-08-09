import React from 'react';
import { Head } from '@inertiajs/react';
import Layout from "@/Layouts/Layout";

const LegalPage = ({ legalPage }) => {
    // If legalPage is null or undefined, show a loading or error message
    if (!legalPage) {
        return (
            <div className="flex items-center justify-center min-h-screen">
                <p>Loading...</p>
            </div>
        );
    }

    return (
        <Layout>
        <div className="flex flex-col items-center justify-start min-h-screen px-4 pt-8">
            <Head title={legalPage.title} />
            <div className="w-full max-w-6xl p-6 bg-white rounded-lg shadow-md">
                <h1 className="mb-6 text-3xl font-bold text-center">{legalPage.title}</h1>
                <div className="prose prose-lg" dangerouslySetInnerHTML={{ __html: legalPage.content }} />
            </div>
        </div>
        </Layout>
    );
};

export default LegalPage;