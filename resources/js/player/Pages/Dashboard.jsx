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

    // Calculate max value for chart scaling
    const maxChartValue = stats.daily_activity ? Math.max(...stats.daily_activity.map(d => Math.max(d.income, d.expense))) : 100

    return (
        <PlayerLayout stats={stats} xp={xp}>
            <div className="page-content">
                {/* Stats Grid */}
                <div className="stats-grid">
                    <SaoPanel>
                        <div className="stat-card">
                            <span className="stat-icon">💰</span>
                            <span className="stat-label">Total Loot</span>
                            <span className="stat-value success">{formatMoney(stats.monthly_income)}</span>
                        </div>
                    </SaoPanel>
                    <SaoPanel>
                        <div className="stat-card">
                            <span className="stat-icon">💥</span>
                            <span className="stat-label">Total Damage</span>
                            <span className="stat-value danger">{formatMoney(stats.monthly_expense)}</span>
                        </div>
                    </SaoPanel>
                    <SaoPanel>
                        <div className="stat-card">
                            <span className="stat-icon">⚖️</span>
                            <span className="stat-label">Balance</span>
                            <span className={`stat-value ${stats.balance >= 0 ? 'success' : 'danger'}`}>
                                {formatMoney(stats.balance)}
                            </span>
                        </div>
                    </SaoPanel>
                    <SaoPanel>
                        <div className="stat-card">
                            <span className="stat-icon">🔥</span>
                            <span className="stat-label">Streak</span>
                            <span className="stat-value" style={{ color: 'var(--sao-orange)' }}>
                                {stats.streak} <span style={{ fontSize: '1rem' }}>DAYS</span>
                            </span>
                        </div>
                    </SaoPanel>
                </div>

                {/* Charts & Breakdown */}
                <div className="content-grid" style={{ marginBottom: '16px' }}>

                    {/* 7-Day Activity Chart */}
                    <SaoPanel>
                        <div style={{ padding: '16px 16px 8px' }}>
                            <h3 className="sao-title" style={{ fontSize: '1rem' }}>
                                <span className="bracket">「</span>7-DAY ACTIVITY<span className="bracket">」</span>
                            </h3>
                        </div>
                        <div style={{ padding: '0 16px 16px', height: '180px', display: 'flex', alignItems: 'flex-end', justifyContent: 'space-between', gap: '8px' }}>
                            {stats.daily_activity && stats.daily_activity.map((day, i) => (
                                <div key={i} style={{ flex: 1, display: 'flex', flexDirection: 'column', alignItems: 'center', gap: '4px', height: '100%' }}>
                                    <div style={{ flex: 1, width: '100%', display: 'flex', alignItems: 'flex-end', gap: '4px', position: 'relative' }}>
                                        {/* Income Bar */}
                                        <div style={{
                                            flex: 1, background: 'var(--sao-success)',
                                            height: `${Math.max(4, (day.income / maxChartValue) * 100)}%`,
                                            borderRadius: '2px 2px 0 0', opacity: 0.8, transition: 'height 0.5s ease'
                                        }} title={`Income: ${formatMoney(day.income)}`} />

                                        {/* Expense Bar */}
                                        <div style={{
                                            flex: 1, background: 'var(--sao-danger)',
                                            height: `${Math.max(4, (day.expense / maxChartValue) * 100)}%`,
                                            borderRadius: '2px 2px 0 0', opacity: 0.8, transition: 'height 0.5s ease'
                                        }} title={`Expense: ${formatMoney(day.expense)}`} />
                                    </div>
                                    <span style={{ fontSize: '0.65rem', color: 'var(--sao-text-dim)' }}>{day.date}</span>
                                </div>
                            ))}
                        </div>
                    </SaoPanel>

                    {/* Top Categories */}
                    <SaoPanel>
                        <div style={{ padding: '16px 16px 8px' }}>
                            <h3 className="sao-title" style={{ fontSize: '1rem' }}>
                                <span className="bracket">「</span>TOP EXPENSES<span className="bracket">」</span>
                            </h3>
                        </div>
                        <div style={{ padding: '0 16px 16px' }}>
                            {stats.top_categories && stats.top_categories.length > 0 ? (
                                <div style={{ display: 'flex', flexDirection: 'column', gap: '12px' }}>
                                    {stats.top_categories.map((cat, i) => (
                                        <div key={i}>
                                            <div style={{ display: 'flex', justifyContent: 'space-between', fontSize: '0.8rem', marginBottom: '4px' }}>
                                                <span style={{ fontWeight: 700 }}>{cat.tag}</span>
                                                <span style={{ color: 'var(--sao-danger)' }}>{formatMoney(cat.amount)}</span>
                                            </div>
                                            <div className="bar-container">
                                                <div className="bar-fill" style={{
                                                    width: `${(cat.amount / stats.monthly_expense) * 100}%`,
                                                    background: 'var(--sao-danger)',
                                                    boxShadow: '0 0 10px rgba(255, 71, 87, 0.3)'
                                                }} />
                                            </div>
                                        </div>
                                    ))}
                                </div>
                            ) : (
                                <div style={{ textAlign: 'center', color: 'var(--sao-text-muted)', padding: '20px' }}>
                                    Sem dados de gastos.
                                </div>
                            )}
                        </div>
                    </SaoPanel>
                </div>

                {/* Content Grid (Trades & Floors) */}
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
