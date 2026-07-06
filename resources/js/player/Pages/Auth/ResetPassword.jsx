import React, { useEffect } from 'react';
import { useForm } from '@inertiajs/react';
import GuestLayout from '../../Layouts/GuestLayout';

export default function ResetPassword({ token, email }) {
    const { data, setData, post, processing, errors, reset } = useForm({
        token: token,
        email: email || '',
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
        post('/reset-password');
    };

    return (
        <GuestLayout title="Redefinir Senha">
            <h2 className="auth-title">Nova Senha</h2>
            <p className="auth-subtitle">Defina uma nova senha para reativar seu Link Start.</p>

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
                        autoComplete="new-password"
                        placeholder=" "
                        onChange={(e) => setData('password', e.target.value)}
                        required
                    />
                    <label htmlFor="password" className="auth-label">Nova Senha</label>
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
                    <label htmlFor="password_confirmation" className="auth-label">Confirmar Nova Senha</label>
                    {errors.password_confirmation && <div style={{ color: 'var(--sao-danger)', fontSize: '0.7rem', marginTop: '4px' }}>{errors.password_confirmation}</div>}
                </div>

                <div className="flex items-center justify-end mt-4">
                    <button className="auth-btn" disabled={processing}>
                        {processing ? 'Salvando...' : 'Redefinir Senha'}
                    </button>
                </div>
            </form>
        </GuestLayout>
    );
}
