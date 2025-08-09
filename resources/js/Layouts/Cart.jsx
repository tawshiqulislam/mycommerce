import react from "react";
import { Link, useForm, usePage } from "@inertiajs/react";

const Cart = () => {
    const { auth, filters, departments, settings } = usePage().props;
    return (
        <Link href={route("shopping-cart.index")}>
            <div className="flex flex-col z-20 group fixed top-[50%] right-5 flex items-center bg-white rounded-xl shadow-lg move-up">
                <img
                    src="/img/footer/cart.png"
                    alt="cart"
                    className="h-8 w-8"
                />
                <p className="text-center text-xs px-2">
                    {auth.shoppingCartCount}
                    {auth.shoppingCartCount <= 1 ? " item" : " items"}
                </p>
                <div className="w-full p-2 bg-cart bg-opacity-70 rounded-b-xl text-center text-xs font-bold text-white">
                    <p>৳{auth.totalCartValue.toFixed(2)}</p>
                </div>
                <span className="sr-only">items in cart, view cart</span>
            </div>
        </Link>
    );
};

export default Cart;
