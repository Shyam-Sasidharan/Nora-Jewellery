import { AnimatePresence, motion } from 'framer-motion';
import { Route, Routes, useLocation } from 'react-router-dom';
import Navbar from '../components/Navbar.jsx';
import Footer from '../components/Footer.jsx';
import Home from '../pages/Home.jsx';
import About from '../pages/About.jsx';
import Collections from '../pages/Collections.jsx';
import Products from '../pages/Products.jsx';
import ProductDetail from '../pages/ProductDetail.jsx';
import Contact from '../pages/Contact.jsx';
import Cart from '../pages/Cart.jsx';
import Wishlist from '../pages/Wishlist.jsx';

const Page = ({ children }) => (
    <motion.main
        initial={{ opacity: 0, y: 18 }}
        animate={{ opacity: 1, y: 0 }}
        exit={{ opacity: 0, y: -12 }}
        transition={{ duration: 0.45, ease: [0.22, 1, 0.36, 1] }}
    >
        {children}
    </motion.main>
);

export default function AppRoutes() {
    const location = useLocation();

    return (
        <div className="react-luxury-shell">
            <Navbar />
            <AnimatePresence mode="wait">
                <Routes location={location} key={location.pathname}>
                    <Route path="/" element={<Page><Home /></Page>} />
                    <Route path="/about" element={<Page><About /></Page>} />
                    <Route path="/categories" element={<Page><Collections /></Page>} />
                    <Route path="/collections" element={<Page><Products /></Page>} />
                    <Route path="/collections/:category" element={<Page><Products /></Page>} />
                    <Route path="/jewellery/:slug" element={<Page><ProductDetail /></Page>} />
                    <Route path="/gallery" element={<Page><Collections showGallery /></Page>} />
                    <Route path="/contact" element={<Page><Contact /></Page>} />
                    <Route path="/cart" element={<Page><Cart /></Page>} />
                    <Route path="/wishlist" element={<Page><Wishlist /></Page>} />
                    <Route path="/search" element={<Page><Products searchMode /></Page>} />
                </Routes>
            </AnimatePresence>
            <Footer />
        </div>
    );
}
