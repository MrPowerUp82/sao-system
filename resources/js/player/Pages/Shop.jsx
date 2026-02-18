import React, { useState } from 'react'
import { router } from '@inertiajs/react'
import PlayerLayout from '../Layouts/PlayerLayout'
import SaoPanel from '../Components/SaoPanel'
import { useSound } from '../Components/SoundManager'

const RARITY_INFO = {
    common: { color: '#8a8a9a', label: 'Common', glow: 'none' },
    uncommon: { color: '#4CAF50', label: 'Uncommon', glow: '0 0 10px rgba(76, 175, 80, 0.3)' },
    rare: { color: '#3498db', label: 'Rare', glow: '0 0 12px rgba(52, 152, 219, 0.4)' },
    epic: { color: '#9b59b6', label: 'Epic', glow: '0 0 15px rgba(155, 89, 182, 0.5)' },
    legendary: { color: '#FF9D00', label: 'Legendary', glow: '0 0 20px rgba(255, 157, 0, 0.5)' },
}

function ShopItemCard({ item, playerCol, onBuy }) {
    const rarity = RARITY_INFO[item.rarity] || RARITY_INFO.common
    const canAfford = playerCol >= item.price
    const isAvailable = item.available

    return (
        <SaoPanel
            style={{
                padding: 0,
                overflow: 'hidden',
                borderColor: rarity.color + '40',
                transition: 'all 0.3s ease',
                cursor: isAvailable && canAfford ? 'pointer' : 'default',
                opacity: isAvailable ? 1 : 0.5,
            }}
        >
            {/* Rarity strip */}
            <div style={{
                height: '3px',
                background: `linear-gradient(to right, transparent, ${rarity.color}, transparent)`,
                boxShadow: rarity.glow,
            }} />

            <div style={{ padding: '20px', display: 'flex', flexDirection: 'column', gap: '12px' }}>
                {/* Icon & Name */}
                <div style={{ display: 'flex', alignItems: 'center', gap: '12px' }}>
                    <div style={{
                        width: '48px',
                        height: '48px',
                        borderRadius: '12px',
                        background: 'var(--sao-dark-elevated)',
                        border: `1px solid ${rarity.color}40`,
                        display: 'flex',
                        alignItems: 'center',
                        justifyContent: 'center',
                        fontSize: '1.5rem',
                        boxShadow: rarity.glow,
                        flexShrink: 0,
                    }}>
                        {item.icon}
                    </div>
                    <div style={{ flex: 1, minWidth: 0 }}>
                        <div style={{
                            fontWeight: 700,
                            fontSize: '0.95rem',
                            color: 'var(--sao-text)',
                            whiteSpace: 'nowrap',
                            overflow: 'hidden',
                            textOverflow: 'ellipsis',
                        }}>
                            {item.name}
                        </div>
                        <div style={{ display: 'flex', gap: '8px', alignItems: 'center', marginTop: '2px' }}>
                            <span style={{
                                fontSize: '0.6rem',
                                fontWeight: 700,
                                textTransform: 'uppercase',
                                letterSpacing: '0.08em',
                                color: rarity.color,
                            }}>
                                {rarity.label}
                            </span>
                            <span style={{
                                fontSize: '0.6rem',
                                color: 'var(--sao-text-muted)',
                            }}>
                                {item.category_label}
                            </span>
                        </div>
                    </div>
                </div>

                {/* Description */}
                {item.description && (
                    <p style={{
                        fontSize: '0.75rem',
                        color: 'var(--sao-text-dim)',
                        lineHeight: '1.4',
                        margin: 0,
                    }}>
                        {item.description}
                    </p>
                )}

                {/* Stock */}
                {item.stock !== null && (
                    <div style={{
                        fontSize: '0.65rem',
                        color: item.stock > 0 ? 'var(--sao-text-muted)' : 'var(--sao-danger)',
                        fontWeight: 600,
                    }}>
                        📦 Estoque: {item.stock > 0 ? item.stock : 'Esgotado'}
                    </div>
                )}

                {/* Divider */}
                <div style={{
                    height: '1px',
                    background: 'linear-gradient(to right, transparent, var(--sao-border-subtle), transparent)',
                }} />

                {/* Price & Buy */}
                <div style={{ display: 'flex', alignItems: 'center', justifyContent: 'space-between' }}>
                    <div style={{
                        display: 'flex',
                        alignItems: 'center',
                        gap: '6px',
                        fontSize: '1.1rem',
                        fontWeight: 700,
                        color: canAfford ? '#FF9D00' : 'var(--sao-danger)',
                        fontVariantNumeric: 'tabular-nums',
                    }}>
                        <span>🪙</span>
                        {item.price.toLocaleString('pt-BR')}
                    </div>

                    <button
                        className={`sao-btn sm ${!canAfford || !isAvailable ? 'outline' : ''}`}
                        disabled={!canAfford || !isAvailable}
                        onClick={() => onBuy(item)}
                        style={{
                            opacity: canAfford && isAvailable ? 1 : 0.4,
                            cursor: canAfford && isAvailable ? 'pointer' : 'not-allowed',
                            pointerEvents: canAfford && isAvailable ? 'auto' : 'none',
                            ...(canAfford && isAvailable ? {} : {
                                background: 'transparent',
                                border: '2px solid var(--sao-text-muted)',
                                color: 'var(--sao-text-muted)',
                                boxShadow: 'none',
                            }),
                        }}
                    >
                        {!isAvailable ? 'ESGOTADO' : !canAfford ? 'COL INSUFICIENTE' : 'COMPRAR'}
                    </button>
                </div>
            </div>
        </SaoPanel>
    )
}

