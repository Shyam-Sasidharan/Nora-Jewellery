import { Link } from 'react-router-dom';
import ProductCard from '../components/ProductCard.jsx';
import { useStore } from '../services/store.jsx';

export default function Wishlist() {
    const { wishlist } = useStore();

    return (
        <>
            <section className="react-page-hero">
                <span>Wishlist</span>
                <h1>Your Saved Nora Pieces</h1>
            </section>
            <section className="react-section react-product-grid">
                {wishlist.map((product, index) => <ProductCard product={product} index={index} key={product.id} />)}
                {wishlist.length === 0 && (
                    <div className="react-page-message">
                        Your wishlist is empty.
                        <Link className="react-gold-button" to="/collections">Explore Jewellery</Link>
                    </div>
                )}
            </section>
        </>
    );
}
