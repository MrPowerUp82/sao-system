import React, { useState } from 'react'
import { router } from '@inertiajs/react'
import PlayerLayout from '../Layouts/PlayerLayout'
import SaoPanel from '../Components/SaoPanel'
import { useSound } from '../Components/SoundManager'

const SLOT_INFO = {
    weapon: { icon: '⚔️', label: 'Weapon', desc: 'Cartão de Crédito' },
    armor: { icon: '🛡️', label: 'Armor', desc: 'Seguro' },
    accessory: { icon: '💍', label: 'Accessory', desc: 'Conta Bancária' },
    consumable: { icon: '🧪', label: 'Consumable', desc: 'Assinatura' },
    material: { icon: '💎', label: 'Material', desc: 'Investimento' },
}

const RARITY_STYLES = {
    common: { color: '#8a8a9a', label: 'Common', glow: 'none' },
    uncommon: { color: '#4CAF50', label: 'Uncommon', glow: '0 0 8px rgba(76, 175, 80, 0.3)' },
    rare: { color: '#3498db', label: 'Rare', glow: '0 0 12px rgba(52, 152, 219, 0.4)' },
    epic: { color: '#9b59b6', label: 'Epic', glow: '0 0 15px rgba(155, 89, 182, 0.5)' },
    legendary: { color: '#FF9D00', label: 'Legendary', glow: '0 0 20px rgba(255, 157, 0, 0.5)' },
}

function ItemCard({ item, onDelete, onToggleEquip }) {
    const rarity = RARITY_STYLES[item.rarity] || RARITY_STYLES.common
    const slot = SLOT_INFO[item.slot] || {}
    const { play } = useSound()

    return (
        <div style={{
            background: 'var(--sao-glass)',
            border: `1px solid ${rarity.color}40`,
            borderRadius: '12px',
            padding: '16px',
            position: 'relative',
            transition: 'all 0.3s ease',
            boxShadow: rarity.glow,
            opacity: item.equipped ? 1 : 0.6,
        }}>
            {/* Rarity indicator */}
            <div style={{
                position: 'absolute', top: '8px', right: '8px',
                fontSize: '0.65rem', fontWeight: 700, letterSpacing: '0.1em',
                color: rarity.color, textTransform: 'uppercase',
                fontFamily: 'Rajdhani, sans-serif',
            }}>
                {rarity.label}
            </div>

            {/* Icon + Name */}
            <div style={{ display: 'flex', alignItems: 'center', gap: '10px', marginBottom: '10px' }}>
                <span style={{ fontSize: '1.8rem' }}>{item.icon}</span>
                <div>
                    <div style={{
                        fontWeight: 600, color: rarity.color,
                        fontFamily: 'Rajdhani, sans-serif', fontSize: '1.05rem',
                    }}>
                        {item.name}
                    </div>
                    <div style={{ fontSize: '0.7rem', color: 'var(--sao-text-dim)' }}>
                        {slot.icon} {slot.label} — {slot.desc}
                    </div>
                </div>
            </div>

            {/* Value */}
            <div style={{
                fontSize: '1.1rem', fontWeight: 700, color: '#76FF03',
                fontFamily: 'Rajdhani, sans-serif', marginBottom: '6px',
            }}>
                R$ {item.value.toLocaleString('pt-BR', { minimumFractionDigits: 2 })}
            </div>

            {/* Description */}
            {item.description && (
                <div style={{ fontSize: '0.75rem', color: 'var(--sao-text-dim)', marginBottom: '8px' }}>
                    {item.description}
                </div>
            )}

            {/* Attributes */}
            {item.attributes && Object.keys(item.attributes).length > 0 && (
                <div style={{ display: 'flex', flexWrap: 'wrap', gap: '4px', marginBottom: '10px' }}>
                    {Object.entries(item.attributes).map(([key, val]) => (
                        <span key={key} className="badge tag" style={{ fontSize: '0.65rem' }}>
                            {key}: {val}
                        </span>
                    ))}
                </div>
            )}

            {/* Actions */}
            <div style={{ display: 'flex', gap: '6px', marginTop: '8px' }}>
                <button
                    className="sao-btn outline"
                    style={{ flex: 1, padding: '4px 8px', fontSize: '0.7rem', justifyContent: 'center' }}
                    onClick={() => { play('click'); onToggleEquip(item) }}
                >
                    {item.equipped ? '📦 Unequip' : '⚔️ Equip'}
                </button>
                <button
                    className="sao-btn outline"
                    style={{ padding: '4px 8px', fontSize: '0.7rem', color: '#ff4444' }}
                    onClick={() => { play('damage'); onDelete(item) }}
                >
                    🗑️
                </button>
            </div>
        </div>
    )
}

