import React, { useState } from 'react'
import { router } from '@inertiajs/react'
import PlayerLayout from '../Layouts/PlayerLayout'
import SaoPanel from '../Components/SaoPanel'
import { useSound } from '../Components/SoundManager'

const ROLE_BADGES = {
    master: { label: 'Guild Master', color: '#FF9D00', icon: '👑' },
    officer: { label: 'Officer', color: '#3498db', icon: '🛡️' },
    member: { label: 'Member', color: '#8a8a9a', icon: '⚔️' },
}

function MemberRow({ member, rank }) {
    const role = ROLE_BADGES[member.role] || ROLE_BADGES.member
    const isTop3 = rank <= 3
    const rankColors = { 1: '#FF9D00', 2: '#C0C0C0', 3: '#CD7F32' }

    return (
        <div style={{
            display: 'flex', alignItems: 'center', gap: '12px',
            padding: '10px 14px', borderRadius: '8px',
            background: isTop3 ? `${rankColors[rank]}10` : 'transparent',
            borderLeft: isTop3 ? `3px solid ${rankColors[rank]}` : '3px solid transparent',
        }}>
            {/* Rank */}
            <div style={{
                width: '28px', textAlign: 'center',
                fontFamily: 'Rajdhani, sans-serif', fontWeight: 700,
                fontSize: isTop3 ? '1.2rem' : '0.9rem',
                color: rankColors[rank] || 'var(--sao-text-dim)',
            }}>
                {rank <= 3 ? ['🥇', '🥈', '🥉'][rank - 1] : `#${rank}`}
            </div>

            {/* Avatar */}
            <div style={{
                width: '36px', height: '36px', borderRadius: '50%',
                background: 'var(--sao-glass)', border: `2px solid ${role.color}`,
                display: 'flex', alignItems: 'center', justifyContent: 'center',
                fontWeight: 700, fontSize: '0.85rem', color: role.color,
            }}>
                {member.name?.[0]?.toUpperCase() || '?'}
            </div>

            {/* Info */}
            <div style={{ flex: 1 }}>
                <div style={{ display: 'flex', alignItems: 'center', gap: '6px' }}>
                    <span style={{ fontWeight: 600, color: 'var(--sao-text)' }}>{member.name}</span>
                    <span style={{ fontSize: '0.6rem', color: role.color, fontWeight: 700 }}>
                        {role.icon} {role.label}
                    </span>
                </div>
                <div style={{ fontSize: '0.7rem', color: 'var(--sao-text-dim)' }}>
                    LV. {member.level} • {member.xp.toLocaleString()} XP
                </div>
            </div>

            {/* Level badge */}
            <div style={{
                fontFamily: 'Rajdhani, sans-serif', fontWeight: 700,
                fontSize: '1.1rem', color: 'var(--sao-text)',
            }}>
                LV.{member.level}
            </div>
        </div>
    )
}

