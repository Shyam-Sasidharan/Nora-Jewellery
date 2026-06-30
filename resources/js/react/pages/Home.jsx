import { motion } from 'framer-motion';
import { useEffect, useState } from 'react';
import { Link } from 'react-router-dom';
import CollectionCard from '../components/CollectionCard.jsx';
import HeroSection from '../components/HeroSection.jsx';
import LoadingSkeleton from '../components/LoadingSkeleton.jsx';
import ProductCard from '../components/ProductCard.jsx';
import { storefront } from '../services/api.js';

export default function Home() {
    const [data, setData] = useState(null);
    const [error, setError] = useState('');

    useEffect(() => {
        storefront.home().then(setData).catch(() => setError('Unable to load Nora Jewellery right now.'));
    }, []);

    if (error) return <div className="react-page-message">{error}</div>;
    if (!data) return <LoadingSkeleton cards={8} />;

    return (
        <>
            <HeroSection banners={data.banners} />
            <section className="react-section react-intro">
                <motion.span initial={{ opacity: 0 }} whileInView={{ opacity: 1 }} viewport={{ once: true }}>Signature Craft</motion.span>
                <motion.h2 initial={{ opacity: 0, y: 24 }} whileInView={{ opacity: 1, y: 0 }} viewport={{ once: true }}>{data.about?.title || 'A House Of Quiet Radiance'}</motion.h2>
                <p>{data.about?.content}</p>
                <div className="react-metrics">
                    <div><strong>18K</strong><small>Fine gold craft</small></div>
                    <div><strong>900+</strong><small>Private designs</small></div>
                    <div><strong>1:1</strong><small>Concierge styling</small></div>
                </div>
            </section>

            <section className="react-section" id="react-collections">
                <div className="react-section-heading">
                    <span>Luxury Collections</span>
                    <h2>Shop By Category</h2>
                </div>
                <div className="react-collection-grid">
                    {data.categories.map((category, index) => <CollectionCard category={category} index={index} key={category.id} />)}
                </div>
            </section>

            <section className="react-section react-featured">
                <div className="react-section-heading">
                    <span>Featured Jewellery</span>
                    <h2>Designed To Catch The Light</h2>
                    <Link to="/collections">View all</Link>
                </div>
                <div className="react-product-grid">
                    {data.featuredProducts.map((product, index) => <ProductCard product={product} index={index} key={product.id} />)}
                </div>
            </section>

            <section className="react-editorial">
                <div>
                    <span>Nora Atelier</span>
                    <h2>Jewellery styled like sculpture, finished like an heirloom.</h2>
                    <p>Every piece is presented with quiet drama: luminous surfaces, restrained silhouettes, and details that reward a closer look.</p>
                    <Link className="react-gold-button" to="/contact">Book Private Appointment</Link>
                </div>
                <div className="react-editorial-images">
                    {data.featuredProducts.slice(0, 3).map((product) => (
                        <Link to={`/jewellery/${product.slug}`} key={product.id}>
                            <img src={product.primary_image?.url || '/images/product-placeholder.svg'} alt={product.name} loading="lazy" />
                        </Link>
                    ))}
                </div>
            </section>

            <section className="react-section">
                <div className="react-section-heading">
                    <span>New Arrivals</span>
                    <h2>Fresh From The Atelier</h2>
                </div>
                <div className="react-product-grid compact">
                    {data.newArrivals.map((product, index) => <ProductCard product={product} index={index} key={product.id} />)}
                </div>
            </section>

            <section className="react-trust">
                {['Certified Finish', 'Private Concierge', 'Luxury Delivery'].map((title, index) => (
                    <article key={title}>
                        <span>0{index + 1}</span>
                        <h3>{title}</h3>
                        <p>{['Premium materials, careful setting, and polish standards built for long wear.', 'Appointments for bridal, gifting, custom pieces, and collection guidance.', 'Secure order handling with delivery settings managed directly from CMS.'][index]}</p>
                    </article>
                ))}
            </section>

            <section className="react-testimonials">
                {data.testimonials.map((item) => (
                    <article key={item.name}>
                        <p>“{item.text}”</p>
                        <strong>{item.name}</strong>
                    </article>
                ))}
            </section>
        </>
    );
}
