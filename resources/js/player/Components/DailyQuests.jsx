import React, { useState, useEffect } from 'react'
import { useSound } from './SoundManager'

export default function DailyQuests({ stats, recent_trades, openTradeModal }) {
    const { play } = useSound()
    const [now, setNow] = useState(Date.now())

    // Update timer every second
    useEffect(() => {
        const timer = setInterval(() => setNow(Date.now()), 1000)
        return () => clearInterval(timer)
    }, [])

    const formatMoney = (val) => {
        return (val || 0).toLocaleString('pt-BR', { style: 'currency', currency: 'BRL' })
    }

    // Check if player has registered a trade today
    const todayStr = new Date().toLocaleDateString('pt-BR')
    const hasTradeToday = recent_trades && recent_trades.some(t => t.created_at === todayStr)

    // Calculate time left until end of day (countdown for active quest)
    const getEndOfDayMs = () => {
        const end = new Date()
        end.setHours(23, 59, 59, 999)
        return end.getTime()
    }
    const endOfDayMs = getEndOfDayMs()
    const diff = Math.max(0, endOfDayMs - now)
    const hours = Math.floor(diff / 3600000)
    const minutes = Math.floor((diff % 3600000) / 60000)
    const seconds = Math.floor((diff % 60000) / 1000)
    const countdownStr = `${String(hours).padStart(2, '0')}:${String(minutes).padStart(2, '0')}:${String(seconds).padStart(2, '0')}`

    // Compute completed quests count
    const completedCount = 1 + (hasTradeToday ? 1 : 0) // Paying bills is always completed in this monthly representation, plus trade if done

    return (
        <div style={{ display: 'flex', flexDirection: 'column', gap: '16px' }}>
            <div style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'center', marginBottom: '8px' }}>
                <h4 className="font-data-label text-data-label text-primary uppercase tracking-widest flex items-center gap-2 text-glow" style={{ color: '#00D1FF', display: 'flex', alignItems: 'center', gap: '8px', margin: 0 }}>
                    <span className="w-2 h-2 bg-primary rounded-full sao-blink" style={{ backgroundColor: '#00D1FF', display: 'inline-block', width: '8px', height: '8px', borderRadius: '50%' }}></span>
                    Daily Quests
                </h4>
                <span className="text-xs font-data-label text-on-surface-variant uppercase tracking-wider" style={{ fontFamily: "'JetBrains Mono', monospace" }}>
                    {completedCount}/3 Complete
                </span>
            </div>

            {/* Quest List */}
            <div style={{ display: 'flex', flexDirection: 'column', gap: '12px' }}>
                
                {/* 1. Completed Quest: Pay Utility Bill / Monthly Expenses */}
                <div className="glass-panel rounded-lg opacity-70 hover:opacity-90 transition-opacity" 
                     style={{ 
                         background: 'rgba(23,31,51,0.2)',
                         display: 'flex',
                         alignItems: 'center',
                         gap: '16px',
                         padding: '16px',
                         borderRadius: '8px'
                     }}>
                    <div className="w-8 h-8 rounded-full bg-primary/20 flex items-center justify-center border border-primary/50 text-primary" 
                         style={{ 
                             borderColor: 'rgba(0, 209, 255, 0.4)', 
                             color: '#00D1FF',
                             width: '32px',
                             height: '32px',
                             borderRadius: '50%',
                             display: 'flex',
                             alignItems: 'center',
                             justifyContent: 'center',
                             flexShrink: 0
                         }}>
                        <span className="material-symbols-outlined text-sm">check</span>
                    </div>
                    <div style={{ flex: 1 }}>
                        <div className="font-body-md text-[14px] text-on-surface line-through" style={{ fontFamily: "'Hanken Grotesk', sans-serif" }}>
                            Pagar Despesas Mensais
                        </div>
                        <div className="font-data-label text-[10px] text-on-surface-variant uppercase tracking-wider" style={{ fontFamily: "'JetBrains Mono', monospace", fontSize: '10px' }}>
                            Reward: System Stability
                        </div>
                    </div>
                    <div className="font-data-value text-xs text-primary font-bold text-glow" style={{ color: '#00D1FF', fontFamily: "'JetBrains Mono', monospace" }}>
                        -{formatMoney(stats?.monthly_expense || 0)}
                    </div>
                </div>

                {/* 2. Active Quest: Register Trade of the Day */}
                {hasTradeToday ? (
                    /* Completed state */
                    <div className="glass-panel rounded-lg opacity-70 hover:opacity-90 transition-opacity" 
                         style={{ 
                             background: 'rgba(23,31,51,0.2)',
                             display: 'flex',
                             alignItems: 'center',
                             gap: '16px',
                             padding: '16px',
                             borderRadius: '8px'
                         }}>
                        <div className="w-8 h-8 rounded-full bg-primary/20 flex items-center justify-center border border-primary/50 text-primary" 
                             style={{ 
                                 borderColor: 'rgba(0, 209, 255, 0.4)', 
                                 color: '#00D1FF',
                                 width: '32px',
                                 height: '32px',
                                 borderRadius: '50%',
                                 display: 'flex',
                                 alignItems: 'center',
                                 justifyContent: 'center',
                                 flexShrink: 0
                             }}>
                            <span className="material-symbols-outlined text-sm">check</span>
                        </div>
                        <div style={{ flex: 1 }}>
                            <div className="font-body-md text-[14px] text-on-surface line-through" style={{ fontFamily: "'Hanken Grotesk', sans-serif" }}>
                                Registrar Trades do Dia
                            </div>
                            <div className="font-data-label text-[10px] text-on-surface-variant uppercase tracking-wider" style={{ fontFamily: "'JetBrains Mono', monospace", fontSize: '10px' }}>
                                Reward: XP & Col Boost
                            </div>
                        </div>
                        <div className="font-data-value text-xs text-glow font-bold" style={{ color: '#4ae183', fontFamily: "'JetBrains Mono', monospace" }}>
                            COMPLETED
                        </div>
                    </div>
                ) : (
                    /* Active/Pending execution state */
                    <div className="glass-panel rounded-lg relative overflow-hidden group hover:bg-surface-variant/30 transition-colors" 
                         style={{ 
                             borderLeft: '2px solid #ff9d00', 
                             background: 'rgba(23,31,51,0.4)',
                             display: 'flex',
                             alignItems: 'center',
                             gap: '16px',
                             padding: '16px',
                             borderRadius: '8px'
                         }}>
                        <div className="w-8 h-8 rounded-full border border-on-surface-variant/50 flex items-center justify-center text-on-surface-variant" 
                             style={{ 
                                 borderColor: 'rgba(255,255,255,0.2)',
                                 width: '32px',
                                 height: '32px',
                                 borderRadius: '50%',
                                 display: 'flex',
                                 alignItems: 'center',
                                 justifyContent: 'center',
                                 flexShrink: 0
                             }}>
                            <span className="material-symbols-outlined text-sm" style={{ color: '#ff9d00' }}>warning</span>
                        </div>
                        <div style={{ flex: 1, position: 'relative', zIndex: 10 }}>
                            <div className="font-body-md text-[14px] text-on-surface font-semibold" style={{ fontFamily: "'Hanken Grotesk', sans-serif" }}>
                                Registrar Trades do Dia
                            </div>
                            <div className="font-data-label text-[10px]" style={{ color: '#ff9d00', fontFamily: "'JetBrains Mono', monospace", fontSize: '10px' }}>
                                Time Limit: {countdownStr}
                            </div>
                        </div>
                        <button 
                            onClick={() => { play('click'); openTradeModal(); }}
                            onMouseEnter={() => play('hover')}
                            className="relative z-10 px-3 py-1 text-[10px] font-data-label uppercase border rounded transition-colors"
                            style={{ 
                                borderColor: '#00D1FF', 
                                color: '#00D1FF', 
                                background: 'transparent',
                                cursor: 'pointer',
                                fontFamily: "'JetBrains Mono', monospace",
                                padding: '6px 12px',
                                borderRadius: '4px'
                            }}
                        >
                            Execute
                        </button>
                    </div>
                )}

                {/* 3. Inactive/Pending Quest: Explore Floor Map */}
                <a href="/player/floor-map" 
                   onClick={() => play('click')}
                   onMouseEnter={() => play('hover')}
                   className="glass-panel rounded-lg relative overflow-hidden group hover:bg-surface-variant/30 transition-colors block text-left" 
                   style={{ 
                       borderLeft: '2px solid #00D1FF', 
                       background: 'rgba(23,31,51,0.4)', 
                       textDecoration: 'none',
                       padding: '16px',
                       borderRadius: '8px',
                       display: 'block'
                   }}>
                    <div style={{ display: 'flex', alignItems: 'center', gap: '16px', width: '100%' }}>
                        <div className="w-8 h-8 rounded-full border border-on-surface-variant/50 flex items-center justify-center text-on-surface-variant" 
                             style={{ 
                                 borderColor: 'rgba(255,255,255,0.2)',
                                 width: '32px',
                                 height: '32px',
                                 borderRadius: '50%',
                                 display: 'flex',
                                 alignItems: 'center',
                                 justifyContent: 'center',
                                 flexShrink: 0
                             }}>
                            <span className="material-symbols-outlined text-sm">radio_button_unchecked</span>
                        </div>
                        <div style={{ flex: 1, position: 'relative', zIndex: 10 }}>
                            <div className="font-body-md text-[14px] text-on-surface group-hover:text-primary transition-colors" style={{ fontFamily: "'Hanken Grotesk', sans-serif" }}>
                                Explorar Floor Map
                            </div>
                            <div className="font-data-label text-[10px] text-on-surface-variant uppercase tracking-wider" style={{ fontFamily: "'JetBrains Mono', monospace", fontSize: '10px' }}>
                                Reward: XP Progression
                            </div>
                        </div>
                        <span className="material-symbols-outlined text-sm text-on-surface-variant group-hover:translate-x-1 transition-transform" style={{ color: 'rgba(255,255,255,0.4)' }}>chevron_right</span>
                    </div>
                </a>
            </div>

            <a href="/player/trade-log"
               onClick={() => play('click')}
               onMouseEnter={() => play('hover')}
               className="w-full mt-4 py-2 border border-white/10 rounded text-xs font-data-label text-on-surface-variant hover:text-primary hover:border-primary/50 transition-colors uppercase tracking-widest bg-surface-container-highest/30 block text-center"
               style={{ 
                   borderColor: 'rgba(255,255,255,0.05)',
                   color: 'var(--sao-text-dim)',
                   fontFamily: "'JetBrains Mono', monospace",
                   textDecoration: 'none',
                   padding: '8px 16px',
                   borderRadius: '4px'
               }}>
                View Full Log
            </a>
        </div>
    )
}
