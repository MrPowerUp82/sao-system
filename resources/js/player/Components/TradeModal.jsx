import React, { useState, useEffect } from 'react'
import { router } from '@inertiajs/react'
import { useSound } from './SoundManager'

const TAG_SUGGESTIONS = ['Cartão', 'Boleto', 'Pix', 'Salário', 'Freelance', 'Aluguel', 'Mercado', 'Transporte', 'Lazer']

export default function TradeModal({ isOpen, onClose }) {
    const [form, setForm] = useState({
        name: '',
        input: '0',
        type: 'v',
        total_value: '',
        installment_value: '',
        installment_amount: '',
        start_date: new Date().toISOString().split('T')[0],
        end_date: '',
        fix: false,
        tags: [],
        description: '',
    })
    const [tagInput, setTagInput] = useState('')
    const [submitting, setSubmitting] = useState(false)
    const { play } = useSound()

    // Play open sound when modal appears
    useEffect(() => {
        if (isOpen) play('open')
    }, [isOpen])

    if (!isOpen) return null

    const handleChange = (field, value) => {
        setForm(prev => ({ ...prev, [field]: value }))
    }

    const addTag = (tag) => {
        if (tag && !form.tags.includes(tag)) {
            setForm(prev => ({ ...prev, tags: [...prev.tags, tag] }))
        }
        setTagInput('')
    }

    const removeTag = (tag) => {
        setForm(prev => ({ ...prev, tags: prev.tags.filter(t => t !== tag) }))
    }

    const handleSubmit = (e) => {
        e.preventDefault()
        setSubmitting(true)
        router.post('/player/trade', form, {
            onSuccess: () => {
                play(form.input === '1' ? 'loot' : 'damage')
                onClose()
                setForm({
                    name: '', input: '0', type: 'v', total_value: '', installment_value: '',
                    installment_amount: '', start_date: new Date().toISOString().split('T')[0],
                    end_date: '', fix: false, tags: [], description: '',
                })
            },
            onFinish: () => setSubmitting(false),
        })
    }

    return (
        <div className="modal-overlay" onClick={(e) => { if (e.target === e.currentTarget) { play('close'); onClose() } }}>
            <div className="modal-content">
                <h2 className="sao-title">
                    <span className="bracket">「</span>REGISTER TRADE<span className="bracket">」</span>
                </h2>

                <form onSubmit={handleSubmit}>
                    <div className="form-group">
                        <label>Trade Name</label>
                        <input
                            type="text"
                            value={form.name}
                            onChange={e => handleChange('name', e.target.value)}
                            placeholder="Ex: Salário, Netflix, Mercado..."
                            required
                        />
                    </div>

                    <div className="form-row">
                        <div className="form-group">
                            <label>Direction</label>
                            <select value={form.input} onChange={e => handleChange('input', e.target.value)}>
                                <option value="1">⬆ Loot (Entrada)</option>
                                <option value="0">⬇ Damage (Saída)</option>
                            </select>
                        </div>
                        <div className="form-group">
                            <label>Type</label>
                            <select value={form.type} onChange={e => handleChange('type', e.target.value)}>
                                <option value="v">⚡ Direct Hit (À vista)</option>
                                <option value="p">🔄 Continuous (Parcelado)</option>
                            </select>
                        </div>
                    </div>

                    <div className="form-row">
                        <div className="form-group">
                            <label>Total Value (Col)</label>
                            <input
                                type="number"
                                step="0.01"
                                value={form.total_value}
                                onChange={e => handleChange('total_value', e.target.value)}
                                placeholder="R$ 0,00"
                                required
                            />
                        </div>
                        <div className="form-group">
                            <label>Date</label>
                            <input
                                type="date"
                                value={form.start_date}
                                onChange={e => handleChange('start_date', e.target.value)}
                                required
                            />
                        </div>
                    </div>

                    {form.type === 'p' && (
                        <>
                            <div className="form-row">
                                <div className="form-group">
                                    <label>Installments</label>
                                    <input
                                        type="number"
                                        value={form.installment_amount}
                                        onChange={e => handleChange('installment_amount', e.target.value)}
                                        placeholder="Nº de parcelas"
                                    />
                                </div>
                                <div className="form-group">
                                    <label>Installment Value</label>
                                    <input
                                        type="number"
                                        step="0.01"
                                        value={form.installment_value}
                                        onChange={e => handleChange('installment_value', e.target.value)}
                                        placeholder="R$ 0,00"
                                    />
                                </div>
                            </div>
                            <div className="form-group">
                                <label>End Date</label>
                                <input
                                    type="date"
                                    value={form.end_date}
                                    onChange={e => handleChange('end_date', e.target.value)}
                                />
                            </div>
                        </>
                    )}

                    <div className="form-group">
                        <label style={{ display: 'flex', alignItems: 'center', gap: '8px' }}>
                            <input
                                type="checkbox"
                                checked={form.fix}
                                onChange={e => handleChange('fix', e.target.checked)}
                                style={{ width: 'auto' }}
                            />
                            Passive Effect (Fixo/Recorrente)
                        </label>
                    </div>

                    {/* Tags */}
                    <div className="form-group">
                        <label>Tags</label>
                        <div style={{ display: 'flex', flexWrap: 'wrap', gap: '4px', marginBottom: '8px' }}>
                            {form.tags.map(tag => (
                                <span key={tag} className="badge tag" style={{ cursor: 'pointer' }} onClick={() => removeTag(tag)}>
                                    {tag} ✕
                                </span>
                            ))}
                        </div>
                        <div style={{ display: 'flex', gap: '6px' }}>
                            <input
                                type="text"
                                value={tagInput}
                                onChange={e => setTagInput(e.target.value)}
                                onKeyDown={e => { if (e.key === 'Enter') { e.preventDefault(); addTag(tagInput) } }}
                                placeholder="Adicionar tag..."
                                style={{ flex: 1 }}
                            />
                        </div>
                        <div style={{ display: 'flex', flexWrap: 'wrap', gap: '4px', marginTop: '6px' }}>
                            {TAG_SUGGESTIONS.filter(s => !form.tags.includes(s)).slice(0, 5).map(s => (
                                <span key={s} className="badge tag" style={{ cursor: 'pointer', opacity: 0.6 }} onClick={() => addTag(s)}>
                                    + {s}
                                </span>
                            ))}
                        </div>
                    </div>

                    <div className="form-group">
                        <label>Description</label>
                        <textarea
                            rows={2}
                            value={form.description}
                            onChange={e => handleChange('description', e.target.value)}
                            placeholder="Observações sobre esse trade..."
                        />
                    </div>

                    <div style={{ display: 'flex', gap: '10px', marginTop: '20px' }}>
                        <button type="submit" className="sao-btn" disabled={submitting} style={{ flex: 1, justifyContent: 'center' }}>
                            {submitting ? '⏳ REGISTERING...' : '⊕ CONFIRM TRADE'}
                        </button>
                        <button type="button" className="sao-btn outline" onClick={onClose}>
                            CANCEL
                        </button>
                    </div>
                </form>
            </div>
        </div>
    )
}
