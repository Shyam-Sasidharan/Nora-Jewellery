import { useEffect, useState } from 'react';
import LoadingSkeleton from '../components/LoadingSkeleton.jsx';
import { storefront } from '../services/api.js';

export default function About() {
    const [about, setAbout] = useState(null);

    useEffect(() => {
        storefront.about().then((response) => setAbout(response.data));
    }, []);

    if (!about) return <LoadingSkeleton cards={3} />;

    return (
        <>
            <section className="react-page-hero">
                <span>About Nora</span>
                <h1>{about.title}</h1>
            </section>
            <section className="react-about-grid">
                <div className="react-about-image">
                    <img src={about.image_url || 'https://images.unsplash.com/photo-1617038260897-41a1f14a8ca0?auto=format&fit=crop&w=1200&q=85'} alt={about.title} />
                </div>
                <div>
                    <span>Craft Philosophy</span>
                    <h2>A quiet language of gold, light, and lasting detail.</h2>
                    <p>{about.content}</p>
                    <div className="react-metrics">
                        <div><strong>{about.data?.heritage || '18K'}</strong><small>Gold-focused craft</small></div>
                        <div><strong>{about.data?.craft || 'Hand'}</strong><small>Finished details</small></div>
                        <div><strong>{about.data?.promise || 'Bespoke'}</strong><small>Private consultations</small></div>
                    </div>
                </div>
            </section>
        </>
    );
}
