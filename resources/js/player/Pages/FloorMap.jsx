import React, { useState } from 'react'
import { router, usePage } from '@inertiajs/react'
import PlayerLayout from '../Layouts/PlayerLayout'
import SaoPanel from '../Components/SaoPanel'

const FLOOR_ICONS = ['🏰', '⚔️', '🐉', '🏆', '💎', '🌟', '🗡️', '🛡️', '🔥', '👑']

export default function FloorMap({ floors }) {
    const { auth } = usePage().props
    const xp = auth?.user ? { current_level: auth.user.level, xp_remaining: 0, progress: 0 } : null

    const [showForm, setShowForm] = useState(false)
    const [form, setForm] = useState({ name: '', target_amount: '', icon: '🏰' })
    const [editingId, setEditingId] = useState(null)
    const [editAmount, setEditAmount] = useState('')

    const formatMoney = (val) => (val || 0).toLocaleString('pt-BR', { style: 'currency', currency: 'BRL' })

    const handleSubmit = (e) => {
        e.preventDefault()
        router.post('/player/floor', form, {
            onSuccess: () => {
                setShowForm(false)
                setForm({ name: '', target_amount: '', icon: '🏰' })
            },
        })
    }

    const handleUpdateAmount = (id) => {
        router.put(`/player/floor/${id}`, { current_amount: editAmount }, {
            onSuccess: () => {
                setEditingId(null)
                setEditAmount('')
            },
        })
    }

    const handleDelete = (id) => {
        if (confirm('Remover este andar?')) {
            router.delete(`/player/floor/${id}`)
        }
    }

    return (
        <PlayerLayout xp={xp}>
            <div className="page-content">
                <div className="page-header">
                    <h1 className="sao-title page-title">
                        <span className="bracket">「</span>AINCRAD FLOOR MAP<span className="bracket">」</span>
                    </h1>
                    <button className="sao-btn" onClick={() => setShowForm(!showForm)}>
                        {showForm ? '✕ CANCEL' : '⊕ NEW FLOOR'}
                    </button>
                </div>

                {/* New Floor Form */}
                {showForm && (
                    <SaoPanel style={{ padding: '20px', marginBottom: '16px' }}>
                        <h3 className="sao-title" style={{ fontSize: '0.9rem', marginBottom: '16px' }}>
                            <span className="bracket">「</span>UNLOCK NEW FLOOR<span className="bracket">」</span>
                        </h3>
                        <form onSubmit={handleSubmit}>
                            <div className="form-row" style={{ marginBottom: '12px' }}>
                                <div className="form-group" style={{ margin: 0 }}>
                                    <label>Floor Name</label>
                                    <input
                                        type="text"
                                        value={form.name}
                                        onChange={e => setForm(prev => ({ ...prev, name: e.target.value }))}
                                        placeholder="Ex: Viagem Japão, Carro novo..."
                                        required
                                    />
                                </div>
                                <div className="form-group" style={{ margin: 0 }}>
                                    <label>Target (Col)</label>
                                    <input
                                        type="number"
                                        step="0.01"
                                        value={form.target_amount}
                                        onChange={e => setForm(prev => ({ ...prev, target_amount: e.target.value }))}
                                        placeholder="R$ 0,00"
                                        required
                                    />
                                </div>
                            </div>
                            <div className="form-group">
                                <label>Icon</label>
                                <div style={{ display: 'flex', gap: '6px', flexWrap: 'wrap' }}>
                                    {FLOOR_ICONS.map(icon => (
                                        <button
                                            key={icon}
                                            type="button"
                                            onClick={() => setForm(prev => ({ ...prev, icon }))}
                                            style={{
                                                width: '36px', height: '36px', fontSize: '1.2rem',
                                                background: form.icon === icon ? 'rgba(255, 157, 0, 0.2)' : 'var(--sao-dark)',
                                                border: form.icon === icon ? '2px solid var(--sao-orange)' : '1px solid var(--sao-border-subtle)',
                                                borderRadius: '8px', cursor: 'pointer',
                                                display: 'flex', alignItems: 'center', justifyContent: 'center',
                                            }}
                                        >
                                            {icon}
                                        </button>
                                    ))}
                                </div>
                            </div>
                            <button type="submit" className="sao-btn" style={{ marginTop: '8px' }}>⊕ UNLOCK FLOOR</button>
                        </form>
                    </SaoPanel>
                )}

                {/* Floor List */}
                <div style={{ display: 'flex', flexDirection: 'column', gap: '12px' }}>
                    {/* Vertical connection line */}
                    {floors && floors.length > 0 ? (
                        floors.map((floor, idx) => (
                            <div key={floor.id} style={{ position: 'relative' }}>
                                {/* Connector */}
                                {idx < floors.length - 1 && (
                                    <div style={{
                                        position: 'absolute', left: '35px', bottom: '-12px',
                                        width: '2px', height: '12px',
                                        background: 'linear-gradient(to bottom, var(--sao-orange), transparent)',
                                        zIndex: 1,
                                    }} />
                                )}

                                <SaoPanel className={floor.status === 'active' ? 'pulse' : ''}>
                                    <div className={`floor-card ${floor.status}`} style={{ display: 'flex', gap: '16px', alignItems: 'center' }}>
                                        {/* Boss/Floor Avatar */}
                                        <div style={{
                                            width: '56px', height: '56px', borderRadius: '12px',
                                            background: 'rgba(255,255,255,0.03)',
                                            border: `2px solid ${floor.status === 'cleared' ? 'var(--sao-success)' : 'var(--sao-danger)'}`,
                                            display: 'flex', alignItems: 'center', justifyContent: 'center',
                                            fontSize: '2rem', flexShrink: 0
                                        }}>
                                            {floor.status === 'cleared' ? '🏆' : (
                                                {
                                                    1: '👹',
                                                    22: '🧙‍♀️',
                                                    48: '🐉',
                                                    74: '👿',
                                                    100: '👑',
                                                }[floor.floor_number] || ['👾', '🕷️', '🦁', '🦅', '🦍', '🦂', '👹'][floor.floor_number % 7]
                                            )}
                                        </div>

                                        <div className="floor-info" style={{ flex: 1 }}>
                                            <div className="floor-name" style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'baseline' }}>
                                                <span style={{ fontSize: '1rem', fontWeight: 700 }}>
                                                    Floor {floor.floor_number}: {floor.name}
                                                </span>
                                                {floor.status === 'cleared' ? (
                                                    <span style={{ fontSize: '0.65rem', fontWeight: 700, color: 'var(--sao-success)', background: 'rgba(74,225,131,0.1)', padding: '2px 8px', borderRadius: '4px', border: '1px solid rgba(74,225,131,0.3)', letterSpacing: '0.05em' }}>
                                                        BOSS DEFEATED
                                                    </span>
                                                ) : (
                                                    <span style={{ fontSize: '0.65rem', fontWeight: 700, color: '#ff4757', background: 'rgba(255,71,87,0.1)', padding: '2px 8px', borderRadius: '4px', border: '1px solid rgba(255,71,87,0.3)', letterSpacing: '0.05em', animation: 'sao-blink 2s infinite' }}>
                                                        BOSS ACTIVE
                                                    </span>
                                                )}
                                            </div>
                                            
                                            <div style={{ fontSize: '0.75rem', color: 'var(--sao-text-dim)', marginTop: '2px' }}>
                                                {floor.status === 'cleared' ? 'O guardião deste andar foi derrotado com sucesso!' : (
                                                    `Boss: ${
                                                        {
                                                            1: 'Illfang the Kobold Lord',
                                                            22: 'The Witch of the Lake',
                                                            48: 'The Wyrm of the Snow',
                                                            74: 'The Gleam Eyes',
                                                            100: 'An Incarnation of the Radius',
                                                        }[floor.floor_number] || `Floor ${floor.floor_number} Guardian`
                                                    }`
                                                )}
                                            </div>

                                            <div style={{ display: 'flex', justifyContent: 'space-between', fontSize: '0.7rem', color: 'var(--sao-text-muted)', fontFamily: "'JetBrains Mono', monospace", marginTop: '8px' }}>
                                                <span>{floor.status === 'cleared' ? `BOSS HP: 0 / ${formatMoney(floor.target_amount)}` : `BOSS HP: ${formatMoney(Math.max(0, floor.target_amount - floor.current_amount))} / ${formatMoney(floor.target_amount)}`}</span>
                                                <span>{floor.status === 'cleared' ? '0%' : `${100 - floor.progress}%`}</span>
                                            </div>

                                            <div className="progress-track" style={{ height: '8px', background: 'rgba(255,255,255,0.05)', borderRadius: '4px', overflow: 'hidden', marginTop: '4px' }}>
                                                <div className="progress-fill-hp" style={{
                                                    width: `${floor.status === 'cleared' ? 0 : 100 - floor.progress}%`,
                                                    background: 'linear-gradient(90deg, #ff4757, #ff6b6b)',
                                                    boxShadow: '0 0 10px rgba(255, 71, 87, 0.4)',
                                                    height: '100%',
                                                }}>
                                                    <div className="progress-glint" />
                                                </div>
                                            </div>
                                        </div>
                                        <div style={{ display: 'flex', flexDirection: 'column', gap: '4px' }}>
                                            {floor.status !== 'cleared' && (
                                                editingId === floor.id ? (
                                                    <div style={{ display: 'flex', gap: '4px' }}>
                                                        <input
                                                            type="number"
                                                            step="0.01"
                                                            value={editAmount}
                                                            onChange={e => setEditAmount(e.target.value)}
                                                            placeholder="Valor"
                                                            style={{
                                                                width: '100px', padding: '4px 8px',
                                                                background: 'var(--sao-dark)', border: '1px solid var(--sao-border-subtle)',
                                                                borderRadius: '6px', color: 'var(--sao-text)',
                                                                fontSize: '0.8rem', outline: 'none',
                                                            }}
                                                        />
                                                        <button className="sao-btn sm" onClick={() => handleUpdateAmount(floor.id)}>✓</button>
                                                        <button className="sao-btn sm outline" onClick={() => setEditingId(null)}>✕</button>
                                                    </div>
                                                ) : (
                                                    <button className="sao-btn sm outline" onClick={() => { setEditingId(floor.id); setEditAmount(floor.current_amount) }}>
                                                        Update
                                                    </button>
                                                )
                                            )}
                                            <button
                                                onClick={() => handleDelete(floor.id)}
                                                style={{
                                                    background: 'none', border: 'none',
                                                    color: 'var(--sao-text-muted)', cursor: 'pointer',
                                                    fontSize: '0.7rem',
                                                }}
                                            >
                                                🗑 Remove
                                            </button>
                                        </div>
                                    </div>
                                </SaoPanel>
                            </div>
                        ))
                    ) : (
                        <SaoPanel>
                            <div style={{ padding: '64px 16px', textAlign: 'center', color: 'var(--sao-text-muted)' }}>
                                <p style={{ fontSize: '3rem', marginBottom: '16px' }}>🗺️</p>
                                <p style={{ fontSize: '1.1rem', fontWeight: 600, color: 'var(--sao-text)', marginBottom: '8px' }}>
                                    Aincrad está vazio
                                </p>
                                <p style={{ fontSize: '0.8rem', marginBottom: '16px' }}>
                                    Crie seu primeiro andar definindo uma meta financeira.
                                </p>
                                <button className="sao-btn" onClick={() => setShowForm(true)}>
                                    🏰 UNLOCK FIRST FLOOR
                                </button>
                            </div>
                        </SaoPanel>
                    )}
                </div>
            </div>
        </PlayerLayout>
    )
}
