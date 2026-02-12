import React, { useState } from 'react'
import { router, usePage } from '@inertiajs/react'
import PlayerLayout from '../Layouts/PlayerLayout'
import SaoPanel from '../Components/SaoPanel'
import TradeModal from '../Components/TradeModal'

export default function TradeLog({ trades, filters }) {
    const [showTradeModal, setShowTradeModal] = useState(false)
    const [search, setSearch] = useState(filters?.search || '')

    const { auth } = usePage().props
    const xp = auth?.user ? { current_level: auth.user.level, xp_remaining: 0, progress: 0 } : null

    const formatMoney = (val) => (val || 0).toLocaleString('pt-BR', { style: 'currency', currency: 'BRL' })

    const getTradeLabel = (trade) => {
        if (trade.fix) return { text: 'PASSIVE EFFECT', className: 'passive' }
        if (trade.input === 1) {
            return { text: trade.type === 'p' ? 'RECURRING LOOT' : 'LOOT ACQUIRED', className: 'loot' }
        }
        return { text: trade.type === 'p' ? 'CONTINUOUS DMG' : 'DAMAGE TAKEN', className: 'damage' }
    }

    const handleFilter = (field, value) => {
        router.get('/player/trade-log', { ...filters, [field]: value, search }, {
            preserveState: true,
            preserveScroll: true,
        })
    }

    const handleSearch = (e) => {
        e.preventDefault()
        handleFilter('search', search)
    }

    const handleDelete = (id) => {
        if (confirm('Remover este trade?')) {
            router.delete(`/player/trade/${id}`)
        }
    }

    return (
        <PlayerLayout xp={xp}>
            <div className="page-content">
                <div className="page-header">
                    <h1 className="sao-title page-title">
                        <span className="bracket">「</span>TRADE LOG<span className="bracket">」</span>
                    </h1>
                    <button className="sao-btn" onClick={() => setShowTradeModal(true)}>
                        ⊕ REGISTER TRADE
                    </button>
                </div>

                {/* Filters */}
                <SaoPanel style={{ padding: '16px', marginBottom: '16px' }}>
                    <div style={{ display: 'flex', gap: '12px', flexWrap: 'wrap', alignItems: 'center' }}>
                        <form onSubmit={handleSearch} style={{ flex: 1, minWidth: '200px' }}>
                            <input
                                type="text"
                                value={search}
                                onChange={e => setSearch(e.target.value)}
                                placeholder="🔍 Buscar trades..."
                                style={{
                                    width: '100%', padding: '8px 14px',
                                    background: 'var(--sao-dark)', border: '1px solid var(--sao-border-subtle)',
                                    borderRadius: '8px', color: 'var(--sao-text)',
                                    fontFamily: 'Inter, sans-serif', fontSize: '0.85rem', outline: 'none',
                                }}
                            />
                        </form>
                        <select
                            value={filters?.input || ''}
                            onChange={e => handleFilter('input', e.target.value)}
                            style={{
                                padding: '8px 14px', background: 'var(--sao-dark)',
                                border: '1px solid var(--sao-border-subtle)', borderRadius: '8px',
                                color: 'var(--sao-text)', fontSize: '0.85rem', outline: 'none',
                            }}
                        >
                            <option value="">Todos</option>
                            <option value="1">⬆ Loot (Entradas)</option>
                            <option value="0">⬇ Damage (Saídas)</option>
                        </select>
                        <select
                            value={filters?.type || ''}
                            onChange={e => handleFilter('type', e.target.value)}
                            style={{
                                padding: '8px 14px', background: 'var(--sao-dark)',
                                border: '1px solid var(--sao-border-subtle)', borderRadius: '8px',
                                color: 'var(--sao-text)', fontSize: '0.85rem', outline: 'none',
                            }}
                        >
                            <option value="">Todos os tipos</option>
                            <option value="v">⚡ Direct Hit (À vista)</option>
                            <option value="p">🔄 Continuous (Parcelado)</option>
                        </select>
                    </div>
                </SaoPanel>

                {/* Trade List */}
                <SaoPanel>
                    {trades?.data?.length > 0 ? (
                        <>
                            {trades.data.map(trade => {
                                const label = getTradeLabel(trade)
                                return (
                                    <div key={trade.id} className="trade-row">
                                        <div className="trade-info">
                                            <span className={`badge ${label.className}`}>{label.text}</span>
                                            <div>
                                                <div className="trade-name">{trade.name}</div>
                                                <div className="trade-date">
                                                    {trade.start_date} {trade.description && `— ${trade.description.substring(0, 40)}...`}
                                                </div>
                                                {trade.tags && trade.tags.length > 0 && (
                                                    <div style={{ display: 'flex', gap: '4px', marginTop: '4px' }}>
                                                        {trade.tags.map((tag, i) => (
                                                            <span key={i} className="badge tag">{tag}</span>
                                                        ))}
                                                    </div>
                                                )}
                                            </div>
                                        </div>
                                        <div style={{ display: 'flex', alignItems: 'center', gap: '12px' }}>
                                            <div className="trade-value" style={{ color: trade.input === 1 ? 'var(--sao-success)' : 'var(--sao-danger)' }}>
                                                {trade.input === 1 ? '+' : '-'}{formatMoney(trade.total_value)}
                                                {trade.type === 'p' && trade.installment_value && (
                                                    <div style={{ fontSize: '0.65rem', color: 'var(--sao-text-muted)' }}>
                                                        {trade.installment_amount}x {formatMoney(trade.installment_value)}
                                                    </div>
                                                )}
                                            </div>
                                            <button
                                                onClick={() => handleDelete(trade.id)}
                                                style={{
                                                    background: 'none', border: 'none', color: 'var(--sao-text-muted)',
                                                    cursor: 'pointer', fontSize: '1rem', padding: '4px',
                                                }}
                                                title="Remover trade"
                                            >
                                                🗑
                                            </button>
                                        </div>
                                    </div>
                                )
                            })}

                            {/* Pagination */}
                            {trades.last_page > 1 && (
                                <div style={{ padding: '16px', display: 'flex', justifyContent: 'center', gap: '8px' }}>
                                    {trades.links?.map((link, i) => (
                                        <button
                                            key={i}
                                            disabled={!link.url || link.active}
                                            onClick={() => link.url && router.get(link.url, {}, { preserveState: true })}
                                            className={`sao-btn sm ${link.active ? '' : 'outline'}`}
                                            dangerouslySetInnerHTML={{ __html: link.label }}
                                        />
                                    ))}
                                </div>
                            )}
                        </>
                    ) : (
                        <div style={{ padding: '48px 16px', textAlign: 'center', color: 'var(--sao-text-muted)' }}>
                            <p style={{ fontSize: '2rem', marginBottom: '12px' }}>⚔️</p>
                            <p style={{ fontSize: '0.9rem', marginBottom: '4px' }}>Nenhum trade encontrado.</p>
                            <p style={{ fontSize: '0.75rem' }}>Registre seu primeiro trade para começar a evoluir!</p>
                        </div>
                    )}
                </SaoPanel>
            </div>

            <TradeModal isOpen={showTradeModal} onClose={() => setShowTradeModal(false)} />
        </PlayerLayout>
    )
}
