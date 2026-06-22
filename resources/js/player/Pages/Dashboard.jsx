import React, { useState } from 'react'
import PlayerLayout from '../Layouts/PlayerLayout'
import SaoPanel from '../Components/SaoPanel'
import TradeModal from '../Components/TradeModal'
import DailyQuests from '../Components/DailyQuests'

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

                {/* ═══════ 2-COLUMN LAYOUT (Stitch-inspired) ═══════ */}
                <div style={{ display: 'grid', gridTemplateColumns: '1fr', gap: '16px' }}>

                    {/* ── Row 1: Stats Overview ── */}
                    <div className="stats-grid">
                        {/* Total Loot */}
                        <div className="sao-panel glass-panel glass-panel-teal" style={{ overflow: 'hidden' }}>
                            <div className="stat-card">
                                <div style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'flex-start' }}>
                                    <div>
                                        <span className="label-caps" style={{ fontSize: '10px', color: 'var(--sao-text-dim)' }}>TOTAL LOOT</span>
                                        <span className="stat-value success" style={{ display: 'block', marginTop: '4px' }}>{formatMoney(stats.monthly_income)}</span>
                                    </div>
                                    <div style={{
                                        width: '40px', height: '40px', borderRadius: '50%',
                                        background: 'rgba(74,225,131,0.1)', display: 'flex',
                                        alignItems: 'center', justifyContent: 'center'
                                    }}>
                                        <span className="material-symbols-outlined" style={{ color: '#4ae183', fontVariationSettings: "'FILL' 1" }}>bar_chart</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {/* Total Damage */}
                        <div className="sao-panel glass-panel glass-panel-teal" style={{ overflow: 'hidden' }}>
                            <div className="stat-card">
                                <div style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'flex-start' }}>
                                    <div>
                                        <span className="label-caps" style={{ fontSize: '10px', color: 'var(--sao-text-dim)' }}>TOTAL DAMAGE</span>
                                        <span className="stat-value danger" style={{ display: 'block', marginTop: '4px' }}>{formatMoney(stats.monthly_expense)}</span>
                                    </div>
                                    <div style={{
                                        width: '40px', height: '40px', borderRadius: '50%',
                                        background: 'rgba(255,71,87,0.1)', display: 'flex',
                                        alignItems: 'center', justifyContent: 'center'
                                    }}>
                                        <span className="material-symbols-outlined" style={{ color: '#ff4757', fontVariationSettings: "'FILL' 1" }}>trending_down</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {/* Balance */}
                        <div className="sao-panel glass-panel glass-panel-teal" style={{ overflow: 'hidden' }}>
                            <div className="stat-card">
                                <div style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'flex-start' }}>
                                    <div>
                                        <span className="label-caps" style={{ fontSize: '10px', color: 'var(--sao-text-dim)' }}>BALANCE</span>
                                        <span className={`stat-value ${stats.balance >= 0 ? 'success' : 'danger'}`} style={{ display: 'block', marginTop: '4px' }}>
                                            {formatMoney(stats.balance)}
                                        </span>
                                    </div>
                                    <div style={{
                                        width: '40px', height: '40px', borderRadius: '50%',
                                        background: 'rgba(74,225,131,0.1)', display: 'flex',
                                        alignItems: 'center', justifyContent: 'center'
                                    }}>
                                        <span className="material-symbols-outlined" style={{ color: stats.balance >= 0 ? '#4ae183' : '#ff4757', fontVariationSettings: "'FILL' 1" }}>account_balance_wallet</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {/* Streak */}
                        <div className="sao-panel glass-panel glass-panel-orange" style={{ overflow: 'hidden' }}>
                            <div className="stat-card">
                                <div style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'flex-start' }}>
                                    <div>
                                        <span className="label-caps" style={{ fontSize: '10px', color: 'var(--sao-text-dim)' }}>STREAK</span>
                                        <span className="stat-value" style={{ display: 'block', marginTop: '4px', color: 'var(--sao-orange)' }}>
                                            {stats.streak} <span style={{ fontSize: '0.8rem', fontWeight: 600 }}>DAYS</span>
                                        </span>
                                    </div>
                                    <div style={{
                                        width: '40px', height: '40px', borderRadius: '50%',
                                        background: 'rgba(255,157,0,0.1)', display: 'flex',
                                        alignItems: 'center', justifyContent: 'center'
                                    }}>
                                        <span className="material-symbols-outlined" style={{ color: '#ff9d00', fontVariationSettings: "'FILL' 1" }}>local_fire_department</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {/* ── Row 2: Charts & Daily Quests ── */}
                    <div className="content-grid" style={{ marginBottom: '16px' }}>

                        {/* 7-Day Activity Chart */}
                        <div className="sao-panel glass-panel glass-panel-teal" style={{ overflow: 'hidden' }}>
                            <div className="panel-header">
                                <h3 style={{ color: '#62bbff' }}>
                                    7-DAY ACTIVITY
                                </h3>
                                <button className="more-btn">
                                    <span className="material-symbols-outlined">more_horiz</span>
                                </button>
                            </div>
                            <div style={{ padding: '16px', height: '180px', display: 'flex', alignItems: 'flex-end', justifyContent: 'space-between', gap: '8px' }}>
                                {stats.daily_activity && stats.daily_activity.map((day, i) => (
                                    <div key={i} style={{ flex: 1, display: 'flex', flexDirection: 'column', alignItems: 'center', gap: '4px', height: '100%' }}>
                                        <div style={{ flex: 1, width: '100%', display: 'flex', alignItems: 'flex-end', gap: '4px', position: 'relative' }}>
                                            {/* Income Bar */}
                                            <div style={{
                                                flex: 1, background: 'linear-gradient(to top, #06bb63, #4ae183)',
                                                height: `${Math.max(4, (day.income / maxChartValue) * 100)}%`,
                                                borderRadius: '2px 2px 0 0', opacity: 0.8, transition: 'height 0.5s ease',
                                                boxShadow: '0 0 6px rgba(74,225,131,0.3)'
                                            }} title={`Income: ${formatMoney(day.income)}`} />

                                            {/* Expense Bar */}
                                            <div style={{
                                                flex: 1, background: 'linear-gradient(to top, #e74c3c, #ff6b6b)',
                                                height: `${Math.max(4, (day.expense / maxChartValue) * 100)}%`,
                                                borderRadius: '2px 2px 0 0', opacity: 0.8, transition: 'height 0.5s ease',
                                                boxShadow: '0 0 6px rgba(255,71,87,0.3)'
                                            }} title={`Expense: ${formatMoney(day.expense)}`} />
                                        </div>
                                        <span className="label-caps" style={{ fontSize: '9px', color: 'var(--sao-text-dim)' }}>{day.date}</span>
                                    </div>
                                ))}
                            </div>
                        </div>

                        {/* Top Categories */}
                        <div className="sao-panel glass-panel glass-panel-teal" style={{ overflow: 'hidden' }}>
                            <div className="panel-header">
                                <h3 style={{ color: '#62bbff' }}>
                                    TOP EXPENSES
                                </h3>
                                <button className="more-btn">
                                    <span className="material-symbols-outlined">more_horiz</span>
                                </button>
                            </div>
                            <div style={{ padding: '16px' }}>
                                {stats.top_categories && stats.top_categories.length > 0 ? (
                                    <div style={{ display: 'flex', flexDirection: 'column', gap: '12px' }}>
                                        {stats.top_categories.map((cat, i) => (
                                            <div key={i}>
                                                <div style={{ display: 'flex', justifyContent: 'space-between', fontSize: '0.8rem', marginBottom: '4px' }}>
                                                    <span style={{ fontWeight: 700, fontFamily: "'Sora', sans-serif" }}>{cat.tag}</span>
                                                    <span style={{ color: 'var(--sao-danger)' }}>{formatMoney(cat.amount)}</span>
                                                </div>
                                                <div className="progress-track" style={{ height: '6px' }}>
                                                    <div className="progress-fill-hp" style={{
                                                        width: `${(cat.amount / stats.monthly_expense) * 100}%`,
                                                        background: 'linear-gradient(90deg, #e74c3c, #ff6b6b)',
                                                        boxShadow: '0 0 8px rgba(255, 71, 87, 0.4)',
                                                    }}>
                                                        <div className="progress-glint" />
                                                    </div>
                                                </div>
                                            </div>
                                        ))}
                                    </div>
                                ) : (
                                    <div style={{ textAlign: 'center', color: 'var(--sao-text-muted)', padding: '20px' }}>
                                        <span className="material-symbols-outlined" style={{ fontSize: '32px', marginBottom: '8px', display: 'block', opacity: 0.5 }}>analytics</span>
                                        Sem dados de gastos.
                                    </div>
                                )}
                            </div>
                        </div>
                    </div>

                    {/* ── Row 3: Trades & Floor Progress ── */}
                    <div className="content-grid">
                        {/* Recent Trades */}
                        <div className="sao-panel glass-panel glass-panel-teal" style={{ overflow: 'hidden' }}>
                            <div className="panel-header">
                                <h3 style={{ color: '#62bbff' }}>
                                    RECENT TRADES
                                </h3>
                                <button className="sao-btn sm" onClick={() => setShowTradeModal(true)}>
                                    <span className="material-symbols-outlined" style={{ fontSize: '14px' }}>add</span>
                                    NEW
                                </button>
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
                                                <div className={`trade-value`}
                                                    style={{ color: trade.input === 1 ? '#4ae183' : '#ff4757' }}>
                                                    {trade.input === 1 ? '+' : '-'}{formatMoney(trade.total_value)}
                                                </div>
                                            </div>
                                        )
                                    })
                                ) : (
                                    <div style={{ padding: '32px 16px', textAlign: 'center', color: 'var(--sao-text-muted)' }}>
                                        <span className="material-symbols-outlined" style={{ fontSize: '32px', marginBottom: '8px', display: 'block', opacity: 0.5 }}>mail</span>
                                        <p style={{ fontSize: '0.8rem', fontFamily: "'Hanken Grotesk', sans-serif" }}>Nenhum trade registrado ainda.</p>
                                        <button className="sao-btn sm" style={{ marginTop: '12px' }} onClick={() => setShowTradeModal(true)}>
                                            <span className="material-symbols-outlined" style={{ fontSize: '14px' }}>add</span>
                                            Registrar primeiro trade
                                        </button>
                                    </div>
                                )}
                            </div>
                        </div>

                        {/* Active Floors */}
                        <div className="sao-panel glass-panel glass-panel-teal" style={{ overflow: 'hidden' }}>
                            <div className="panel-header">
                                <h3 style={{ color: '#62bbff' }}>
                                    FLOOR PROGRESS
                                </h3>
                                <button className="more-btn">
                                    <span className="material-symbols-outlined">more_horiz</span>
                                </button>
                            </div>
                            <div style={{ padding: '8px' }}>
                                {active_floors && active_floors.length > 0 ? (
                                    active_floors.map(floor => (
                                        <div key={floor.id} style={{
                                            padding: '14px',
                                            margin: '4px 0',
                                            borderRadius: '8px',
                                            background: 'rgba(51,52,60,0.2)',
                                            border: `1px solid ${floor.status === 'active' ? 'rgba(255,157,0,0.3)' : 'rgba(255,255,255,0.05)'}`,
                                            transition: 'border-color 0.2s',
                                        }}>
                                            <div style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'flex-end', marginBottom: '8px' }}>
                                                <span style={{ fontFamily: "'Sora', sans-serif", fontSize: '0.85rem', fontWeight: 600 }}>
                                                    Floor {floor.floor_number}: {floor.name}
                                                    {floor.status === 'cleared' && (
                                                        <span className="material-symbols-outlined" style={{ fontSize: '14px', marginLeft: '6px', color: '#4ae183', verticalAlign: 'middle' }}>check_circle</span>
                                                    )}
                                                </span>
                                                <span className="label-caps" style={{ fontSize: '10px', color: 'rgba(74,225,131,0.8)' }}>
                                                    {formatMoney(floor.current_amount)} / {formatMoney(floor.target_amount)}
                                                </span>
                                            </div>
                                            <div className="progress-track" style={{ height: '8px' }}>
                                                <div className="progress-fill-hp" style={{
                                                    width: `${floor.progress}%`,
                                                }}>
                                                    <div className="progress-glint" />
                                                </div>
                                            </div>
                                        </div>
                                    ))
                                ) : (
                                    <div style={{ padding: '32px 16px', textAlign: 'center', color: 'var(--sao-text-muted)' }}>
                                        <span className="material-symbols-outlined" style={{ fontSize: '32px', marginBottom: '8px', display: 'block', opacity: 0.5 }}>castle</span>
                                        <p style={{ fontSize: '0.8rem', fontFamily: "'Hanken Grotesk', sans-serif" }}>Nenhum andar desbloqueado.</p>
                                        <a href="/player/floor-map" className="sao-btn sm" style={{ marginTop: '12px' }}>
                                            <span className="material-symbols-outlined" style={{ fontSize: '14px' }}>explore</span>
                                            Explorar Floor Map
                                        </a>
                                    </div>
                                )}
                            </div>
                        </div>
                    </div>

                    {/* ── Row 4: Daily Quests ── */}
                    <DailyQuests />

                </div>
            </div>

            <TradeModal isOpen={showTradeModal} onClose={() => setShowTradeModal(false)} />
        </PlayerLayout>
    )
}
