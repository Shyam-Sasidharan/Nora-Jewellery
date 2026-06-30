import { Heart, ShoppingBag } from 'lucide-react';
import { motion } from 'framer-motion';
import { Link } from 'react-router-dom';
import { useStore } from '../services/store.jsx';

export default function ProductCard({ product, index = 0 }) {
    const { addToCart, toggleWishlist, isWishlisted } = useStore();
    const image = product.primary_image?.url || '/images/product-placeholder.svg';
    const canBuy = product.is_in_stock && !product.price_on_request && product.price !== null;

    return (
        <motion.article
            className="react-product-card"
            initial={{ opacity: 0, y: 24 }}
            whileInView={{ opacity: 1, y: 0 }}
            viewport={{ once: true, margin: '-60px' }}
            transition={{ delay: index * 0.045, duration: 0.45 }}
        >
            <Link className="react-product-image" to={`/jewellery/${product.slug}`}>
                <img src={image} alt={product.name} loading="lazy" />
                <span className="shine" />
                {(product.is_new_arrival || product.is_featured) && <em>{product.is_new_arrival ? 'New' : 'Featured'}</em>}
            </Link>
            <div className="react-product-info">
                <span>{product.category?.name}</span>
                <h3><Link to={`/jewellery/${product.slug}`}>{product.name}</Link></h3>
                <div className="react-price">
                    {product.compare_at_price_label && <del>{product.compare_at_price_label}</del>}
                    <strong>{product.price_label}</strong>
                </div>
                <small className={product.is_in_stock ? 'in-stock' : 'out-stock'}>{product.is_in_stock ? `${product.stock_quantity} in stock` : 'Out of stock'}</small>
            </div>
            <div className="react-card-actions">
                <button type="button" onClick={() => toggleWishlist(product)} className={isWishlisted(product.id) ? 'active' : ''}><Heart size={17} /></button>
                <button type="button" disabled={!canBuy} onClick={() => addToCart(product, 1)}><ShoppingBag size={17} /> Add</button>
            </div>
        </motion.article>
    );
}
