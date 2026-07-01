import axios from 'axios';

const api = axios.create({
    baseURL: '/api/storefront',
    headers: {
        Accept: 'application/json',
    },
});

export const storefront = {
    home: () => api.get('/home').then((response) => response.data),
    categories: () => api.get('/categories').then((response) => response.data),
    products: (params = {}) => api.get('/products', { params }).then((response) => response.data),
    product: (slug) => api.get(`/products/${slug}`).then((response) => response.data),
    about: () => api.get('/about').then((response) => response.data),
    gallery: () => api.get('/gallery').then((response) => response.data),
    contact: () => api.get('/contact').then((response) => response.data),
    delivery: () => api.get('/delivery').then((response) => response.data),
    sendContact: (payload) => api.post('/contact', payload).then((response) => response.data),
    checkout: (payload) => api.post('/checkout', payload).then((response) => response.data),
};

export default api;
