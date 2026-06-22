import React, { useState, useEffect } from 'react'

/**
 * DailyQuests — Stitch-inspired daily quests panel
 * Front-end only component showing gamified task tracking
 * Can be connected to backend later via props
 */
export default function DailyQuests({ quests: externalQuests }) {
    // Default quests if none provided from backend
    const defaultQuests = [
        {
            id: 1,
            title: 'Registrar trades do dia',
            reward: '+25 XP',
            status: 'active',
            timeLimit: null,
        },
        {
            id: 2,
            title: 'Revisar Floor Progress',
            reward: 'System Stability',
            status: 'pending',
            timeLimit: null,
        },
        {
            id: 3,
            title: 'Checar balanço semanal',
            reward: '+50 XP',
            status: 'pending',
            timeLimit: null,
        },
    ]

    const quests = externalQuests || defaultQuests

    const [now, setNow] = useState(Date.now())

    // Tick timer every second for countdown quests
    useEffect(() => {
        const timer = setInterval(() => setNow(Date.now()), 1000)
        return () => clearInterval(timer)
    }, [])

    const formatCountdown = (targetMs) => {
        if (!targetMs) return null
        const diff = Math.max(0, targetMs - now)
        const hours = Math.floor(diff / 3600000)
        const minutes = Math.floor((diff % 3600000) / 60000)
        const seconds = Math.floor((diff % 60000) / 1000)
        return `${String(hours).padStart(2, '0')}:${String(minutes).padStart(2, '0')}:${String(seconds).padStart(2, '0')}`
    }

    return (
        <div className="sao-panel glass-panel glass-panel-teal" style={{ overflow: 'hidden' }}>
            <div className="panel-header">
                <h3 style={{ color: '#62bbff' }}>
                    DAILY QUESTS
                </h3>
                <button className="more-btn">
                    <span className="material-symbols-outlined">more_horiz</span>
                </button>
            </div>
            <div style={{ padding: '12px', display: 'flex', flexDirection: 'column', gap: '8px' }}>
                {quests.map(quest => (
                    <div key={quest.id} style={{
                        padding: '14px 16px',
                        borderRadius: '8px',
                        background: quest.status === 'completed'
                            ? 'rgba(51,52,60,0.15)'
                            : 'rgba(51,52,60,0.3)',
                        border: `1px solid ${
                            quest.status === 'active'
                                ? 'rgba(98,187,255,0.2)'
                                : quest.status === 'completed'
                                    ? 'rgba(74,225,131,0.1)'
                                    : 'rgba(255,255,255,0.05)'
                        }`,
                        display: 'flex',
                        justifyContent: 'space-between',
                        alignItems: 'center',
                        gap: '16px',
                        opacity: quest.status === 'completed' ? 0.6 : 1,
                        position: 'relative',
                        overflow: 'hidden',
                        transition: 'all 0.2s ease',
                    }}>
                        {/* Active indicator bar */}
                        {quest.status === 'active' && (
                            <div style={{
                                position: 'absolute', left: 0, top: 0, bottom: 0,
                                width: '3px', background: '#62bbff',
                            }} />
                        )}

                        <div style={{ flex: 1 }}>
                            <div style={{ display: 'flex', alignItems: 'center', gap: '8px', marginBottom: '4px' }}>
                                <h4 style={{
                                    fontFamily: "'Hanken Grotesk', sans-serif",
                                    fontSize: '0.9rem', fontWeight: 600,
                                    textDecoration: quest.status === 'completed' ? 'line-through' : 'none',
                                    textDecorationColor: 'rgba(255,255,255,0.3)',
                                }}>
                                    {quest.title}
                                </h4>
                                {quest.status === 'active' && quest.timeLimit && (
                                    <span className="label-caps" style={{ fontSize: '10px', color: '#ff9d00' }}>
                                        ({formatCountdown(quest.timeLimit)})
                                    </span>
                                )}
                                {quest.status === 'completed' && (
                                    <span className="label-caps" style={{ fontSize: '10px', color: '#4ae183' }}>
                                        (Completed)
                                    </span>
                                )}
                                {quest.status === 'pending' && (
                                    <span className="label-caps" style={{ fontSize: '10px', color: '#ff9d00' }}>
                                        (Pending)
                                    </span>
                                )}
                            </div>
                            <p style={{
                                fontFamily: "'Hanken Grotesk', sans-serif",
                                fontSize: '0.8rem',
                                color: quest.status === 'completed'
                                    ? 'rgba(74,225,131,0.7)'
                                    : 'rgba(98,187,255,0.8)',
                            }}>
                                Reward: {quest.reward}
                            </p>
                        </div>

                        {/* Action button */}
                        {quest.status === 'active' && (
                            <button style={{
                                padding: '6px 16px',
                                borderRadius: '999px',
                                border: '1px solid #62bbff',
                                color: '#62bbff',
                                background: 'transparent',
                                fontFamily: "'JetBrains Mono', monospace",
                                fontSize: '11px', fontWeight: 700,
                                letterSpacing: '0.08em',
                                cursor: 'pointer',
                                whiteSpace: 'nowrap',
                                transition: 'all 0.2s ease',
                            }}
                            onMouseEnter={e => {
                                e.currentTarget.style.background = '#62bbff'
                                e.currentTarget.style.color = '#0c0e15'
                            }}
                            onMouseLeave={e => {
                                e.currentTarget.style.background = 'transparent'
                                e.currentTarget.style.color = '#62bbff'
                            }}
                            >
                                EXECUTE
                            </button>
                        )}

                        {quest.status === 'completed' && (
                            <div style={{
                                width: '32px', height: '32px', borderRadius: '50%',
                                background: 'rgba(74,225,131,0.1)',
                                border: '1px solid rgba(74,225,131,0.3)',
                                display: 'flex', alignItems: 'center', justifyContent: 'center',
                            }}>
                                <span className="material-symbols-outlined" style={{ fontSize: '16px', color: '#4ae183' }}>check</span>
                            </div>
                        )}

                        {quest.status === 'pending' && (
                            <button style={{
                                padding: '6px 16px',
                                borderRadius: '999px',
                                border: '1px solid rgba(255,255,255,0.2)',
                                color: 'var(--sao-text-dim)',
                                background: 'transparent',
                                fontFamily: "'JetBrains Mono', monospace",
                                fontSize: '11px', fontWeight: 700,
                                letterSpacing: '0.08em',
                                cursor: 'pointer',
                                whiteSpace: 'nowrap',
                                transition: 'all 0.2s ease',
                            }}
                            onMouseEnter={e => {
                                e.currentTarget.style.borderColor = 'rgba(255,255,255,0.4)'
                                e.currentTarget.style.color = 'var(--sao-text)'
                            }}
                            onMouseLeave={e => {
                                e.currentTarget.style.borderColor = 'rgba(255,255,255,0.2)'
                                e.currentTarget.style.color = 'var(--sao-text-dim)'
                            }}
                            >
                                VIEW
                            </button>
                        )}
                    </div>
                ))}
            </div>
        </div>
    )
}