function AddItemModal({ isOpen, onClose }) {
    const { play } = useSound()
    const [form, setForm] = useState({
        name: '', slot: 'weapon', rarity: 'common',
        value: '', description: '', icon: '',
        attributes: {},
    })
    const [attrKey, setAttrKey] = useState('')
    const [attrVal, setAttrVal] = useState('')
    const [submitting, setSubmitting] = useState(false)

    if (!isOpen) return null

    const handleChange = (field, value) => setForm(prev => ({ ...prev, [field]: value }))

    const addAttribute = () => {
        if (attrKey && attrVal) {
            setForm(prev => ({ ...prev, attributes: { ...prev.attributes, [attrKey]: attrVal } }))
            setAttrKey('')
            setAttrVal('')
        }
    }

    const removeAttribute = (key) => {
        setForm(prev => {
            const attrs = { ...prev.attributes }
            delete attrs[key]
            return { ...prev, attributes: attrs }
        })
    }

    const handleSubmit = (e) => {
        e.preventDefault()
        setSubmitting(true)
        router.post('/player/inventory', form, {
            onSuccess: () => {
                play('loot')
                onClose()
                setForm({ name: '', slot: 'weapon', rarity: 'common', value: '', description: '', icon: '', attributes: {} })
            },
            onFinish: () => setSubmitting(false),
        })
    }

    return (
        <div className="modal-overlay" onClick={(e) => { if (e.target === e.currentTarget) { play('close'); onClose() } }}>
            <div className="modal-content" style={{ maxWidth: '480px' }}>
                <h2 className="sao-title">
                    <span className="bracket">「</span>ACQUIRE ITEM<span className="bracket">」</span>
                </h2>

                <form onSubmit={handleSubmit}>
                    <div className="form-group">
                        <label>Item Name</label>
                        <input type="text" value={form.name} onChange={e => handleChange('name', e.target.value)}
                            placeholder="Ex: Nubank Ultravioleta, Bitcoin..." required />
                    </div>

                    <div className="form-row">
                        <div className="form-group">
                            <label>Slot</label>
                            <select value={form.slot} onChange={e => handleChange('slot', e.target.value)}>
                                {Object.entries(SLOT_INFO).map(([key, info]) => (
                                    <option key={key} value={key}>{info.icon} {info.label} ({info.desc})</option>
                                ))}
                            </select>
                        </div>
                        <div className="form-group">
                            <label>Rarity</label>
                            <select value={form.rarity} onChange={e => handleChange('rarity', e.target.value)}
                                style={{ color: RARITY_STYLES[form.rarity]?.color }}>
                                {Object.entries(RARITY_STYLES).map(([key, info]) => (
                                    <option key={key} value={key} style={{ color: info.color }}>{info.label}</option>
                                ))}
                            </select>
                        </div>
                    </div>

                    <div className="form-row">
                        <div className="form-group" style={{ flex: 2 }}>
                            <label>Value (R$)</label>
                            <input type="number" step="0.01" value={form.value}
                                onChange={e => handleChange('value', e.target.value)}
                                placeholder="0,00" required />
                        </div>
                        <div className="form-group" style={{ flex: 1 }}>
                            <label>Icon</label>
                            <input type="text" value={form.icon}
                                onChange={e => handleChange('icon', e.target.value)}
                                placeholder={SLOT_INFO[form.slot]?.icon || '📦'}
                                maxLength={4} />
                        </div>
                    </div>

                    <div className="form-group">
                        <label>Description</label>
                        <textarea rows={2} value={form.description}
                            onChange={e => handleChange('description', e.target.value)}
                            placeholder="Detalhes sobre o item..." />
                    </div>

                    {/* Custom Attributes */}
                    <div className="form-group">
                        <label>Attributes</label>
                        <div style={{ display: 'flex', flexWrap: 'wrap', gap: '4px', marginBottom: '6px' }}>
                            {Object.entries(form.attributes).map(([k, v]) => (
                                <span key={k} className="badge tag" style={{ cursor: 'pointer' }}
                                    onClick={() => removeAttribute(k)}>
                                    {k}: {v} ✕
                                </span>
                            ))}
                        </div>
                        <div style={{ display: 'flex', gap: '6px' }}>
                            <input type="text" value={attrKey} onChange={e => setAttrKey(e.target.value)}
                                placeholder="Chave (ex: banco)" style={{ flex: 1 }} />
                            <input type="text" value={attrVal} onChange={e => setAttrVal(e.target.value)}
                                placeholder="Valor (ex: Nubank)" style={{ flex: 1 }}
                                onKeyDown={e => { if (e.key === 'Enter') { e.preventDefault(); addAttribute() } }} />
                            <button type="button" className="sao-btn outline" onClick={addAttribute}
                                style={{ padding: '6px 10px', fontSize: '0.75rem' }}>+</button>
                        </div>
                    </div>

                    <div style={{ display: 'flex', gap: '10px', marginTop: '20px' }}>
                        <button type="submit" className="sao-btn" disabled={submitting}
                            style={{ flex: 1, justifyContent: 'center' }}>
                            {submitting ? '⏳ ACQUIRING...' : '⊕ ACQUIRE ITEM'}
                        </button>
                        <button type="button" className="sao-btn outline" onClick={() => { play('close'); onClose() }}>
                            CANCEL
                        </button>
                    </div>
                </form>
            </div>
        </div>
    )
}

