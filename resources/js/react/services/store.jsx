import { createContext, useContext, useMemo, useState } from 'react';

const StoreContext = createContext(null);

const read = (key, fallback) => {
    try {
        return JSON.parse(localStorage.getItem(key)) ?? fallback;
    } catch {
        return fallback;
    }
};

const write = (key, value) => localStorage.setItem(key, JSON.stringify(value));

export function StoreProvider({ children }) {
    const [cart, setCart] = useState(() => read('nora_cart', []));
    const [wishlist, setWishlist] = useState(() => read('nora_wishlist', []));

    const addToCart = (product, quantity = 1) => {
        setCart((items) => {
            const current = items.find((item) => item.product.id === product.id);
            const next = current
                ? items.map((item) => item.product.id === product.id ? { ...item, quantity: Math.min(item.quantity + quantity, product.stock_quantity || item.quantity + quantity) } : item)
                : [...items, { product, quantity }];
            write('nora_cart', next);
            return next;
        });
    };

    const updateCart = (productId, quantity) => {
        setCart((items) => {
            const next = items.map((item) => item.product.id === productId ? { ...item, quantity: Math.max(1, quantity) } : item);
            write('nora_cart', next);
            return next;
        });
    };

    const removeFromCart = (productId) => {
        setCart((items) => {
            const next = items.filter((item) => item.product.id !== productId);
            write('nora_cart', next);
            return next;
        });
    };

    const toggleWishlist = (product) => {
        setWishlist((items) => {
            const exists = items.some((item) => item.id === product.id);
            const next = exists ? items.filter((item) => item.id !== product.id) : [...items, product];
            write('nora_wishlist', next);
            return next;
        });
    };

    const value = useMemo(() => ({
        cart,
        wishlist,
        addToCart,
        updateCart,
        removeFromCart,
        toggleWishlist,
        isWishlisted: (productId) => wishlist.some((item) => item.id === productId),
        cartCount: cart.reduce((sum, item) => sum + item.quantity, 0),
    }), [cart, wishlist]);

    return <StoreContext.Provider value={value}>{children}</StoreContext.Provider>;
}

export const useStore = () => useContext(StoreContext);