function PurchaseModal({ item, playerCol, onConfirm, onCancel }) {
    if (!item) return null
    const rarity = RARITY_INFO[item.rarity] || RARITY_INFO.common
    const remaining = playerCol - item.price

    return (
        <div className="modal-overlay" onClick={onCancel}>
            <div className="modal-content" onClick={e => e.stopPropagation()} style={{ maxWidth: '420px' }}>
                <h2 style={{ display: 'flex', alignItems: 'center', gap: '8px' }}>
                    <span style={{ color: 'var(--sao-orange)' }}>⟦</span>
                    Confirmar Compra
                    <span style={{ color: 'var(--sao-orange)' }}>⟧</span>
                </h2>

                {/* Item preview */}
                <div style={{
                    display: 'flex', alignItems: 'center', gap: '16px',
                    padding: '16px',
                    background: 'var(--sao-dark)',
                    borderRadius: '12px',
                    border: `1px solid ${rarity.color}30`,
                    marginBottom: '20px',
                }}>
                    <div style={{
                        width: '56px', height: '56px', borderRadius: '12px',
                        background: 'var(--sao-dark-elevated)',
                        border: `1px solid ${rarity.color}40`,
                        display: 'flex', alignItems: 'center', justifyContent: 'center',
                        fontSize: '1.8rem', boxShadow: rarity.glow, flexShrink: 0,
                    }}>
                        {item.icon}
                    </div>
                    <div>
                        <div style={{ fontWeight: 700, fontSize: '1rem' }}>{item.name}</div>
                        <div style={{ fontSize: '0.7rem', color: rarity.color, fontWeight: 600, textTransform: 'uppercase' }}>
                            {rarity.label} • {item.category_label}
                        </div>
                    </div>
                </div>

                {/* Price breakdown */}
                <div style={{
                    display: 'flex', flexDirection: 'column', gap: '10px',
                    marginBottom: '24px',
                }}>
                    <div style={{ display: 'flex', justifyContent: 'space-between', fontSize: '0.85rem' }}>
                        <span style={{ color: 'var(--sao-text-dim)' }}>Saldo atual</span>
                        <span style={{ fontWeight: 700, color: '#FF9D00' }}>🪙 {playerCol.toLocaleString('pt-BR')}</span>
                    </div>
                    <div style={{ display: 'flex', justifyContent: 'space-between', fontSize: '0.85rem' }}>
                        <span style={{ color: 'var(--sao-text-dim)' }}>Custo</span>
                        <span style={{ fontWeight: 700, color: 'var(--sao-danger)' }}>- 🪙 {item.price.toLocaleString('pt-BR')}</span>
                    </div>
                    <div style={{
                        height: '1px',
                        background: 'linear-gradient(to right, transparent, var(--sao-border), transparent)',
                    }} />
                    <div style={{ display: 'flex', justifyContent: 'space-between', fontSize: '0.95rem' }}>
                        <span style={{ color: 'var(--sao-text)', fontWeight: 600 }}>Saldo restante</span>
                        <span style={{ fontWeight: 700, color: remaining >= 0 ? '#4CAF50' : 'var(--sao-danger)' }}>
                            🪙 {remaining.toLocaleString('pt-BR')}
                        </span>
                    </div>
                </div>

                {/* Actions */}
                <div style={{ display: 'flex', gap: '12px', justifyContent: 'flex-end' }}>
                    <button className="sao-btn outline sm" onClick={onCancel}>
                        CANCELAR
                    </button>
                    <button className="sao-btn sm" onClick={onConfirm}>
                        ✓ CONFIRMAR
                    </button>
                </div>
            </div>
        </div>
    )
}