export default function Inventory({ items, total_value, filters, slot_options }) {
    const [showModal, setShowModal] = useState(false)
    const [activeSlot, setActiveSlot] = useState(filters?.slot || null)
    const { play } = useSound()

    const filteredItems = activeSlot ? items.filter(i => i.slot === activeSlot) : items

    const handleFilter = (slot) => {
        play('click')
        const newSlot = slot === activeSlot ? null : slot
        setActiveSlot(newSlot)
        router.get('/player/inventory', newSlot ? { slot: newSlot } : {}, { preserveState: true })
    }

    const handleDelete = (item) => {
        if (confirm(`Descartar "${item.name}"?`)) {
            router.delete(`/player/inventory/${item.id}`)
        }
    }

    const handleToggleEquip = (item) => {
        router.put(`/player/inventory/${item.id}`, { equipped: !item.equipped })
    }

    // Group by slot
    const equippedCount = items.filter(i => i.equipped).length
    const totalItems = items.length

    return (
        <PlayerLayout>
            <div className="page-content">
                {/* Header */}
                <div style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'center', marginBottom: '20px' }}>
                    <h1 className="sao-title" style={{ margin: 0 }}>
                        <span className="bracket">「</span>INVENTORY<span className="bracket">」</span>
                    </h1>
                    <button className="sao-btn" onClick={() => { play('open'); setShowModal(true) }}>
                        ⊕ Acquire Item
                    </button>
                </div>

                {/* Summary */}
                <div className="stats-grid" style={{ marginBottom: '20px' }}>
                    <SaoPanel>
                        <div style={{ textAlign: 'center' }}>
                            <div style={{ fontSize: '0.7rem', color: 'var(--sao-text-dim)', textTransform: 'uppercase', letterSpacing: '0.1em' }}>
                                Total Items
                            </div>
                            <div style={{ fontSize: '1.8rem', fontWeight: 700, fontFamily: 'Rajdhani, sans-serif', color: 'var(--sao-text)' }}>
                                {totalItems}
                            </div>
                        </div>
                    </SaoPanel>
                    <SaoPanel>
                        <div style={{ textAlign: 'center' }}>
                            <div style={{ fontSize: '0.7rem', color: 'var(--sao-text-dim)', textTransform: 'uppercase', letterSpacing: '0.1em' }}>
                                Equipped
                            </div>
                            <div style={{ fontSize: '1.8rem', fontWeight: 700, fontFamily: 'Rajdhani, sans-serif', color: '#76FF03' }}>
                                {equippedCount}
                            </div>
                        </div>
                    </SaoPanel>
                    <SaoPanel>
                        <div style={{ textAlign: 'center' }}>
                            <div style={{ fontSize: '0.7rem', color: 'var(--sao-text-dim)', textTransform: 'uppercase', letterSpacing: '0.1em' }}>
                                Net Worth
                            </div>
                            <div style={{ fontSize: '1.4rem', fontWeight: 700, fontFamily: 'Rajdhani, sans-serif', color: '#FF9D00' }}>
                                R$ {total_value.toLocaleString('pt-BR', { minimumFractionDigits: 2 })}
                            </div>
                        </div>
                    </SaoPanel>
                </div>

                {/* Slot Filters */}
                <div style={{ display: 'flex', gap: '8px', marginBottom: '20px', flexWrap: 'wrap' }}>
                    <button
                        className={`sao-btn ${!activeSlot ? '' : 'outline'}`}
                        style={{ padding: '6px 14px', fontSize: '0.75rem' }}
                        onClick={() => handleFilter(null)}
                    >
                        All
                    </button>
                    {Object.entries(SLOT_INFO).map(([key, info]) => (
                        <button
                            key={key}
                            className={`sao-btn ${activeSlot === key ? '' : 'outline'}`}
                            style={{ padding: '6px 14px', fontSize: '0.75rem' }}
                            onClick={() => handleFilter(key)}
                        >
                            {info.icon} {info.label}
                        </button>
                    ))}
                </div>

                {/* Items Grid */}
                {filteredItems.length > 0 ? (
                    <div style={{
                        display: 'grid',
                        gridTemplateColumns: 'repeat(auto-fill, minmax(280px, 1fr))',
                        gap: '16px',
                    }}>
                        {filteredItems.map(item => (
                            <ItemCard
                                key={item.id}
                                item={item}
                                onDelete={handleDelete}
                                onToggleEquip={handleToggleEquip}
                            />
                        ))}
                    </div>
                ) : (
                    <SaoPanel>
                        <div style={{ textAlign: 'center', padding: '40px', color: 'var(--sao-text-dim)' }}>
                            <div style={{ fontSize: '2rem', marginBottom: '8px' }}>📦</div>
                            <div>Nenhum item encontrado. Adquira seu primeiro equipamento!</div>
                        </div>
                    </SaoPanel>
                )}

                {/* Add Item Modal */}
                <AddItemModal isOpen={showModal} onClose={() => setShowModal(false)} />
            </div>
        </PlayerLayout>
    )
}
