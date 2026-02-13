import React, { useEffect } from 'react';
import { useForm, Link } from '@inertiajs/react';
import GuestLayout from '../../Layouts/GuestLayout';

export default function Register() {
    const { data, setData, post, processing, errors, reset } = useForm({
        name: '',
        email: '',
        password: '',
        password_confirmation: '',
    });

    useEffect(() => {
        return () => {
            reset('password', 'password_confirmation');
        };
    }, []);

    const submit = (e) => {
        e.preventDefault();
        post(route('register'));
    };

    return (
        <GuestLayout title="Register">
            <h2 className="auth-title">System Init</h2>
            <p className="auth-subtitle">Create New Player ID</p>

            <form onSubmit={submit}>
                <div className="auth-input-group">
                    <input
                        id="name"
                        type="text"
                        name="name"
                        value={data.name}
                        className="auth-input"
                        autoComplete="name"
                        placeholder=" "
                        onChange={(e) => setData('name', e.target.value)}
                        required
                    />
                    <label htmlFor="name" className="auth-label">Player Name (IGN)</label>
                    {errors.name && <div style={{ color: 'var(--sao-danger)', fontSize: '0.7rem', marginTop: '4px' }}>{errors.name}</div>}
                </div>

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
                    <label htmlFor="email" className="auth-label">Real World Email</label>
                    {errors.email && <div style={{ color: 'var(--sao-danger)', fontSize: '0.7rem', marginTop: '4px' }}>{errors.email}</div>}
                </div>

                <div className="auth-input-group">
                    <input
                        id="password"
                        type="password"
                        name="password"
                        value={data.password}
                        className="auth-input"
                        autoComplete="new-password"
                        placeholder=" "
                        onChange={(e) => setData('password', e.target.value)}
                        required
                    />
                    <label htmlFor="password" className="auth-label">Password</label>
                    {errors.password && <div style={{ color: 'var(--sao-danger)', fontSize: '0.7rem', marginTop: '4px' }}>{errors.password}</div>}
                </div>

                <div className="auth-input-group">
                    <input
                        id="password_confirmation"
                        type="password"
                        name="password_confirmation"
                        value={data.password_confirmation}
                        className="auth-input"
                        autoComplete="new-password"
                        placeholder=" "
                        onChange={(e) => setData('password_confirmation', e.target.value)}
                        required
                    />
                    <label htmlFor="password_confirmation" className="auth-label">Confirm Password</label>
                    {errors.password_confirmation && <div style={{ color: 'var(--sao-danger)', fontSize: '0.7rem', marginTop: '4px' }}>{errors.password_confirmation}</div>}
                </div>

                <div className="flex items-center justify-end mt-4">
                    <button className="auth-btn" disabled={processing}>
                        {processing ? 'Registering...' : 'Register ID'}
                    </button>
                </div>

                <Link
                    href={route('login')}
                    className="auth-link"
                >
                    Already have a Player ID? Log In
                </Link>
            </form>
        </GuestLayout>
    );
}
