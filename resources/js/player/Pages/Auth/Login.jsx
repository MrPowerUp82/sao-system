import React, { useEffect } from 'react';
import { useForm, Link } from '@inertiajs/react';
import GuestLayout from '../../Layouts/GuestLayout';

export default function Login({ status }) {
    const { data, setData, post, processing, errors, reset } = useForm({
        email: '',
        password: '',
        remember: false,
    });

    useEffect(() => {
        return () => {
            reset('password');
        };
    }, []);

    const submit = (e) => {
        e.preventDefault();
        post(route('login'));
    };

    return (
        <GuestLayout title="Log In">
            <h2 className="auth-title">Link Start!</h2>
            <p className="auth-subtitle">System Authentication Protocol</p>

            {status && <div className="mb-4 text-sm font-medium text-green-600">{status}</div>}

            <form onSubmit={submit}>
                <div className="auth-input-group">
                    <input
                        id="email"
                        type="email"
                        name="email"
                        value={data.email}
                        className="auth-input"
                        autoComplete="username"
                        placeholder=" "
                        onChange={(e) => setData('email', e.target.value)}
                        required
                    />
                    <label htmlFor="email" className="auth-label">Email Address</label>
                    {errors.email && <div style={{ color: 'var(--sao-danger)', fontSize: '0.7rem', marginTop: '4px' }}>{errors.email}</div>}
                </div>

                <div className="auth-input-group">
                    <input
                        id="password"
                        type="password"
                        name="password"
                        value={data.password}
                        className="auth-input"
                        autoComplete="current-password"
                        placeholder=" "
                        onChange={(e) => setData('password', e.target.value)}
                        required
                    />
                    <label htmlFor="password" className="auth-label">Password</label>
                    {errors.password && <div style={{ color: 'var(--sao-danger)', fontSize: '0.7rem', marginTop: '4px' }}>{errors.password}</div>}
                </div>

                <div className="block mt-4">
                    <label className="flex items-center">
                        <input
                            type="checkbox"
                            name="remember"
                            checked={data.remember}
                            onChange={(e) => setData('remember', e.target.checked)}
                            style={{ accentColor: 'var(--sao-orange)' }}
                        />
                        <span className="ms-2 text-sm text-gray-400" style={{ fontSize: '0.8rem' }}>Remember me</span>
                    </label>
                </div>

                <div className="flex items-center justify-end mt-4">
                    <button className="auth-btn" disabled={processing}>
                        {processing ? 'Connecting...' : 'Link Start!'}
                    </button>
                </div>

                <Link
                    href={route('register')}
                    className="auth-link"
                >
                    Initialize New Account Data
                </Link>
            </form>
        </GuestLayout>
    );
}
