import React, { useState, useEffect, useRef } from 'react'
import { useSound } from '../Components/SoundManager'
import axios from 'axios'

// Simple Markdown Renderer (Bold, Italic, List)
const MarkdownRenderer = ({ text }) => {
    if (!text) return null;

    // Split by new lines for lists
    const lines = text.split('\n');
    return (
        <div>
            {lines.map((line, i) => {
                // List item
                if (line.trim().startsWith('- ')) {
                    return <li key={i} style={{ marginLeft: '20px' }} dangerouslySetInnerHTML={{ __html: parseInline(line.substring(2)) }} />
                }
                // Paragraph
                return <div key={i} style={{ minHeight: line.trim() === '' ? '8px' : 'auto' }} dangerouslySetInnerHTML={{ __html: parseInline(line) || '&nbsp;' }} />
            })}
        </div>
    )
}

const parseInline = (text) => {
    let parsed = text
        .replace(/\*\*(.*?)\*\*/g, '<strong>$1</strong>') // Bold
        .replace(/\*(.*?)\*/g, '<em>$1</em>') // Italic
        .replace(/`(.*?)`/g, '<code style="background:rgba(255,255,255,0.1);padding:2px 4px;border-radius:4px">$1</code>') // Code
    return parsed
}

export default function YuiCompanion({ user }) {
    const [isOpen, setIsOpen] = useState(false)
    const [tab, setTab] = useState('chat') // chat, alerts, quests
    const [messages, setMessages] = useState([
        { role: 'yui', text: `Olá, ${user.name}! Sou a Y.U.I., sua assistente de navegação. 🧚‍♀️\n\nComo posso ajudar nas suas finanças hoje?`, time: new Date().toLocaleTimeString().slice(0, 5) }
    ])
    const [input, setInput] = useState('')
    const [loading, setLoading] = useState(false)
    const [status, setStatus] = useState({ alerts: [], active_quest: null })
    const messagesEndRef = useRef(null)
    const { play } = useSound()

    // Quick Actions
    const quickActions = [
        "💰 Meu Saldo",
        "📊 Gastos da semana",
        "🛡️ Criar Quest",
        "💡 Dica do dia"
    ]

    useEffect(() => {
        // Fetch initial status (alerts/quests)
        fetchStatus()

        // Poll status every 60s
        const interval = setInterval(fetchStatus, 60000)
        return () => clearInterval(interval)
    }, [])

    const fetchStatus = () => {
        axios.get('/player/yui/status').then(res => {
            setStatus(res.data)
            if (res.data.alerts.length > 0) {
                play('notification')
            }
        })
    }

    useEffect(() => {
        messagesEndRef.current?.scrollIntoView({ behavior: 'smooth' })
    }, [messages, loading])

    const handleSend = async (msgText = input) => {
        if (!msgText.trim() || loading) return

        const userMsg = { role: 'user', text: msgText, time: new Date().toLocaleTimeString().slice(0, 5) }

        // Optimistic UI
        setMessages(prev => [...prev, userMsg])
        setInput('')
        setLoading(true)
        play('click')

        // Context History (last 10 messages)
        const history = messages.slice(-10).map(m => ({ role: m.role, text: m.text }))

        try {
            const res = await axios.post('/player/yui/chat', { message: msgText, history })
            const replyMsg = { role: 'yui', text: res.data.reply, time: new Date().toLocaleTimeString().slice(0, 5) }
            setMessages(prev => [...prev, replyMsg])
            play('message')
        } catch (err) {
            setMessages(prev => [...prev, { role: 'yui', text: '⚠️ Erro de conexão com o servidor de Aincrad.', time: new Date().toLocaleTimeString().slice(0, 5) }])
            play('damage')
        } finally {
            setLoading(false)
        }
    }

    const toggleOpen = () => {
        play(isOpen ? 'close' : 'open')
        setIsOpen(!isOpen)
    }

    // Quest Completion
    const completeQuest = () => {
        play('confirm')
        // Optimistic update
        setStatus(prev => ({ ...prev, active_quest: { ...prev.active_quest, status: 'completed' } }))
        // In real app, call API
        axios.post('/player/yui/complete-quest').then(res => {
            play('levelUp') // or generic success
            // Show toast/reward
        })
    }

    return (
        <div style={{ position: 'fixed', bottom: '20px', right: '20px', zIndex: 9999, display: 'flex', flexDirection: 'column', alignItems: 'flex-end', pointerEvents: 'none' }}>

            {/* Expanded Panel */}
            {isOpen && (
                <div className="sao-panel yui-panel" style={{
                    width: '360px', height: '520px', marginBottom: '16px',
                    display: 'flex', flexDirection: 'column', padding: 0,
                    overflow: 'hidden', animation: 'fadeInUp 0.3s cubic-bezier(0.18, 0.89, 0.32, 1.28)',
                    pointerEvents: 'auto', border: '1px solid rgba(255,157,0,0.5)',
                    boxShadow: '0 0 40px rgba(0,0,0,0.5), 0 0 10px rgba(255,157,0,0.2)'
                }}>
                    {/* Header */}
                    <div style={{
                        padding: '12px 16px', background: 'rgba(20,20,30,0.95)', borderBottom: '1px solid var(--sao-border-subtle)',
                        display: 'flex', alignItems: 'center', gap: '12px'
                    }}>
                        <div style={{ position: 'relative' }}>
                            <div style={{
                                width: '32px', height: '32px', borderRadius: '50%',
                                background: '#fff', boxShadow: '0 0 15px #fff',
                                display: 'flex', alignItems: 'center', justifyContent: 'center',
                                overflow: 'hidden', animation: 'pulse-avatar 2s infinite'
                            }}>
                                <img src="/images/yui.png" alt="Yui" style={{ width: '100%', height: '100%', objectFit: 'cover' }} />
                            </div>
                            <div style={{
                                position: 'absolute', bottom: -2, right: -2, width: '10px', height: '10px',
                                background: '#4CAF50', borderRadius: '50%', border: '2px solid #1a1a2e'
                            }} />
                        </div>
                        <div style={{ flex: 1 }}>
                            <div style={{ fontFamily: 'Rajdhani', fontWeight: 700, fontSize: '1.1rem', lineHeight: 1 }}>Y.U.I.</div>
                            <div style={{ fontSize: '0.7rem', color: 'var(--sao-text-dim)', textTransform: 'uppercase', letterSpacing: '1px' }}>Navigation Pixie</div>
                        </div>
                        <button onClick={toggleOpen} style={{ background: 'none', border: 'none', color: 'var(--sao-text-dim)', cursor: 'pointer', fontSize: '1.2rem', padding: '4px' }}>✕</button>
                    </div>

                    {/* Tabs */}
                    <div style={{ display: 'flex', borderBottom: '1px solid var(--sao-border-subtle)', background: 'rgba(0,0,0,0.2)' }}>
                        {['chat', 'alerts', 'quests'].map(t => (
                            <button key={t}
                                onClick={() => { setTab(t); play('click') }}
                                style={{
                                    flex: 1, padding: '10px', background: 'none', border: 'none',
                                    color: tab === t ? 'var(--sao-orange)' : 'var(--sao-text-dim)',
                                    fontWeight: 700, fontSize: '0.75rem', textTransform: 'uppercase',
                                    borderBottom: tab === t ? '2px solid var(--sao-orange)' : '2px solid transparent',
                                    transition: 'all 0.2s', cursor: 'pointer'
                                }}>
                                {t === 'alerts' && status.alerts.length > 0 && '⚠️ '}
                                {t}
                                {t === 'quests' && status.active_quest && <span style={{ fontSize: '0.6rem', background: 'var(--sao-orange)', color: '#000', borderRadius: '4px', padding: '0 4px', marginLeft: '4px' }}>!</span>}
                            </button>
                        ))}
                    </div>

                    {/* Content */}
                    <div style={{ flex: 1, overflowY: 'auto', padding: '16px', display: 'flex', flexDirection: 'column', gap: '16px', background: 'rgba(10,10,16,0.8)' }}>

                        {/* CHAT TAB */}
                        {tab === 'chat' && (
                            <>
                                <div style={{ flex: 1, display: 'flex', flexDirection: 'column', gap: '16px' }}>
                                    {messages.map((m, i) => (
                                        <div key={i} style={{
                                            alignSelf: m.role === 'user' ? 'flex-end' : 'flex-start',
                                            maxWidth: '90%', display: 'flex', flexDirection: 'column',
                                            alignItems: m.role === 'user' ? 'flex-end' : 'flex-start'
                                        }}>
                                            <div style={{
                                                background: m.role === 'user' ? 'linear-gradient(135deg, rgba(255, 157, 0, 0.2), rgba(255, 157, 0, 0.1))' : 'rgba(255, 255, 255, 0.08)',
                                                padding: '10px 14px', borderRadius: m.role === 'user' ? '12px 12px 2px 12px' : '12px 12px 12px 2px',
                                                fontSize: '0.9rem', lineHeight: '1.5',
                                                border: m.role === 'user' ? '1px solid rgba(255,157,0,0.3)' : '1px solid var(--sao-border-subtle)',
                                                boxShadow: m.role === 'user' ? '0 2px 8px rgba(0,0,0,0.2)' : 'none'
                                            }}>
                                                <MarkdownRenderer text={m.text} />
                                            </div>
                                            <span style={{ fontSize: '0.65rem', color: 'var(--sao-text-muted)', marginTop: '4px', margin: '0 4px' }}>{m.time}</span>
                                        </div>
                                    ))}

                                    {/* Typing Indicator */}
                                    {loading && (
                                        <div style={{
                                            alignSelf: 'flex-start', background: 'rgba(255, 255, 255, 0.05)',
                                            padding: '8px 12px', borderRadius: '12px 12px 12px 2px',
                                            border: '1px solid var(--sao-border-subtle)', display: 'flex', gap: '4px'
                                        }}>
                                            <span className="typing-dot" style={{ animationDelay: '0s' }}>●</span>
                                            <span className="typing-dot" style={{ animationDelay: '0.2s' }}>●</span>
                                            <span className="typing-dot" style={{ animationDelay: '0.4s' }}>●</span>
                                        </div>
                                    )}
                                    <div ref={messagesEndRef} />
                                </div>

                                {/* Quick Actions */}
                                {!loading && (
                                    <div style={{ display: 'flex', gap: '8px', overflowX: 'auto', paddingBottom: '4px', scrollbarWidth: 'none' }}>
                                        {quickActions.map(action => (
                                            <button key={action}
                                                onClick={() => handleSend(action)}
                                                style={{
                                                    background: 'rgba(255,255,255,0.05)', border: '1px solid var(--sao-border-subtle)',
                                                    borderRadius: '12px', padding: '6px 12px', fontSize: '0.75rem', color: 'var(--sao-text-dim)',
                                                    whiteSpace: 'nowrap', cursor: 'pointer', transition: 'all 0.2s'
                                                }}
                                                className="quick-action-btn"
                                            >
                                                {action}
                                            </button>
                                        ))}
                                    </div>
                                )}

                                {/* Input Area */}
                                <form onSubmit={(e) => { e.preventDefault(); handleSend(); }} style={{ display: 'flex', gap: '8px', marginTop: '4px' }}>
                                    <input
                                        type="text" value={input} onChange={e => setInput(e.target.value)}
                                        placeholder="Pergunte algo..."
                                        style={{
                                            flex: 1, background: 'rgba(0,0,0,0.4)',
                                            border: '1px solid var(--sao-border-subtle)', borderRadius: '8px',
                                            padding: '10px', color: '#fff', fontSize: '0.9rem', outline: 'none'
                                        }}
                                    />
                                    <button type="submit" disabled={loading} className="sao-btn" style={{ padding: '0 14px', borderRadius: '8px' }}>➤</button>
                                </form>
                            </>
                        )}

                        {/* ALERTS TAB */}
                        {tab === 'alerts' && (
                            <div className="animate-fade-in">
                                {status.alerts.length > 0 ? (
                                    status.alerts.map((alert, i) => (
                                        <div key={i} style={{
                                            padding: '16px', background: 'rgba(255, 71, 87, 0.1)',
                                            border: '1px solid var(--sao-danger)', borderRadius: '8px',
                                            boxShadow: '0 0 15px rgba(255, 71, 87, 0.1)'
                                        }}>
                                            <div style={{ display: 'flex', alignItems: 'center', gap: '10px', marginBottom: '8px' }}>
                                                <div style={{ fontSize: '1.5rem', filter: 'drop-shadow(0 0 5px rgba(255,71,87,0.8))' }}>{alert.icon}</div>
                                                <span style={{ fontFamily: 'Rajdhani', fontWeight: 700, fontSize: '1.1rem', color: 'var(--sao-danger)' }}>{alert.title}</span>
                                            </div>
                                            <div style={{ fontSize: '0.9rem', lineHeight: '1.5', color: 'var(--sao-text)' }}>{alert.message}</div>
                                        </div>
                                    ))
                                ) : (
                                    <div style={{ textAlign: 'center', color: 'var(--sao-text-muted)', marginTop: '80px', display: 'flex', flexDirection: 'column', alignItems: 'center', gap: '12px' }}>
                                        <div style={{ fontSize: '3rem', opacity: 0.2 }}>🛡️</div>
                                        <div>Nenhum alerta do sistema.</div>
                                        <div style={{ fontSize: '0.8rem', opacity: 0.6 }}>Todos os sistemas operando normalmente.</div>
                                    </div>
                                )}
                            </div>
                        )}

                        {/* QUESTS TAB */}
                        {tab === 'quests' && (
                            <div className="animate-fade-in">
                                {status.active_quest ? (
                                    <div style={{
                                        padding: '16px', background: 'linear-gradient(135deg, rgba(255, 157, 0, 0.15), rgba(0,0,0,0.2))',
                                        border: '1px solid var(--sao-orange)', borderRadius: '8px',
                                        position: 'relative', overflow: 'hidden'
                                    }}>
                                        {status.active_quest.status === 'completed' && (
                                            <div style={{
                                                position: 'absolute', inset: 0, background: 'rgba(0,0,0,0.7)',
                                                display: 'flex', alignItems: 'center', justifyContent: 'center',
                                                zIndex: 10, flexDirection: 'column', gap: '8px'
                                            }}>
                                                <div style={{ fontSize: '2rem' }}>🎉</div>
                                                <div style={{ fontWeight: 700, color: 'var(--sao-success)' }}>QUEST COMPLETE!</div>
                                            </div>
                                        )}
                                        <div style={{
                                            textTransform: 'uppercase', fontSize: '0.7rem', color: 'var(--sao-orange)',
                                            fontWeight: 700, marginBottom: '12px', letterSpacing: '2px', display: 'flex', justifyContent: 'space-between'
                                        }}>
                                            <span>Active Daily Quest</span>
                                            <span>Time Remaining: 14h</span>
                                        </div>
                                        <div style={{ display: 'flex', gap: '16px' }}>
                                            <div style={{
                                                fontSize: '2.5rem', background: 'var(--sao-dark-elevated)',
                                                width: '64px', height: '64px', display: 'flex', alignItems: 'center', justifyContent: 'center',
                                                borderRadius: '12px', border: '1px solid var(--sao-border)'
                                            }}>{status.active_quest.icon}</div>
                                            <div style={{ flex: 1 }}>
                                                <div style={{ fontFamily: 'Rajdhani', fontWeight: 700, fontSize: '1.2rem', marginBottom: '4px' }}>{status.active_quest.title}</div>
                                                <div style={{ fontSize: '0.9rem', color: 'var(--sao-text-dim)', marginBottom: '12px', lineHeight: '1.4' }}>{status.active_quest.description}</div>

                                                <div style={{
                                                    background: 'rgba(0,0,0,0.3)', padding: '4px 8px', borderRadius: '4px',
                                                    display: 'inline-flex', alignItems: 'center', gap: '6px', border: '1px solid rgba(118, 255, 3, 0.3)'
                                                }}>
                                                    <span style={{ fontSize: '0.8rem' }}>Reward:</span>
                                                    <span style={{ color: '#76FF03', fontWeight: 700 }}>{status.active_quest.reward_xp} XP</span>
                                                </div>
                                            </div>
                                        </div>

                                        <button
                                            onClick={completeQuest}
                                            disabled={status.active_quest.status === 'completed'}
                                            style={{
                                                width: '100%', marginTop: '16px', padding: '10px',
                                                background: 'var(--sao-orange)', border: 'none', borderRadius: '6px',
                                                color: '#000', fontWeight: 700, textTransform: 'uppercase', letterSpacing: '1px',
                                                cursor: 'pointer', transition: 'all 0.2s',
                                                opacity: status.active_quest.status === 'completed' ? 0.5 : 1
                                            }}
                                        >
                                            {status.active_quest.status === 'completed' ? 'Completed' : 'Submit Quest'}
                                        </button>
                                    </div>
                                ) : (
                                    <div style={{ textAlign: 'center', color: 'var(--sao-text-muted)', marginTop: '80px' }}>
                                        <div style={{ fontSize: '3rem', opacity: 0.2 }}>📜</div>
                                        <div style={{ marginTop: '12px' }}>Nenhuma quest ativa.</div>
                                        <button className="sao-btn outline sm" style={{ marginTop: '16px' }} onClick={() => handleSend("Criar quest")}>Gerar Quest</button>
                                    </div>
                                )}
                            </div>
                        )}

                    </div>
                </div>
            )}

            {/* Minimized Orb */}
            {!isOpen && (
                <button
                    onClick={toggleOpen}
                    style={{
                        width: '64px', height: '64px', borderRadius: '50%',
                        background: 'radial-gradient(circle at 30% 30%, #fff, #a0d8ef, #0099ff)',
                        boxShadow: '0 0 20px rgba(0, 153, 255, 0.6), inset 0 0 10px rgba(255,255,255,0.8)',
                        border: '2px solid rgba(255,255,255,0.8)', cursor: 'pointer', pointerEvents: 'auto',
                        display: 'flex', alignItems: 'center', justifyContent: 'center',
                        fontSize: '2rem', animation: 'float 4s ease-in-out infinite', overflow: 'hidden'
                    }}
                >
                    <img src="/images/yui.png" alt="Yui" style={{ width: '100%', height: '100%', objectFit: 'cover', borderRadius: '50%' }} />
                    {(status.alerts.length > 0 || status.active_quest) && (
                        <div style={{
                            position: 'absolute', top: 0, right: 0, width: '18px', height: '18px',
                            background: '#ff4444', borderRadius: '50%', border: '2px solid #fff',
                            display: 'flex', alignItems: 'center', justifyContent: 'center', fontSize: '0.6rem', fontWeight: 700, color: '#fff'
                        }}>
                            {status.alerts.length + (status.active_quest ? 1 : 0)}
                        </div>
                    )}
                </button>
            )}

            <style>{`
                .typing-dot {
                    animation: typing 1.4s infinite ease-in-out both;
                    font-size: 0.6rem;
                    color: var(--sao-text-dim);
                }
                @keyframes typing {
                    0%, 80%, 100% { transform: scale(0); }
                    40% { transform: scale(1); }
                }
                @keyframes pulse-avatar {
                    0% { box-shadow: 0 0 10px #fff; }
                    50% { box-shadow: 0 0 20px #fff, 0 0 40px rgba(0,153,255,0.5); }
                    100% { box-shadow: 0 0 10px #fff; }
                }
                .quick-action-btn:hover {
                    background: rgba(255,255,255,0.1) !important;
                    border-color: var(--sao-orange) !important;
                    color: var(--sao-orange) !important;
                }
            `}</style>
        </div>
    )
}
