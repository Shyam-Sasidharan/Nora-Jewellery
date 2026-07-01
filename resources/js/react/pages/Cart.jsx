import { Link } from 'react-router-dom';
import { useEffect, useMemo, useState } from 'react';
import { storefront } from '../services/api.js';
import { useStore } from '../services/store.jsx';

export default function Cart() {
    const { cart, updateCart, removeFromCart, clearCart } = useStore();
    const [delivery, setDelivery] = useState({ is_free_delivery: true, delivery_charge: 0 });
    const [checkout, setCheckout] = useState({
        customer_name: '',
        customer_email: '',
        customer_phone: '',
        shipping_address: '',
        notes: '',
    });
    const [submitting, setSubmitting] = useState(false);
    const [notice, setNotice] = useState('');
    const [error, setError] = useState('');

    useEffect(() => {
        storefront.delivery().then((response) => setDelivery(response.data));
    }, []);

    const subtotal = useMemo(() => cart.reduce((sum, item) => sum + Number(item.product.price || 0) * item.quantity, 0), [cart]);
    const deliveryCharge = delivery.is_free_delivery ? 0 : Number(delivery.delivery_charge || 0);
    const total = subtotal + deliveryCharge;
    const hasUnavailableItems = cart.some((item) => item.product.price_on_request || !item.product.is_in_stock || item.quantity > item.product.stock_quantity);

    const placeOrder = async (event) => {
        event.preventDefault();
        setNotice('');
        setError('');
        setSubmitting(true);

        try {
            const response = await storefront.checkout({
                ...checkout,
                items: cart.map((item) => ({
                    product_id: item.product.id,
                    quantity: item.quantity,
                })),
            });

            clearCart();
            setCheckout({ customer_name: '', customer_email: '', customer_phone: '', shipping_address: '', notes: '' });
            setNotice(`${response.message} Order number: ${response.order.order_number}`);
        } catch (exception) {
            setError(exception.response?.data?.message || 'Unable to place order. Please check your details and try again.');
        } finally {
            setSubmitting(false);
        }
    };

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
                                        {(item.product.price_on_request || !item.product.is_in_stock) && <small>Not available for direct checkout</small>}
                                    </div>
                                    <input type="number" min="1" max={item.product.stock_quantity || 1} value={item.quantity} onChange={(event) => updateCart(item.product.id, Number(event.target.value))} />
                                    <strong>₹{(Number(item.product.price || 0) * item.quantity).toLocaleString('en-IN', { maximumFractionDigits: 2 })}</strong>
                                    <button type="button" onClick={() => removeFromCart(item.product.id)}>Remove</button>
                                </article>
                            ))}
                        </div>
                        <aside className="react-cart-summary">
                            <div><span>Subtotal</span><strong>₹{subtotal.toLocaleString('en-IN', { maximumFractionDigits: 2 })}</strong></div>
                            <div><span>Delivery</span><strong>{deliveryCharge > 0 ? `₹${deliveryCharge.toLocaleString('en-IN')}` : 'Free'}</strong></div>
                            <div><span>Total</span><strong>₹{total.toLocaleString('en-IN', { maximumFractionDigits: 2 })}</strong></div>
                            {notice && <div className="react-notice success">{notice}</div>}
                            {error && <div className="react-notice error">{error}</div>}
                            {hasUnavailableItems && <div className="react-notice error">Remove request-only or out-of-stock items before checkout.</div>}
                            <form className="react-cart-checkout" onSubmit={placeOrder}>
                                <input required placeholder="Full name" value={checkout.customer_name} onChange={(event) => setCheckout({ ...checkout, customer_name: event.target.value })} />
                                <input required type="email" placeholder="Email" value={checkout.customer_email} onChange={(event) => setCheckout({ ...checkout, customer_email: event.target.value })} />
                                <input placeholder="Phone" value={checkout.customer_phone} onChange={(event) => setCheckout({ ...checkout, customer_phone: event.target.value })} />
                                <textarea required rows="4" placeholder="Delivery address" value={checkout.shipping_address} onChange={(event) => setCheckout({ ...checkout, shipping_address: event.target.value })} />
                                <textarea rows="3" placeholder="Order notes" value={checkout.notes} onChange={(event) => setCheckout({ ...checkout, notes: event.target.value })} />
                                <button className="react-gold-button" type="submit" disabled={submitting || hasUnavailableItems}>
                                    {submitting ? 'Placing Order...' : 'Place Order'}
                                </button>
                            </form>
                        </aside>
                    </>
                )}
            </section>
        </>
    );
}
