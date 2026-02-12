import preset from './vendor/filament/support/tailwind.config.preset'

export default {
    presets: [preset],
    content: [
        './app/Filament/**/*.php',
        './resources/views/filament/**/*.blade.php',
        './vendor/filament/**/*.blade.php',
        './filament-sql-field/resources/**/*.blade.php',
        './resources/views/landing.blade.php',
    ],
    theme: {
        extend: {
            colors: {
                'hud': {
                    'dark': '#110f1a',
                    'panel': '#1a1628',
                    'surface': '#241f35',
                },
                'neon': {
                    'cyan': '#FF7B00',
                    'blue': '#FFAA33',
                },
                'rpg': {
                    'gold': '#FFD700',
                    'red': '#ef4444',
                    'green': '#22c55e',
                },
            },
            fontFamily: {
                'display': ['Orbitron', 'sans-serif'],
                'heading': ['Rajdhani', 'sans-serif'],
                'body': ['Inter', 'sans-serif'],
                'mono': ['Share Tech Mono', 'VT323', 'monospace'],
                'console': ['VT323', 'monospace'],
            },
            animation: {
                'neon-pulse': 'neon-pulse 2s ease-in-out infinite',
                'float': 'float 6s ease-in-out infinite',
                'glow-line': 'glow-line 3s ease-in-out infinite',
            },
            keyframes: {
                'neon-pulse': {
                    '0%, 100%': {
                        boxShadow: '0 0 15px rgba(255,123,0,0.4), 0 0 40px rgba(255,123,0,0.15)',
                    },
                    '50%': {
                        boxShadow: '0 0 25px rgba(255,123,0,0.6), 0 0 60px rgba(255,123,0,0.25)',
                    },
                },
                'float': {
                    '0%, 100%': { transform: 'translateY(0)' },
                    '50%': { transform: 'translateY(-10px)' },
                },
                'glow-line': {
                    '0%, 100%': { opacity: '0.3' },
                    '50%': { opacity: '0.8' },
                },
            },
        },
    },
}