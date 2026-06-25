import React, { useEffect, useState } from 'react'
import { useSound } from './SoundManager'

export default function FloorClearedOverlay({ show, floorNumber, floorName, onClose }) {
    const [phase, setPhase] = useState('enter') // enter → glow → exit
    const { play } = useSound()

    useEffect(() => {
        if (!show) {
            setPhase('enter')
            return
        }

        // play('floorCleared') will be played (can also trigger here to be sure)
        const glowTimer = setTimeout(() => setPhase('glow'), 200)
        const exitTimer = setTimeout(() => setPhase('exit'), 4200)
        const closeTimer = setTimeout(() => onClose?.(), 5000)

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
                : 'rgba(0, 0, 0, 0.9)',
            transition: 'background 0.6s ease',
            pointerEvents: phase === 'exit' ? 'none' : 'auto',
            backdropFilter: phase === 'exit' ? 'none' : 'blur(4px)',
        }}>
            {/* Golden radial glow */}
            <div style={{
                position: 'absolute',
                width: '500px',
                height: '500px',
                borderRadius: '50%',
                background: 'radial-gradient(circle, rgba(255, 215, 0, 0.25), transparent 70%)',
                opacity: phase === 'glow' ? 1 : 0,
                transform: phase === 'glow' ? 'scale(1.6)' : 'scale(0.4)',
                transition: 'all 1.2s cubic-bezier(0.19, 1, 0.22, 1)',
            }} />

            {/* Glowing Golden Particles */}
            {phase !== 'exit' && Array.from({ length: 16 }).map((_, i) => (
                <div key={i} style={{
                    position: 'absolute',
                    width: i % 3 === 0 ? '6px' : '4px',
                    height: i % 3 === 0 ? '6px' : '4px',
                    borderRadius: '50%',
                    background: '#FFD700',
                    boxShadow: '0 0 12px #FFD700, 0 0 20px #FFA500',
                    animation: `clear-particle-${i % 4} 2.5s ease-out forwards`,
                    animationDelay: `${i * 0.05}s`,
                    opacity: phase === 'glow' ? 1 : 0,
                }} />
            ))}

            {/* Subtitle / Header */}
            <div style={{
                fontFamily: "'Rajdhani', sans-serif",
                fontWeight: 700,
                fontSize: '1rem',
                letterSpacing: '0.4em',
                textTransform: 'uppercase',
                color: 'rgba(255, 215, 0, 0.8)',
                textShadow: '0 0 8px rgba(255, 215, 0, 0.5)',
                marginBottom: '16px',
                opacity: phase === 'enter' ? 0 : phase === 'exit' ? 0 : 1,
                transform: phase === 'enter' ? 'translateY(-20px)' : 'translateY(0)',
                transition: 'all 0.5s ease',
            }}>
                ✦ CONGRATULATIONS ✦
            </div>

            {/* Boss Defeated Banner */}
            <div style={{
                background: 'linear-gradient(90deg, transparent, rgba(255, 179, 0, 0.15), transparent)',
                borderTop: '2px solid rgba(255, 215, 0, 0.4)',
                borderBottom: '2px solid rgba(255, 215, 0, 0.4)',
                padding: '20px 60px',
                textAlign: 'center',
                opacity: phase === 'enter' ? 0 : phase === 'exit' ? 0 : 1,
                transform: phase === 'enter' ? 'scaleY(0)' : 'scaleY(1)',
                transition: 'all 0.5s cubic-bezier(0.19, 1, 0.22, 1)',
                transitionDelay: '0.1s',
                marginBottom: '20px',
                width: '100%',
                maxWidth: '600px',
                boxShadow: '0 0 30px rgba(255, 215, 0, 0.05)',
            }}>
                <h1 style={{
                    margin: 0,
                    fontFamily: "'Rajdhani', sans-serif",
                    fontWeight: 900,
                    fontSize: '2.5rem',
                    letterSpacing: '0.15em',
                    color: '#FFD700',
                    textShadow: '0 0 15px rgba(255, 215, 0, 0.7), 0 0 30px rgba(255, 165, 0, 0.4)',
                    textTransform: 'uppercase',
                }}>
                    BOSS DEFEATED
                </h1>
                <div style={{
                    fontFamily: "'Rajdhani', sans-serif",
                    fontWeight: 600,
                    fontSize: '1.2rem',
                    color: '#FFF',
                    letterSpacing: '0.05em',
                    marginTop: '8px',
                    textShadow: '0 0 10px rgba(255,255,255,0.5)',
                }}>
                    Floor {floorNumber} Cleared
                </div>
            </div>

            {/* Floor Name Display */}
            <div style={{
                fontFamily: "'Sora', sans-serif",
                fontWeight: 600,
                fontSize: '1.05rem',
                color: 'rgba(255, 255, 255, 0.7)',
                textAlign: 'center',
                opacity: phase === 'glow' ? 1 : 0,
                transform: phase === 'glow' ? 'translateY(0)' : 'translateY(15px)',
                transition: 'all 0.6s ease',
                transitionDelay: '0.3s',
            }}>
                {floorName}
            </div>

            {/* Decorative crossed lines */}
            <div style={{
                display: 'flex',
                alignItems: 'center',
                gap: '16px',
                marginTop: '32px',
                opacity: phase === 'glow' ? 1 : 0,
                transition: 'opacity 0.5s ease',
                transitionDelay: '0.5s',
            }}>
                <div style={{
                    width: '100px', height: '1px',
                    background: 'linear-gradient(to right, transparent, #FFD700)',
                }} />
                <span style={{ color: '#FFD700', fontSize: '0.9rem', animation: 'sao-blink 1.5s infinite' }}>⚔️</span>
                <div style={{
                    width: '100px', height: '1px',
                    background: 'linear-gradient(to left, transparent, #FFD700)',
                }} />
            </div>

            {/* Particle Keyframes styling */}
            <style>{`
                @keyframes clear-particle-0 {
                    0% { transform: translate(0, 0) scale(1); opacity: 1; }
                    100% { transform: translate(-140px, -160px) scale(0); opacity: 0; }
                }
                @keyframes clear-particle-1 {
                    0% { transform: translate(0, 0) scale(1); opacity: 1; }
                    100% { transform: translate(150px, -130px) scale(0); opacity: 0; }
                }
                @keyframes clear-particle-2 {
                    0% { transform: translate(0, 0) scale(1); opacity: 1; }
                    100% { transform: translate(-110px, 140px) scale(0); opacity: 0; }
                }
                @keyframes clear-particle-3 {
                    0% { transform: translate(0, 0) scale(1); opacity: 1; }
                    100% { transform: translate(120px, 120px) scale(0); opacity: 0; }
                }
            `}</style>
        </div>
    )
}
