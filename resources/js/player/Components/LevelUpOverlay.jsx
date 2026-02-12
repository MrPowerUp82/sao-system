import React, { useEffect, useState } from 'react'
import { useSound } from './SoundManager'

export default function LevelUpOverlay({ show, level, onClose }) {
    const [phase, setPhase] = useState('enter') // enter → glow → exit
    const { play } = useSound()

    useEffect(() => {
        if (!show) {
            setPhase('enter')
            return
        }

        play('levelUp')

        const glowTimer = setTimeout(() => setPhase('glow'), 200)
        const exitTimer = setTimeout(() => setPhase('exit'), 2800)
        const closeTimer = setTimeout(() => onClose?.(), 3500)

        return () => {
            clearTimeout(glowTimer)
            clearTimeout(exitTimer)
            clearTimeout(closeTimer)
        }
    }, [show])

    if (!show) return null

    return (
        <div style={{
            position: 'fixed',
            inset: 0,
            zIndex: 9999,
            display: 'flex',
            flexDirection: 'column',
            alignItems: 'center',
            justifyContent: 'center',
            background: phase === 'exit'
                ? 'rgba(0, 0, 0, 0)'
                : 'rgba(0, 0, 0, 0.85)',
            transition: 'background 0.5s ease',
            pointerEvents: phase === 'exit' ? 'none' : 'auto',
        }}>
            {/* Radial glow */}
            <div style={{
                position: 'absolute',
                width: '400px',
                height: '400px',
                borderRadius: '50%',
                background: 'radial-gradient(circle, rgba(255, 157, 0, 0.3), transparent 70%)',
                opacity: phase === 'glow' ? 1 : 0,
                transform: phase === 'glow' ? 'scale(1.5)' : 'scale(0.5)',
                transition: 'all 1s ease-out',
            }} />

            {/* Particles */}
            {phase !== 'exit' && Array.from({ length: 12 }).map((_, i) => (
                <div key={i} style={{
                    position: 'absolute',
                    width: '4px',
                    height: '4px',
                    borderRadius: '50%',
                    background: i % 2 === 0 ? '#FF9D00' : '#FFB347',
                    boxShadow: `0 0 8px ${i % 2 === 0 ? 'rgba(255, 157, 0, 0.8)' : 'rgba(255, 179, 71, 0.8)'}`,
                    animation: `levelup-particle-${i % 4} 2s ease-out forwards`,
                    animationDelay: `${i * 0.08}s`,
                    opacity: phase === 'glow' ? 1 : 0,
                }} />
            ))}

            {/* LEVEL UP text */}
            <div style={{
                fontFamily: 'Rajdhani, sans-serif',
                fontWeight: 700,
                fontSize: '0.85rem',
                letterSpacing: '0.3em',
                textTransform: 'uppercase',
                color: 'rgba(255, 157, 0, 0.7)',
                marginBottom: '8px',
                opacity: phase === 'enter' ? 0 : phase === 'exit' ? 0 : 1,
                transform: phase === 'enter' ? 'translateY(20px)' : 'translateY(0)',
                transition: 'all 0.4s ease',
                transitionDelay: '0.1s',
            }}>
                ✦ LEVEL UP ✦
            </div>

            {/* Level Number */}
            <div style={{
                fontFamily: 'Rajdhani, sans-serif',
                fontWeight: 700,
                fontSize: '5rem',
                lineHeight: 1,
                color: '#FF9D00',
                textShadow: '0 0 40px rgba(255, 157, 0, 0.6), 0 0 80px rgba(255, 157, 0, 0.3)',
                opacity: phase === 'enter' ? 0 : phase === 'exit' ? 0 : 1,
                transform: phase === 'enter' ? 'scale(0.5)' : phase === 'glow' ? 'scale(1.1)' : 'scale(1)',
                transition: 'all 0.6s cubic-bezier(0.34, 1.56, 0.64, 1)',
            }}>
                LV. {level}
            </div>

            {/* Subtitle */}
            <div style={{
                fontFamily: 'Rajdhani, sans-serif',
                fontSize: '0.9rem',
                color: 'var(--sao-text-dim, #8a8a9a)',
                marginTop: '12px',
                opacity: phase === 'glow' ? 1 : 0,
                transition: 'opacity 0.5s ease',
                transitionDelay: '0.4s',
            }}>
                「 Your power grows stronger 」
            </div>

            {/* Horizontal line decorations */}
            <div style={{
                display: 'flex',
                alignItems: 'center',
                gap: '16px',
                marginTop: '20px',
                opacity: phase === 'glow' ? 1 : 0,
                transition: 'opacity 0.5s ease',
                transitionDelay: '0.5s',
            }}>
                <div style={{
                    width: '60px', height: '1px',
                    background: 'linear-gradient(to right, transparent, #FF9D00)',
                }} />
                <span style={{ color: '#FF9D00', fontSize: '0.7rem' }}>◆</span>
                <div style={{
                    width: '60px', height: '1px',
                    background: 'linear-gradient(to left, transparent, #FF9D00)',
                }} />
            </div>

            {/* Particle keyframes injected via style tag */}
            <style>{`
                @keyframes levelup-particle-0 {
                    0% { transform: translate(0, 0) scale(1); opacity: 1; }
                    100% { transform: translate(-80px, -120px) scale(0); opacity: 0; }
                }
                @keyframes levelup-particle-1 {
                    0% { transform: translate(0, 0) scale(1); opacity: 1; }
                    100% { transform: translate(90px, -100px) scale(0); opacity: 0; }
                }
                @keyframes levelup-particle-2 {
                    0% { transform: translate(0, 0) scale(1); opacity: 1; }
                    100% { transform: translate(-60px, 110px) scale(0); opacity: 0; }
                }
                @keyframes levelup-particle-3 {
                    0% { transform: translate(0, 0) scale(1); opacity: 1; }
                    100% { transform: translate(70px, 90px) scale(0); opacity: 0; }
                }
            `}</style>
        </div>
    )
}
