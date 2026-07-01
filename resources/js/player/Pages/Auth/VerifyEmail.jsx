import React from 'react';
import { useForm, Head, Link } from '@inertiajs/react';
import GuestLayout from '../../Layouts/GuestLayout';

export default function VerifyEmail({ status }) {
    const { post, processing } = useForm({});

    const submit = (e) => {
        e.preventDefault();
        post('/player/email/verification-notification');
    };

    return (
        <GuestLayout title="Verificação de E-mail">
            <div style={{ textAlign: 'center', marginBottom: '24px' }}>
                <h2 className="auth-title" style={{ fontSize: '1.8rem', color: 'var(--sao-orange)', textShadow: '0 0 12px rgba(255,157,0,0.4)' }}>
                    [ SYSTEM LOCK ]
                </h2>
                <p className="auth-subtitle" style={{ letterSpacing: '0.15em' }}>VERIFICAÇÃO DE DADOS COMPORTAMENTAIS</p>
            </div>

            <div style={{
                color: '#dac2ad',
                fontFamily: 'Hanken Grotesk, sans-serif',
                fontSize: '0.9rem',
                lineHeight: '1.6',
                textAlign: 'center',
                marginBottom: '24px'
            }}>
                Obrigado por se registrar! Antes de iniciar o seu treinamento em Aincrad, você precisa verificar seu endereço de e-mail clicando no link que acabamos de enviar. Se você não recebeu o e-mail, teremos o prazer de lhe enviar outro.
            </div>

            {status === 'verification-link-sent' && (
                <div style={{
                    backgroundColor: 'rgba(74, 225, 131, 0.1)',
                    border: '1px solid rgba(74, 225, 131, 0.3)',
                    borderRadius: '6px',
                    padding: '12px',
                    marginBottom: '20px',
                    fontSize: '0.8rem',
                    color: 'var(--sao-success, #4ae183)',
                    textAlign: 'center'
                }}>
                    Um novo link de verificação foi enviado para o endereço de e-mail fornecido durante o registro.
                </div>
            )}

            <form onSubmit={submit}>
                <div style={{ display: 'flex', flexDirection: 'column', gap: '16px' }}>
                    <button
                        type="submit"
                        className="auth-btn"
                        style={{
                            width: '100%',
                            padding: '14px',
                            fontSize: '1rem',
                            fontWeight: 'bold',
                            backgroundColor: 'var(--sao-orange)',
                            color: '#11131a',
                            border: 'none',
                            borderRadius: '8px',
                            cursor: 'pointer',
                            transition: 'all 0.3s ease',
                            boxShadow: '0 0 16px rgba(255,157,0,0.3)',
                            textTransform: 'uppercase',
                            letterSpacing: '0.05em'
                        }}
                        disabled={processing}
                    >
                        {processing ? 'Enviando...' : 'Reenviar E-mail de Verificação'}
                    </button>

                    <div style={{ display: 'flex', justifyContent: 'center', marginTop: '10px' }}>
                        <Link
                            href="/logout"
                            method="post"
                            as="button"
                            className="auth-link"
                            style={{ background: 'none', border: 'none', cursor: 'pointer', fontSize: '0.8rem' }}
                        >
                            Desconectar da Conta
                        </Link>
                    </div>
                </div>
            </form>
        </GuestLayout>
    );
}
