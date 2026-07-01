import React from 'react';
import { useForm, Link } from '@inertiajs/react';
import GuestLayout from '../../../Layouts/GuestLayout';

export default function Pricing({ stripeKey, envMode, plan }) {
    const { post, processing } = useForm();

    const handleSubscribe = (e) => {
        e.preventDefault();
        post('/player/subscription/checkout');
    };

    // Plano dinâmico com fallback estático
    const planName = plan ? plan.name : 'Aincrad Full Pass';
    const planPrice = plan ? plan.price : 19.70;
    const planFeatures = plan ? plan.features : [
        'Barra de HP (Saldo) e Controle de XP',
        'Trade Log de Combate (Receitas/Despesas)',
        'Inventário Completo de Ativos e Passivos',
        'Guild System (Comunidade, Ranking & Chat)',
        'Assistência por Inteligência com YUI',
        'Evolução pelos 100 Andares de Aincrad'
    ];

    return (
        <GuestLayout title="Planos de Assinatura">
            <div style={{ textAlign: 'center', marginBottom: '24px' }}>
                <h2 className="auth-title" style={{ fontSize: '2rem', color: 'var(--sao-orange)', textShadow: '0 0 12px rgba(255,157,0,0.4)' }}>
                    [ LINK START ]
                </h2>
                <p className="auth-subtitle" style={{ letterSpacing: '0.15em' }}>REGISTRO DE CONTRATO DE AVENTUREIRO</p>
            </div>

            {envMode === 'sandbox_mock' && (
                <div style={{
                    backgroundColor: 'rgba(255, 157, 0, 0.1)',
                    border: '1px solid rgba(255, 157, 0, 0.3)',
                    borderRadius: '6px',
                    padding: '12px',
                    marginBottom: '20px',
                    fontSize: '0.8rem',
                    color: '#ffc485',
                    textAlign: 'center'
                }}>
                    <strong>⚠️ Modo de Simulação Ativo:</strong> As chaves do Stripe não foram configuradas no arquivo .env. O pagamento será simulado instantaneamente e sem cobranças!
                </div>
            )}

            <div style={{
                background: 'rgba(255, 255, 255, 0.03)',
                border: '1px solid rgba(255, 255, 255, 0.08)',
                borderRadius: '12px',
                padding: '24px',
                textAlign: 'center',
                position: 'relative',
                boxShadow: '0 8px 32px rgba(0, 0, 0, 0.4)',
                backdropFilter: 'blur(8px)'
            }}>
                <div style={{
                    position: 'absolute',
                    top: '-12px',
                    left: '50%',
                    transform: 'translateX(-50%)',
                    backgroundColor: 'var(--sao-orange)',
                    color: '#11131a',
                    fontWeight: 'bold',
                    fontSize: '0.7rem',
                    padding: '4px 16px',
                    borderRadius: '20px',
                    letterSpacing: '0.1em',
                    textTransform: 'uppercase'
                }}>
                    Unique Item Class
                </div>

                <h3 style={{ fontFamily: 'Sora, sans-serif', fontSize: '1.4rem', color: '#fff', marginTop: '8px' }}>
                    {planName}
                </h3>
                <p style={{ fontSize: '0.8rem', color: 'var(--sao-text-dim)', margin: '8px 0 20px 0' }}>
                    Acesso completo ao HUD financeiro gamificado.
                </p>

                <hr style={{ border: '0', borderTop: '1px solid rgba(255,255,255,0.08)', margin: '16px 0' }} />

                <ul style={{
                    textAlign: 'left',
                    listStyle: 'none',
                    padding: 0,
                    margin: '20px 0',
                    fontSize: '0.9rem',
                    color: '#dac2ad',
                    display: 'flex',
                    flexDirection: 'column',
                    gap: '12px'
                }}>
                    {planFeatures.map((feature, idx) => (
                        <li key={idx} style={{ display: 'flex', alignItems: 'center', gap: '10px' }}>
                            <span style={{ color: 'var(--sao-orange)', fontSize: '1.2rem' }}>⚔️</span>
                            <span>{feature}</span>
                        </li>
                    ))}
                </ul>

                <hr style={{ border: '0', borderTop: '1px solid rgba(255,255,255,0.08)', margin: '16px 0' }} />

                <div style={{ margin: '24px 0 16px 0' }}>
                    <span style={{ fontSize: '1rem', color: 'rgba(255,255,255,0.4)', textDecoration: 'line-through' }}>R$ 49,90</span>
                    <div style={{ display: 'flex', justifyContent: 'center', alignItems: 'baseline', gap: '4px' }}>
                        <span style={{ fontSize: '2.5rem', fontWeight: '900', color: '#fff', fontFamily: 'Sora, sans-serif' }}>
                            R$ {planPrice.toLocaleString('pt-BR', { minimumFractionDigits: 2 })}
                        </span>
                        <span style={{ color: 'var(--sao-text-dim)', fontSize: '0.8rem' }}>/ mês</span>
                    </div>
                    <p style={{ fontSize: '0.75rem', color: 'var(--sao-text-dim)', marginTop: '4px' }}>
                        Garantia de 7 dias com reembolso total.
                    </p>
                </div>

                <form onSubmit={handleSubscribe}>
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
                        {processing ? 'Carregando Contrato...' : 'ACCEPT TRADE (INICIAR CONTRATO)'}
                    </button>
                </form>
            </div>

            <div style={{ textAlign: 'center', marginTop: '20px' }}>
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
        </GuestLayout>
    );
}
