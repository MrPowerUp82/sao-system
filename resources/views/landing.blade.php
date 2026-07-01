<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script>
        (function () {
            const theme = localStorage.getItem('theme') || 'light';
            if (theme === 'dark') {
                document.documentElement.classList.add('dark');
            } else {
                document.documentElement.classList.remove('dark');
            }
        })();

        function toggleTheme() {
            const doc = document.documentElement;
            const isDark = doc.classList.contains('dark');
            const themeIcons = document.querySelectorAll('.theme-icon');
            
            if (isDark) {
                doc.classList.remove('dark');
                localStorage.setItem('theme', 'light');
                themeIcons.forEach(icon => icon.textContent = 'dark_mode');
            } else {
                doc.classList.add('dark');
                localStorage.setItem('theme', 'dark');
                themeIcons.forEach(icon => icon.textContent = 'light_mode');
            }
        }

        // Set correct icon on load
        document.addEventListener('DOMContentLoaded', () => {
            const isDark = document.documentElement.classList.contains('dark');
            const themeIcons = document.querySelectorAll('.theme-icon');
            themeIcons.forEach(icon => {
                icon.textContent = isDark ? 'light_mode' : 'dark_mode';
            });
        });
    </script>
    <title>SAO System — Interface de Aincrad</title>
    <meta name="description"
        content="O sistema que vai transformar sua vida. Desbloqueie seu potencial com módulos de treinamento estilo RPG.">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Sora:wght@400;600;700;800&family=Hanken+Grotesk:wght@400;500;600&family=JetBrains+Mono:wght@700&family=Rajdhani:wght@400;500;600;700&display=swap"
        rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap"
        rel="stylesheet">

    @vite(['resources/css/landing.css', 'resources/js/landing.js'])
</head>

