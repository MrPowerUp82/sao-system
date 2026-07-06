import React from 'react';
import { useForm, Link } from '@inertiajs/react';
import GuestLayout from '../../Layouts/GuestLayout';

export default function ForgotPassword({ status }) {
    const { data, setData, post, processing, errors } = useForm({
        email: '',
    });

    const submit = (e) => {
        e.preventDefault();
        post('/forgot-password');
    };

    return (
        <GuestLayout title="Recuperar Senha">
            <h2 className="auth-title">Recovery Crystal</h2>
            <p className="auth-subtitle">Informe seu e-mail e enviaremos um cristal de teletransporte para redefinir sua senha.</p>

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

                <div className="flex items-center justify-end mt-4">
                    <button className="auth-btn" disabled={processing}>
                        {processing ? 'Enviando...' : 'Enviar Link de Recuperação'}
                    </button>
                </div>

                <Link href="/login" className="auth-link">
                    Voltar para o Login
                </Link>
            </form>
        </GuestLayout>
    );
}
