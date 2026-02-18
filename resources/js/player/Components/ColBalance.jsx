import React from 'react'
import { usePage } from '@inertiajs/react'

export default function ColBalance({ col }) {
    const displayCol = col ?? usePage().props.auth?.user?.col ?? 0

    return (
        <div style={{
            display: 'flex',
            alignItems: 'center',
            gap: '6px',
            padding: '6px 14px',
            background: 'rgba(255, 157, 0, 0.08)',
            border: '1px solid rgba(255, 157, 0, 0.25)',
            borderRadius: '999px',
            fontSize: '0.8rem',
            fontWeight: 700,
            color: '#FF9D00',
            fontVariantNumeric: 'tabular-nums',
            letterSpacing: '0.03em',
            whiteSpace: 'nowrap',
        }}>
            <span style={{ fontSize: '1rem' }}>🪙</span>
            <span>{displayCol.toLocaleString('pt-BR')}</span>
            <span style={{ fontSize: '0.65rem', color: '#FFB347', fontWeight: 500 }}>COL</span>
        </div>
    )
}
