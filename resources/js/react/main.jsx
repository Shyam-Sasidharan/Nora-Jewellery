import React from 'react';
import { createRoot } from 'react-dom/client';
import { BrowserRouter } from 'react-router-dom';
import { StoreProvider } from './services/store.jsx';
import AppRoutes from './routes/AppRoutes.jsx';
import './styles/react.css';
import 'swiper/css';
import 'swiper/css/effect-fade';
import 'swiper/css/pagination';

createRoot(document.getElementById('nora-react-root')).render(
    <React.StrictMode>
        <BrowserRouter>
            <StoreProvider>
                <AppRoutes />
            </StoreProvider>
        </BrowserRouter>
    </React.StrictMode>,
);
