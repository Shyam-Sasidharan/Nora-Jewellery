import { useEffect, useState } from 'react';
import CollectionCard from '../components/CollectionCard.jsx';
import LoadingSkeleton from '../components/LoadingSkeleton.jsx';
import { storefront } from '../services/api.js';

export default function Collections({ showGallery = false }) {
    const [items, setItems] = useState(null);

    useEffect(() => {
        (showGallery ? storefront.gallery() : storefront.categories()).then((response) => setItems(response.data));
    }, [showGallery]);

    if (!items) return <LoadingSkeleton cards={8} />;

    return (
        <>
            <section className="react-page-hero">
                <span>{showGallery ? 'Gallery' : 'Categories'}</span>
                <h1>{showGallery ? 'Light, Detail, And Occasion' : 'Every Collection, Beautifully Organized'}</h1>
            </section>
            <section className={showGallery ? 'react-gallery-grid' : 'react-collection-grid react-section'}>
                {showGallery
                    ? items.map((image) => <img key={image.id} src={image.url} alt={image.alt} loading="lazy" />)
                    : items.map((category, index) => <CollectionCard category={category} index={index} key={category.id} />)}
            </section>
        </>
    );
}
