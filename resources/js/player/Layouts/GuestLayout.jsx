import React from 'react';
import { Head } from '@inertiajs/react';

export default function GuestLayout({ children, title }) {
    return (
        <div className="auth-layout">
            <Head title={title} />

            {/* Background Rings */}
            <div className="auth-ring" />
            <div className="auth-ring" />
            <div className="auth-ring" />

            {/* Main Card */}
            <div className="auth-card">
                {children}
            </div>

            {/* Overlay Scanline Effect (optional, adding for extra flair) */}
            <div style={{
                position: 'absolute', top: 0, left: 0, right: 0, bottom: 0,
                background: 'linear-gradient(to bottom, rgba(255,157,0,0.03), transparent 2px)',
                backgroundSize: '100% 4px', pointerEvents: 'none', zIndex: 1
            }} />
        </div>
    );
}