<body class="antialiased bg-grid">

    {{-- ══════════════════════════════════════════════════════
     NERVEGEAR BOOT SEQUENCE — Fullscreen Overlay
     ══════════════════════════════════════════════════════ --}}
    <div id="nervegear-overlay">
        <div id="ng-content">
            {{-- Phase 1: Boot --}}
            <div id="ng-boot" class="ng-phase">
                <div class="ng-line" data-delay="300">NerveGear Online</div>
                <div class="ng-line" data-delay="900">Hardware Check.............. <span class="ng-ok">OK</span></div>
                <div class="ng-line" data-delay="1500">Network Connection.......... <span class="ng-ok">OK</span></div>
                <div class="ng-line" data-delay="2100">Consciousness Transfer...... <span class="ng-ok">OK</span></div>
            </div>

            {{-- Phase 2: Calibration --}}
            <div id="ng-calibrate" class="ng-phase">
                <div class="ng-center-text">
                    <p class="ng-label">— LANGUAGE SELECTION —</p>
                    <p class="ng-value">PORTUGUÊS (BR)</p>
                </div>
            </div>

            <div id="ng-senses" class="ng-phase">
                <div class="ng-sense-grid">
                    <div class="ng-sense" data-delay="0">
                        <span class="ng-sense-icon">👁️</span>
                        <span class="ng-sense-label">SIGHT</span>
                        <div class="ng-sense-bar">
                            <div class="ng-sense-fill"></div>
                        </div>
                    </div>
                    <div class="ng-sense" data-delay="400">
                        <span class="ng-sense-icon">👂</span>
                        <span class="ng-sense-label">HEARING</span>
                        <div class="ng-sense-bar">
                            <div class="ng-sense-fill"></div>
                        </div>
                    </div>
                    <div class="ng-sense" data-delay="800">
                        <span class="ng-sense-icon">✋</span>
                        <span class="ng-sense-label">TOUCH</span>
                        <div class="ng-sense-bar">
                            <div class="ng-sense-fill"></div>
                        </div>
                    </div>
                    <div class="ng-sense" data-delay="1200">
                        <span class="ng-sense-icon">👅</span>
                        <span class="ng-sense-label">TASTE</span>
                        <div class="ng-sense-bar">
                            <div class="ng-sense-fill"></div>
                        </div>
                    </div>
                    <div class="ng-sense" data-delay="1600">
                        <span class="ng-sense-icon">👃</span>
                        <span class="ng-sense-label">SMELL</span>
                        <div class="ng-sense-bar">
                            <div class="ng-sense-fill"></div>
                        </div>
                    </div>
                </div>
                <p class="ng-sub-text">Calibrating sensory input...</p>
            </div>

            {{-- Phase 3: LINK START --}}
            <div id="ng-linkstart" class="ng-phase">
                <div class="ng-link-container">
                    <div class="ng-link-text">
                        <span>L</span><span>I</span><span>N</span><span>K</span>
                        <span class="ng-space"></span>
                        <span>S</span><span>T</span><span>A</span><span>R</span><span>T</span>
                    </div>
                </div>
            </div>

            {{-- Phase 4: White flash + fade --}}
            <div id="ng-flash"></div>
        </div>

        <button id="ng-skip" onclick="skipNerveGear()">SKIP ▸</button>
    </div>

    <style>
        /* ═══════════════════════════════════════════════ */
        /* NERVEGEAR BOOT SEQUENCE STYLES                 */
        /* ═══════════════════════════════════════════════ */
        #nervegear-overlay {
            position: fixed;
            inset: 0;
            z-index: 99999;
            background: #000;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: opacity 0.8s ease;
        }

        #nervegear-overlay.ng-done {
            opacity: 0;
            pointer-events: none;
        }

        #ng-content {
            position: relative;
            width: 100%;
            max-width: 600px;
            padding: 20px;
        }

        .ng-phase {
            display: none;
            opacity: 0;
            transition: opacity 0.5s ease;
        }

        .ng-phase.active {
            display: block;
            opacity: 1;
        }

        /* Boot Lines */
        .ng-line {
            font-family: 'JetBrains Mono', 'Courier New', monospace;
            font-size: 14px;
            color: #0a0;
            margin: 6px 0;
            opacity: 0;
            transform: translateX(-10px);
            transition: all 0.4s ease;
        }

        .ng-line.show {
            opacity: 1;
            transform: translateX(0);
        }

        .ng-ok {
            color: #0f0;
            font-weight: 700;
        }

        /* Calibrate */
        .ng-center-text {
            text-align: center;
        }

        .ng-label {
            font-family: 'JetBrains Mono', 'Rajdhani', sans-serif;
            font-size: 13px;
            color: #666;
            letter-spacing: 0.2em;
            margin-bottom: 8px;
        }

        .ng-value {
            font-family: 'Sora', 'Rajdhani', sans-serif;
            font-size: 28px;
            font-weight: 700;
            color: #fff;
            text-shadow: 0 0 20px rgba(255, 255, 255, 0.3);
        }

        /* Senses */
        .ng-sense-grid {
            display: flex;
            flex-direction: column;
            gap: 14px;
            max-width: 350px;
            margin: 0 auto;
        }

        .ng-sense {
            display: flex;
            align-items: center;
            gap: 12px;
            opacity: 0;
            transform: translateY(8px);
            transition: all 0.5s ease;
        }

        .ng-sense.show {
            opacity: 1;
            transform: translateY(0);
        }

        .ng-sense-icon {
            font-size: 20px;
            width: 28px;
            text-align: center;
        }

        .ng-sense-label {
            font-family: 'JetBrains Mono', 'Rajdhani', sans-serif;
            font-size: 12px;
            font-weight: 700;
            color: #888;
            letter-spacing: 0.15em;
            width: 70px;
        }

        .ng-sense-bar {
            flex: 1;
            height: 4px;
            background: #222;
            border-radius: 2px;
            overflow: hidden;
        }

        .ng-sense-fill {
            height: 100%;
            width: 0%;
            background: linear-gradient(90deg, #0af, #0f8);
            border-radius: 2px;
            transition: width 0.8s ease;
        }

        .ng-sense.show .ng-sense-fill {
            width: 100%;
        }

        .ng-sub-text {
            text-align: center;
            margin-top: 20px;
            font-family: 'JetBrains Mono', 'Courier New', monospace;
            font-size: 11px;
            color: #555;
            animation: blink-text 1s infinite;
        }

        @keyframes blink-text {

            0%,
            100% {
                opacity: 1;
            }

            50% {
                opacity: 0.3;
            }
        }

        /* LINK START */
        .ng-link-container {
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 200px;
        }

        .ng-link-text {
            display: flex;
            gap: 4px;
        }

        .ng-link-text span {
            font-family: 'Sora', 'Rajdhani', sans-serif;
            font-size: clamp(48px, 12vw, 96px);
            font-weight: 800;
            color: transparent;
            letter-spacing: 0.05em;
            animation: link-letter 0.6s forwards;
            opacity: 0;
        }

        .ng-space {
            width: 0.3em;
        }

        @keyframes link-letter {
            0% {
                color: transparent;
                text-shadow: none;
                opacity: 0;
                transform: scale(0.5);
            }

            50% {
                color: #fff;
                text-shadow: 0 0 40px rgba(255, 157, 0, 0.8);
                opacity: 1;
                transform: scale(1.1);
            }

            100% {
                color: #fff;
                text-shadow: 0 0 20px rgba(255, 157, 0, 0.4);
                opacity: 1;
                transform: scale(1);
            }
        }

        /* Flash */
        #ng-flash {
            position: fixed;
            inset: 0;
            background: #fff;
            opacity: 0;
            pointer-events: none;
            z-index: 10;
            transition: opacity 0.15s ease;
        }

        #ng-flash.flash-on {
            opacity: 1;
        }

        #ng-flash.flash-fade {
            transition: opacity 1.5s ease;
            opacity: 0;
        }

        /* Skip Button */
        #ng-skip {
            position: fixed;
            bottom: 30px;
            right: 30px;
            z-index: 100000;
            background: none;
            border: 1px solid #333;
            color: #555;
            font-family: 'JetBrains Mono', 'Rajdhani', sans-serif;
            font-size: 12px;
            font-weight: 700;
            letter-spacing: 0.15em;
            padding: 8px 20px;
            cursor: pointer;
            border-radius: 4px;
            transition: all 0.3s ease;
        }

        #ng-skip:hover {
            color: #ff9d00;
            border-color: #ff9d00;
        }
    </style>

    <script>
        function runNerveGear() {
            const overlay = document.getElementById('nervegear-overlay');
            if (!overlay) return;

            // Check if already seen this session
            if (sessionStorage.getItem('ng-seen')) {
                overlay.remove();
                return;
            }

            let aborted = false;
            const phases = {
                boot: document.getElementById('ng-boot'),
                calibrate: document.getElementById('ng-calibrate'),
                senses: document.getElementById('ng-senses'),
                linkstart: document.getElementById('ng-linkstart'),
                flash: document.getElementById('ng-flash'),
            };

            function showPhase(name) {
                Object.values(phases).forEach(p => {
                    if (p && p.classList) p.classList.remove('active');
                });
                if (phases[name]) phases[name].classList.add('active');
            }

            function finish() {
                if (aborted) return;
                aborted = true;
                sessionStorage.setItem('ng-seen', '1');
                overlay.classList.add('ng-done');
                setTimeout(() => overlay.remove(), 1000);
            }

            window.skipNerveGear = function() {
                finish();
            };

            // ── Phase 1: Boot (0 - 3500ms) ──
            showPhase('boot');
            const lines = phases.boot.querySelectorAll('.ng-line');
            lines.forEach(line => {
                const delay = parseInt(line.dataset.delay) || 0;
                setTimeout(() => {
                    if (!aborted) line.classList.add('show');
                }, delay);
            });

            // ── Phase 2: Language (3500 - 5200ms) ──
            setTimeout(() => {
                if (aborted) return;
                showPhase('calibrate');
            }, 3500);

            // ── Phase 3: Senses (5200 - 8500ms) ──
            setTimeout(() => {
                if (aborted) return;
                showPhase('senses');
                const senses = phases.senses.querySelectorAll('.ng-sense');
                senses.forEach(s => {
                    const delay = parseInt(s.dataset.delay) || 0;
                    setTimeout(() => {
                        if (!aborted) s.classList.add('show');
                    }, delay);
                });
            }, 5200);

            // ── Phase 4: LINK START (8500 - 10500ms) ──
            setTimeout(() => {
                if (aborted) return;
                showPhase('linkstart');

                // Stagger each letter
                const letters = phases.linkstart.querySelectorAll('.ng-link-text span:not(.ng-space)');
                letters.forEach((letter, i) => {
                    letter.style.animationDelay = `${i * 0.08}s`;
                });
            }, 8500);

            // ── Phase 5: Flash & Reveal (10500ms) ──
            setTimeout(() => {
                if (aborted) return;
                phases.flash.classList.add('flash-on');

                setTimeout(() => {
                    phases.flash.classList.remove('flash-on');
                    phases.flash.classList.add('flash-fade');
                    finish();
                }, 400);
            }, 10500);
        }

        document.addEventListener('DOMContentLoaded', runNerveGear);
    </script>

    {{-- ══════════════════════════════════════════════════════
     HEADER — Player HUD (Fixed Top)
     ══════════════════════════════════════════════════════ --}}
    <header
        class="fixed top-0 left-0 right-0 z-50 py-3 px-4 md:px-8 flex items-center justify-between pointer-events-none"
        style="background: var(--sao-header-bg); backdrop-filter: blur(16px); -webkit-backdrop-filter: blur(16px); border-bottom: 1px solid var(--sao-border-subtle); box-shadow: var(--sao-header-shadow); transition: all 0.3s ease;">

        @auth
            {{-- Player Info (Logged In) --}}
            <a href="{{ route('player.dashboard') }}"
                class="sao-panel px-4 py-2.5 flex items-center gap-3 pointer-events-auto hover:scale-105 transition-transform">
                <div
                    class="w-9 h-9 rounded-full bg-gradient-to-br from-orange-400 to-orange-600 border-2 border-orange-400 flex items-center justify-center text-white font-bold text-sm shadow-inner overflow-hidden"
                    style="box-shadow: 0 0 12px rgba(255,157,0,0.4);">
                    <img src="/images/yui.png" alt="Avatar" class="w-full h-full object-cover">
                </div>
                <div>
                    <p class="font-bold text-base leading-none text-sao-text tracking-wide">{{ Auth::user()->name }}</p>
                    <p class="text-xs text-sao-orange font-semibold mt-0.5" style="font-family: 'JetBrains Mono', monospace; letter-spacing: 0.1em;">LV. {{ Auth::user()->level ?? 1 }} <span
                            class="text-sao-muted font-normal">// LOGGED IN</span></p>
                </div>
            </a>
        @else
            {{-- Guest (Login/Register) --}}
            <div class="flex items-center gap-3 pointer-events-auto">
                <a href="{{ route('login') }}"
                    class="sao-panel px-5 py-2.5 font-bold text-sao-text hover:text-sao-orange transition-colors">
                    LINK START
                </a>
                <a href="{{ route('register') }}" class="sao-btn sm">
                    REGISTER
                </a>
            </div>
        @endauth

        <div class="flex items-center gap-3 pointer-events-auto">
            {{-- Theme Toggle --}}
            <button onclick="toggleTheme()" class="sao-nav-btn flex items-center justify-center rounded-full border border-sao transition-all cursor-pointer" style="width: 40px; height: 40px;" title="Alternar Tema">
                <span class="material-symbols-outlined theme-icon" style="font-size: 20px;">light_mode</span>
            </button>

            {{-- HP / XP Bar (Center-Right) --}}
            <div class="sao-panel px-4 py-2.5 w-48 md:w-64">
                <div class="flex justify-between items-center mb-1.5">
                    <span class="text-[11px] font-bold text-sao-muted tracking-wider uppercase" style="font-family: 'JetBrains Mono', monospace;">HP</span>
                    <span id="hp-text" class="text-[11px] font-bold text-sao-dim tabular-nums" style="font-family: 'JetBrains Mono', monospace;">100 / 100</span>
                </div>
                <div class="hp-bar-container">
                    <div class="hp-bar-fill" id="hp-bar" style="width: 100%"></div>
                </div>
            </div>
        </div>
    </header>

    {{-- ══════════════════════════════════════════════════════
     FLOATING VERTICAL MENU (Right Edge) — Material Symbols
     ══════════════════════════════════════════════════════ --}}
    <nav class="hidden md:flex fixed top-1/2 right-4 md:right-6 -translate-y-1/2 z-50 flex-col gap-3 py-6 px-2 rounded-full"
        style="background: var(--sao-header-bg); backdrop-filter: blur(16px); -webkit-backdrop-filter: blur(16px); border: 1px solid var(--sao-panel-border); box-shadow: var(--sao-panel-shadow);">
        @foreach ([
            ['icon' => 'home', 'fill' => true, 'label' => 'Início', 'target' => '#hero', 'active' => true],
            ['icon' => 'account_tree', 'fill' => false, 'label' => 'Skill Tree', 'target' => '#modules', 'active' => false],
            ['icon' => 'monitoring', 'fill' => false, 'label' => 'Status', 'target' => '#status', 'active' => false],
            ['icon' => 'map', 'fill' => false, 'label' => 'Jornada', 'target' => '#jornada', 'active' => false],
            ['icon' => 'forum', 'fill' => false, 'label' => 'Registros', 'target' => '#depoimentos', 'active' => false],
            ['icon' => 'play_circle', 'fill' => false, 'label' => 'Aceitar Missão', 'target' => route('register'), 'active' => false],
        ] as $nav)
            @if ($nav['active'])
                <a href="{{ $nav['target'] }}"
                    class="w-12 h-12 rounded-full flex items-center justify-center relative group transition-transform active:scale-90"
                    style="background: #ff9d00; color: #11131a; box-shadow: 0 0 15px rgba(255,157,0,0.5);"
                    title="{{ $nav['label'] }}">
                    <span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 1; font-size: 22px;">{{ $nav['icon'] }}</span>
                    <div
                        class="absolute right-16 top-1/2 -translate-y-1/2 opacity-0 group-hover:opacity-100 transition-opacity duration-200 sao-panel px-3 py-1.5 text-xs font-bold text-gray-300 whitespace-nowrap">
                        {{ $nav['label'] }}
                    </div>
                </a>
            @else
                <a href="{{ $nav['target'] }}"
                    class="w-12 h-12 rounded-full flex items-center justify-center relative group transition-all hover:scale-110 hover:bg-white/10"
                    style="color: var(--sao-text-dim);"
                    title="{{ $nav['label'] }}"
                    onmouseover="this.style.color='#ff9d00'" onmouseout="this.style.color='var(--sao-text-dim)'">
                    <span class="material-symbols-outlined" style="font-size: 22px;">{{ $nav['icon'] }}</span>
                    <div
                        class="absolute right-16 top-1/2 -translate-y-1/2 opacity-0 group-hover:opacity-100 transition-opacity duration-200 sao-panel px-3 py-1.5 text-xs font-bold whitespace-nowrap"
                        style="color: var(--sao-text);">
                        {{ $nav['label'] }}
                    </div>
                </a>
            @endif
        @endforeach
    </nav>

    {{-- ══════════════════════════════════════════════════════
     MAIN CONTENT
     ══════════════════════════════════════════════════════ --}}
    <main class="relative z-10 pt-28 pb-20 max-w-7xl mx-auto">

        {{-- ─── HERO ─── --}}
        <section id="hero" class="min-h-[85vh] flex items-center justify-center px-6">
            <div data-animate class="sao-panel p-10 md:p-16 max-w-4xl mx-auto text-center relative overflow-hidden"
                style="box-shadow: 0 0 40px rgba(255,157,0,0.08);">
                {{-- Decorative corner brackets --}}
                <div class="absolute top-8 left-8 text-5xl opacity-30" style="color: #ff9d00; font-family: 'Sora', sans-serif; font-weight: 800;">[</div>
                <div class="absolute bottom-8 right-8 text-5xl opacity-30" style="color: #ff9d00; font-family: 'Sora', sans-serif; font-weight: 800;">]</div>

                <p class="text-sm font-semibold text-sao-orange tracking-[0.2em] uppercase mb-6 label-caps">SYSTEM NOTIFICATION</p>
                <h1 class="text-5xl sm:text-6xl md:text-7xl font-black leading-[0.95]"
                    style="font-family: 'Sora', sans-serif; letter-spacing: -0.02em; color: var(--sao-text);">
                    <span style="color: #ff9d00;">[</span> LINK START <span style="color: #ff9d00;">]</span>
                </h1>
                <p class="mt-6 text-lg md:text-xl max-w-2xl mx-auto leading-relaxed font-medium"
                    style="font-family: 'Hanken Grotesk', sans-serif; color: var(--sao-text-dim);">
                    Bem-vindo a Aincrad. O sistema detectou potencial em você.
                    Aceite a missão e desbloqueie sua evolução.
                </p>

                <div class="mt-10 flex flex-col sm:flex-row items-center justify-center gap-4">
                    <a href="{{ route('register') }}" class="sao-btn">
                        <span class="material-symbols-outlined" style="font-size: 18px;">play_circle</span>
                        ACEITAR MISSÃO
                    </a>
                </div>

                {{-- Trust --}}
                <div class="mt-8 flex flex-wrap justify-center gap-8 label-caps text-[10px]" style="color: var(--sao-text-muted);">
                    <span>✓ +2.400 Players</span>
                    <span>★ 4.9/5 Rating</span>
                    <span>⟳ Garantia 7 Dias</span>
                </div>
            </div>
        </section>

        <hr class="sao-divider max-w-xs mx-auto">

        {{-- ─── PROBLEMA (Debuffs) ─── --}}
        <section class="py-16 md:py-24 px-6">
            <div class="max-w-6xl mx-auto">
                <div data-animate class="text-center mb-12">
                    <h2 class="section-title text-3xl md:text-4xl font-bold">DEBUFFS DETECTADOS</h2>
                    <p class="mt-3 max-w-lg mx-auto" style="color: #dac2ad; font-family: 'Hanken Grotesk', sans-serif;">Status negativos travando sua evolução.</p>
                </div>

                @php
                    $debuffs = [
                        [
                            'icon' => 'local_fire_department',
                            'title' => 'Burnout',
                            'desc' => 'Energia mental esgotada.',
                            'stat' => '-40% Energia',
                        ],
                        [
                            'icon' => 'hourglass_empty',
                            'title' => 'Procrastinação',
                            'desc' => 'Paralisia por análise travando ações.',
                            'stat' => '-60% Ação',
                        ],
                        [
                            'icon' => 'block',
                            'title' => 'Estagnação',
                            'desc' => 'Sem progresso visível nas skills.',
                            'stat' => '-80% XP',
                        ],
                        [
                            'icon' => 'blur_on',
                            'title' => 'Falta de Foco',
                            'desc' => 'Distrações constantes, zero resultados.',
                            'stat' => '-50% Precisão',
                        ],
                        [
                            'icon' => 'sentiment_stressed',
                            'title' => 'Impostor',
                            'desc' => 'Medo de agir por insegurança.',
                            'stat' => '-70% Confiança',
                        ],
                        [
                            'icon' => 'schedule',
                            'title' => 'Sem Rotina',
                            'desc' => 'Dias caóticos sem estrutura.',
                            'stat' => '-55% Disciplina',
                        ],
                    ];
                @endphp

                <div data-animate class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
                    @foreach ($debuffs as $d)
                        <div class="sao-panel p-6 group hover:border-orange-500/40">
                            <div class="flex items-center justify-between mb-4">
                                <span class="material-symbols-outlined text-3xl text-sao-orange">{{ $d['icon'] }}</span>
                                <span class="label-caps text-[10px]" style="color: #ffb4ab;">{{ $d['stat'] }}</span>
                            </div>
                            <h3 class="font-bold text-lg text-gray-100 group-hover:text-sao-orange transition-colors"
                                style="font-family: 'Sora', sans-serif;">
                                {{ $d['title'] }}</h3>
                            <p class="mt-1 text-sm" style="color: #dac2ad; font-family: 'Hanken Grotesk', sans-serif;">{{ $d['desc'] }}</p>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>

        <hr class="sao-divider max-w-xs mx-auto">

        {{-- ─── SOLUÇÃO ─── --}}
        <section class="py-16 md:py-24 px-6">
            <div class="max-w-5xl mx-auto">
                <div class="grid lg:grid-cols-2 gap-10 items-center">
                    <div data-animate>
                        <h2 class="section-title text-3xl md:text-4xl font-bold justify-start">O SISTEMA</h2>
                        <p class="mt-4 leading-relaxed text-lg"
                            style="color: #dac2ad; font-family: 'Hanken Grotesk', sans-serif;">
                            O SAO System é um programa de transformação pessoal gamificado.
                            Cada módulo é uma skill. Cada semana é um nível.
                            Você não estuda — você <strong class="text-gray-100">treina, evolui e sobe de nível</strong>.
                        </p>
                        <ul class="mt-6 space-y-3">
                            @foreach (['8 Módulos de treinamento', 'Sistema de XP e progressão', 'Comunidade exclusiva (Guilda)', 'Suporte direto do Mestre'] as $item)
                                <li class="flex items-center gap-3 text-gray-300">
                                    <span
                                        class="w-6 h-6 rounded-full bg-sao-orange text-white flex items-center justify-center text-xs font-bold"
                                        style="box-shadow: 0 0 10px rgba(255,157,0,0.3);">
                                        <span class="material-symbols-outlined" style="font-size: 14px;">add</span>
                                    </span>
                                    <span class="font-medium" style="font-family: 'Hanken Grotesk', sans-serif;">{{ $item }}</span>
                                </li>
                            @endforeach
                        </ul>
                    </div>

                    {{-- Stats Card --}}
                    <div data-animate class="sao-panel-orange p-8 text-center" style="border-radius: 12px;">
                        <div class="mb-4">
                            <span class="material-symbols-outlined text-sao-orange" style="font-size: 48px; font-variation-settings: 'FILL' 1;">swords</span>
                        </div>
                        <h3 class="text-xl font-bold text-gray-100" style="font-family: 'Sora', sans-serif;">
                            <span style="color: #ff9d00;">[</span> SAO SYSTEM <span style="color: #ff9d00;">]</span>
                        </h3>
                        <p class="label-caps text-sao-orange mt-1">PROGRAMA COMPLETO DE EVOLUÇÃO</p>
                        <div class="mt-6 grid grid-cols-2 gap-3">
                            @foreach ([['val' => '8', 'label' => 'Módulos'], ['val' => '50+', 'label' => 'Aulas'], ['val' => '2.4k+', 'label' => 'Players'], ['val' => '4 sem', 'label' => 'Duração']] as $stat)
                                <div class="rounded-lg p-3" style="background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.08);">
                                    <span class="text-2xl font-black text-gray-100" style="font-family: 'Sora', sans-serif;">{{ $stat['val'] }}</span>
                                    <p class="label-caps text-[10px] mt-0.5" style="color: rgba(226,226,236,0.5);">
                                        {{ $stat['label'] }}</p>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <hr class="sao-divider max-w-xs mx-auto">

        {{-- ─── MÓDULOS (Skill Tree — Quest Log Cards) ─── --}}
        <section id="modules" class="py-16 md:py-24 px-6">
            <div class="max-w-6xl mx-auto">
                <div data-animate class="text-center mb-12">
                    <h2 class="section-title text-3xl md:text-4xl font-bold">SKILL TREE</h2>
                    <p class="mt-3 max-w-lg mx-auto" style="color: #dac2ad; font-family: 'Hanken Grotesk', sans-serif;">Desbloqueie cada skill para atingir o nível máximo.
                    </p>
                </div>

                @php
                    $modules = [
                        [
                            'num' => '01',
                            'icon' => 'psychology',
                            'title' => 'Mentalidade de Jogador',
                            'desc' => 'Reprograme sua mente para operar como um Player de elite.',
                            'xp' => 250,
                            'lv' => 7,
                        ],
                        [
                            'num' => '02',
                            'icon' => 'target',
                            'title' => 'Missões & Objetivos',
                            'desc' => 'Transforme metas em missões executáveis.',
                            'xp' => 300,
                            'lv' => 8,
                        ],
                        [
                            'num' => '03',
                            'icon' => 'bolt',
                            'title' => 'Produtividade Extrema',
                            'desc' => 'Técnicas de alto rendimento para multiplicar seu output.',
                            'xp' => 350,
                            'lv' => 6,
                        ],
                        [
                            'num' => '04',
                            'icon' => 'swords',
                            'title' => 'Disciplina Forjada',
                            'desc' => 'Hábitos no piloto automático. Sem motivação, só sistema.',
                            'xp' => 400,
                            'lv' => 7,
                        ],
                        [
                            'num' => '05',
                            'icon' => 'menu_book',
                            'title' => 'Aprendizado Acelerado',
                            'desc' => 'Domine qualquer skill 3x mais rápido.',
                            'xp' => 300,
                            'lv' => 5,
                        ],
                        [
                            'num' => '06',
                            'icon' => 'account_balance',
                            'title' => 'Finanças do Player',
                            'desc' => 'Gold management aplicado à vida real.',
                            'xp' => 350,
                            'lv' => 6,
                        ],
                        [
                            'num' => '07',
                            'icon' => 'diversity_3',
                            'title' => 'Social Engineering',
                            'desc' => 'Comunicação, networking e influência.',
                            'xp' => 300,
                            'lv' => 5,
                        ],
                        [
                            'num' => '08',
                            'icon' => 'trophy',
                            'title' => 'Boss Final',
                            'desc' => 'Integre tudo. Projeto final com mentoria exclusiva.',
                            'xp' => 500,
                            'lv' => 6,
                        ],
                    ];
                @endphp

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-5">
                    @foreach ($modules as $m)
                        <div data-animate class="sao-quest-panel p-5 flex flex-col">
                            {{-- Quest Header --}}
                            <div class="flex items-center justify-between mb-3">
                                <span class="label-caps text-sao-orange">QUEST
                                    {{ $m['num'] }}</span>
                                <span class="label-caps text-[10px]" style="color: rgba(226,226,236,0.4);">+{{ $m['xp'] }} EXP</span>
                            </div>
                            {{-- Icon & Title --}}
                            <div class="mb-2">
                                <span class="material-symbols-outlined text-sao-orange" style="font-size: 32px;">{{ $m['icon'] }}</span>
                            </div>
                            <h4 class="font-bold text-base text-gray-100 leading-snug" style="font-family: 'Sora', sans-serif;">{{ $m['title'] }}</h4>
                            <p class="text-xs mt-1 flex-1" style="color: #dac2ad; font-family: 'Hanken Grotesk', sans-serif;">{{ $m['desc'] }}</p>
                            {{-- Level Requirement --}}
                            <div class="mt-4 pt-3 flex items-center justify-between"
                                style="border-top: 1px solid rgba(255,255,255,0.08);">
                                <span class="label-caps text-[10px]" style="color: rgba(226,226,236,0.4);">{{ $m['lv'] }}
                                    Aulas</span>
                                <span class="material-symbols-outlined text-sao-orange" style="font-size: 20px;">add_circle</span>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>

        <hr class="sao-divider max-w-xs mx-auto">

        {{-- ─── STATUS (Atributos) ─── --}}
        <section id="status" class="py-16 md:py-24 px-6">
            <div class="max-w-5xl mx-auto">
                <div class="grid lg:grid-cols-2 gap-10 items-center">
                    <div data-animate>
                        <h2 class="section-title text-3xl md:text-4xl font-bold justify-start">CHARACTER STATUS</h2>
                        <p class="mt-4 leading-relaxed" style="color: #dac2ad; font-family: 'Hanken Grotesk', sans-serif;">
                            Projeção de evolução baseada nos resultados médios dos players que completaram o
                            treinamento.
                        </p>
                    </div>

                    <div data-animate class="sao-panel p-6 space-y-5">
                        @php
                            $stats = [
                                ['name' => 'STR — Força Mental', 'val' => 85],
                                ['name' => 'INT — Inteligência', 'val' => 78],
                                ['name' => 'VIT — Disciplina', 'val' => 92],
                                ['name' => 'AGI — Produtividade', 'val' => 80],
                                ['name' => 'DEX — Carisma', 'val' => 65],
                            ];
                        @endphp

                        @foreach ($stats as $s)
                            <div>
                                <div class="flex justify-between mb-1">
                                    <span
                                        class="label-caps text-[11px]" style="color: rgba(226,226,236,0.6);">{{ $s['name'] }}</span>
                                    <span class="label-caps text-[11px] text-sao-orange">{{ $s['val'] }}%</span>
                                </div>
                                <div class="hp-bar-container">
                                    <div class="hp-bar-fill status-fill"
                                        style="--target-width: {{ $s['val'] }}%"></div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </section>

        <hr class="sao-divider max-w-xs mx-auto">

        {{-- ─── JORNADA (Timeline) ─── --}}
        <section id="jornada" class="py-16 md:py-24 px-6">
            <div class="max-w-3xl mx-auto">
                <div data-animate class="text-center mb-12">
                    <h2 class="section-title text-3xl md:text-4xl font-bold">QUEST MAP</h2>
                </div>

                <div class="relative">
                    <div
                        class="absolute left-6 md:left-1/2 md:-translate-x-1/2 top-0 bottom-0 w-0.5"
                        style="background: linear-gradient(to bottom, rgba(255,157,0,0.5), rgba(255,157,0,0.2), transparent);"></div>

                    @php
                        $quests = [
                            [
                                'week' => 'Semana 1',
                                'title' => 'Despertar',
                                'desc' => 'Conecte-se ao sistema. Diagnóstico completo e definição da build ideal.',
                                'icon' => 'wb_twilight',
                            ],
                            [
                                'week' => 'Semana 2',
                                'title' => 'Primeira Missão',
                                'desc' => 'Execute sua primeira quest. Implemente os fundamentos.',
                                'icon' => 'swords',
                            ],
                            [
                                'week' => 'Semana 3',
                                'title' => 'Boss Fight',
                                'desc' => 'Quebre padrões limitantes. Avance para a fase avançada.',
                                'icon' => 'bug_report',
                            ],
                            [
                                'week' => 'Semana 4',
                                'title' => 'Ascensão',
                                'desc' => 'Integração total. New Game+ desbloqueado.',
                                'icon' => 'trophy',
                            ],
                        ];
                    @endphp

                    <div class="space-y-10">
                        @foreach ($quests as $idx => $q)
                            <div data-animate
                                class="relative flex items-start gap-5 {{ $idx % 2 !== 0 ? 'md:flex-row-reverse md:text-right' : '' }}">
                                <div
                                    class="z-10 flex-shrink-0 w-12 h-12 sao-panel flex items-center justify-center rounded-full text-xl shadow-lg">
                                    <span class="material-symbols-outlined text-sao-orange" style="font-size: 24px;">{{ $q['icon'] }}</span>
                                </div>
                                <div class="flex-1 sao-panel p-5">
                                    <span
                                        class="label-caps text-sao-orange">{{ $q['week'] }}</span>
                                    <h3 class="mt-1 font-bold text-lg text-gray-100" style="font-family: 'Sora', sans-serif;">{{ $q['title'] }}</h3>
                                    <p class="mt-1 text-sm" style="color: #dac2ad; font-family: 'Hanken Grotesk', sans-serif;">{{ $q['desc'] }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </section>

        <hr class="sao-divider max-w-xs mx-auto">

        {{-- ─── DEPOIMENTOS ─── --}}
        <section id="depoimentos" class="py-16 md:py-24 px-6">
            <div class="max-w-6xl mx-auto">
                <div data-animate class="text-center mb-12">
                    <h2 class="section-title text-3xl md:text-4xl font-bold">SYSTEM LOGS</h2>
                    <p class="mt-3 max-w-lg mx-auto" style="color: #dac2ad; font-family: 'Hanken Grotesk', sans-serif;">Feedbacks verificados dos players.</p>
                </div>

                @php
                    $testimonials = [
                        [
                            'name' => 'PlayerKaito',
                            'lv' => 47,
                            'text' =>
                                'Minha produtividade aumentou 300%. Em 2 semanas já tinha resultados que tentei por meses.',
                            'stars' => 5,
                        ],
                        [
                            'name' => 'SakuraX',
                            'lv' => 32,
                            'text' =>
                                'A gamificação fez toda diferença. Pela primeira vez, estudar virou algo que eu QUERO fazer.',
                            'stars' => 5,
                        ],
                        [
                            'name' => 'DarkBlade99',
                            'lv' => 55,
                            'text' => 'O módulo de disciplina me transformou. Acordo 5h da manhã sem despertador.',
                            'stars' => 5,
                        ],
                        [
                            'name' => 'LunaRise',
                            'lv' => 28,
                            'text' => 'O formato RPG torna tudo envolvente. Recomendo pra quem é gamer como eu.',
                            'stars' => 4,
                        ],
                        [
                            'name' => 'PhoenixAsh',
                            'lv' => 41,
                            'text' => 'Boss Fight na semana 3 foi o turning point. Mudou tudo.',
                            'stars' => 5,
                        ],
                        [
                            'name' => 'ZeroTwo',
                            'lv' => 39,
                            'text' => 'Em 1 mês recuperei o investimento. O módulo 6 vale ouro.',
                            'stars' => 5,
                        ],
                    ];
                @endphp

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
                    @foreach ($testimonials as $t)
                        <div data-animate class="sao-panel p-5">
                            <div class="flex items-center gap-3 mb-3">
                                <div
                                    class="w-9 h-9 rounded-full bg-gradient-to-br from-orange-500 to-orange-700 flex items-center justify-center text-white text-sm font-bold"
                                    style="box-shadow: 0 0 10px rgba(255,157,0,0.3); border: 1px solid rgba(255,157,0,0.5);">
                                    {{ strtoupper(substr($t['name'], 0, 1)) }}
                                </div>
                                <div>
                                    <span class="font-bold text-sm text-gray-100">{{ '@' . $t['name'] }}</span>
                                    <span class="block label-caps text-[10px] text-sao-orange">Nível
                                        {{ $t['lv'] }}</span>
                                </div>
                            </div>
                            <p class="text-sm italic leading-relaxed" style="color: #dac2ad; font-family: 'Hanken Grotesk', sans-serif;">"{{ $t['text'] }}"</p>
                            <div class="mt-3 pt-3 flex justify-between items-center"
                                style="border-top: 1px solid rgba(255,255,255,0.08);">
                                <div class="text-yellow-500 text-xs">
                                    @for ($i = 0; $i < $t['stars']; $i++)
                                        ⭐
                                    @endfor
                                </div>
                                <span class="label-caps text-[10px]" style="color: #4ae183;">✓ Verificado</span>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>

        <hr class="sao-divider max-w-xs mx-auto">

        {{-- ─── CHECKOUT (Trade Window) ─── --}}
        <section id="checkout" class="py-16 md:py-32 px-6">
            <div class="max-w-lg mx-auto">
                <div data-animate class="text-center mb-10">
                    <h2 class="section-title text-3xl md:text-4xl font-bold">MISSÃO FINAL</h2>
                </div>

                <div data-animate class="sao-panel-orange p-1.5" style="border-radius: 12px;">
                    <div class="p-6 md:p-8 rounded-[10px]" style="background: var(--sao-surface, rgba(17,19,26,0.6)); backdrop-filter: blur(12px); border: 1px solid var(--sao-panel-border);">
                        {{-- Item --}}
                        <div class="flex items-start gap-5 mb-6">
                            <div
                                class="w-20 h-20 rounded-xl flex items-center justify-center shadow-inner flex-shrink-0"
                                style="background: linear-gradient(135deg, rgba(255,157,0,0.15), rgba(255,157,0,0.05)); border: 2px solid rgba(255,157,0,0.3);">
                                <span class="material-symbols-outlined text-sao-orange" style="font-size: 40px; font-variation-settings: 'FILL' 1;">description</span>
                            </div>
                            <div>
                                <h3 class="font-bold text-xl uppercase" style="font-family: 'Sora', sans-serif; color: var(--sao-text);">{{ $plan ? $plan->name : 'SAO SYSTEM ACCESS' }}</h3>
                                <p class="label-caps text-sao-orange mt-0.5">CLASS: UNIQUE ITEM</p>
                                <p class="text-xs mt-2 leading-relaxed" style="color: #dac2ad; font-family: 'Hanken Grotesk', sans-serif;">Acesso completo a todos os recursos de Aincrad e assistência com YUI.</p>
                            </div>
                        </div>

                        <hr class="sao-divider">

                        {{-- Features --}}
                        <ul class="space-y-2.5 mb-6 text-sm">
                            @php
                                $features = $plan ? $plan->features : [
                                    'Barra de HP (Saldo) e Controle de XP',
                                    'Trade Log de Combate (Receitas/Despesas)',
                                    'Inventário Completo de Ativos e Passivos',
                                    'Guild System (Comunidade, Ranking & Chat)',
                                    'Assistência por YUI',
                                    'Evolução pelos 100 Andares de Aincrad'
                                ];
                            @endphp
                            @foreach ($features as $f)
                                <li class="flex items-center gap-2.5" style="color: var(--sao-text);">
                                    <span
                                        class="w-5 h-5 rounded-full bg-sao-orange flex items-center justify-center flex-shrink-0"
                                        style="box-shadow: 0 0 8px rgba(255,157,0,0.3);">
                                        <span class="material-symbols-outlined" style="font-size: 12px; color: #11131a;">add</span>
                                    </span>
                                    <span style="font-family: 'Hanken Grotesk', sans-serif;">{{ $f }}</span>
                                </li>
                            @endforeach
                        </ul>

                        <hr class="sao-divider">

                        {{-- Price --}}
                        <div class="flex items-end justify-between mb-6">
                            <span class="label-caps" style="color: var(--sao-text-muted);">Cost:</span>
                            <div class="text-right">
                                <span class="block text-sm line-through" style="color: rgba(226,226,236,0.3);">R$ 49,90</span>
                                <span class="text-4xl font-black" style="font-family: 'Sora', sans-serif; color: var(--sao-text);">R$ {{ number_format($plan ? $plan->price : 19.70, 2, ',', '.') }}</span>
                                <p class="label-caps text-[10px] mt-0.5" style="color: var(--sao-text-muted);">Cobrado mensalmente / Sem fidelidade</p>
                            </div>
                        </div>

                        {{-- CTA --}}
                        <a href="{{ route('register') }}" class="sao-btn w-full justify-center text-lg py-4">
                            <span class="material-symbols-outlined" style="font-size: 22px;">play_circle</span>
                            ACCEPT TRADE
                        </a>

                        {{-- Guarantee --}}
                        <p class="mt-4 text-center text-xs" style="color: var(--sao-text-muted);">
                            🛡️ Garantia de 7 dias — Sem risco. Devolvemos 100%.
                        </p>
                    </div>
                </div>
            </div>
        </section>

    </main>

    {{-- ─── FOOTER ─── --}}
    <footer class="text-center py-10 border-t" style="border-color: var(--sao-border-subtle); background: var(--sao-header-bg); transition: all 0.3s ease;">
        <div class="flex justify-center gap-6 mb-3">
            <a href="#" class="text-sm text-sao-dim hover:text-sao-orange transition-colors" style="font-family: 'Hanken Grotesk', sans-serif;">Terms of Service</a>
            <a href="#" class="text-sm text-sao-dim hover:text-sao-orange transition-colors" style="font-family: 'Hanken Grotesk', sans-serif;">Privacy Policy</a>
            <a href="#" class="text-sm text-sao-dim hover:text-sao-orange transition-colors" style="font-family: 'Hanken Grotesk', sans-serif;">System Status</a>
        </div>
        <p class="label-caps text-sao-muted">
            © {{ date('Y') }} SAO System Interface // Aincrad Floor 1. All rights reserved.
        </p>
    </footer>

</body>

</html>
