import { Mail, MapPin, Phone } from 'lucide-react';
import { useEffect, useState } from 'react';
import { useSearchParams } from 'react-router-dom';
import LoadingSkeleton from '../components/LoadingSkeleton.jsx';
import { storefront } from '../services/api.js';

export default function Contact() {
    const [searchParams] = useSearchParams();
    const [contact, setContact] = useState(null);
    const [form, setForm] = useState({ name: '', email: '', phone: '', subject: searchParams.get('subject') || '', message: '' });
    const [status, setStatus] = useState('');
    const [error, setError] = useState('');

    useEffect(() => {
        storefront.contact().then((response) => setContact(response.data));
    }, []);

    const submit = async (event) => {
        event.preventDefault();
        setStatus('');
        setError('');
        try {
            const response = await storefront.sendContact(form);
            setStatus(response.message);
            setForm({ name: '', email: '', phone: '', subject: '', message: '' });
        } catch (exception) {
            setError(exception.response?.data?.message || 'Please check the form and try again.');
        }
    };

    if (!contact) return <LoadingSkeleton cards={3} />;
    const data = contact.data || {};

    return (
        <>
            <section className="react-page-hero">
                <span>Contact</span>
                <h1>{contact.title || 'Book A Private Jewellery Appointment'}</h1>
            </section>
            <section className="react-contact-grid">
                <div className="react-contact-card">
                    <p>{contact.content}</p>
                    <div><Phone size={18} /><span>{data.phone}</span></div>
                    <div><Mail size={18} /><span>{data.email}</span></div>
                    <div><MapPin size={18} /><span>{data.address}</span></div>
                </div>
                <form className="react-form" onSubmit={submit}>
                    {status && <div className="react-notice success">{status}</div>}
                    {error && <div className="react-notice error">{error}</div>}
                    <input required placeholder="Name" value={form.name} onChange={(event) => setForm({ ...form, name: event.target.value })} />
                    <input required type="email" placeholder="Email" value={form.email} onChange={(event) => setForm({ ...form, email: event.target.value })} />
                    <input placeholder="Phone" value={form.phone} onChange={(event) => setForm({ ...form, phone: event.target.value })} />
                    <input placeholder="Subject" value={form.subject} onChange={(event) => setForm({ ...form, subject: event.target.value })} />
                    <textarea required rows="6" placeholder="Tell us what you are looking for" value={form.message} onChange={(event) => setForm({ ...form, message: event.target.value })} />
                    <button className="react-gold-button" type="submit">Send Enquiry</button>
                </form>
            </section>
        </>
    );
}
