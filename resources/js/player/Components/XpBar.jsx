import React from 'react'

export default function XpBar({ progress = 0, currentLevel = 1, xpRemaining = 0 }) {
    return (
        <div className="hud-bar-group">
            <div className="hud-bar-label">
                <span className="label-name">EXP</span>
                <span className="label-value" style={{ color: 'var(--sao-info)' }}>
                    LV.{currentLevel} — {xpRemaining} XP to next
                </span>
            </div>
            <div className="bar-container">
                <div
                    className="bar-fill xp"
                    style={{ width: `${Math.min(100, Math.max(0, progress))}%` }}
                />
            </div>
        </div>
    )
}
