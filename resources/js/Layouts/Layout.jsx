import ApplicationLogo from "@/Components/ApplicationLogo";
import Navbar from "@/Layouts/Navbar/Navbar";
import { Link, usePage } from "@inertiajs/react";
import Cart from "./Cart";
import Footer from "./Footer/Footer";
import NotificationToast from "@/Components/Notification/NotificationToast";

export default function Layout({ children }) {
    const { auth } = usePage().props;
    return (
        <>
            <NotificationToast />
            <Navbar auth={auth} />
            <Cart />
            <main>{children}</main>
            <Footer />
        </>
    );
}
