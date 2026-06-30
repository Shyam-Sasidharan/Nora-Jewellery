import { motion } from 'framer-motion';
import { Link } from 'react-router-dom';

export default function CollectionCard({ category, index = 0 }) {
    return (
        <motion.div
            initial={{ opacity: 0, y: 24 }}
            whileInView={{ opacity: 1, y: 0 }}
            viewport={{ once: true }}
            transition={{ delay: index * 0.06 }}
        >
            <Link className="react-collection-card" to={`/collections/${category.slug}`} style={{ backgroundImage: `linear-gradient(180deg, rgba(6,5,4,.06), rgba(6,5,4,.82)), url(${category.image_url || '/images/product-placeholder.svg'})` }}>
                <span>{category.products_count || 0} pieces</span>
                <h3>{category.name}</h3>
                <p>{category.description}</p>
            </Link>
        </motion.div>
    );
}
