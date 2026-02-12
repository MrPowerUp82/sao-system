import React, { useState } from 'react'
import PlayerLayout from '../Layouts/PlayerLayout'
import SaoPanel from '../Components/SaoPanel'
import TradeModal from '../Components/TradeModal'

export default function Dashboard({ stats, xp, recent_trades, active_floors }) {
    const [showTradeModal, setShowTradeModal] = useState(false)

    const formatMoney = (val) => {
        return (val || 0).toLocaleString('pt-BR', { style: 'currency', currency: 'BRL' })
    }

    const getTradeLabel = (trade) => {
        if (trade.fix) return { text: 'PASSIVE', className: 'passive' }
        if (trade.input === 1) return { text: 'LOOT', className: 'loot' }
        return { text: 'DAMAGE', className: 'damage' }
    }

    return (
        <PlayerLayout stats={stats} xp={xp}>
            <div className="page-content">
                {/* Quick Stats */}
                <div className="stats-grid">
                    <SaoPanel>
                        <div className="stat-card">
                            <span className="stat-icon">💰</span>
                            <span className="stat-label">Total Loot (Entradas)</span>
                            <span className="stat-value success">{formatMoney(stats.monthly_income)}</span>
                        </div>
                    </SaoPanel>
                    <SaoPanel>
                        <div className="stat-card">
                            <span className="stat-icon">💥</span>
                            <span className="stat-label">Total Damage (Saídas)</span>
                            <span className="stat-value danger">{formatMoney(stats.monthly_expense)}</span>
                        </div>
                    </SaoPanel>
                    <SaoPanel>
                        <div className="stat-card">
                            <span className="stat-icon">⚖️</span>
                            <span className="stat-label">Balance (Saldo)</span>
                            <span className={`stat-value ${stats.balance >= 0 ? 'success' : 'danger'}`}>
                                {formatMoney(stats.balance)}
                            </span>
                        </div>
                    </SaoPanel>
                    <SaoPanel>
                        <div className="stat-card">
                            <span className="stat-icon">⭐</span>
                            <span className="stat-label">Player Level</span>
                            <span className="stat-value" style={{ color: 'var(--sao-orange)' }}>
                                LV. {xp.current_level}
                            </span>
                        </div>
                    </SaoPanel>
                </div>

                {/* Content Grid */}
                <div className="content-grid">
                    {/* Recent Trades */}
                    <SaoPanel>
                        <div style={{ padding: '16px 16px 8px' }}>
                            <div style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'center' }}>
                                <h3 className="sao-title" style={{ fontSize: '1rem' }}>
                                    <span className="bracket">「</span>RECENT TRADES<span className="bracket">」</span>
                                </h3>
                                <button className="sao-btn sm" onClick={() => setShowTradeModal(true)}>
                                    ⊕ NEW
                                </button>
                            </div>
                        </div>
                        <div>
                            {recent_trades && recent_trades.length > 0 ? (
                                recent_trades.map(trade => {
                                    const label = getTradeLabel(trade)
                                    return (
                                        <div key={trade.id} className="trade-row">
                                            <div className="trade-info">
                                                <span className={`badge ${label.className}`}>{label.text}</span>
                                                <div>
                                                    <div className="trade-name">{trade.name}</div>
                                                    <div className="trade-date">{trade.created_at}</div>
                                                </div>
                                            </div>
                                            <div className={`trade-value ${trade.input === 1 ? '' : ''}`}
                                                style={{ color: trade.input === 1 ? 'var(--sao-success)' : 'var(--sao-danger)' }}>
                                                {trade.input === 1 ? '+' : '-'}{formatMoney(trade.total_value)}
                                            </div>
                                        </div>
                                    )
                                })
                            ) : (
                                <div style={{ padding: '32px 16px', textAlign: 'center', color: 'var(--sao-text-muted)' }}>
                                    <p style={{ fontSize: '1.5rem', marginBottom: '8px' }}>📭</p>
                                    <p style={{ fontSize: '0.8rem' }}>Nenhum trade registrado ainda.</p>
                                    <button className="sao-btn sm" style={{ marginTop: '12px' }} onClick={() => setShowTradeModal(true)}>
                                        ⊕ Registrar primeiro trade
                                    </button>
                                </div>
                            )}
                        </div>
                    </SaoPanel>

                    {/* Active Floors */}
                    <SaoPanel>
                        <div style={{ padding: '16px 16px 8px' }}>
                            <h3 className="sao-title" style={{ fontSize: '1rem' }}>
                                <span className="bracket">「</span>FLOOR PROGRESS<span className="bracket">」</span>
                            </h3>
                        </div>
                        <div>
                            {active_floors && active_floors.length > 0 ? (
                                active_floors.map(floor => (
                                    <div key={floor.id} className={`floor-card sao-panel ${floor.status === 'active' ? 'active pulse' : floor.status === 'cleared' ? 'cleared' : ''}`}
                                        style={{ margin: '8px 12px', border: floor.status === 'active' ? '1px solid var(--sao-orange)' : undefined }}>
                                        <div className="floor-number">{floor.icon}</div>
                                        <div className="floor-info">
                                            <div className="floor-name">
                                                Floor {floor.floor_number}: {floor.name}
                                                {floor.status === 'cleared' && ' ✅'}
                                            </div>
                                            <div className="floor-amount">
                                                {formatMoney(floor.current_amount)} / {formatMoney(floor.target_amount)}
                                            </div>
                                            <div className="bar-container" style={{ marginTop: '6px' }}>
                                                <div className="bar-fill hp" style={{
                                                    width: `${floor.progress}%`,
                                                    background: floor.status === 'cleared' ? 'var(--sao-xp)' : 'var(--sao-hp-full)',
                                                }} />
                                            </div>
                                        </div>
                                    </div>
                                ))
                            ) : (
                                <div style={{ padding: '32px 16px', textAlign: 'center', color: 'var(--sao-text-muted)' }}>
                                    <p style={{ fontSize: '1.5rem', marginBottom: '8px' }}>🗺️</p>
                                    <p style={{ fontSize: '0.8rem' }}>Nenhum andar desbloqueado.</p>
                                    <a href="/player/floor-map" className="sao-btn sm" style={{ marginTop: '12px' }}>
                                        🏰 Explorar Floor Map
                                    </a>
                                </div>
                            )}
                        </div>
                    </SaoPanel>
                </div>
            </div>

            <TradeModal isOpen={showTradeModal} onClose={() => setShowTradeModal(false)} />
        </PlayerLayout>
    )
}
