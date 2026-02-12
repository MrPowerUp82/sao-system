import React, { useState, useEffect, useRef } from 'react'
import { Link, usePage } from '@inertiajs/react'
import HpBar from '../Components/HpBar'
import XpBar from '../Components/XpBar'
import LevelUpOverlay from '../Components/LevelUpOverlay'
import { useSound } from '../Components/SoundManager'

const NAV_ITEMS = [
    { icon: '🏠', label: 'Dashboard', href: '/player', name: 'player.dashboard' },
    { icon: '⚔️', label: 'Trade Log', href: '/player/trade-log', name: 'player.trade-log' },
    { icon: '🗺️', label: 'Floor Map', href: '/player/floor-map', name: 'player.floor-map' },
    { icon: '🎒', label: 'Inventory', href: '/player/inventory', name: 'player.inventory' },
    { icon: '🏰', label: 'Guild', href: '/player/guild', name: 'player.guild' },
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
        <div className="player-layout">
            {/* Level Up Overlay */}
            <LevelUpOverlay
                show={showLevelUp}
                level={levelUpLevel}
                onClose={() => setShowLevelUp(false)}
            />

            {/* Sidebar */}
            <aside className="player-sidebar">
                <div className="sidebar-logo">SAO</div>
                <nav className="sidebar-nav">
                    {NAV_ITEMS.map(item => (
                        <Link
                            key={item.href}
                            href={item.href}
                            className={currentPath === item.href ? 'active' : ''}
                            onClick={() => play('click')}
                        >
                            <span>{item.icon}</span>
                            <span className="tooltip">{item.label}</span>
                        </Link>
                    ))}
                </nav>

                {/* Sound Toggle */}
                <button
                    onClick={() => { toggleSound(); play('click') }}
                    style={{
                        width: '44px', height: '44px', borderRadius: '12px',
                        border: '1px solid var(--sao-border-subtle)',
                        background: 'transparent', color: 'var(--sao-text-dim)',
                        cursor: 'pointer', fontSize: '1.1rem',
                        display: 'flex', alignItems: 'center', justifyContent: 'center',
                        marginTop: 'auto', marginBottom: '8px',
                        transition: 'all 0.2s ease',
                    }}
                    title={soundEnabled ? 'Desativar sons' : 'Ativar sons'}
                >
                    {soundEnabled ? '🔊' : '🔇'}
                </button>
            </aside>

            {/* Main */}
            <div className="player-main">
                {/* Top HUD Bar */}
                <header className="hud-topbar">
                    <div className="hud-player-info">
                        <div className="hud-avatar">
                            {user?.player_name?.[0]?.toUpperCase() || user?.name?.[0]?.toUpperCase() || 'P'}
                        </div>
                        <div>
                            <div className="hud-player-name">{user?.player_name || user?.name || 'Player'}</div>
                            <div className="hud-player-level">
                                LV. {xp?.current_level || user?.level || 1}
                                <span style={{ color: 'var(--sao-text-muted)', marginLeft: '6px' }}>
                                    // {stats?.month_label || 'SYSTEM ONLINE'}
                                </span>
                            </div>
                        </div>
                    </div>

                    <div className="hud-bars">
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
                        ⊕ {flash.success}
                    </div>
                )}

                {/* Page Content */}
                {children}
            </div>
        </div>
    )
}