function GuildCard({ guild, onLeave }) {
    const { play } = useSound()
    const [showMembers, setShowMembers] = useState(false)
    const [copied, setCopied] = useState(false)

    const copyCode = () => {
        navigator.clipboard.writeText(guild.invite_code)
        setCopied(true)
        play('confirm')
        setTimeout(() => setCopied(false), 2000)
    }

    return (
        <SaoPanel>
            {/* Header */}
            <div style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'flex-start', marginBottom: '16px' }}>
                <div style={{ display: 'flex', alignItems: 'center', gap: '10px' }}>
                    <span style={{ fontSize: '2rem' }}>{guild.icon}</span>
                    <div>
                        <h3 style={{
                            margin: 0, fontFamily: 'Rajdhani, sans-serif', fontWeight: 700,
                            color: 'var(--sao-text)', fontSize: '1.2rem',
                        }}>
                            {guild.name}
                        </h3>
                        <div style={{ fontSize: '0.7rem', color: 'var(--sao-text-dim)' }}>
                            Master: {guild.master.name} • {guild.member_count} membros
                        </div>
                    </div>
                </div>
                {guild.is_master && (
                    <span style={{
                        fontSize: '0.6rem', fontWeight: 700, color: '#FF9D00',
                        background: 'rgba(255, 157, 0, 0.1)', padding: '2px 8px',
                        borderRadius: '4px', letterSpacing: '0.1em',
                    }}>
                        👑 MASTER
                    </span>
                )}
            </div>

            {guild.description && (
                <p style={{ fontSize: '0.8rem', color: 'var(--sao-text-dim)', margin: '0 0 12px 0' }}>
                    {guild.description}
                </p>
            )}

            {/* Stats */}
            <div style={{ display: 'grid', gridTemplateColumns: '1fr 1fr 1fr', gap: '8px', marginBottom: '14px' }}>
                <div style={{ textAlign: 'center', padding: '8px', borderRadius: '8px', background: 'rgba(255, 255, 255, 0.03)' }}>
                    <div style={{ fontSize: '0.6rem', color: 'var(--sao-text-dim)', textTransform: 'uppercase' }}>Members</div>
                    <div style={{ fontSize: '1.3rem', fontWeight: 700, fontFamily: 'Rajdhani, sans-serif' }}>{guild.member_count}</div>
                </div>
                <div style={{ textAlign: 'center', padding: '8px', borderRadius: '8px', background: 'rgba(255, 255, 255, 0.03)' }}>
                    <div style={{ fontSize: '0.6rem', color: 'var(--sao-text-dim)', textTransform: 'uppercase' }}>Total XP</div>
                    <div style={{ fontSize: '1.3rem', fontWeight: 700, fontFamily: 'Rajdhani, sans-serif', color: '#FF9D00' }}>
                        {guild.total_xp.toLocaleString()}
                    </div>
                </div>
                <div style={{ textAlign: 'center', padding: '8px', borderRadius: '8px', background: 'rgba(255, 255, 255, 0.03)' }}>
                    <div style={{ fontSize: '0.6rem', color: 'var(--sao-text-dim)', textTransform: 'uppercase' }}>Avg Level</div>
                    <div style={{ fontSize: '1.3rem', fontWeight: 700, fontFamily: 'Rajdhani, sans-serif', color: '#76FF03' }}>
                        {guild.avg_level}
                    </div>
                </div>
            </div>

            {/* Invite Code */}
            <div style={{
                display: 'flex', alignItems: 'center', gap: '8px',
                padding: '8px 12px', borderRadius: '8px',
                background: 'rgba(255, 255, 255, 0.03)',
                border: '1px dashed var(--sao-border-subtle)',
                marginBottom: '12px',
            }}>
                <span style={{ fontSize: '0.7rem', color: 'var(--sao-text-dim)' }}>Invite Code:</span>
                <code style={{
                    fontFamily: 'monospace', fontWeight: 700, letterSpacing: '0.15em',
                    color: '#FF9D00', fontSize: '0.9rem',
                }}>
                    {guild.invite_code}
                </code>
                <button
                    onClick={copyCode}
                    style={{
                        marginLeft: 'auto', background: 'none', border: 'none',
                        color: copied ? '#76FF03' : 'var(--sao-text-dim)',
                        cursor: 'pointer', fontSize: '0.75rem',
                    }}
                >
                    {copied ? '✓ Copiado' : '📋 Copiar'}
                </button>
            </div>

            {/* Member list toggle */}
            <button
                className="sao-btn outline"
                onClick={() => { play('click'); setShowMembers(!showMembers) }}
                style={{ width: '100%', justifyContent: 'center', fontSize: '0.75rem', marginBottom: showMembers ? '10px' : '0' }}
            >
                {showMembers ? '▲ Esconder Ranking' : '▼ Ver Ranking'}
            </button>

            {showMembers && (
                <div style={{ display: 'flex', flexDirection: 'column', gap: '4px', marginTop: '6px' }}>
                    {guild.members.map((m, i) => (
                        <MemberRow key={m.id} member={m} rank={i + 1} />
                    ))}
                </div>
            )}

            {/* Leave */}
            <button
                className="sao-btn outline"
                onClick={() => onLeave(guild)}
                style={{
                    width: '100%', justifyContent: 'center', fontSize: '0.7rem',
                    marginTop: '10px', color: '#ff4444', borderColor: '#ff444430',
                }}
            >
                {guild.is_master ? '🗑️ Dissolver Guild' : '🚪 Sair da Guild'}
            </button>
        </SaoPanel>
    )
}

