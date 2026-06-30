import { Camera, Gem, Mail, MapPin, Phone } from 'lucide-react';
import { Link } from 'react-router-dom';

export default function Footer() {
    return (
        <footer className="react-footer">
            <div>
                <img src="/images/nora-jewels-logo.webp" alt="Nora Jewels" />
                <p>Fine jewellery crafted for modern heirlooms, private appointments, and luminous celebrations.</p>
                <div className="react-socials">
                    <Link to="/gallery"><Camera size={16} /> Instagram</Link>
                    <Link to="/contact"><Mail size={16} /> Concierge</Link>
                    <Link to="/collections"><Gem size={16} /> Lookbook</Link>
                </div>
            </div>
            <div>
                <h3>Explore</h3>
                <Link to="/collections">Collections</Link>
                <Link to="/categories">Categories</Link>
                <Link to="/wishlist">Wishlist</Link>
                <Link to="/cart">Cart</Link>
            </div>
            <div>
                <h3>Visit</h3>
                <p><MapPin size={16} /> Nora Jewellery Studio, MG Road, Bengaluru</p>
                <p><Phone size={16} /> +91 98765 43210</p>
                <p><Mail size={16} /> concierge@norajewellery.test</p>
            </div>
        </footer>
    );
}
