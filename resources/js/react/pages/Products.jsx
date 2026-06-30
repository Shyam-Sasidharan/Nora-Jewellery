import { Search } from 'lucide-react';
import { useEffect, useState } from 'react';
import { useParams, useSearchParams } from 'react-router-dom';
import LoadingSkeleton from '../components/LoadingSkeleton.jsx';
import ProductCard from '../components/ProductCard.jsx';
import { storefront } from '../services/api.js';

export default function Products({ searchMode = false }) {
    const { category } = useParams();
    const [searchParams, setSearchParams] = useSearchParams();
    const [payload, setPayload] = useState(null);
    const [query, setQuery] = useState(searchParams.get('q') || '');

    useEffect(() => {
        setPayload(null);
        storefront.products({ category, q: searchParams.get('q') || undefined }).then(setPayload);
    }, [category, searchParams]);

    const submit = (event) => {
        event.preventDefault();
        setSearchParams(query ? { q: query } : {});
    };

    return (
        <>
            <section className="react-page-hero">
                <span>{payload?.category?.name || (searchMode ? 'Search' : 'Collections')}</span>
                <h1>{payload?.category?.description || 'Fine Jewellery For The Moments That Stay'}</h1>
            </section>
            <section className="react-section react-product-tools">
                <form onSubmit={submit}>
                    <Search size={18} />
                    <input value={query} onChange={(event) => setQuery(event.target.value)} placeholder="Search jewellery" />
                    <button className="react-gold-button" type="submit">Search</button>
                </form>
            </section>
            {!payload ? <LoadingSkeleton cards={8} /> : (
                <section className="react-section react-product-grid">
                    {payload.data.map((product, index) => <ProductCard product={product} index={index} key={product.id} />)}
                    {payload.data.length === 0 && <div className="react-page-message">No jewellery matched your search.</div>}
                </section>
            )}
        </>
    );
}