function CreateGuildModal({ isOpen, onClose }) {
    const { play } = useSound()
    const [form, setForm] = useState({ name: '', icon: '⚔️', description: '' })
    const [submitting, setSubmitting] = useState(false)

    if (!isOpen) return null

    const icons = ['⚔️', '🛡️', '🏰', '🐉', '🦅', '🔥', '💀', '👑', '🌟', '⚡', '🎯', '🗡️']

    const handleSubmit = (e) => {
        e.preventDefault()
        setSubmitting(true)
        router.post('/player/guild', form, {
            onSuccess: () => { play('loot'); onClose(); setForm({ name: '', icon: '⚔️', description: '' }) },
            onFinish: () => setSubmitting(false),
        })
    }

    return (
        <div className="modal-overlay" onClick={(e) => { if (e.target === e.currentTarget) { play('close'); onClose() } }}>
            <div className="modal-content" style={{ maxWidth: '420px' }}>
                <h2 className="sao-title">
                    <span className="bracket">「</span>CREATE GUILD<span className="bracket">」</span>
                </h2>

                <form onSubmit={handleSubmit}>
                    <div className="form-group">
                        <label>Guild Name</label>
                        <input type="text" value={form.name} maxLength={30}
                            onChange={e => setForm(prev => ({ ...prev, name: e.target.value }))}
                            placeholder="Ex: Knights of the Blood..." required />
                    </div>

                    <div className="form-group">
                        <label>Guild Icon</label>
                        <div style={{ display: 'flex', flexWrap: 'wrap', gap: '6px' }}>
                            {icons.map(icon => (
                                <button type="button" key={icon}
                                    onClick={() => { play('click'); setForm(prev => ({ ...prev, icon })) }}
                                    style={{
                                        width: '40px', height: '40px', borderRadius: '8px',
                                        fontSize: '1.3rem', cursor: 'pointer',
                                        background: form.icon === icon ? 'rgba(255, 157, 0, 0.2)' : 'var(--sao-glass)',
                                        border: form.icon === icon ? '2px solid #FF9D00' : '1px solid var(--sao-border-subtle)',
                                        display: 'flex', alignItems: 'center', justifyContent: 'center',
                                    }}>
                                    {icon}
                                </button>
                            ))}
                        </div>
                    </div>

                    <div className="form-group">
                        <label>Description</label>
                        <textarea rows={2} value={form.description} maxLength={200}
                            onChange={e => setForm(prev => ({ ...prev, description: e.target.value }))}
                            placeholder="Objetivo ou descrição da guild..." />
                    </div>

                    <div style={{ display: 'flex', gap: '10px', marginTop: '20px' }}>
                        <button type="submit" className="sao-btn" disabled={submitting}
                            style={{ flex: 1, justifyContent: 'center' }}>
                            {submitting ? '⏳ CREATING...' : '⊕ CREATE GUILD'}
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

function JoinGuildModal({ isOpen, onClose }) {
    const { play } = useSound()
    const [code, setCode] = useState('')
    const [submitting, setSubmitting] = useState(false)

    if (!isOpen) return null

    const handleSubmit = (e) => {
        e.preventDefault()
        setSubmitting(true)
        router.post('/player/guild/join', { invite_code: code.toUpperCase() }, {
            onSuccess: () => { play('confirm'); onClose(); setCode('') },
            onFinish: () => setSubmitting(false),
        })
    }

    return (
        <div className="modal-overlay" onClick={(e) => { if (e.target === e.currentTarget) { play('close'); onClose() } }}>
            <div className="modal-content" style={{ maxWidth: '380px' }}>
                <h2 className="sao-title">
                    <span className="bracket">「</span>JOIN GUILD<span className="bracket">」</span>
                </h2>

                <form onSubmit={handleSubmit}>
                    <div className="form-group">
                        <label>Invite Code</label>
                        <input type="text" value={code} maxLength={8}
                            onChange={e => setCode(e.target.value.toUpperCase())}
                            placeholder="Ex: A1B2C3D4" required
                            style={{ textAlign: 'center', letterSpacing: '0.2em', fontFamily: 'monospace', fontSize: '1.2rem' }} />
                    </div>

                    <div style={{ display: 'flex', gap: '10px', marginTop: '20px' }}>
                        <button type="submit" className="sao-btn" disabled={submitting || code.length < 8}
                            style={{ flex: 1, justifyContent: 'center' }}>
                            {submitting ? '⏳ JOINING...' : '⊕ JOIN GUILD'}
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

export default function Guild({ guilds }) {
    const [showCreate, setShowCreate] = useState(false)
    const [showJoin, setShowJoin] = useState(false)
    const { play } = useSound()

    const handleLeave = (guild) => {
        const msg = guild.is_master
            ? `Dissolver a guild "${guild.name}"? Todos os membros serão removidos.`
            : `Sair da guild "${guild.name}"?`
        if (confirm(msg)) {
            play('damage')
            router.delete(`/player/guild/${guild.id}/leave`)
        }
    }

    return (
        <PlayerLayout>
            <div className="page-content">
                {/* Header */}
                <div style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'center', marginBottom: '20px', flexWrap: 'wrap', gap: '10px' }}>
                    <h1 className="sao-title" style={{ margin: 0 }}>
                        <span className="bracket">「</span>GUILDS<span className="bracket">」</span>
                    </h1>
                    <div style={{ display: 'flex', gap: '8px' }}>
                        <button className="sao-btn outline" onClick={() => { play('open'); setShowJoin(true) }}>
                            🔑 Join Guild
                        </button>
                        <button className="sao-btn" onClick={() => { play('open'); setShowCreate(true) }}>
                            ⊕ Create Guild
                        </button>
                    </div>
                </div>

                {/* Guild List */}
                {guilds.length > 0 ? (
                    <div style={{ display: 'grid', gridTemplateColumns: 'repeat(auto-fill, minmax(380px, 1fr))', gap: '16px' }}>
                        {guilds.map(guild => (
                            <GuildCard key={guild.id} guild={guild} onLeave={handleLeave} />
                        ))}
                    </div>
                ) : (
                    <SaoPanel>
                        <div style={{ textAlign: 'center', padding: '50px 20px' }}>
                            <div style={{ fontSize: '3rem', marginBottom: '12px' }}>🏰</div>
                            <h3 style={{ fontFamily: 'Rajdhani, sans-serif', fontWeight: 700, color: 'var(--sao-text)', margin: '0 0 8px 0' }}>
                                Nenhuma Guild
                            </h3>
                            <p style={{ fontSize: '0.8rem', color: 'var(--sao-text-dim)', margin: '0 0 20px 0' }}>
                                Crie sua própria guild ou entre em uma usando um código de convite.
                            </p>
                            <div style={{ display: 'flex', gap: '10px', justifyContent: 'center' }}>
                                <button className="sao-btn outline" onClick={() => { play('open'); setShowJoin(true) }}>
                                    🔑 Tenho um Código
                                </button>
                                <button className="sao-btn" onClick={() => { play('open'); setShowCreate(true) }}>
                                    ⊕ Criar Guild
                                </button>
                            </div>
                        </div>
                    </SaoPanel>
                )}

                {/* Modals */}
                <CreateGuildModal isOpen={showCreate} onClose={() => setShowCreate(false)} />
                <JoinGuildModal isOpen={showJoin} onClose={() => setShowJoin(false)} />
            </div>
        </PlayerLayout>
    )
}
