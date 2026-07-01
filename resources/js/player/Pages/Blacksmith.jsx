import React, { useState, useEffect } from 'react'
import { router } from '@inertiajs/react'
import PlayerLayout from '../Layouts/PlayerLayout'
import SaoPanel from '../Components/SaoPanel'
import { useSound } from '../Components/SoundManager'

const RARITY_STYLES = {
    common: { color: '#8a8a9a', label: 'Common', glow: 'none' },
    uncommon: { color: '#4CAF50', label: 'Uncommon', glow: '0 0 8px rgba(76, 175, 80, 0.3)' },
    rare: { color: '#3498db', label: 'Rare', glow: '0 0 12px rgba(52, 152, 219, 0.4)' },
    epic: { color: '#9b59b6', label: 'Epic', glow: '0 0 15px rgba(155, 89, 182, 0.5)' },
    legendary: { color: '#FF9D00', label: 'Legendary', glow: '0 0 20px rgba(255, 157, 0, 0.5)' },
}

const BASE_COSTS = {
    common: 30,
    uncommon: 60,
    rare: 120,
    epic: 250,
    legendary: 500,
}

const SUCCESS_RATES = {
    0: 100, // +0 -> +1
    1: 100, // +1 -> +2
    2: 100, // +2 -> +3
    3: 85,  // +3 -> +4
    4: 70,  // +4 -> +5
    5: 55,  // +5 -> +6
    6: 40,  // +6 -> +7
    7: 25,  // +7 -> +8
    8: 15,  // +8 -> +9
    9: 8,   // +9 -> +10
}

