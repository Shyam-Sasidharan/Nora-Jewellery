import { motion } from 'framer-motion';
import { Link } from 'react-router-dom';
import { Autoplay, EffectFade, Pagination } from 'swiper/modules';
import { Swiper, SwiperSlide } from 'swiper/react';

export default function HeroSection({ banners = [] }) {
    const slides = banners.length ? banners : [{
        title: 'Luxury That Lives Beyond The Moment',
        subtitle: 'Hand-finished rings, necklaces, bridal sets, and bespoke pieces shaped with refined detail.',
        eyebrow: 'Nora Jewellery',
        cta_label: 'Explore Collections',
        cta_url: '/collections',
        image_url: 'https://images.unsplash.com/photo-1515562141207-7a88fb7ce338?auto=format&fit=crop&w=1800&q=85',
    }];

    return (
        <section className="react-hero">
            <div className="react-particles"><i /><i /><i /><i /><i /></div>
            <Swiper modules={[Autoplay, EffectFade, Pagination]} effect="fade" autoplay={{ delay: 5200 }} pagination={{ clickable: true }} loop className="react-hero-swiper">
                {slides.map((banner) => (
                    <SwiperSlide key={banner.id || banner.title}>
                        <div className="react-hero-slide" style={{ backgroundImage: `linear-gradient(100deg, rgba(5,4,3,.9), rgba(18,12,5,.66), rgba(5,4,3,.15)), url(${banner.image_url})` }}>
                            <motion.div className="react-hero-copy" initial={{ opacity: 0, y: 40 }} animate={{ opacity: 1, y: 0 }} transition={{ duration: 0.7 }}>
                                <span>{banner.eyebrow || 'Nora Jewellery'}</span>
                                <h1>{banner.title}</h1>
                                <p>{banner.subtitle}</p>
                                <Link className="react-gold-button" to={banner.cta_url || '/collections'}>{banner.cta_label || 'Explore Collections'}</Link>
                            </motion.div>
                            <motion.div className="react-hero-jewel" animate={{ y: [0, -18, 0], rotate: [0, 2, 0] }} transition={{ duration: 7, repeat: Infinity }}>
                                <img src={banner.image_url} alt={banner.title} />
                                <strong>Atelier Finish</strong>
                                <small>Gold, diamonds, and private styling</small>
                            </motion.div>
                        </div>
                    </SwiperSlide>
                ))}
            </Swiper>
            <a className="react-scroll-cue" href="#react-collections"><span /></a>
        </section>
    );
}
