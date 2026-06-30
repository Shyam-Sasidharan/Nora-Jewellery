import { Heart, Menu, Search, ShoppingBag, X } from 'lucide-react';
import { useEffect, useState } from 'react';
import { Link, NavLink } from 'react-router-dom';
import { useStore } from '../services/store.jsx';

const nav = [
    ['Home', '/'],
    ['About', '/about'],
    ['Categories', '/categories'],
    ['Collections', '/collections'],
    ['Gallery', '/gallery'],
    ['Contact', '/contact'],
];

export default function Navbar() {
    const [open, setOpen] = useState(false);
    const [scrolled, setScrolled] = useState(false);
    const { cartCount, wishlist } = useStore();

    useEffect(() => {
        const onScroll = () => setScrolled(window.scrollY > 20);
        onScroll();
        window.addEventListener('scroll', onScroll, { passive: true });
        return () => window.removeEventListener('scroll', onScroll);
    }, []);

    return (
        <header className={`react-nav ${scrolled ? 'is-scrolled' : ''}`}>
            <div className="react-nav-inner">
                <Link className="react-logo" to="/">
                    <img src="/images/nora-jewels-logo.webp" alt="Nora Jewels" />
                </Link>
                <nav className={`react-links ${open ? 'is-open' : ''}`}>
                    {nav.map(([label, url]) => (
                        <NavLink key={url} to={url} onClick={() => setOpen(false)}>{label}</NavLink>
                    ))}
                </nav>
                <div className="react-nav-actions">
                    <Link to="/search" aria-label="Search"><Search size={18} /></Link>
                    <Link to="/wishlist" aria-label="Wishlist"><Heart size={18} /><span>{wishlist.length}</span></Link>
                    <Link to="/cart" aria-label="Cart"><ShoppingBag size={18} /><span>{cartCount}</span></Link>
                    <Link className="react-nav-cta" to="/contact">Appointment</Link>
                </div>
                <button className="react-menu-button" type="button" onClick={() => setOpen(!open)} aria-label="Toggle menu">
                    {open ? <X size={22} /> : <Menu size={22} />}
                </button>
            </div>
        </header>
    );
}