export default function Blacksmith({ items, col }) {
    const { play } = useSound()
    const [selectedItem, setSelectedItem] = useState(null)
    const [isRefining, setIsRefining] = useState(false)
    const [refineSuccess, setRefineSuccess] = useState(null) // 'success', 'fail', null
    const [outcomeMessage, setOutcomeMessage] = useState('')

    // Reset selected item details if items list updates
    useEffect(() => {
        if (selectedItem) {
            const updated = items.find(i => i.id === selectedItem.id)
            if (updated) setSelectedItem(updated)
        }
    }, [items])

    const handleSelect = (item) => {
        if (isRefining) return
        play('click')
        setSelectedItem(item)
        setRefineSuccess(null)
        setOutcomeMessage('')
    }

    const startRefining = () => {
        if (!selectedItem || isRefining) return
        if (selectedItem.refinement_level >= 10) return

        const cost = (BASE_COSTS[selectedItem.rarity] || 30) * (selectedItem.refinement_level + 1)
        if (col < cost) {
            play('damage')
            alert('Você não tem Col suficiente para realizar este refino!')
            return
        }

        setIsRefining(true)
        setRefineSuccess(null)
        setOutcomeMessage('')

        // Hammering Sound Loop
        let hits = 0
        const interval = setInterval(() => {
            if (hits < 3) {
                play('confirm')
                hits++
            } else {
                clearInterval(interval)
            }
        }, 300)

        // Perform Server Request after animation
        setTimeout(() => {
            router.post(`/player/blacksmith/${selectedItem.id}/refine`, {}, {
                preserveState: true,
                preserveScroll: true,
                onSuccess: (page) => {
                    const flash = page.props.flash || {}
                    setIsRefining(false)
                    if (flash.success) {
                        play('loot')
                        setRefineSuccess('success')
                        setOutcomeMessage(flash.success)
                    } else if (flash.warning) {
                        play('damage')
                        setRefineSuccess('fail')
                        setOutcomeMessage(flash.warning)
                    } else if (flash.error) {
                        play('damage')
                        alert(flash.error)
                    }
                },
                onError: (err) => {
                    setIsRefining(false)
                    play('damage')
                }
            })
        }, 1500)
    }

    const rarity = selectedItem ? (RARITY_STYLES[selectedItem.rarity] || RARITY_STYLES.common) : null
    const baseCost = selectedItem ? (BASE_COSTS[selectedItem.rarity] || 30) : 0
    const refineCost = selectedItem ? baseCost * (selectedItem.refinement_level + 1) : 0
    const successRate = selectedItem ? (SUCCESS_RATES[selectedItem.refinement_level] ?? 0) : 0

    return (
        <PlayerLayout>
            <div className="page-content" style={{ display: 'grid', gridTemplateColumns: '1fr', gap: '20px' }}>
                
                {/* Header */}
                <div style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'center' }}>
                    <div>
                        <h1 className="sao-title" style={{ margin: 0, textShadow: '0 0 15px rgba(255, 157, 0, 0.4)' }}>
                            <span className="bracket">「</span>LISBETH'S WORKSHOP<span className="bracket">」</span>
                        </h1>
                        <p style={{ margin: '4px 0 0 0', fontSize: '0.75rem', color: 'var(--sao-text-dim)', textTransform: 'uppercase', fontFamily: "'JetBrains Mono', monospace" }}>
                            Refine seu equipamento financeiro para ganhar bônus de XP e redução de dano
                        </p>
                    </div>

                    {/* Col Balance Widget */}
                    <div style={{
                        background: 'var(--sao-glass)',
                        border: '1px solid var(--sao-border-subtle)',
                        borderRadius: '8px',
                        padding: '10px 16px',
                        display: 'flex',
                        alignItems: 'center',
                        gap: '8px',
                        boxShadow: '0 0 10px rgba(0, 209, 255, 0.1)'
                    }}>
                        <span style={{ fontSize: '1.2rem' }}>🪙</span>
                        <div style={{ display: 'flex', flexDirection: 'column' }}>
                            <span style={{ fontSize: '9px', color: 'var(--sao-text-dim)', fontFamily: "'JetBrains Mono', monospace", textTransform: 'uppercase' }}>SEU SALDO</span>
                            <span style={{ fontSize: '1.1rem', fontWeight: 700, color: '#ff9d00', fontFamily: "'JetBrains Mono', monospace" }}>
                                {col.toLocaleString('pt-BR')} <span style={{ fontSize: '0.7rem', fontWeight: 600 }}>Col</span>
                            </span>
                        </div>
                    </div>
                </div>

                <div style={{
                    display: 'grid',
                    gridTemplateColumns: 'repeat(auto-fit, minmax(320px, 1fr))',
                    gap: '20px',
                    alignItems: 'start'
                }}>
                    
                    {/* ── COLUNA 1: SELECIONAR ITEM ── */}
                    <div style={{ display: 'flex', flexDirection: 'column', gap: '12px' }}>
                        <h3 style={{
                            margin: 0, fontSize: '0.85rem', fontWeight: 700, color: 'var(--sao-orange)',
                            letterSpacing: '0.05em', textTransform: 'uppercase', fontFamily: "'Sora', sans-serif"
                        }}>
                            Selecione uma Arma ou Armadura
                        </h3>

                        <div style={{
                            maxHeight: '480px', overflowY: 'auto', display: 'flex', flexDirection: 'column', gap: '10px',
                            paddingRight: '4px'
                        }}>
                            {items && items.length > 0 ? (
                                items.map(item => {
                                    const itemRarity = RARITY_STYLES[item.rarity] || RARITY_STYLES.common
                                    const isSelected = selectedItem?.id === item.id

                                    return (
                                        <div 
                                            key={item.id}
                                            onClick={() => handleSelect(item)}
                                            style={{
                                                background: isSelected ? 'rgba(255, 157, 0, 0.08)' : 'var(--sao-glass)',
                                                border: `1px solid ${isSelected ? 'var(--sao-orange)' : `${itemRarity.color}35`}`,
                                                borderRadius: '10px',
                                                padding: '12px',
                                                cursor: 'pointer',
                                                transition: 'all 0.2s ease',
                                                display: 'flex',
                                                alignItems: 'center',
                                                gap: '12px',
                                                boxShadow: isSelected ? '0 0 12px rgba(255, 157, 0, 0.2)' : 'none',
                                                opacity: isRefining ? 0.5 : 1
                                            }}
                                        >
                                            {/* Item Icon/Image */}
                                            <div style={{
                                                width: '42px', height: '42px', borderRadius: '8px',
                                                overflow: 'hidden', background: 'var(--sao-dark)',
                                                border: `1px solid ${itemRarity.color}50`, display: 'flex',
                                                alignItems: 'center', justifyContent: 'center', flexShrink: 0
                                            }}>
                                                {item.image_url ? (
                                                    <img src={item.image_url} alt={item.name} style={{ width: '100%', height: '100%', objectFit: 'cover' }} />
                                                ) : (
                                                    <span style={{ fontSize: '1.5rem' }}>{item.icon || '📦'}</span>
                                                )}
                                            </div>

                                            {/* Name & Details */}
                                            <div style={{ flex: 1, minWidth: 0 }}>
                                                <div style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'center' }}>
                                                    <span style={{ fontWeight: 700, color: itemRarity.color, fontSize: '0.9rem', overflow: 'hidden', textOverflow: 'ellipsis', whiteSpace: 'nowrap' }}>
                                                        {item.name}
                                                    </span>
                                                    {item.refinement_level > 0 && (
                                                        <span style={{
                                                            fontSize: '10px', fontWeight: 800, color: 'var(--sao-orange)',
                                                            fontFamily: "'JetBrains Mono', monospace"
                                                        }}>
                                                            +{item.refinement_level}
                                                        </span>
                                                    )}
                                                </div>
                                                <div style={{ display: 'flex', justifyContent: 'space-between', marginTop: '4px', fontSize: '0.7rem', color: 'var(--sao-text-dim)' }}>
                                                    <span>{item.slot_label}</span>
                                                    <span>R$ {item.value.toLocaleString('pt-BR')}</span>
                                                </div>
                                            </div>
                                        </div>
                                    )
                                })
                            ) : (
                                <SaoPanel>
                                    <div style={{ padding: '30px', textAlign: 'center', color: 'var(--sao-text-muted)' }}>
                                        <span style={{ fontSize: '2rem', display: 'block', marginBottom: '8px' }}>⚔️</span>
                                        Nenhuma arma ou armadura no inventário para refinar. compre itens na Loja primeiro!
                                    </div>
                                </SaoPanel>
                            )}
                        </div>
                    </div>

                    {/* ── COLUNA 2: OFICINA / ANVIL DE REFINE ── */}
                    <div>
                        {selectedItem ? (
                            <div style={{ display: 'flex', flexDirection: 'column', gap: '16px' }}>
                                
                                {/* Anvil Panel */}
                                <div style={{
                                    background: 'var(--sao-glass)',
                                    border: '1px solid var(--sao-border-subtle)',
                                    borderRadius: '12px',
                                    padding: '24px',
                                    position: 'relative',
                                    overflow: 'hidden',
                                    boxShadow: '0 0 20px rgba(0, 0, 0, 0.4)'
                                }}>
                                    
                                    {/* Upgrade visual flow */}
                                    <div style={{
                                        display: 'flex',
                                        justifyContent: 'space-around',
                                        alignItems: 'center',
                                        marginBottom: '24px',
                                        position: 'relative',
                                        padding: '10px 0'
                                    }}>
                                        {/* Current State */}
                                        <div style={{ display: 'flex', flexDirection: 'column', alignItems: 'center', gap: '8px' }}>
                                            <div style={{
                                                width: '64px', height: '64px', borderRadius: '12px',
                                                background: 'var(--sao-dark)', border: `2px solid ${rarity.color}`,
                                                display: 'flex', alignItems: 'center', justifyContent: 'center',
                                                overflow: 'hidden', boxShadow: rarity.glow
                                            }}>
                                                {selectedItem.image_url ? (
                                                    <img src={selectedItem.image_url} alt={selectedItem.name} style={{ width: '100%', height: '100%', objectFit: 'cover' }} />
                                                ) : (
                                                    <span style={{ fontSize: '2rem' }}>{selectedItem.icon}</span>
                                                )}
                                            </div>
                                            <span style={{ fontSize: '0.75rem', fontWeight: 700, color: rarity.color, textAlign: 'center' }}>
                                                {selectedItem.name} <br/> 
                                                <span style={{ fontFamily: "'JetBrains Mono', monospace", color: 'var(--sao-orange)' }}>
                                                    +{selectedItem.refinement_level}
                                                </span>
                                            </span>
                                        </div>

                                        {/* Refine Transition Animation */}
                                        <div style={{ display: 'flex', flexDirection: 'column', alignItems: 'center', position: 'relative' }}>
                                            {isRefining ? (
                                                <div style={{ display: 'flex', flexDirection: 'column', alignItems: 'center', gap: '4px' }}>
                                                    {/* Hammer Striking Icon Animation */}
                                                    <span className="material-symbols-outlined" style={{
                                                        fontSize: '36px',
                                                        color: 'var(--sao-orange)',
                                                        animation: 'sao-hammer-hit 0.3s infinite ease-in-out',
                                                        textShadow: '0 0 10px rgba(255, 157, 0, 0.6)'
                                                    }}>
                                                        construction
                                                    </span>
                                                    <span style={{ fontSize: '8px', fontWeight: 700, letterSpacing: '0.1em', color: 'var(--sao-orange)', fontFamily: "'JetBrains Mono', monospace" }}>REFINING...</span>
                                                </div>
                                            ) : (
                                                <span className="material-symbols-outlined" style={{ fontSize: '32px', color: 'var(--sao-text-dim)' }}>
                                                    double_arrow
                                                </span>
                                            )}
                                        </div>

                                        {/* Next State */}
                                        <div style={{ display: 'flex', flexDirection: 'column', alignItems: 'center', gap: '8px', opacity: selectedItem.refinement_level >= 10 ? 0.3 : 1 }}>
                                            <div style={{
                                                width: '64px', height: '64px', borderRadius: '12px',
                                                background: 'var(--sao-dark)', border: `2px dashed ${selectedItem.refinement_level >= 10 ? 'var(--sao-border-subtle)' : 'var(--sao-orange)'}`,
                                                display: 'flex', alignItems: 'center', justifyContent: 'center',
                                                overflow: 'hidden', position: 'relative'
                                            }}>
                                                {selectedItem.image_url ? (
                                                    <img src={selectedItem.image_url} alt={selectedItem.name} style={{ width: '100%', height: '100%', objectFit: 'cover' }} />
                                                ) : (
                                                    <span style={{ fontSize: '2rem' }}>{selectedItem.icon}</span>
                                                )}
                                                {selectedItem.refinement_level < 10 && (
                                                    <div style={{ position: 'absolute', inset: 0, background: 'rgba(255, 157, 0, 0.1)', display: 'flex', alignItems: 'center', justifyContent: 'center' }}>
                                                        <span style={{ fontSize: '1.2rem', fontWeight: 800, color: 'var(--sao-orange)', textShadow: '0 0 8px rgba(255, 157, 0, 0.6)' }}>
                                                            +{selectedItem.refinement_level + 1}
                                                        </span>
                                                    </div>
                                                )}
                                            </div>
                                            <span style={{ fontSize: '0.75rem', fontWeight: 700, color: selectedItem.refinement_level >= 10 ? 'var(--sao-text-dim)' : 'var(--sao-orange)', textAlign: 'center' }}>
                                                {selectedItem.refinement_level >= 10 ? 'MAX LEVEL' : (
                                                    <>
                                                        {selectedItem.name} <br/>
                                                        <span style={{ fontFamily: "'JetBrains Mono', monospace" }}>
                                                            +{selectedItem.refinement_level + 1}
                                                        </span>
                                                    </>
                                                )}
                                            </span>
                                        </div>
                                    </div>

                                    {/* Buff details comparisons */}
                                    <div style={{
                                        background: 'rgba(0, 0, 0, 0.2)',
                                        border: '1px solid var(--sao-border-subtle)',
                                        borderRadius: '8px',
                                        padding: '12px',
                                        marginBottom: '20px',
                                        fontSize: '0.8rem',
                                        display: 'flex',
                                        flexDirection: 'column',
                                        gap: '6px'
                                    }}>
                                        <div style={{ fontWeight: 700, color: 'var(--sao-text)', fontSize: '0.75rem', textTransform: 'uppercase', letterSpacing: '0.05em', display: 'flex', alignItems: 'center', gap: '4px' }}>
                                            <span className="material-symbols-outlined" style={{ fontSize: '14px', color: 'var(--sao-orange)' }}>bolt</span>
                                            Benefícios do Aprimoramento
                                        </div>
                                        
                                        {selectedItem.slot === 'weapon' ? (
                                            <div style={{ color: 'var(--sao-text-dim)', display: 'flex', justifyContent: 'space-between' }}>
                                                <span>Multiplicador de XP obtido em Trades:</span>
                                                <span style={{ fontWeight: 600 }}>
                                                    <span style={{ color: rarity.color }}>+{selectedItem.refinement_level * 5}%</span>
                                                    {selectedItem.refinement_level < 10 && (
                                                        <span style={{ color: 'var(--sao-orange)' }}> ➔ +{(selectedItem.refinement_level + 1) * 5}%</span>
                                                    )}
                                                </span>
                                            </div>
                                        ) : (
                                            <div style={{ color: 'var(--sao-text-dim)', display: 'flex', justifyContent: 'space-between' }}>
                                                <span>Mitigação de "Dano" de Despesas:</span>
                                                <span style={{ fontWeight: 600 }}>
                                                    <span style={{ color: rarity.color }}>-{selectedItem.refinement_level * 3}%</span>
                                                    {selectedItem.refinement_level < 10 && (
                                                        <span style={{ color: 'var(--sao-orange)' }}> ➔ -{(selectedItem.refinement_level + 1) * 3}%</span>
                                                    )}
                                                </span>
                                            </div>
                                        )}
                                        
                                        <div style={{ color: 'var(--sao-text-dim)', fontSize: '0.7rem', fontStyle: 'italic', borderTop: '1px solid rgba(255,255,255,0.05)', paddingTop: '4px', marginTop: '4px' }}>
                                            * O efeito entra em ação automaticamente ao equipar o item no Inventário.
                                        </div>
                                    </div>

                                    {/* Cost & Success Rate */}
                                    {selectedItem.refinement_level < 10 ? (
                                        <div style={{
                                            display: 'grid',
                                            gridTemplateColumns: '1fr 1fr',
                                            gap: '12px',
                                            marginBottom: '20px',
                                            fontSize: '0.85rem'
                                        }}>
                                            <div style={{ background: 'var(--sao-dark)', border: '1px solid var(--sao-border-subtle)', borderRadius: '6px', padding: '10px', textAlign: 'center' }}>
                                                <div style={{ fontSize: '9px', color: 'var(--sao-text-dim)', textTransform: 'uppercase', fontFamily: "'JetBrains Mono', monospace" }}>CUSTO DE REFINE</div>
                                                <div style={{ fontSize: '1.1rem', fontWeight: 800, color: col >= refineCost ? '#ff9d00' : 'var(--sao-danger)', marginTop: '4px', fontFamily: "'JetBrains Mono', monospace" }}>
                                                    {refineCost} Col
                                                </div>
                                            </div>
                                            <div style={{ background: 'var(--sao-dark)', border: '1px solid var(--sao-border-subtle)', borderRadius: '6px', padding: '10px', textAlign: 'center' }}>
                                                <div style={{ fontSize: '9px', color: 'var(--sao-text-dim)', textTransform: 'uppercase', fontFamily: "'JetBrains Mono', monospace" }}>TAXA DE SUCESSO</div>
                                                <div style={{ fontSize: '1.1rem', fontWeight: 800, color: successRate >= 70 ? '#4ae183' : successRate >= 40 ? '#ff9d00' : '#ff4757', marginTop: '4px', fontFamily: "'JetBrains Mono', monospace" }}>
                                                    {successRate}%
                                                </div>
                                            </div>
                                        </div>
                                    ) : (
                                        <div style={{
                                            background: 'rgba(74,225,131,0.08)',
                                            border: '1px solid rgba(74,225,131,0.3)',
                                            borderRadius: '6px',
                                            padding: '12px',
                                            textAlign: 'center',
                                            color: '#4ae183',
                                            fontWeight: 700,
                                            fontSize: '0.85rem',
                                            marginBottom: '20px',
                                            letterSpacing: '0.05em'
                                        }}>
                                            ESTE ITEM ATINGIU O NÍVEL MÁXIMO DE REFINAMENTO (+10) ⚔️
                                        </div>
                                    )}

                                    {/* Action button */}
                                    {selectedItem.refinement_level < 10 && (
                                        <button
                                            onClick={startRefining}
                                            disabled={isRefining}
                                            className="sao-btn"
                                            style={{
                                                width: '100%',
                                                justifyContent: 'center',
                                                padding: '12px',
                                                fontSize: '0.9rem',
                                                background: isRefining ? 'var(--sao-dark)' : 'linear-gradient(90deg, #ff9d00, #ff4757)',
                                                border: 'none',
                                                boxShadow: isRefining ? 'none' : '0 0 15px rgba(255, 71, 87, 0.4)',
                                                cursor: isRefining ? 'not-allowed' : 'pointer',
                                                fontWeight: 800,
                                                letterSpacing: '0.05em'
                                            }}
                                        >
                                            {isRefining ? 'FORJANDO...' : 'INICIAR REFINAMENTO 🔨'}
                                        </button>
                                    )}
                                </div>

                                {/* Outcome overlay/alert */}
                                {refineSuccess && (
                                    <div style={{
                                        background: refineSuccess === 'success' ? 'rgba(74,225,131,0.1)' : 'rgba(255,71,87,0.1)',
                                        border: `1px solid ${refineSuccess === 'success' ? '#4ae183' : '#ff4757'}`,
                                        borderRadius: '10px',
                                        padding: '16px',
                                        textAlign: 'center',
                                        animation: 'sao-fade-in 0.3s ease',
                                        boxShadow: refineSuccess === 'success' ? '0 0 15px rgba(74, 225, 131, 0.15)' : 'none'
                                    }}>
                                        <h4 style={{
                                            margin: '0 0 6px 0',
                                            fontFamily: "'Sora', sans-serif",
                                            fontWeight: 900,
                                            fontSize: '1rem',
                                            color: refineSuccess === 'success' ? '#4ae183' : '#ff4757',
                                            letterSpacing: '0.08em',
                                            textTransform: 'uppercase'
                                        }}>
                                            {refineSuccess === 'success' ? '「 REFINE SUCCESS 」' : '「 REFINE FAILED 」'}
                                        </h4>
                                        <p style={{ margin: 0, fontSize: '0.8rem', color: 'var(--sao-text)' }}>
                                            {outcomeMessage}
                                        </p>
                                        {selectedItem.refinement_level >= 4 && refineSuccess === 'fail' && (
                                            <p style={{ margin: '4px 0 0 0', fontSize: '0.7rem', color: 'var(--sao-text-dim)', fontStyle: 'italic' }}>
                                                Nível reduzido para evitar quebra do item.
                                            </p>
                                        )}
                                    </div>
                                )}
                            </div>
                        ) : (
                            <SaoPanel>
                                <div style={{
                                    height: '340px', display: 'flex', flexDirection: 'column', alignItems: 'center',
                                    justifyContent: 'center', color: 'var(--sao-text-dim)', textAlign: 'center', padding: '20px'
                                }}>
                                    <span className="material-symbols-outlined" style={{ fontSize: '4rem', color: 'var(--sao-text-muted)', opacity: 0.5, marginBottom: '12px' }}>
                                        construction
                                    </span>
                                    <h4 style={{ margin: '0 0 8px 0', fontFamily: "'Sora', sans-serif", fontWeight: 700, fontSize: '1rem' }}>
                                        Oficina da Lisbeth
                                    </h4>
                                    <p style={{ margin: 0, fontSize: '0.8rem', maxWidth: '300px', lineHeight: '1.5' }}>
                                        Selecione um item do seu inventário à esquerda para colocá-lo na bigorna e iniciar o aprimoramento.
                                    </p>
                                </div>
                            </SaoPanel>
                        )}
                    </div>
                </div>
            </div>
            
            <style>{`
                @keyframes sao-hammer-hit {
                    0% { transform: translateY(0) rotate(0deg); }
                    30% { transform: translateY(-15px) rotate(-25deg); }
                    80% { transform: translateY(2px) rotate(10deg); }
                    100% { transform: translateY(0) rotate(0deg); }
                }
                @keyframes sao-fade-in {
                    from { opacity: 0; transform: translateY(8px); }
                    to { opacity: 1; transform: translateY(0); }
                }
            `}</style>
        </PlayerLayout>
    )
}
