import React from 'react'

export default function HpBar({ percentage = 100, label = 'HP', showValue = true, valueText = '' }) {
    const getBarGradient = (pct) => {
        if (pct > 60) return 'var(--sao-hp-full)'
        if (pct > 30) return 'var(--sao-hp-mid)'
        return 'var(--sao-hp-low)'
    }

    const getGlow = (pct) => {
        if (pct > 60) return '0 0 10px var(--sao-success-glow), 0 0 25px rgba(118, 255, 3, 0.2)'
        if (pct > 30) return '0 0 10px rgba(243, 156, 18, 0.5)'
        return '0 0 10px var(--sao-danger-glow)'
    }

    return (
        <div className="hud-bar-group">
            <div className="hud-bar-label">
                <span className="label-name">{label}</span>
                {showValue && (
                    <span className="label-value" style={{ color: percentage > 60 ? 'var(--sao-success)' : percentage > 30 ? 'var(--sao-warning)' : 'var(--sao-danger)' }}>
                        {valueText || `${percentage}%`}
                    </span>
                )}
            </div>
            <div className="bar-container">
                <div
                    className="bar-fill hp"
                    style={{
                        width: `${Math.min(100, Math.max(0, percentage))}%`,
                        background: getBarGradient(percentage),
                        boxShadow: getGlow(percentage),
                    }}
                />
            </div>
        </div>
    )
}
