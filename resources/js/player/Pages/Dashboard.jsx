import React, { useState } from 'react'
import { usePage } from '@inertiajs/react'
import PlayerLayout from '../Layouts/PlayerLayout'
import TradeModal from '../Components/TradeModal'
import DailyQuests from '../Components/DailyQuests'
import { useSound } from '../Components/SoundManager'

export default function Dashboard({ stats, xp, recent_trades, active_floors }) {
    const { auth } = usePage().props
    const user = auth?.user
    const { play } = useSound()

    const [showTradeModal, setShowTradeModal] = useState(false)
    const [systemStatus, setSystemStatus] = useState('VERIFY')
    const [verifying, setVerifying] = useState(false)

    const formatMoney = (val) => {
        return (val || 0).toLocaleString('pt-BR', { style: 'currency', currency: 'BRL' })
    }

    const getTradeLabel = (trade) => {
        if (trade.fix) return { text: 'PASSIVE', className: 'passive' }
        if (trade.input === 1) return { text: 'LOOT', className: 'loot' }
        return { text: 'DAMAGE', className: 'damage' }
    }

    const handleVerify = () => {
        if (verifying || systemStatus === 'SECURE') return

        setVerifying(true)
        setSystemStatus('SCANNING')
        play('click')

        setTimeout(() => {
            setSystemStatus('SECURE')
            setVerifying(false)
            play('confirm')

            // Reset back to VERIFY after 4 seconds
            setTimeout(() => {
                setSystemStatus('VERIFY')
            }, 4000)
        }, 1500)
    }

    // Calculate max value for chart scaling
    const maxChartValue = stats.daily_activity ? Math.max(...stats.daily_activity.map(d => Math.max(d.income, d.expense))) : 100

    return (
        <PlayerLayout stats={stats} xp={xp}>
            <div className="page-content" style={{ padding: '24px 16px' }}>

                {/* ═══════ SAO SYSTEM HOME HERO (3 Columns) ═══════ */}
                <div className="aincrad-hero-grid anim-header">
                    
                    {/* ── Column 1: Player Info & HP ── */}
                    <div className="space-y-6 anim-glitch-card delay-1" style={{ display: 'flex', flexDirection: 'column', gap: '16px' }}>
                        
                        {/* Status Card */}
                        <div className="glass-panel rounded-xl p-6 relative overflow-hidden" 
                             style={{ 
                                 background: 'var(--sao-glass)', 
                                 borderRadius: '12px', 
                                 border: '1px solid rgba(0, 209, 255, 0.2)',
                                 padding: '24px',
                                 position: 'relative'
                             }}>
                            <div className="absolute top-2 right-3 font-data-label text-[10px] text-on-surface-variant/50 tracking-widest" 
                                 style={{ 
                                     position: 'absolute',
                                     top: '8px',
                                     right: '12px',
                                     fontFamily: "'JetBrains Mono', monospace", 
                                     color: 'var(--sao-text-muted)',
                                     fontSize: '10px'
                                 }}>
                                ID: 00{user?.id || 1}-PL
                            </div>
                            
                            <div style={{ display: 'flex', alignItems: 'center', gap: '16px', marginBottom: '24px' }}>
                                <div className="w-16 h-16 rounded-full clip-hex bg-surface-variant border-2 border-primary-container p-1 relative avatar-glow" 
                                     style={{ 
                                         width: '64px', 
                                         height: '64px', 
                                         borderColor: '#00D1FF',
                                         borderRadius: '50%',
                                         display: 'flex',
                                         alignItems: 'center',
                                         justifyContent: 'center',
                                         position: 'relative'
                                     }}>
                                    <div className="w-full h-full bg-surface clip-hex flex items-center justify-center font-display text-2xl font-bold text-glow" 
                                         style={{ 
                                             color: '#00D1FF', 
                                             fontFamily: "'Sora', sans-serif",
                                             fontSize: user?.equipped_avatar ? '2rem' : '1.5rem',
                                             fontWeight: 700,
                                             width: '100%',
                                             height: '100%',
                                             display: 'flex',
                                             alignItems: 'center',
                                             justifyContent: 'center'
                                         }}>
                                        {user?.equipped_avatar || user?.player_name?.[0]?.toUpperCase() || user?.name?.[0]?.toUpperCase() || 'P'}
                                    </div>
                                </div>
                                <div>
                                    <h3 className="font-headline-lg text-headline-lg-mobile text-on-surface text-glow" 
                                         style={{ 
                                             fontFamily: "'Sora', sans-serif", 
                                             fontSize: '1.4rem', 
                                             fontWeight: 700, 
                                             margin: 0, 
                                             textShadow: '0 0 10px rgba(0, 209, 255, 0.4)',
                                             color: 'var(--sao-text)',
                                             display: 'flex',
                                             alignItems: 'center',
                                             gap: '8px'
                                         }}>
                                        {user?.player_name || user?.name || 'Player'}
                                        {user?.equipped_title && (
                                            <span style={{
                                                fontSize: '9px', fontWeight: 700, color: 'var(--sao-orange)',
                                                background: 'rgba(255, 157, 0, 0.1)', padding: '2px 8px',
                                                borderRadius: '4px', border: '1px solid rgba(255, 157, 0, 0.3)',
                                                textTransform: 'uppercase', letterSpacing: '0.05em',
                                                fontFamily: "'JetBrains Mono', monospace",
                                                textShadow: 'none'
                                            }}>
                                                {user.equipped_title}
                                            </span>
                                        )}
                                    </h3>
                                    <div style={{ display: 'flex', alignItems: 'center', gap: '8px', marginTop: '4px' }}>
                                        <span className="px-2 py-0.5 font-data-label text-[10px] rounded uppercase" 
                                              style={{ 
                                                  background: 'rgba(0, 209, 255, 0.2)', 
                                                  border: '1px solid rgba(0, 209, 255, 0.4)', 
                                                  color: '#00D1FF', 
                                                  fontFamily: "'JetBrains Mono', monospace", 
                                                  fontSize: '10px',
                                                  padding: '2px 6px',
                                                  borderRadius: '4px'
                                              }}>
                                            LVL {xp?.current_level || user?.level || 1}
                                        </span>
                                        <span className="text-on-surface-variant font-data-label text-xs uppercase" 
                                              style={{ fontFamily: "'JetBrains Mono', monospace", color: 'var(--sao-text-dim)', fontSize: '12px' }}>
                                            Front Liner
                                        </span>
                                    </div>
                                </div>
                            </div>

                            {/* HP / Balance Tracker */}
                            <div className="space-y-2" style={{ marginTop: '16px' }}>
                                <div style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'baseline', marginBottom: '6px' }}>
                                    <span className="font-data-label text-data-label text-on-surface-variant uppercase" 
                                          style={{ fontFamily: "'JetBrains Mono', monospace", fontSize: '11px', color: 'var(--sao-text-dim)' }}>
                                        Current Col (HP)
                                    </span>
                                    <span className="font-data-value text-data-value text-primary text-glow" 
                                          style={{ color: '#00D1FF', fontFamily: "'JetBrains Mono', monospace", fontSize: '1.2rem', fontWeight: 700 }}>
                                        {formatMoney(stats?.balance || 0)}
                                    </span>
                                </div>
                                <div className="w-full h-3 bg-surface-container-highest rounded-full overflow-hidden border border-white/5 relative" 
                                     style={{ height: '10px', background: 'rgba(255,255,255,0.05)', borderRadius: '999px', border: '1px solid rgba(255,255,255,0.1)', position: 'relative', overflow: 'hidden' }}>
                                    {/* Segmented layout overlay */}
                                    <div className="absolute inset-0 bg-[linear-gradient(90deg,transparent_90%,rgba(0,0,0,0.5)_90%)] bg-[length:10%_100%] z-10 pointer-events-none" style={{ position: 'absolute', top: 0, left: 0, right: 0, bottom: 0 }} />
                                    <div 
                                        className="h-full hp-bar-fill relative z-0" 
                                        style={{ 
                                            width: `${stats?.hp_percentage || 0}%`,
                                            background: stats?.hp_percentage < 30 
                                                ? 'linear-gradient(90deg, #ff4757, #ff6b6b)'
                                                : stats?.hp_percentage < 70
                                                    ? 'linear-gradient(90deg, #ff9d00, #fdcb6e)'
                                                    : 'linear-gradient(90deg, #00D1FF, #0082A0)',
                                            boxShadow: stats?.hp_percentage < 30 
                                                ? '0 0 10px #ff4757'
                                                : stats?.hp_percentage < 70
                                                    ? '0 0 10px #ff9d00'
                                                    : '0 0 10px #00D1FF',
                                            height: '100%',
                                            transition: 'width 1s ease-in-out',
                                            borderRadius: '999px'
                                        }} 
                                    />
                                </div>
                                <div style={{ display: 'flex', justifyContent: 'space-between', fontSize: '10px', color: 'var(--sao-text-muted)', fontFamily: "'JetBrains Mono', monospace", marginTop: '4px' }}>
                                    <span>HP: {stats?.hp_percentage || 0}%</span>
                                    <span>Limit: {formatMoney(stats?.monthly_income || 0)}</span>
                                </div>
                            </div>
                        </div>

                        {/* Monthly Loot */}
                        <div className="glass-panel rounded-lg p-5 flex items-center justify-between border-l-4 border-l-primary-container" 
                             style={{ 
                                 display: 'flex', 
                                 justifyContent: 'space-between', 
                                 alignItems: 'center', 
                                 background: 'var(--sao-glass)', 
                                 borderLeft: '4px solid #00D1FF', 
                                 borderTop: '1px solid var(--sao-border-subtle)', 
                                 borderRight: '1px solid var(--sao-border-subtle)', 
                                 borderBottom: '1px solid var(--sao-border-subtle)', 
                                 padding: '20px', 
                                 borderRadius: '8px' 
                             }}>
                            <div>
                                <div className="font-data-label text-[12px] text-on-surface-variant uppercase mb-1 flex items-center gap-1" 
                                     style={{ display: 'flex', alignItems: 'center', gap: '4px', fontFamily: "'JetBrains Mono', monospace", fontSize: '11px', color: 'var(--sao-text-dim)' }}>
                                    <span className="material-symbols-outlined text-[14px]" style={{ fontSize: '16px' }}>trending_up</span>
                                    Monthly Loot (Income)
                                </div>
                                <div className="font-data-value text-[20px] text-on-surface text-glow" 
                                     style={{ color: '#00D1FF', fontSize: '1.25rem', fontWeight: 700, fontFamily: "'JetBrains Mono', monospace" }}>
                                    + {formatMoney(stats?.monthly_income || 0)}
                                </div>
                            </div>
                            <div className="w-10 h-10 rounded-full bg-primary/10 flex items-center justify-center border border-primary/30" 
                                 style={{ 
                                     width: '40px', 
                                     height: '40px', 
                                     background: 'rgba(0, 209, 255, 0.1)', 
                                     border: '1px solid rgba(0, 209, 255, 0.3)', 
                                     borderRadius: '50%', 
                                     display: 'flex', 
                                     alignItems: 'center', 
                                     justifyContent: 'center' 
                                 }}>
                                <span className="material-symbols-outlined text-primary" style={{ color: '#00D1FF', fontVariationSettings: "'FILL' 1" }}>star</span>
                            </div>
                        </div>

                    </div>

                    {/* ── Column 2: Central Circular Menu ── */}
                    <div className="flex justify-center py-12 md:py-0 relative anim-center-menu" style={{ display: 'flex', justifyContent: 'center', height: '320px', position: 'relative', alignItems: 'center' }}>
                        {/* Decorative Outer Rings */}
                        <div className="absolute inset-0 flex items-center justify-center pointer-events-none" style={{ position: 'absolute', top: 0, left: 0, right: 0, bottom: 0, display: 'flex', alignItems: 'center', justifyContent: 'center' }}>
                            <div className="w-[300px] h-[300px] rounded-full border border-primary/20 border-dashed animate-spin-slow absolute" 
                                 style={{ width: '280px', height: '280px', borderRadius: '50%', border: '1px dashed rgba(0, 209, 255, 0.2)', position: 'absolute' }}></div>
                            <div className="w-[260px] h-[260px] rounded-full border border-primary/10 absolute animate-spin-reverse-slow" 
                                 style={{ width: '240px', height: '240px', borderRadius: '50%', border: '1px solid rgba(0, 209, 255, 0.1)', position: 'absolute' }}></div>
                            {/* Crosshairs */}
                            <div className="w-[320px] h-[1px] bg-primary/10 absolute" style={{ width: '300px', height: '1px', backgroundColor: 'rgba(0, 209, 255, 0.08)', position: 'absolute' }}></div>
                            <div className="h-[320px] w-[1px] bg-primary/10 absolute" style={{ height: '300px', width: '1px', backgroundColor: 'rgba(0, 209, 255, 0.08)', position: 'absolute' }}></div>
                        </div>
                        
                        <div className="relative w-64 h-64 z-10 flex items-center justify-center" style={{ position: 'relative', width: '240px', height: '240px', display: 'flex', alignItems: 'center', justifyContent: 'center' }}>
                            {/* Center Fingerprint Verify Button */}
                            <button 
                                onClick={handleVerify}
                                onMouseEnter={() => play('hover')}
                                className="absolute w-24 h-24 rounded-full flex flex-col items-center justify-center gap-1 menu-item-hover z-20 group"
                                style={{ 
                                    position: 'absolute',
                                    width: '90px',
                                    height: '90px',
                                    borderRadius: '50%',
                                    border: systemStatus === 'SECURE' ? '1px solid rgba(74, 225, 131, 0.6)' : '1px solid rgba(0, 209, 255, 0.5)', 
                                    background: 'rgba(12, 14, 21, 0.95)',
                                    cursor: 'pointer',
                                    display: 'flex',
                                    flexDirection: 'column',
                                    alignItems: 'center',
                                    justifyContent: 'center',
                                    gap: '2px',
                                    boxShadow: systemStatus === 'SECURE' ? '0 0 15px rgba(74, 225, 131, 0.3)' : '0 0 15px rgba(0, 209, 255, 0.2)'
                                }}
                            >
                                <span className={`material-symbols-outlined text-3xl ${verifying ? 'sao-blink' : ''}`} 
                                      style={{ 
                                          fontSize: '32px',
                                          fontVariationSettings: "'FILL' 0", 
                                          color: systemStatus === 'SECURE' ? '#4ae183' : systemStatus === 'SCANNING' ? '#ff9d00' : '#00D1FF',
                                          textShadow: systemStatus === 'SECURE' ? '0 0 8px rgba(74, 225, 131, 0.6)' : '0 0 8px rgba(0, 209, 255, 0.6)'
                                      }}>
                                    {systemStatus === 'SECURE' ? 'check_circle' : 'fingerprint'}
                                </span>
                                <span className="font-data-label text-[10px] uppercase tracking-widest" 
                                      style={{ 
                                          color: systemStatus === 'SECURE' ? '#4ae183' : systemStatus === 'SCANNING' ? '#ff9d00' : '#00D1FF',
                                          fontFamily: "'JetBrains Mono', monospace",
                                          fontSize: '9px',
                                          letterSpacing: '0.05em'
                                      }}>
                                    {systemStatus}
                                </span>
                            </button>
                            
                            {/* Orbiting Navigation Options */}
                            {/* Top: Trade Log */}
                            <a 
                                href="/player/trade-log"
                                onMouseEnter={() => play('hover')}
                                onClick={() => play('click')}
                                className="absolute w-12 h-12 rounded-full glass-panel flex items-center justify-center menu-item-hover text-on-surface-variant hover:text-primary border-primary/30"
                                style={{ 
                                    position: 'absolute',
                                    top: 0,
                                    width: '46px',
                                    height: '46px',
                                    borderRadius: '50%',
                                    border: '1px solid rgba(0, 209, 255, 0.3)',
                                    background: 'var(--sao-glass)',
                                    display: 'flex',
                                    alignItems: 'center',
                                    justifyContent: 'center',
                                    color: 'var(--sao-text-dim)',
                                    cursor: 'pointer'
                                }}
                                title="Trade Log"
                            >
                                <span className="material-symbols-outlined" style={{ fontSize: '20px' }}>analytics</span>
                            </a>
                            
                            {/* Right: Add Transaction */}
                            <button 
                                onClick={() => { play('click'); setShowTradeModal(true); }}
                                onMouseEnter={() => play('hover')}
                                className="absolute w-12 h-12 rounded-full glass-panel flex items-center justify-center menu-item-hover text-on-surface-variant hover:text-primary border-primary/30"
                                style={{ 
                                    position: 'absolute',
                                    right: 0,
                                    width: '46px',
                                    height: '46px',
                                    borderRadius: '50%',
                                    border: '1px solid rgba(0, 209, 255, 0.3)',
                                    background: 'var(--sao-glass)',
                                    display: 'flex',
                                    alignItems: 'center',
                                    justifyContent: 'center',
                                    color: 'var(--sao-text-dim)',
                                    cursor: 'pointer'
                                }}
                                title="Registrar Trade"
                            >
                                <span className="material-symbols-outlined" style={{ fontSize: '20px' }}>sync_alt</span>
                            </button>
                            
                            {/* Bottom: Shop */}
                            <a 
                                href="/player/shop"
                                onMouseEnter={() => play('hover')}
                                onClick={() => play('click')}
                                className="absolute w-12 h-12 rounded-full glass-panel flex items-center justify-center menu-item-hover text-on-surface-variant hover:text-primary border-primary/30"
                                style={{ 
                                    position: 'absolute',
                                    bottom: 0,
                                    width: '46px',
                                    height: '46px',
                                    borderRadius: '50%',
                                    border: '1px solid rgba(0, 209, 255, 0.3)',
                                    background: 'var(--sao-glass)',
                                    display: 'flex',
                                    alignItems: 'center',
                                    justifyContent: 'center',
                                    color: 'var(--sao-text-dim)',
                                    cursor: 'pointer'
                                }}
                                title="Shop"
                            >
                                <span className="material-symbols-outlined" style={{ fontSize: '20px' }}>shopping_cart</span>
                            </a>
                            
                            {/* Left: Floor Map */}
                            <a 
                                href="/player/floor-map"
                                onMouseEnter={() => play('hover')}
                                onClick={() => play('click')}
                                className="absolute w-12 h-12 rounded-full glass-panel flex items-center justify-center menu-item-hover text-on-surface-variant hover:text-primary border-primary/30"
                                style={{ 
                                    position: 'absolute',
                                    left: 0,
                                    width: '46px',
                                    height: '46px',
                                    borderRadius: '50%',
                                    border: '1px solid rgba(0, 209, 255, 0.3)',
                                    background: 'var(--sao-glass)',
                                    display: 'flex',
                                    alignItems: 'center',
                                    justifyContent: 'center',
                                    color: 'var(--sao-text-dim)',
                                    cursor: 'pointer'
                                }}
                                title="Floor Map"
                            >
                                <span className="material-symbols-outlined" style={{ fontSize: '20px' }}>shield</span>
                            </a>
                        </div>
                    </div>

                    {/* ── Column 3: Daily Quests ── */}
                    <div className="space-y-4 anim-right-panel delay-3">
                        <DailyQuests stats={stats} recent_trades={recent_trades} openTradeModal={() => setShowTradeModal(true)} />
                    </div>

                </div>

                {/* ═══════ TACTICAL LEDGER (Detailed Widgets) ═══════ */}
                <div className="ledger-section">
                    <div className="ledger-header" style={{ marginBottom: '24px' }}>
                        <h3 className="ledger-title" style={{ margin: 0 }}>Tactical Ledger</h3>
                        <span className="label-caps" style={{ fontSize: '11px', color: 'var(--sao-text-dim)', fontFamily: "'JetBrains Mono', monospace" }}>
                            System Ledger // Detailed Logs & Progression
                        </span>
                    </div>

                    {/* Row 1: Detailed Stats overview cards */}
                    <div className="stats-grid" style={{ display: 'grid', gridTemplateColumns: 'repeat(auto-fit, minmax(200px, 1fr))', gap: '16px', marginBottom: '24px' }}>
                        {/* Total Loot */}
                        <div className="sao-panel glass-panel glass-panel-teal" style={{ overflow: 'hidden' }}>
                            <div className="stat-card">
                                <div style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'flex-start' }}>
                                    <div>
                                        <span className="label-caps" style={{ fontSize: '10px', color: 'var(--sao-text-dim)', fontFamily: "'JetBrains Mono', monospace" }}>TOTAL LOOT</span>
                                        <span className="stat-value success" style={{ display: 'block', marginTop: '4px', fontSize: '1.25rem', fontWeight: 700, color: '#4ae183' }}>{formatMoney(stats.monthly_income)}</span>
                                    </div>
                                    <div style={{
                                        width: '40px', height: '40px', borderRadius: '50%',
                                        background: 'rgba(74,225,131,0.1)', display: 'flex',
                                        alignItems: 'center', justifyContent: 'center'
                                    }}>
                                        <span className="material-symbols-outlined" style={{ color: '#4ae183', fontVariationSettings: "'FILL' 1" }}>bar_chart</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {/* Total Damage */}
                        <div className="sao-panel glass-panel glass-panel-teal" style={{ overflow: 'hidden' }}>
                            <div className="stat-card">
                                <div style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'flex-start' }}>
                                    <div>
                                        <span className="label-caps" style={{ fontSize: '10px', color: 'var(--sao-text-dim)', fontFamily: "'JetBrains Mono', monospace" }}>TOTAL DAMAGE</span>
                                        <span className="stat-value danger" style={{ display: 'block', marginTop: '4px', fontSize: '1.25rem', fontWeight: 700, color: '#ff4757' }}>{formatMoney(stats.monthly_expense)}</span>
                                    </div>
                                    <div style={{
                                        width: '40px', height: '40px', borderRadius: '50%',
                                        background: 'rgba(255,71,87,0.1)', display: 'flex',
                                        alignItems: 'center', justifyContent: 'center'
                                    }}>
                                        <span className="material-symbols-outlined" style={{ color: '#ff4757', fontVariationSettings: "'FILL' 1" }}>trending_down</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {/* Balance */}
                        <div className="sao-panel glass-panel glass-panel-teal" style={{ overflow: 'hidden' }}>
                            <div className="stat-card">
                                <div style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'flex-start' }}>
                                    <div>
                                        <span className="label-caps" style={{ fontSize: '10px', color: 'var(--sao-text-dim)', fontFamily: "'JetBrains Mono', monospace" }}>BALANCE</span>
                                        <span className={`stat-value ${stats.balance >= 0 ? 'success' : 'danger'}`} 
                                              style={{ display: 'block', marginTop: '4px', fontSize: '1.25rem', fontWeight: 700, color: stats.balance >= 0 ? '#4ae183' : '#ff4757' }}>
                                            {formatMoney(stats.balance)}
                                        </span>
                                    </div>
                                    <div style={{
                                        width: '40px', height: '40px', borderRadius: '50%',
                                        background: stats.balance >= 0 ? 'rgba(74,225,131,0.1)' : 'rgba(255,71,87,0.1)', display: 'flex',
                                        alignItems: 'center', justifyContent: 'center'
                                    }}>
                                        <span className="material-symbols-outlined" style={{ color: stats.balance >= 0 ? '#4ae183' : '#ff4757', fontVariationSettings: "'FILL' 1" }}>account_balance_wallet</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {/* Streak */}
                        <div className="sao-panel glass-panel glass-panel-orange" style={{ overflow: 'hidden' }}>
                            <div className="stat-card">
                                <div style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'flex-start' }}>
                                    <div>
                                        <span className="label-caps" style={{ fontSize: '10px', color: 'var(--sao-text-dim)', fontFamily: "'JetBrains Mono', monospace" }}>STREAK</span>
                                        <span className="stat-value" style={{ display: 'block', marginTop: '4px', color: 'var(--sao-orange)', fontSize: '1.25rem', fontWeight: 700 }}>
                                            {stats.streak} <span style={{ fontSize: '0.8rem', fontWeight: 600 }}>DAYS</span>
                                        </span>
                                    </div>
                                    <div style={{
                                        width: '40px', height: '40px', borderRadius: '50%',
                                        background: 'rgba(255,157,0,0.1)', display: 'flex',
                                        alignItems: 'center', justifyContent: 'center'
                                    }}>
                                        <span className="material-symbols-outlined" style={{ color: '#ff9d00', fontVariationSettings: "'FILL' 1" }}>local_fire_department</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {/* Row 2: Chart & Top Expenses */}
                    <div className="content-grid" style={{ display: 'grid', gridTemplateColumns: 'repeat(auto-fit, minmax(300px, 1fr))', gap: '16px', marginBottom: '24px' }}>
                        
                        {/* 7-Day Activity Chart */}
                        <div className="sao-panel glass-panel glass-panel-teal" style={{ overflow: 'hidden' }}>
                            <div className="panel-header" style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'center', padding: '12px 16px', borderBottom: '1px solid var(--sao-border-subtle)' }}>
                                <h3 style={{ color: '#00D1FF', fontFamily: "'Sora', sans-serif", fontSize: '0.95rem', fontWeight: 700, textTransform: 'uppercase', margin: 0, textShadow: '0 0 10px rgba(0, 209, 255, 0.3)' }}>
                                    7-Day Activity
                                </h3>
                                <span className="material-symbols-outlined" style={{ color: 'var(--sao-text-muted)', fontSize: '18px' }}>more_horiz</span>
                            </div>
                            <div style={{ padding: '16px', height: '180px', display: 'flex', alignItems: 'flex-end', justifyContent: 'space-between', gap: '8px' }}>
                                {stats.daily_activity && stats.daily_activity.map((day, i) => (
                                    <div key={i} style={{ flex: 1, display: 'flex', flexDirection: 'column', alignItems: 'center', gap: '4px', height: '100%' }}>
                                        <div style={{ flex: 1, width: '100%', display: 'flex', alignItems: 'flex-end', gap: '4px', position: 'relative' }}>
                                            {/* Income Bar */}
                                            <div style={{
                                                flex: 1, background: 'linear-gradient(to top, #06bb63, #4ae183)',
                                                height: `${Math.max(4, (day.income / maxChartValue) * 100)}%`,
                                                borderRadius: '2px 2px 0 0', opacity: 0.8, transition: 'height 0.5s ease',
                                                boxShadow: '0 0 6px rgba(74,225,131,0.3)'
                                            }} title={`Income: ${formatMoney(day.income)}`} />

                                            {/* Expense Bar */}
                                            <div style={{
                                                flex: 1, background: 'linear-gradient(to top, #e74c3c, #ff6b6b)',
                                                height: `${Math.max(4, (day.expense / maxChartValue) * 100)}%`,
                                                borderRadius: '2px 2px 0 0', opacity: 0.8, transition: 'height 0.5s ease',
                                                boxShadow: '0 0 6px rgba(255,71,87,0.3)'
                                            }} title={`Expense: ${formatMoney(day.expense)}`} />
                                        </div>
                                        <span className="label-caps" style={{ fontSize: '9px', color: 'var(--sao-text-dim)', fontFamily: "'JetBrains Mono', monospace" }}>{day.date}</span>
                                    </div>
                                ))}
                            </div>
                        </div>

                        {/* Top Categories */}
                        <div className="sao-panel glass-panel glass-panel-teal" style={{ overflow: 'hidden' }}>
                            <div className="panel-header" style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'center', padding: '12px 16px', borderBottom: '1px solid var(--sao-border-subtle)' }}>
                                <h3 style={{ color: '#00D1FF', fontFamily: "'Sora', sans-serif", fontSize: '0.95rem', fontWeight: 700, textTransform: 'uppercase', margin: 0, textShadow: '0 0 10px rgba(0, 209, 255, 0.3)' }}>
                                    Top Expenses
                                </h3>
                                <span className="material-symbols-outlined" style={{ color: 'var(--sao-text-muted)', fontSize: '18px' }}>more_horiz</span>
                            </div>
                            <div style={{ padding: '16px' }}>
                                {stats.top_categories && stats.top_categories.length > 0 ? (
                                    <div style={{ display: 'flex', flexDirection: 'column', gap: '12px' }}>
                                        {stats.top_categories.map((cat, i) => (
                                            <div key={i}>
                                                <div style={{ display: 'flex', justifyContent: 'space-between', fontSize: '0.8rem', marginBottom: '4px' }}>
                                                    <span style={{ fontWeight: 700, fontFamily: "'Sora', sans-serif" }}>{cat.tag}</span>
                                                    <span style={{ color: 'var(--sao-danger)' }}>{formatMoney(cat.amount)}</span>
                                                </div>
                                                <div className="progress-track" style={{ height: '6px', background: 'var(--sao-surface)', borderRadius: '4px', overflow: 'hidden' }}>
                                                    <div className="progress-fill-hp" style={{
                                                        width: `${(cat.amount / stats.monthly_expense) * 100}%`,
                                                        background: 'linear-gradient(90deg, #e74c3c, #ff6b6b)',
                                                        boxShadow: '0 0 8px rgba(255, 71, 87, 0.4)',
                                                        height: '100%'
                                                    }}>
                                                        <div className="progress-glint" />
                                                    </div>
                                                </div>
                                            </div>
                                        ))}
                                    </div>
                                ) : (
                                    <div style={{ textAlign: 'center', color: 'var(--sao-text-muted)', padding: '20px' }}>
                                        <span className="material-symbols-outlined" style={{ fontSize: '32px', marginBottom: '8px', display: 'block', opacity: 0.5 }}>analytics</span>
                                        Sem dados de gastos.
                                    </div>
                                )}
                            </div>
                        </div>

                    </div>

                    {/* Row 3: Recent Trades & Active Floors */}
                    <div className="content-grid" style={{ display: 'grid', gridTemplateColumns: 'repeat(auto-fit, minmax(300px, 1fr))', gap: '16px' }}>
                        
                        {/* Recent Trades */}
                        <div className="sao-panel glass-panel glass-panel-teal" style={{ overflow: 'hidden' }}>
                            <div className="panel-header" style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'center', padding: '12px 16px', borderBottom: '1px solid var(--sao-border-subtle)' }}>
                                <h3 style={{ color: '#00D1FF', fontFamily: "'Sora', sans-serif", fontSize: '0.95rem', fontWeight: 700, textTransform: 'uppercase', margin: 0, textShadow: '0 0 10px rgba(0, 209, 255, 0.3)' }}>
                                    Recent Trades
                                </h3>
                                <button className="sao-btn sm" onClick={() => { play('click'); setShowTradeModal(true); }}
                                        style={{ 
                                            background: 'rgba(0, 209, 255, 0.1)', 
                                            border: '1px solid #00D1FF', 
                                            color: '#00D1FF',
                                            padding: '4px 10px',
                                            fontSize: '10px',
                                            borderRadius: '4px',
                                            cursor: 'pointer',
                                            fontFamily: "'JetBrains Mono', monospace",
                                            display: 'flex',
                                            alignItems: 'center',
                                            gap: '4px'
                                        }}>
                                    <span className="material-symbols-outlined" style={{ fontSize: '12px' }}>add</span>
                                    NEW
                                </button>
                            </div>
                            <div>
                                {recent_trades && recent_trades.length > 0 ? (
                                    recent_trades.map(trade => {
                                        const label = getTradeLabel(trade)
                                        return (
                                            <div key={trade.id} className="trade-row" 
                                                 style={{ 
                                                     display: 'flex', 
                                                     justifyContent: 'space-between', 
                                                     alignItems: 'center', 
                                                     padding: '12px 16px', 
                                                     borderBottom: '1px solid var(--sao-border-subtle)' 
                                                 }}>
                                                <div className="trade-info" style={{ display: 'flex', alignItems: 'center', gap: '10px' }}>
                                                    <span className={`badge ${label.className}`} 
                                                          style={{ 
                                                              fontSize: '9px', 
                                                              padding: '2px 6px', 
                                                              borderRadius: '4px',
                                                              fontWeight: 700,
                                                              background: label.className === 'loot' 
                                                                ? 'rgba(74,225,131,0.1)' 
                                                                : label.className === 'damage' 
                                                                    ? 'rgba(255,71,87,0.1)' 
                                                                    : 'var(--sao-surface)',
                                                              color: label.className === 'loot' 
                                                                ? '#4ae183' 
                                                                : label.className === 'damage' 
                                                                    ? '#ff4757' 
                                                                    : '#8a8a9a'
                                                          }}>{label.text}</span>
                                                    <div>
                                                        <div className="trade-name" style={{ fontSize: '13px', fontWeight: 600, color: 'var(--sao-text)' }}>{trade.name}</div>
                                                        <div className="trade-date" style={{ fontSize: '10px', color: 'var(--sao-text-muted)', marginTop: '2px' }}>{trade.created_at}</div>
                                                    </div>
                                                </div>
                                                <div className={`trade-value`}
                                                    style={{ color: trade.input === 1 ? '#4ae183' : '#ff4757', fontWeight: 700, fontFamily: "'JetBrains Mono', monospace" }}>
                                                    {trade.input === 1 ? '+' : '-'}{formatMoney(trade.total_value)}
                                                </div>
                                            </div>
                                        )
                                    })
                                ) : (
                                    <div style={{ padding: '32px 16px', textAlign: 'center', color: 'var(--sao-text-dim)' }}>
                                        <span className="material-symbols-outlined" style={{ fontSize: '32px', marginBottom: '8px', display: 'block', opacity: 0.5 }}>mail</span>
                                        <p style={{ fontSize: '0.8rem', fontFamily: "'Hanken Grotesk', sans-serif" }}>Nenhum trade registrado ainda.</p>
                                        <button className="sao-btn sm" style={{ marginTop: '12px' }} onClick={() => setShowTradeModal(true)}>
                                            <span className="material-symbols-outlined" style={{ fontSize: '14px' }}>add</span>
                                            Registrar primeiro trade
                                        </button>
                                    </div>
                                )}
                            </div>
                        </div>

                        {/* Aincrad Boss Battle Progress */}
                        <div className="sao-panel glass-panel glass-panel-teal" style={{ overflow: 'hidden' }}>
                            <div className="panel-header" style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'center', padding: '12px 16px', borderBottom: '1px solid var(--sao-border-subtle)' }}>
                                <h3 style={{ color: '#ff4757', fontFamily: "'Sora', sans-serif", fontSize: '0.95rem', fontWeight: 700, textTransform: 'uppercase', margin: 0, textShadow: '0 0 10px rgba(255, 71, 87, 0.3)', display: 'flex', alignItems: 'center', gap: '6px' }}>
                                    ⚔️ Aincrad Boss Battles
                                </h3>
                                <span className="material-symbols-outlined" style={{ color: 'var(--sao-text-muted)', fontSize: '18px' }}>more_horiz</span>
                            </div>
                            <div style={{ padding: '12px 8px', display: 'flex', flexDirection: 'column', gap: '8px' }}>
                                {active_floors && active_floors.length > 0 ? (
                                    active_floors.map(floor => {
                                        // Boss dictionary mapping
                                        const BOSSES = {
                                            1: { name: 'Illfang the Kobold Lord', avatar: '👹', desc: 'Dificuldade Inicial: Junte seus primeiros Col!' },
                                            22: { name: 'The Witch of the Lake', avatar: '🧙‍♀️', desc: 'Dona do Lago da Floresta: Desbloqueie sua cabana!' },
                                            48: { name: 'The Wyrm of the Snow', avatar: '🐉', desc: 'Dragão de Gelo Cristalino: Forje sua arma!' },
                                            74: { name: 'The Gleam Eyes', avatar: '👿', desc: 'O Demônio de Olhos Azuis: Enfrente o boss!' },
                                            100: { name: 'An Incarnation of the Radius', avatar: '👑', desc: 'O Boss Final de Aincrad!' },
                                        };
                                        const boss = BOSSES[floor.floor_number] || {
                                            name: `Floor ${floor.floor_number} Guardian`,
                                            avatar: ['👾', '🕷️', '🦁', '🦅', '🦍', '🦂', '👹'][floor.floor_number % 7],
                                            desc: `Guardião do andar ${floor.floor_number}`
                                        };

                                        const bossMaxHp = floor.target_amount;
                                        const bossCurrentHp = Math.max(0, floor.target_amount - floor.current_amount);
                                        const bossHpPercentage = 100 - floor.progress;

                                        return (
                                            <div key={floor.id} style={{
                                                padding: '14px',
                                                borderRadius: '8px',
                                                background: floor.status === 'cleared' ? 'rgba(74,225,131,0.03)' : 'var(--sao-surface)',
                                                border: `1px solid ${floor.status === 'cleared' ? 'rgba(74,225,131,0.2)' : floor.status === 'active' ? 'rgba(255,71,87,0.25)' : 'var(--sao-border-subtle)'}`,
                                                transition: 'border-color 0.2s',
                                                display: 'flex',
                                                flexDirection: 'column',
                                                gap: '8px',
                                                boxShadow: floor.status === 'cleared' ? '0 0 10px rgba(74, 225, 131, 0.05)' : undefined
                                            }}>
                                                {/* Header: Floor & Boss info */}
                                                <div style={{ display: 'flex', alignItems: 'center', gap: '10px' }}>
                                                    <div style={{
                                                        width: '36px', height: '36px', borderRadius: '8px',
                                                        background: 'var(--sao-bg)', border: `1px solid ${floor.status === 'cleared' ? 'var(--sao-success)' : 'var(--sao-danger)'}`,
                                                        display: 'flex', alignItems: 'center', justifyContent: 'center',
                                                        fontSize: '1.4rem'
                                                    }}>
                                                        {floor.status === 'cleared' ? '🏆' : boss.avatar}
                                                    </div>
                                                    <div style={{ flex: 1 }}>
                                                        <div style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'baseline' }}>
                                                            <span style={{ fontFamily: "'Sora', sans-serif", fontSize: '0.85rem', fontWeight: 700, color: floor.status === 'cleared' ? '#4ae183' : 'var(--sao-text)' }}>
                                                                Floor {floor.floor_number}: {floor.name}
                                                            </span>
                                                            {floor.status === 'cleared' ? (
                                                                <span style={{ fontSize: '9px', fontWeight: 700, color: '#4ae183', background: 'rgba(74,225,131,0.1)', padding: '1px 6px', borderRadius: '3px', border: '1px solid rgba(74,225,131,0.3)', fontFamily: "'JetBrains Mono', monospace" }}>
                                                                    DEFEATED
                                                                </span>
                                                            ) : (
                                                                <span style={{ fontSize: '9px', fontWeight: 700, color: '#ff4757', background: 'rgba(255,71,87,0.1)', padding: '1px 6px', borderRadius: '3px', border: '1px solid rgba(255,71,87,0.3)', fontFamily: "'JetBrains Mono', monospace", animation: 'sao-blink 2s infinite' }}>
                                                                    ACTIVE
                                                                </span>
                                                            )}
                                                        </div>
                                                        <div style={{ fontSize: '0.65rem', color: 'var(--sao-text-dim)', marginTop: '2px' }}>
                                                            {floor.status === 'cleared' ? 'O boss deste andar foi derrotado!' : `Boss: ${boss.name}`}
                                                        </div>
                                                    </div>
                                                </div>

                                                {/* Bar & Stats */}
                                                <div style={{ marginTop: '4px' }}>
                                                    <div style={{ display: 'flex', justifyContent: 'space-between', fontSize: '10px', color: 'var(--sao-text-muted)', fontFamily: "'JetBrains Mono', monospace", marginBottom: '4px' }}>
                                                        <span>{floor.status === 'cleared' ? 'BOSS HP: 0 / ' + formatMoney(bossMaxHp) : `BOSS HP: ${formatMoney(bossCurrentHp)} / ${formatMoney(bossMaxHp)}`}</span>
                                                        <span>{floor.status === 'cleared' ? '0%' : `${bossHpPercentage}%`}</span>
                                                    </div>
                                                    <div className="progress-track" style={{ height: '8px', background: 'var(--sao-surface)', borderRadius: '4px', overflow: 'hidden', border: '1px solid var(--sao-border-subtle)' }}>
                                                        <div className="progress-fill-hp" style={{
                                                            width: `${floor.status === 'cleared' ? 0 : bossHpPercentage}%`,
                                                            background: 'linear-gradient(90deg, #ff4757, #ff6b6b)',
                                                            boxShadow: '0 0 10px rgba(255, 71, 87, 0.4)',
                                                            height: '100%',
                                                            transition: 'width 1s ease-in-out'
                                                        }}>
                                                            <div className="progress-glint" />
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        );
                                    })
                                ) : (
                                    <div style={{ padding: '32px 16px', textAlign: 'center', color: 'var(--sao-text-dim)' }}>
                                        <span className="material-symbols-outlined" style={{ fontSize: '32px', marginBottom: '8px', display: 'block', opacity: 0.5 }}>castle</span>
                                        <p style={{ fontSize: '0.8rem', fontFamily: "'Hanken Grotesk', sans-serif" }}>Nenhum andar desbloqueado.</p>
                                        <a href="/player/floor-map" className="sao-btn sm" style={{ marginTop: '12px' }}>
                                            <span className="material-symbols-outlined" style={{ fontSize: '14px' }}>explore</span>
                                            Explorar Floor Map
                                        </a>
                                    </div>
                                )}
                            </div>
                        </div>

                    </div>
                </div>

            </div>

            <TradeModal isOpen={showTradeModal} onClose={() => setShowTradeModal(false)} />
        </PlayerLayout>
    )
}
