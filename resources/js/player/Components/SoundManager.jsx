import React, { createContext, useContext, useCallback, useRef, useState } from 'react'

const SoundContext = createContext(null)

// All sounds are synthesized via Web Audio API — no external files needed
const SOUNDS = {
    click: { type: 'sine', freq: 800, duration: 0.08, volume: 0.15 },
    hover: { type: 'sine', freq: 600, duration: 0.04, volume: 0.06 },
    open: { type: 'sine', freq: [400, 600, 800], duration: 0.15, volume: 0.12 },
    close: { type: 'sine', freq: [800, 600, 400], duration: 0.12, volume: 0.1 },
    confirm: { type: 'sine', freq: [523, 659, 784], duration: 0.2, volume: 0.15 },
    loot: { type: 'sine', freq: [523, 659, 784, 1047], duration: 0.35, volume: 0.18 },
    damage: { type: 'sawtooth', freq: [200, 150], duration: 0.25, volume: 0.12 },
    levelUp: { type: 'sine', freq: [523, 659, 784, 1047, 1319], duration: 0.6, volume: 0.2 },
    notification: { type: 'triangle', freq: [880, 1100], duration: 0.15, volume: 0.12 },
    floorCleared: { type: 'sine', freq: [523, 659, 784, 1047, 784, 1047, 1319], duration: 0.8, volume: 0.2 },
    error: { type: 'square', freq: [300, 200], duration: 0.2, volume: 0.1 },
}

function playSound(audioCtx, soundDef) {
    if (!audioCtx) return

    const { type, freq, duration, volume } = soundDef
    const frequencies = Array.isArray(freq) ? freq : [freq]
    const stepDuration = duration / frequencies.length

    frequencies.forEach((f, i) => {
        const osc = audioCtx.createOscillator()
        const gain = audioCtx.createGain()

        osc.type = type
        osc.frequency.value = f
        osc.connect(gain)
        gain.connect(audioCtx.destination)

        const startTime = audioCtx.currentTime + i * stepDuration
        gain.gain.setValueAtTime(volume, startTime)
        gain.gain.exponentialRampToValueAtTime(0.001, startTime + stepDuration)

        osc.start(startTime)
        osc.stop(startTime + stepDuration + 0.05)
    })
}

export function SoundProvider({ children }) {
    const audioCtxRef = useRef(null)
    const [enabled, setEnabled] = useState(() => {
        if (typeof window !== 'undefined') {
            return localStorage.getItem('sao-sound') !== 'off'
        }
        return true
    })

    const getAudioCtx = useCallback(() => {
        if (!audioCtxRef.current) {
            audioCtxRef.current = new (window.AudioContext || window.webkitAudioContext)()
        }
        if (audioCtxRef.current.state === 'suspended') {
            audioCtxRef.current.resume()
        }
        return audioCtxRef.current
    }, [])

    const play = useCallback((soundName) => {
        if (!enabled) return
        const def = SOUNDS[soundName]
        if (!def) return
        try {
            playSound(getAudioCtx(), def)
        } catch (e) {
            // Silently fail — audio isn't critical
        }
    }, [enabled, getAudioCtx])

    const toggle = useCallback(() => {
        setEnabled(prev => {
            const next = !prev
            localStorage.setItem('sao-sound', next ? 'on' : 'off')
            return next
        })
    }, [])

    return (
        <SoundContext.Provider value={{ play, enabled, toggle }}>
            {children}
        </SoundContext.Provider>
    )
}

export function useSound() {
    const ctx = useContext(SoundContext)
    if (!ctx) return { play: () => { }, enabled: false, toggle: () => { } }
    return ctx
}

// HOC for adding click sound to any element
export function SoundButton({ children, sound = 'click', onClick, ...props }) {
    const { play } = useSound()

    const handleClick = (e) => {
        play(sound)
        onClick?.(e)
    }

    return (
        <button {...props} onClick={handleClick}>
            {children}
        </button>
    )
}
