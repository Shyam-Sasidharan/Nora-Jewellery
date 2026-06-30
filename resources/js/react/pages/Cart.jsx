import { Link } from 'react-router-dom';
import { useEffect, useMemo, useState } from 'react';
import { storefront } from '../services/api.js';
import { useStore } from '../services/store.jsx';

export default function Cart() {
    const { cart, updateCart, removeFromCart } = useStore();
    const [delivery, setDelivery] = useState({ is_free_delivery: true, delivery_charge: 0 });

    useEffect(() => {
        storefront.delivery().then((response) => setDelivery(response.data));
    }, []);

    const subtotal = useMemo(() => cart.reduce((sum, item) => sum + Number(item.product.price || 0) * item.quantity, 0), [cart]);
    const deliveryCharge = delivery.is_free_delivery ? 0 : Number(delivery.delivery_charge || 0);
    const total = subtotal + deliveryCharge;

    return (
        <>
            <section className="react-page-hero">
                <span>Cart</span>
                <h1>Your Jewellery Selection</h1>
            </section>
            <section className="react-cart">
                {cart.length === 0 ? (
                    <div className="react-page-message">
                        Your cart is empty.
                        <Link className="react-gold-button" to="/collections">Shop Collections</Link>
                    </div>
                ) : (
                    <>
                        <div className="react-cart-list">
                            {cart.map((item) => (
                                <article key={item.product.id}>
                                    <img src={item.product.primary_image?.url || '/images/product-placeholder.svg'} alt={item.product.name} />
                                    <div>
                                        <h3>{item.product.name}</h3>
                                        <span>{item.product.price_label}</span>
                                    </div>
                                    <input type="number" min="1" value={item.quantity} onChange={(event) => updateCart(item.product.id, Number(event.target.value))} />
                                    <strong>₹{(Number(item.product.price || 0) * item.quantity).toLocaleString('en-IN', { maximumFractionDigits: 2 })}</strong>
                                    <button type="button" onClick={() => removeFromCart(item.product.id)}>Remove</button>
                                </article>
                            ))}
                        </div>
                        <aside className="react-cart-summary">
                            <div><span>Subtotal</span><strong>₹{subtotal.toLocaleString('en-IN', { maximumFractionDigits: 2 })}</strong></div>
                            <div><span>Delivery</span><strong>{deliveryCharge > 0 ? `₹${deliveryCharge.toLocaleString('en-IN')}` : 'Free'}</strong></div>
                            <div><span>Total</span><strong>₹{total.toLocaleString('en-IN', { maximumFractionDigits: 2 })}</strong></div>
                            <Link className="react-gold-button" to="/contact?subject=Checkout%20Enquiry">Checkout Enquiry</Link>
                        </aside>
                    </>
                )}
            </section>
        </>
    );
}
