import React, { useState } from 'react'
import PlayerLayout from '../Layouts/PlayerLayout'
import SaoPanel from '../Components/SaoPanel'
import { useSound } from '../Components/SoundManager'

const CATEGORIES = [
    { id: 'all', label: 'All Monsters', icon: 'auto_awesome' },
    { id: 'boss', label: 'Floor Bosses', icon: 'skull' },
    { id: 'master', label: 'Master Tier', icon: 'military_tech' },
    { id: 'elite', label: 'Elite Tier', icon: 'shield' },
    { id: 'intermediate', label: 'Intermediate', icon: 'swords' },
    { id: 'common', label: 'Common', icon: 'pest_control' },
]

export default function Bestiary({ monsters }) {
    const { play } = useSound()
    const [selectedCategory, setSelectedCategory] = useState('all')
    const [searchQuery, setSearchQuery] = useState('')
    const [selectedMonster, setSelectedMonster] = useState(null)

    // Filter logic
    const filteredMonsters = monsters.filter(monster => {
        const matchesCategory = selectedCategory === 'all' || monster.category === selectedCategory
        const matchesSearch = monster.name.toLowerCase().includes(searchQuery.toLowerCase()) ||
            (monster.description && monster.description.toLowerCase().includes(searchQuery.toLowerCase()))
        return matchesCategory && matchesSearch
    })

    const handleSelectCategory = (catId) => {
        play('click')
        setSelectedCategory(catId)
    }

    const handleOpenMonster = (monster) => {
        play('confirm')
        setSelectedMonster(monster)
    }

    const handleCloseMonster = () => {
        play('click')
        setSelectedMonster(null)
    }

    return (
        <PlayerLayout>
            <div className="page-content" style={{ padding: '24px 16px', maxWidth: '1200px', margin: '0 auto' }}>
                {/* Header */}
                <div className="page-header" style={{ marginBottom: '32px' }}>
                    <h1 className="sao-title page-title" style={{ margin: 0 }}>
                        <span className="bracket">「</span>SAO BESTIARY<span className="bracket">」</span>
                    </h1>
                    <p style={{ color: 'var(--sao-text-dim)', fontSize: '0.85rem', marginTop: '6px', fontFamily: "'JetBrains Mono', monospace" }}>
                        SYSTEM_DATABASE // MONSTER_INDEX // FLOOR_GUARDIANS
                    </p>
                </div>

                {/* Filters & Search */}
                <div style={{ display: 'flex', flexDirection: 'column', gap: '16px', marginBottom: '32px' }}>
                    {/* Search Bar */}
                    <div style={{ position: 'relative', width: '100%', maxWidth: '400px' }}>
                        <input
                            type="text"
                            placeholder="Procurar monstro ou descrição..."
                            value={searchQuery}
                            onChange={(e) => setSearchQuery(e.target.value)}
                            style={{
                                width: '100%',
                                padding: '12px 16px',
                                paddingLeft: '40px',
                                background: 'var(--sao-glass)',
                                border: '1px solid var(--sao-border-subtle)',
                                borderRadius: '8px',
                                color: 'var(--sao-text)',
                                fontSize: '0.9rem',
                                outline: 'none',
                                transition: 'all 0.3s ease',
                            }}
                            onFocus={(e) => e.target.style.borderColor = 'var(--sao-orange)'}
                            onBlur={(e) => e.target.style.borderColor = 'var(--sao-border-subtle)'}
                        />
                        <span className="material-symbols-outlined" style={{
                            position: 'absolute',
                            left: '12px',
                            top: '50%',
                            transform: 'translateY(-50%)',
                            color: 'var(--sao-text-muted)',
                            fontSize: '20px',
                            pointerEvents: 'none'
                        }}>search</span>
                    </div>

                    {/* Category Tabs */}
                    <div style={{ display: 'flex', gap: '8px', flexWrap: 'wrap' }}>
                        {CATEGORIES.map(cat => {
                            const isActive = selectedCategory === cat.id
                            return (
                                <button
                                    key={cat.id}
                                    onClick={() => handleSelectCategory(cat.id)}
                                    style={{
                                        display: 'flex',
                                        alignItems: 'center',
                                        gap: '6px',
                                        padding: '8px 16px',
                                        background: isActive ? 'rgba(255, 157, 0, 0.2)' : 'var(--sao-glass)',
                                        border: isActive ? '2px solid var(--sao-orange)' : '1px solid var(--sao-border-subtle)',
                                        borderRadius: '8px',
                                        color: isActive ? 'var(--sao-orange)' : 'var(--sao-text-dim)',
                                        fontSize: '0.8rem',
                                        fontWeight: 700,
                                        cursor: 'pointer',
                                        textTransform: 'uppercase',
                                        letterSpacing: '0.05em',
                                        fontFamily: "'JetBrains Mono', monospace",
                                        transition: 'all 0.2s ease',
                                    }}
                                >
                                    <span className="material-symbols-outlined" style={{ fontSize: '16px' }}>{cat.icon}</span>
                                    {cat.label}
                                </button>
                            )
                        })}
                    </div>
                </div>

                {/* Monster Grid */}
                {filteredMonsters.length > 0 ? (
                    <div style={{
                        display: 'grid',
                        gridTemplateColumns: 'repeat(auto-fill, minmax(280px, 1fr))',
                        gap: '20px',
                    }}>
                        {filteredMonsters.map(monster => {
                            const isBoss = monster.category === 'boss'
                            const glowColor = monster.category_color
                            return (
                                <SaoPanel
                                    key={monster.id}
                                    onClick={() => handleOpenMonster(monster)}
                                    style={{
                                        padding: 0,
                                        overflow: 'hidden',
                                        borderColor: glowColor + '40',
                                        cursor: 'pointer',
                                        transition: 'all 0.3s ease',
                                        boxShadow: isBoss ? `0 0 15px ${glowColor}25` : 'none',
                                    }}
                                    className="monster-card-panel"
                                >
                                    {/* Top Rarity Strip */}
                                    <div style={{
                                        height: '4px',
                                        background: `linear-gradient(90deg, transparent, ${glowColor}, transparent)`,
                                    }} />

                                    <div style={{ padding: '20px', display: 'flex', flexDirection: 'column', gap: '16px' }}>
                                        {/* Image Container */}
                                        <div style={{
                                            width: '100%',
                                            height: '160px',
                                            borderRadius: '8px',
                                            overflow: 'hidden',
                                            background: 'var(--sao-dark-elevated)',
                                            border: '1px solid rgba(255,255,255,0.05)',
                                            display: 'flex',
                                            alignItems: 'center',
                                            justifyContent: 'center',
                                            position: 'relative',
                                        }}>
                                            {monster.image_url ? (
                                                <img
                                                    src={monster.image_url}
                                                    alt={monster.name}
                                                    style={{ width: '100%', height: '100%', objectFit: 'cover' }}
                                                />
                                            ) : (
                                                <span style={{ fontSize: '3rem' }}>{monster.icon}</span>
                                            )}

                                            {/* Floor Badge */}
                                            <div style={{
                                                position: 'absolute',
                                                bottom: '8px',
                                                right: '8px',
                                                background: 'rgba(0,0,0,0.7)',
                                                border: '1px solid rgba(255,255,255,0.2)',
                                                borderRadius: '4px',
                                                padding: '2px 8px',
                                                fontSize: '10px',
                                                color: '#fff',
                                                fontFamily: "'JetBrains Mono', monospace",
                                                fontWeight: 700,
                                            }}>
                                                {monster.specific_floor ? `FL ${monster.specific_floor}` : `FL ${monster.floor_min}-${monster.floor_max}`}
                                            </div>
                                        </div>

                                        {/* Info */}
                                        <div>
                                            <h3 style={{
                                                margin: 0,
                                                fontFamily: "'Sora', sans-serif",
                                                fontSize: '1.1rem',
                                                fontWeight: 700,
                                                color: 'var(--sao-text)',
                                                display: 'flex',
                                                alignItems: 'center',
                                                justifyContent: 'space-between',
                                            }}>
                                                {monster.name}
                                                <span style={{ fontSize: '1.2rem' }}>{monster.icon}</span>
                                            </h3>
                                            <div style={{ display: 'flex', gap: '8px', marginTop: '6px' }}>
                                                <span style={{
                                                    fontSize: '9px',
                                                    fontWeight: 700,
                                                    color: glowColor,
                                                    background: `${glowColor}10`,
                                                    border: `1px solid ${glowColor}30`,
                                                    padding: '2px 8px',
                                                    borderRadius: '4px',
                                                    textTransform: 'uppercase',
                                                    fontFamily: "'JetBrains Mono', monospace",
                                                }}>
                                                    {monster.category_label}
                                                </span>
                                                <span style={{
                                                    fontSize: '9px',
                                                    fontWeight: 700,
                                                    color: '#4ae183',
                                                    background: 'rgba(74,225,131,0.1)',
                                                    border: '1px solid rgba(74,225,131,0.3)',
                                                    padding: '2px 8px',
                                                    borderRadius: '4px',
                                                    fontFamily: "'JetBrains Mono', monospace",
                                                }}>
                                                    +{monster.xp_reward} XP
                                                </span>
                                            </div>
                                        </div>

                                        {/* Short description */}
                                        {monster.description && (
                                            <p style={{
                                                margin: 0,
                                                fontSize: '0.75rem',
                                                color: 'var(--sao-text-dim)',
                                                lineHeight: '1.4',
                                                display: '-webkit-box',
                                                WebkitLineClamp: 2,
                                                WebkitBoxOrient: 'vertical',
                                                overflow: 'hidden',
                                                textOverflow: 'ellipsis',
                                            }}>
                                                {monster.description}
                                            </p>
                                        )}
                                    </div>
                                </SaoPanel>
                            )
                        })}
                    </div>
                ) : (
                    <SaoPanel>
                        <div style={{ padding: '64px 16px', textAlign: 'center', color: 'var(--sao-text-muted)' }}>
                            <p style={{ fontSize: '3rem', marginBottom: '16px' }}>👾</p>
                            <p style={{ fontSize: '1.1rem', fontWeight: 600, color: 'var(--sao-text)', marginBottom: '8px' }}>
                                Nenhum monstro detectado
                            </p>
                            <p style={{ fontSize: '0.8rem' }}>
                                Tente ajustar sua busca ou mudar a categoria selecionada.
                            </p>
                        </div>
                    </SaoPanel>
                )}

                {/* Monster Detail Overlay (NerveGear Style popup) */}
                {selectedMonster && (
                    <div style={{
                        position: 'fixed',
                        inset: 0,
                        background: 'rgba(12, 14, 21, 0.8)',
                        backdropFilter: 'blur(8px)',
                        display: 'flex',
                        alignItems: 'center',
                        justifyContent: 'center',
                        zIndex: 9999,
                        padding: '16px',
                    }} onClick={handleCloseMonster}>
                        <SaoPanel
                            style={{
                                width: '100%',
                                maxWidth: '500px',
                                padding: '24px',
                                background: 'var(--sao-glass-elevated)',
                                border: `2px solid ${selectedMonster.category_color}`,
                                boxShadow: `0 0 30px ${selectedMonster.category_color}30`,
                                position: 'relative',
                            }}
                            onClick={(e) => e.stopPropagation()}
                        >
                            {/* Close button */}
                            <button
                                onClick={handleCloseMonster}
                                style={{
                                    position: 'absolute',
                                    top: '16px',
                                    right: '16px',
                                    background: 'none',
                                    border: 'none',
                                    color: 'var(--sao-text-muted)',
                                    cursor: 'pointer',
                                    fontSize: '1.5rem',
                                    zIndex: 10,
                                }}
                            >
                                ✕
                            </button>

                            {/* Title */}
                            <h2 style={{
                                margin: 0,
                                fontFamily: "'Sora', sans-serif",
                                fontSize: '1.5rem',
                                fontWeight: 800,
                                color: 'var(--sao-text)',
                                textShadow: `0 0 10px ${selectedMonster.category_color}40`,
                                display: 'flex',
                                alignItems: 'center',
                                gap: '10px',
                                marginBottom: '20px',
                            }}>
                                <span className="bracket" style={{ color: selectedMonster.category_color }}>「</span>
                                {selectedMonster.name}
                                <span className="bracket" style={{ color: selectedMonster.category_color }}>」</span>
                            </h2>

                            {/* Full Art */}
                            <div style={{
                                width: '100%',
                                height: '240px',
                                borderRadius: '12px',
                                overflow: 'hidden',
                                background: 'var(--sao-dark)',
                                border: '1px solid rgba(255,255,255,0.08)',
                                marginBottom: '20px',
                                display: 'flex',
                                alignItems: 'center',
                                justifyContent: 'center',
                            }}>
                                {selectedMonster.image_url ? (
                                    <img
                                        src={selectedMonster.image_url}
                                        alt={selectedMonster.name}
                                        style={{ width: '100%', height: '100%', objectFit: 'contain' }}
                                    />
                                ) : (
                                    <span style={{ fontSize: '5rem' }}>{selectedMonster.icon}</span>
                                )}
                            </div>

                            {/* Stats sockets */}
                            <div style={{ display: 'grid', gridTemplateColumns: 'repeat(3, 1fr)', gap: '8px', marginBottom: '20px' }}>
                                <div style={{
                                    background: 'rgba(255,255,255,0.03)',
                                    border: '1px solid rgba(255,255,255,0.06)',
                                    borderRadius: '8px',
                                    padding: '10px',
                                    textAlign: 'center',
                                }}>
                                    <div style={{ fontSize: '10px', color: 'var(--sao-text-muted)', fontFamily: "'JetBrains Mono', monospace" }}>CATEGORIA</div>
                                    <div style={{ fontSize: '0.8rem', fontWeight: 700, color: selectedMonster.category_color, marginTop: '4px' }}>
                                        {selectedMonster.category_label}
                                    </div>
                                </div>
                                <div style={{
                                    background: 'rgba(255,255,255,0.03)',
                                    border: '1px solid rgba(255,255,255,0.06)',
                                    borderRadius: '8px',
                                    padding: '10px',
                                    textAlign: 'center',
                                }}>
                                    <div style={{ fontSize: '10px', color: 'var(--sao-text-muted)', fontFamily: "'JetBrains Mono', monospace" }}>LOCALIZAÇÃO</div>
                                    <div style={{ fontSize: '0.8rem', fontWeight: 700, color: 'var(--sao-text)', marginTop: '4px' }}>
                                        {selectedMonster.specific_floor ? `Andar ${selectedMonster.specific_floor}` : `Andares ${selectedMonster.floor_min}-${selectedMonster.floor_max}`}
                                    </div>
                                </div>
                                <div style={{
                                    background: 'rgba(255,255,255,0.03)',
                                    border: '1px solid rgba(255,255,255,0.06)',
                                    borderRadius: '8px',
                                    padding: '10px',
                                    textAlign: 'center',
                                }}>
                                    <div style={{ fontSize: '10px', color: 'var(--sao-text-muted)', fontFamily: "'JetBrains Mono', monospace" }}>RECOMPENSA</div>
                                    <div style={{ fontSize: '0.8rem', fontWeight: 700, color: '#4ae183', marginTop: '4px' }}>
                                        +{selectedMonster.xp_reward} XP
                                    </div>
                                </div>
                            </div>

                            {/* Description / Lore */}
                            <div>
                                <h4 style={{
                                    margin: 0,
                                    fontFamily: "'Sora', sans-serif",
                                    fontSize: '0.9rem',
                                    fontWeight: 700,
                                    marginBottom: '8px',
                                    textTransform: 'uppercase',
                                    letterSpacing: '0.05em',
                                    color: 'var(--sao-orange)',
                                }}>
                                    LORE // METÁFORA FINANCEIRA
                                </h4>
                                <p style={{
                                    margin: 0,
                                    fontSize: '0.85rem',
                                    color: 'var(--sao-text-dim)',
                                    lineHeight: '1.6',
                                    fontFamily: "'Hanken Grotesk', sans-serif",
                                }}>
                                    {selectedMonster.description}
                                </p>
                            </div>
                        </SaoPanel>
                    </div>
                )}
            </div>
        </PlayerLayout>
    )
}
