import React from 'react';
import { Link } from '@inertiajs/react';
import GuestLayout from '../../../Layouts/GuestLayout';

export default function Success({ successMessage }) {
    return (
        <GuestLayout title="Assinatura Ativada">
            <div style={{ textAlign: 'center', padding: '20px' }}>
                {/* SAO Success Emblem */}
                <div style={{
                    width: '90px',
                    height: '90px',
                    borderRadius: '50%',
                    backgroundColor: 'rgba(74, 225, 131, 0.1)',
                    border: '3px solid var(--sao-success, #4ae183)',
                    display: 'flex',
                    alignItems: 'center',
                    justifyContent: 'center',
                    margin: '0 auto 24px auto',
                    boxShadow: '0 0 20px rgba(74, 225, 131, 0.3)',
                    animation: 'pulse-success 2s infinite'
                }}>
                    <span style={{ fontSize: '3rem', color: 'var(--sao-success, #4ae183)' }}>✓</span>
                </div>

                <h2 className="auth-title" style={{ color: 'var(--sao-success, #4ae183)', fontSize: '2rem', textShadow: '0 0 12px rgba(74,225,131,0.3)' }}>
                    [ CONTRATO CONFIRMADO ]
                </h2>
                <p className="auth-subtitle" style={{ letterSpacing: '0.15em', marginBottom: '30px' }}>TRANSAÇÃO APROVADA PELO SISTEMA</p>

                <p style={{
                    color: '#dac2ad',
                    fontFamily: 'Hanken Grotesk, sans-serif',
                    fontSize: '1rem',
                    lineHeight: '1.6',
                    marginBottom: '32px'
                }}>
                    {successMessage || 'Parabéns! Suas credenciais foram ativadas no banco de dados central do SAO Financial System.'}
                </p>

                <Link
                    href="/player"
                    className="auth-btn"
                    style={{
                        display: 'inline-flex',
                        alignItems: 'center',
                        justifyContent: 'center',
                        gap: '8px',
                        width: '100%',
                        padding: '14px',
                        fontSize: '1.1rem',
                        fontWeight: 'bold',
                        backgroundColor: 'var(--sao-success, #4ae183)',
                        color: '#11131a',
                        border: 'none',
                        borderRadius: '8px',
                        cursor: 'pointer',
                        textDecoration: 'none',
                        boxShadow: '0 0 16px rgba(74,225,131,0.3)',
                        textTransform: 'uppercase',
                        letterSpacing: '0.05em'
                    }}
                >
                    Link Start! (Entrar no Sistema)
                </Link>
            </div>

            <style dangerouslySetInnerHTML={{__html: `
                @keyframes pulse-success {
                    0% { transform: scale(1); box-shadow: 0 0 20px rgba(74, 225, 131, 0.3); }
                    50% { transform: scale(1.05); box-shadow: 0 0 30px rgba(74, 225, 131, 0.5); }
                    100% { transform: scale(1); box-shadow: 0 0 20px rgba(74, 225, 131, 0.3); }
                }
            `}} />
        </GuestLayout>
    );
}
