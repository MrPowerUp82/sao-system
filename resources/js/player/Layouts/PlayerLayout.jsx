import React, { useState, useEffect, useRef } from 'react'
import { Link, usePage } from '@inertiajs/react'
import HpBar from '../Components/HpBar'
import XpBar from '../Components/XpBar'
import LevelUpOverlay from '../Components/LevelUpOverlay'
import { useSound } from '../Components/SoundManager'
import YuiCompanion from '../Components/YuiCompanion'
import ColBalance from '../Components/ColBalance'

const NAV_ITEMS = [
    { icon: 'dashboard', label: 'Dashboard', href: '/player', name: 'player.dashboard' },
    { icon: 'receipt_long', label: 'Trade Log', href: '/player/trade-log', name: 'player.trade-log' },
    { icon: 'map', label: 'Floor Map', href: '/player/floor-map', name: 'player.floor-map' },
    { icon: 'inventory_2', label: 'Inventory', href: '/player/inventory', name: 'player.inventory' },
    { icon: 'storefront', label: 'Shop', href: '/player/shop', name: 'player.shop' },
    { icon: 'groups', label: 'Guild', href: '/player/guild', name: 'player.guild' },
]

export default function PlayerLayout({ children, stats, xp }) {
    const { auth, flash } = usePage().props
    const user = auth?.user
    const currentPath = window.location.pathname
    const { play, enabled: soundEnabled, toggle: toggleSound } = useSound()

    // Level-up detection
    const [showLevelUp, setShowLevelUp] = useState(false)
    const [levelUpLevel, setLevelUpLevel] = useState(1)
    const prevLevelRef = useRef(null)

    useEffect(() => {
        const currentLevel = xp?.current_level || user?.level || 1
        if (prevLevelRef.current !== null && currentLevel > prevLevelRef.current) {
            setLevelUpLevel(currentLevel)
            setShowLevelUp(true)
        }
        prevLevelRef.current = currentLevel
    }, [xp?.current_level, user?.level])

    // Flash sound
    useEffect(() => {
        if (flash?.success) {
            if (flash.success.includes('FLOOR CLEARED')) {
                play('floorCleared')
            } else if (flash.success.includes('XP')) {
                play('confirm')
            } else {
                play('notification')
            }
        }
        if (flash?.error) {
            play('error')
        }
    }, [flash?.success, flash?.error])

    return (
        <div className="player-layout bg-grid-pattern">
            {/* Level Up Overlay */}
            <LevelUpOverlay
                show={showLevelUp}
                level={levelUpLevel}
                onClose={() => setShowLevelUp(false)}
            />

            {/* Sidebar — Expanded with labels */}
            <aside className="player-sidebar" style={{ width: '96px', borderRight: '1px solid rgba(255,255,255,0.05)', background: 'rgba(25,27,34,0.8)', backdropFilter: 'blur(16px)' }}>
                {/* Logo */}
                <div style={{
                    display: 'flex', alignItems: 'center', justifyContent: 'center',
                    marginBottom: '24px', gap: '4px'
                }}>
                    <span className="material-symbols-outlined" style={{
                        fontSize: '28px', color: '#62bbff',
                        fontVariationSettings: "'FILL' 1"
                    }}>hexagon</span>
                </div>

                <nav className="sidebar-nav" style={{ gap: '4px' }}>
                    {NAV_ITEMS.map(item => {
                        const isActive = currentPath === item.href
                        return (
                            <Link
                                key={item.href}
                                href={item.href}
                                className={isActive ? 'nav-orb-active' : ''}
                                onClick={() => play('click')}
                                style={{
                                    display: 'flex', flexDirection: 'column',
                                    alignItems: 'center', justifyContent: 'center',
                                    padding: '12px 8px', width: '100%',
                                    borderRadius: '0', gap: '4px',
                                    color: isActive ? '#62bbff' : 'var(--sao-text-dim)',
                                    textDecoration: 'none', transition: 'all 0.2s ease',
                                    borderLeft: isActive ? undefined : '3px solid transparent',
                                    background: isActive ? undefined : 'transparent',
                                }}
                                onMouseEnter={e => {
                                    if (!isActive) {
                                        e.currentTarget.style.background = 'rgba(255,255,255,0.05)'
                                        e.currentTarget.style.color = 'var(--sao-text)'
                                    }
                                }}
                                onMouseLeave={e => {
                                    if (!isActive) {
                                        e.currentTarget.style.background = 'transparent'
                                        e.currentTarget.style.color = 'var(--sao-text-dim)'
                                    }
                                }}
                            >
                                <span className="material-symbols-outlined" style={{
                                    fontSize: '22px',
                                    fontVariationSettings: isActive ? "'FILL' 1" : "'FILL' 0"
                                }}>{item.icon}</span>
                                <span className="label-caps" style={{ fontSize: '9px', letterSpacing: '0.08em' }}>
                                    {item.label.split(' ')[0].toUpperCase()}
                                </span>
                            </Link>
                        )
                    })}
                </nav>

                {/* Sound Toggle + Settings */}
                <div style={{ marginTop: 'auto', display: 'flex', flexDirection: 'column', alignItems: 'center', gap: '8px', paddingBottom: '12px' }}>
                    <button
                        onClick={() => { toggleSound(); play('click') }}
                        style={{
                            width: '100%', padding: '12px 8px',
                            borderRadius: '0', border: 'none',
                            background: 'transparent', color: 'var(--sao-text-dim)',
                            cursor: 'pointer', fontSize: '0.7rem', display: 'flex',
                            flexDirection: 'column', alignItems: 'center', gap: '4px',
                            transition: 'all 0.2s ease',
                        }}
                        title={soundEnabled ? 'Desativar sons' : 'Ativar sons'}
                    >
                        <span className="material-symbols-outlined" style={{ fontSize: '20px' }}>
                            {soundEnabled ? 'volume_up' : 'volume_off'}
                        </span>
                        <span className="label-caps" style={{ fontSize: '9px' }}>SOUND</span>
                    </button>
                </div>
            </aside>

            {/* Main */}
            <div className="player-main" style={{ marginLeft: '96px' }}>
                {/* Top HUD Bar — Premium */}
                <header className="hud-topbar" style={{
                    background: 'rgba(12,14,21,0.9)',
                    backdropFilter: 'blur(16px)',
                    borderBottom: '1px solid rgba(255,255,255,0.05)',
                    boxShadow: '0 4px 20px rgba(255,157,0,0.05)',
                }}>
                    <div className="hud-player-info">
                        {/* Avatar with glow ring */}
                        <div style={{ position: 'relative', flexShrink: 0 }}>
                            <div className="hud-avatar avatar-glow" style={{
                                width: '42px', height: '42px',
                                background: 'linear-gradient(135deg, var(--sao-orange), var(--sao-orange-light))',
                                position: 'relative', zIndex: 1,
                            }}>
                                {user?.player_name?.[0]?.toUpperCase() || user?.name?.[0]?.toUpperCase() || 'P'}
                            </div>
                            <div className="avatar-ring-outer" />
                        </div>
                        <div>
                            <div className="hud-player-name" style={{ fontFamily: "'Sora', sans-serif", fontWeight: 700 }}>
                                {user?.player_name || user?.name || 'Player'}
                            </div>
                            <div className="label-caps" style={{ fontSize: '10px', color: 'var(--sao-orange)', marginTop: '2px' }}>
                                LV. {xp?.current_level || user?.level || 1}
                                <span style={{ color: 'var(--sao-text-muted)', marginLeft: '6px' }}>
                                    // {stats?.month_label || 'SYSTEM ONLINE'}
                                </span>
                            </div>
                        </div>
                        <ColBalance col={user?.col} />
                    </div>

                    <div className="hud-bars" style={{ gap: '12px' }}>
                        {stats && (
                            <HpBar
                                percentage={stats.hp_percentage}
                                label="HP"
                                valueText={`R$ ${stats.balance?.toLocaleString('pt-BR', { minimumFractionDigits: 2 })} / R$ ${stats.monthly_income?.toLocaleString('pt-BR', { minimumFractionDigits: 2 })}`}
                            />
                        )}
                        {xp && (
                            <XpBar
                                progress={xp.progress}
                                currentLevel={xp.current_level}
                                xpRemaining={xp.xp_remaining}
                            />
                        )}
                    </div>
                </header>

                {/* Flash Messages */}
                {flash?.success && (
                    <div className="flash-message">
                        <span className="material-symbols-outlined" style={{ fontSize: '16px', marginRight: '6px', verticalAlign: 'middle' }}>check_circle</span>
                        {flash.success}
                    </div>
                )}
                {flash?.error && (
                    <div className="flash-message" style={{
                        borderColor: 'var(--sao-danger)',
                        color: 'var(--sao-danger)',
                    }}>
                        <span className="material-symbols-outlined" style={{ fontSize: '16px', marginRight: '6px', verticalAlign: 'middle' }}>error</span>
                        {flash.error}
                    </div>
                )}

                {/* Page Content */}
                {children}
            </div>

            <YuiCompanion user={user} />
        </div>
    )
}