export default function Shop({ items, player_col, filters, category_options }) {
    const [activeCategory, setActiveCategory] = useState(filters?.category || null)
    const [confirmItem, setConfirmItem] = useState(null)
    const [purchasing, setPurchasing] = useState(false)
    const { play } = useSound()

    const handleFilter = (category) => {
        const newCategory = category === activeCategory ? null : category
        setActiveCategory(newCategory)
        router.get('/player/shop', newCategory ? { category: newCategory } : {}, {
            preserveState: true,
            preserveScroll: true,
        })
    }

    const handleBuy = (item) => {
        play('click')
        setConfirmItem(item)
    }

    const handleConfirmPurchase = () => {
        if (purchasing || !confirmItem) return
        setPurchasing(true)
        play('confirm')

        router.post(`/player/shop/${confirmItem.id}/purchase`, {}, {
            onSuccess: () => {
                setConfirmItem(null)
            },
            onFinish: () => setPurchasing(false),
        })
    }

    return (
        <PlayerLayout>
            <div className="page-content">
                {/* Header */}
                <div className="page-header">
                    <h1 className="sao-title page-title">
                        <span className="bracket">⟦</span>
                        SHOP
                        <span className="bracket">⟧</span>
                    </h1>
                    <div style={{
                        display: 'flex', alignItems: 'center', gap: '8px',
                        padding: '8px 18px',
                        background: 'rgba(255, 157, 0, 0.08)',
                        border: '1px solid rgba(255, 157, 0, 0.25)',
                        borderRadius: '12px',
                    }}>
                        <span style={{ fontSize: '1.2rem' }}>🪙</span>
                        <span style={{
                            fontSize: '1.2rem', fontWeight: 700,
                            color: '#FF9D00', fontVariantNumeric: 'tabular-nums',
                        }}>
                            {player_col.toLocaleString('pt-BR')}
                        </span>
                        <span style={{ fontSize: '0.7rem', color: '#FFB347', fontWeight: 500 }}>COL</span>
                    </div>
                </div>

                {/* Category Filters */}
                <div style={{
                    display: 'flex', gap: '8px', marginBottom: '24px', flexWrap: 'wrap',
                }}>
                    <button
                        className={`sao-btn sm ${!activeCategory ? '' : 'outline'}`}
                        onClick={() => handleFilter(null)}
                    >
                        TODOS
                    </button>
                    {Object.entries(category_options).map(([key, label]) => (
                        <button
                            key={key}
                            className={`sao-btn sm ${activeCategory === key ? '' : 'outline'}`}
                            onClick={() => handleFilter(key)}
                        >
                            {label}
                        </button>
                    ))}
                </div>

                {/* Items Grid */}
                {items.length === 0 ? (
                    <SaoPanel style={{ padding: '48px', textAlign: 'center' }}>
                        <div style={{ fontSize: '2rem', marginBottom: '12px' }}>🏪</div>
                        <div style={{ color: 'var(--sao-text-dim)', fontSize: '0.9rem' }}>
                            Nenhum item disponível nesta categoria.
                        </div>
                    </SaoPanel>
                ) : (
                    <div style={{
                        display: 'grid',
                        gridTemplateColumns: 'repeat(auto-fill, minmax(300px, 1fr))',
                        gap: '16px',
                    }}>
                        {items.map(item => (
                            <ShopItemCard
                                key={item.id}
                                item={item}
                                playerCol={player_col}
                                onBuy={handleBuy}
                            />
                        ))}
                    </div>
                )}
            </div>

            {/* Purchase Modal */}
            <PurchaseModal
                item={confirmItem}
                playerCol={player_col}
                onConfirm={handleConfirmPurchase}
                onCancel={() => { setConfirmItem(null); play('click') }}
            />
        </PlayerLayout>
    )
}
