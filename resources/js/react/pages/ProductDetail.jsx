import { Heart, Minus, Plus, ShoppingBag } from 'lucide-react';
import { useEffect, useState } from 'react';
import { Link, useParams } from 'react-router-dom';
import LoadingSkeleton from '../components/LoadingSkeleton.jsx';
import ProductCard from '../components/ProductCard.jsx';
import { storefront } from '../services/api.js';
import { useStore } from '../services/store.jsx';

export default function ProductDetail() {
    const { slug } = useParams();
    const [payload, setPayload] = useState(null);
    const [quantity, setQuantity] = useState(1);
    const { addToCart, toggleWishlist, isWishlisted } = useStore();

    useEffect(() => {
        setPayload(null);
        storefront.product(slug).then(setPayload);
    }, [slug]);

    if (!payload) return <LoadingSkeleton cards={4} />;

    const product = payload.data;
    const image = product.primary_image?.url || '/images/product-placeholder.svg';
    const canBuy = product.is_in_stock && !product.price_on_request && product.price !== null;

    return (
        <>
            <section className="react-detail">
                <div className="react-detail-media">
                    <img src={image} alt={product.name} />
                    <div>
                        {product.images.map((item) => <img src={item.url} alt={item.alt} key={item.id} />)}
                    </div>
                </div>
                <div className="react-detail-copy">
                    <span>{product.category?.name}</span>
                    <h1>{product.name}</h1>
                    <div className="react-price detail">
                        {product.compare_at_price_label && <del>{product.compare_at_price_label}</del>}
                        <strong>{product.price_label}</strong>
                    </div>
                    <small className={product.is_in_stock ? 'in-stock' : 'out-stock'}>{product.is_in_stock ? `${product.stock_quantity} in stock` : 'Out of stock'}</small>
                    <p>{product.short_description}</p>
                    <div className="react-description">{product.description}</div>
                    <div className="react-detail-actions">
                        <div className="qty-stepper">
                            <button type="button" onClick={() => setQuantity(Math.max(1, quantity - 1))}><Minus size={16} /></button>
                            <strong>{quantity}</strong>
                            <button type="button" onClick={() => setQuantity(Math.min(product.stock_quantity || quantity + 1, quantity + 1))}><Plus size={16} /></button>
                        </div>
                        <button className="react-gold-button" type="button" disabled={!canBuy} onClick={() => addToCart(product, quantity)}><ShoppingBag size={18} /> Add To Cart</button>
                        <button className={`react-wish-button ${isWishlisted(product.id) ? 'active' : ''}`} type="button" onClick={() => toggleWishlist(product)}><Heart size={18} /> Wishlist</button>
                    </div>
                    <Link className="react-outline-button" to={`/contact?subject=${encodeURIComponent(`Enquiry for ${product.name}`)}`}>Request Appointment</Link>
                </div>
            </section>
            {payload.related.length > 0 && (
                <section className="react-section">
                    <div className="react-section-heading">
                        <span>Related</span>
                        <h2>You May Also Love</h2>
                    </div>
                    <div className="react-product-grid compact">
                        {payload.related.map((item, index) => <ProductCard product={item} index={index} key={item.id} />)}
                    </div>
                </section>
            )}
        </>
    );
}
