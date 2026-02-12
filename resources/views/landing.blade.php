<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>SAO System — Seu Despertar Começa Agora</title>
    <meta name="description" content="O sistema que vai transformar sua vida. Desbloqueie seu potencial com módulos de treinamento estilo RPG.">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@700;900&family=Rajdhani:wght@500;600;700&family=Inter:wght@300;400;500;600&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">

    <div id="boot-sequence" class="boot-layer flex-col bg-black text-neon-cyan/80 z-[99999]">
        <div id="boot-text" class="text-left w-full max-w-2xl px-6 min-h-[300px]"></div>
        <div id="tunnel-effect" class="absolute inset-0 pointer-events-none opacity-0 transition-opacity duration-1000">
             <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[200vw] h-[200vw] rounded-full
                         border-[100px] border-neon-cyan animate-ping opacity-20"></div>
        </div>
        <button id="skip-boot" class="absolute bottom-8 text-xs font-mono text-slate-600 hover:text-white transition-colors">
            PRESS [ESC] TO SKIP
        </button>
    </div>

    @vite(['resources/css/landing.css', 'resources/js/landing.js'])
</head>
<body class="noise-overlay crt-scanlines font-body antialiased bg-hud-dark text-slate-300 overflow-hidden">

{{-- ══════════════════════════════════════════════════════
     NAVIGATION — HUD Style Fixed Bar
     ══════════════════════════════════════════════════════ --}}
<nav id="nav-hud"
     class="fixed top-0 left-0 right-0 z-50 bg-transparent transition-all duration-300">
    <div class="max-w-7xl mx-auto px-6 lg:px-16">
        <div class="flex items-center justify-between h-16">
            {{-- Logo --}}
            <a href="#" class="flex items-center gap-2">
                <span class="font-display text-lg font-bold text-neon-cyan tracking-wider">
                    SAO
                </span>
                <span class="font-mono text-xs text-slate-500">SYSTEM v2.0</span>
            </a>

            {{-- Nav Links --}}
            <div class="hidden md:flex items-center gap-8">
                <a href="#modulos" class="font-mono text-xs text-slate-400 hover:text-neon-cyan uppercase tracking-wider transition-colors">Módulos</a>
                <a href="#jornada" class="font-mono text-xs text-slate-400 hover:text-neon-cyan uppercase tracking-wider transition-colors">Jornada</a>
                <a href="#depoimentos" class="font-mono text-xs text-slate-400 hover:text-neon-cyan uppercase tracking-wider transition-colors">Registros</a>
                <a href="#checkout"
                   class="px-5 py-2 border border-neon-cyan/40 text-neon-cyan font-mono text-xs uppercase tracking-wider
                          hover:bg-neon-cyan/10 transition-all rounded-sm">
                    Entrar
                </a>
            </div>

            {{-- Mobile Menu Toggle --}}
            <button id="mobile-toggle"
                    class="md:hidden text-slate-400 hover:text-neon-cyan transition-colors"
                    onclick="document.getElementById('mobile-menu').classList.toggle('hidden')">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
            </button>
        </div>

        {{-- Mobile Menu --}}
        <div id="mobile-menu" class="hidden md:hidden pb-4 space-y-3">
            <a href="#modulos" class="block font-mono text-sm text-slate-400 hover:text-neon-cyan uppercase tracking-wider transition-colors">Módulos</a>
            <a href="#jornada" class="block font-mono text-sm text-slate-400 hover:text-neon-cyan uppercase tracking-wider transition-colors">Jornada</a>
            <a href="#depoimentos" class="block font-mono text-sm text-slate-400 hover:text-neon-cyan uppercase tracking-wider transition-colors">Registros</a>
            <a href="#checkout" class="block font-mono text-sm text-neon-cyan uppercase tracking-wider">Entrar no Sistema</a>
        </div>
    </div>
</nav>


{{-- ══════════════════════════════════════════════════════
     SECTION 1: HERO — "Seu Despertar Começa Agora"
     ══════════════════════════════════════════════════════ --}}
<section id="hero" class="relative min-h-screen flex items-center justify-center overflow-hidden">
    {{-- Particles Canvas --}}
    <div class="absolute inset-0 z-0">
        <canvas id="particles-bg" class="w-full h-full"></canvas>
        <div class="absolute inset-0 bg-gradient-to-b from-transparent via-hud-dark/60 to-hud-dark"></div>
    </div>

    {{-- Radial Glow behind title --}}
    <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[600px] h-[600px]
                bg-neon-cyan/5 rounded-full blur-[120px] pointer-events-none"></div>

    {{-- Content --}}
    <div class="relative z-10 text-center px-6 max-w-4xl mx-auto">
        {{-- System Online Tag --}}
        <div data-animate class="inline-flex items-center gap-2 px-4 py-1.5
                    border border-neon-cyan/30 rounded-sm mb-8
                    font-mono text-xs text-neon-cyan tracking-[0.2em] uppercase">
            <span class="w-2 h-2 bg-neon-cyan rounded-full animate-pulse"></span>
            SYSTEM ONLINE
        </div>

        {{-- Title --}}
        <h1 data-animate class="font-display text-4xl sm:text-5xl md:text-7xl lg:text-8xl font-black
                   text-white leading-[0.95] tracking-tight text-glow-cyan">
            SEU DESPERTAR<br>
            <span class="bg-gradient-to-r from-neon-cyan to-neon-blue bg-clip-text text-transparent">
                COMEÇA AGORA
            </span>
        </h1>

        {{-- Subtitle --}}
        <p data-animate class="mt-6 text-base sm:text-lg md:text-xl text-slate-400 max-w-2xl mx-auto
                  font-light leading-relaxed">
            Você foi escolhido. O sistema detectou potencial em você.
            <span class="text-white font-medium">Aceite a missão</span> e desbloqueie sua evolução.
        </p>

        {{-- CTA --}}
        <div data-animate class="mt-10">
            <a href="#checkout"
               class="inline-flex items-center gap-3 px-10 py-4
                      bg-neon-cyan/10 border border-neon-cyan text-neon-cyan
                      font-display font-bold text-base sm:text-lg tracking-wider uppercase
                      rounded-sm btn-glow btn-scan animate-neon-pulse
                      hover:bg-neon-cyan/20 transition-colors duration-300 group">
                ⚔ ACEITAR MISSÃO
                <span class="group-hover:translate-x-1 transition-transform">→</span>
            </a>
        </div>

        {{-- Trust Badges --}}
        <div data-animate class="mt-8 flex flex-wrap items-center justify-center gap-6 text-xs font-mono text-slate-500">
            <span class="flex items-center gap-1.5">
                <svg class="w-4 h-4 text-rpg-green" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                +2.400 Players
            </span>
            <span class="flex items-center gap-1.5">
                <svg class="w-4 h-4 text-rpg-gold" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                4.9/5 Rating
            </span>
            <span class="flex items-center gap-1.5">
                <svg class="w-4 h-4 text-neon-cyan" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                Garantia 7 dias
            </span>
        </div>
    </div>

    {{-- Scroll Indicator --}}
    <div class="absolute bottom-8 left-1/2 -translate-x-1/2 animate-bounce text-slate-600">
        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M19 14l-7 7m0 0l-7-7m7 7V3"/>
        </svg>
    </div>
</section>


{{-- ══════════════════════════════════════════════════════
     SECTION 2: PROBLEMA — "O Mundo Real Te Prendeu"
     ══════════════════════════════════════════════════════ --}}
<section class="relative py-20 lg:py-32">
    <div class="max-w-7xl mx-auto px-6 lg:px-16">
        {{-- Section Header --}}
        <div data-animate class="text-center mb-16">
            <span class="font-mono text-xs text-rpg-red tracking-[0.2em] uppercase">⚠ ALERTA DO SISTEMA</span>
            <h2 class="mt-4 font-heading text-3xl sm:text-4xl lg:text-5xl font-bold text-white">
                O Mundo Real Te <span class="text-rpg-red">Prendeu</span>
            </h2>
            <p class="mt-4 text-slate-400 max-w-xl mx-auto">
                Debuffs ativos detectados no seu perfil. Estes são os status negativos que estão travando sua evolução.
            </p>
        </div>

        {{-- Debuff Cards --}}
        <div data-stagger class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @php
            $debuffs = [
                ['icon' => '🔥', 'title' => 'Burnout', 'desc' => 'Energia mental esgotada. Performance caindo a cada dia.', 'stat' => '-40% Energia'],
                ['icon' => '😶‍🌫️', 'title' => 'Procrastinação', 'desc' => 'Tarefas acumuladas. Paralisia por análise travando ações.', 'stat' => '-60% Ação'],
                ['icon' => '📉', 'title' => 'Estagnação', 'desc' => 'Mesmo nível há meses. Sem progresso visível nas skills.', 'stat' => '-80% XP'],
                ['icon' => '🌀', 'title' => 'Falta de Foco', 'desc' => 'Distrações constantes. Múltiplas abas abertas, zero resultados.', 'stat' => '-50% Precisão'],
                ['icon' => '😰', 'title' => 'Síndrome do Impostor', 'desc' => 'Dúvida sobre suas habilidades. Medo de agir por insegurança.', 'stat' => '-70% Confiança'],
                ['icon' => '⏰', 'title' => 'Sem Rotina', 'desc' => 'Dias caóticos sem estrutura. Reagindo ao mundo, sem liderar.', 'stat' => '-55% Disciplina'],
            ];
            @endphp

            @foreach($debuffs as $d)
            <div class="bg-hud-panel border border-rpg-red/10 rounded-sm p-6 panel-inset
                        hover:border-rpg-red/30 transition-colors duration-500 group">
                <div class="flex items-center justify-between mb-4">
                    <span class="text-3xl">{{ $d['icon'] }}</span>
                    <span class="font-mono text-xs text-rpg-red font-medium tracking-wider">{{ $d['stat'] }}</span>
                </div>
                <h3 class="font-heading text-lg font-bold text-white group-hover:text-rpg-red transition-colors">
                    {{ $d['title'] }}
                </h3>
                <p class="mt-2 text-sm text-slate-400 leading-relaxed">{{ $d['desc'] }}</p>
            </div>
            @endforeach
        </div>
    </div>
</section>


{{-- Divider --}}
<div class="divider-neon max-w-xs mx-auto"></div>


{{-- ══════════════════════════════════════════════════════
     SECTION 3: SOLUÇÃO — "O Sistema Que Vai Te Libertar"
     ══════════════════════════════════════════════════════ --}}
<section class="relative py-20 lg:py-32 overflow-hidden">
    {{-- Background glow --}}
    <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2
                w-[500px] h-[500px] bg-neon-cyan/[0.03] rounded-full blur-[100px] pointer-events-none"></div>

    <div class="relative max-w-7xl mx-auto px-6 lg:px-16">
        <div class="grid lg:grid-cols-2 gap-12 lg:gap-20 items-center">
            {{-- Left: Text --}}
            <div data-animate>
                <span class="font-mono text-xs text-neon-cyan tracking-[0.2em] uppercase">✦ SOLUÇÃO ENCONTRADA</span>
                <h2 class="mt-4 font-heading text-3xl sm:text-4xl lg:text-5xl font-bold text-white leading-tight">
                    O Sistema Que Vai <br>
                    <span class="bg-gradient-to-r from-neon-cyan to-neon-blue bg-clip-text text-transparent">
                        Te Libertar
                    </span>
                </h2>
                <p class="mt-6 text-slate-400 leading-relaxed">
                    O SAO System é um programa de transformação pessoal gamificado.
                    Cada módulo é uma skill. Cada semana é um nível.
                    Você não estuda — você <span class="text-white font-medium">treina, evolui e sobe de nível</span>.
                </p>

                <ul class="mt-8 space-y-4">
                    @foreach(['8 Módulos de treinamento avançado', 'Sistema de XP e progressão', 'Comunidade exclusiva (Guilda)', 'Suporte direto do Mestre'] as $item)
                    <li class="flex items-center gap-3">
                        <span class="flex-shrink-0 w-6 h-6 rounded-sm bg-neon-cyan/10 border border-neon-cyan/30 flex items-center justify-center">
                            <svg class="w-3.5 h-3.5 text-neon-cyan" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                            </svg>
                        </span>
                        <span class="text-sm text-slate-300">{{ $item }}</span>
                    </li>
                    @endforeach
                </ul>
            </div>

            {{-- Right: "Legendary Item" Card --}}
            <div data-animate="scale" class="relative">
                <div class="border-neon-gradient rounded-sm p-8 text-center">
                    <div class="absolute -top-3 left-1/2 -translate-x-1/2 px-4 py-1
                                bg-rpg-gold text-hud-dark font-mono text-xs font-bold uppercase tracking-wider rounded-sm">
                        ★ Item Lendário
                    </div>
                    <div class="text-6xl mt-4 mb-4 animate-float">⚔️</div>
                    <h3 class="font-display text-2xl font-bold text-white">SAO SYSTEM</h3>
                    <p class="font-mono text-xs text-rpg-gold mt-1">Programa Completo de Evolução</p>
                    <div class="mt-6 grid grid-cols-2 gap-4">
                        <div class="bg-hud-dark/60 rounded-sm p-3">
                            <span class="font-display text-2xl font-bold text-neon-cyan" data-counter="8" data-counter-suffix="">0</span>
                            <p class="text-xs text-slate-500 font-mono mt-1">Módulos</p>
                        </div>
                        <div class="bg-hud-dark/60 rounded-sm p-3">
                            <span class="font-display text-2xl font-bold text-rpg-gold" data-counter="50" data-counter-suffix="+">0</span>
                            <p class="text-xs text-slate-500 font-mono mt-1">Aulas</p>
                        </div>
                        <div class="bg-hud-dark/60 rounded-sm p-3">
                            <span class="font-display text-2xl font-bold text-rpg-green" data-counter="2400" data-counter-suffix="+">0</span>
                            <p class="text-xs text-slate-500 font-mono mt-1">Players</p>
                        </div>
                        <div class="bg-hud-dark/60 rounded-sm p-3">
                            <span class="font-display text-2xl font-bold text-white" data-counter="4" data-counter-suffix=" sem">0</span>
                            <p class="text-xs text-slate-500 font-mono mt-1">Duração</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>


{{-- ══════════════════════════════════════════════════════
     SECTION 4: MÓDULOS — "Skill Tree"
     ══════════════════════════════════════════════════════ --}}
<section id="modulos" class="relative py-20 lg:py-32">
    <div class="max-w-7xl mx-auto px-6 lg:px-16">
        {{-- Section Header --}}
        <div data-animate class="text-center mb-16">
            <span class="font-mono text-xs text-neon-cyan tracking-[0.2em] uppercase">📋 SKILL TREE</span>
            <h2 class="mt-4 font-heading text-3xl sm:text-4xl lg:text-5xl font-bold text-white">
                Módulos de <span class="bg-gradient-to-r from-neon-cyan to-neon-blue bg-clip-text text-transparent">Treinamento</span>
            </h2>
            <p class="mt-4 text-slate-400 max-w-xl mx-auto">
                Cada módulo é uma skill que você desbloqueia. Complete todos para atingir o nível máximo.
            </p>
        </div>

        {{-- Module Cards Grid --}}
        @php
        $modules = [
            ['num' => '01', 'icon' => '🧠', 'title' => 'Mentalidade de Jogador', 'desc' => 'Reprograme sua mente para operar como um Player de elite no jogo da vida.', 'xp' => 250, 'lessons' => 7],
            ['num' => '02', 'icon' => '🎯', 'title' => 'Missões & Objetivos', 'desc' => 'Defina suas quest lines com clareza. Transforme metas em missões executáveis.', 'xp' => 300, 'lessons' => 8],
            ['num' => '03', 'icon' => '⚡', 'title' => 'Produtividade Extrema', 'desc' => 'Técnicas de alto rendimento para multiplicar sua output diária.', 'xp' => 350, 'lessons' => 6],
            ['num' => '04', 'icon' => '🗡️', 'title' => 'Disciplina Forjada', 'desc' => 'Construa hábitos que funcionam no piloto automático. Sem motivação, só sistema.', 'xp' => 400, 'lessons' => 7],
            ['num' => '05', 'icon' => '📖', 'title' => 'Aprendizado Acelerado', 'desc' => 'Domine qualquer skill 3x mais rápido com técnicas de meta-aprendizado.', 'xp' => 300, 'lessons' => 5],
            ['num' => '06', 'icon' => '💰', 'title' => 'Finanças do Player', 'desc' => 'Gerencie seus recursos como um profissional. Gold management aplicado à vida.', 'xp' => 350, 'lessons' => 6],
            ['num' => '07', 'icon' => '🤝', 'title' => 'Social Engineering', 'desc' => 'Comunicação, networking e influência. Aumente seu carisma e liderança.', 'xp' => 300, 'lessons' => 5],
            ['num' => '08', 'icon' => '🏆', 'title' => 'Boss Final', 'desc' => 'Integre todas as skills. Projeto final de evolução com mentoria exclusiva.', 'xp' => 500, 'lessons' => 6],
        ];
        @endphp

        {{-- Module List (Vertical Menu Style) --}}
        <div data-stagger class="grid grid-cols-1 md:grid-cols-2 gap-4 max-w-5xl mx-auto">
            @foreach($modules as $m)
            <div class="relative group bg-hud-panel border-l-4 border-l-transparent hover:border-l-neon-cyan
                        border-y border-r border-white/5 p-4 flex items-center gap-4 transition-all duration-300
                        hover:bg-white/[0.02]">
                <div class="font-console text-2xl text-neon-cyan opacity-80 group-hover:opacity-100">{{ $m['icon'] }}</div>
                <div class="flex-1">
                    <h3 class="font-display text-base text-white tracking-wide group-hover:text-neon-cyan transition-colors">
                        <span class="text-xs text-slate-500 mr-2">{{ $m['num'] }}</span> {{ $m['title'] }}
                    </h3>
                </div>
                <div class="text-right">
                    <span class="block font-console text-xs text-rpg-gold">LV.{{ $m['lessons'] }}</span>
                    <span class="block font-mono text-[10px] text-slate-600">EXP +{{ $m['xp'] }}</span>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>


{{-- ══════════════════════════════════════════════════════
     SECTION 5: ATRIBUTOS — Status Bars
     ══════════════════════════════════════════════════════ --}}
<section class="relative py-20 lg:py-32">
    <div class="max-w-7xl mx-auto px-6 lg:px-16">
        <div class="grid lg:grid-cols-2 gap-12 lg:gap-20 items-center">
            {{-- Left: Heading --}}
            <div data-animate>
                <span class="font-mono text-xs text-rpg-gold tracking-[0.2em] uppercase">📊 CHARACTER STATS</span>
                <h2 class="mt-4 font-heading text-3xl sm:text-4xl lg:text-5xl font-bold text-white leading-tight">
                    Seus Status <br>
                    <span class="text-rpg-gold text-glow-gold">Após o Treinamento</span>
                </h2>
                <p class="mt-6 text-slate-400 leading-relaxed">
                    Ao completar o sistema, seus atributos serão transformados.
                    Veja a projeção de evolução baseada nos resultados médios dos players.
                </p>
            </div>

            {{-- Right: Status Bars --}}
            <div data-animate class="space-y-6">
                @php
                $stats = [
                    ['icon' => '🗡️', 'name' => 'Força Mental', 'value' => 85, 'color' => 'from-neon-cyan to-neon-blue'],
                    ['icon' => '📖', 'name' => 'Inteligência', 'value' => 78, 'color' => 'from-neon-blue to-blue-600'],
                    ['icon' => '🛡️', 'name' => 'Disciplina', 'value' => 92, 'color' => 'from-rpg-green to-emerald-600'],
                    ['icon' => '✨', 'name' => 'Carisma', 'value' => 65, 'color' => 'from-rpg-gold to-amber-600'],
                    ['icon' => '⚡', 'name' => 'Produtividade', 'value' => 80, 'color' => 'from-neon-cyan to-neon-blue'],
                ];
                @endphp

                @foreach($stats as $s)
                <div>
                    <div class="flex items-center justify-between mb-2">
                        <div class="flex items-center gap-3">
                            <span class="text-lg">{{ $s['icon'] }}</span>
                            <span class="font-mono text-sm font-medium text-white uppercase tracking-wider">
                                {{ $s['name'] }}
                            </span>
                        </div>
                        <span class="font-display font-bold text-neon-cyan text-lg"
                              data-counter="{{ $s['value'] }}" data-counter-suffix="%">0%</span>
                    </div>
                    <div class="h-3 bg-white/5 rounded-none overflow-hidden border border-white/5">
                        <div class="h-full bg-gradient-to-r {{ $s['color'] }} status-bar-fill"
                             style="--fill: {{ $s['value'] / 100 }}"></div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</section>


{{-- Divider --}}
<div class="divider-neon max-w-xs mx-auto"></div>


{{-- ══════════════════════════════════════════════════════
     SECTION 6: TIMELINE — "Sua Jornada"
     ══════════════════════════════════════════════════════ --}}
<section id="jornada" class="relative py-20 lg:py-32">
    <div class="max-w-3xl mx-auto px-6 lg:px-16">
        {{-- Section Header --}}
        <div data-animate class="text-center mb-16">
            <span class="font-mono text-xs text-neon-cyan tracking-[0.2em] uppercase">🗺️ MAPA DA JORNADA</span>
            <h2 class="mt-4 font-heading text-3xl sm:text-4xl lg:text-5xl font-bold text-white">
                Sua <span class="bg-gradient-to-r from-neon-cyan to-neon-blue bg-clip-text text-transparent">Jornada</span>
            </h2>
        </div>

        {{-- Timeline --}}
        <div class="relative">
            {{-- Center Line --}}
            <div class="absolute left-6 md:left-1/2 md:-translate-x-1/2 top-0 bottom-0 w-[2px] timeline-line"></div>

            @php
            $timeline = [
                ['week' => 'Semana 1', 'title' => 'Despertar', 'desc' => 'Conecte-se ao sistema. Diagnóstico completo do seu perfil e definição da build ideal.', 'icon' => '🌅'],
                ['week' => 'Semana 2', 'title' => 'Primeira Missão', 'desc' => 'Execute sua primeira quest. Implemente os fundamentos de produtividade e mentalidade.', 'icon' => '⚔️'],
                ['week' => 'Semana 3', 'title' => 'Boss Fight', 'desc' => 'Enfrente o grande desafio. Quebre padrões limitantes e avance para a fase avançada.', 'icon' => '🐉'],
                ['week' => 'Semana 4', 'title' => 'Ascensão', 'desc' => 'Integração total. Você emerge como um player completamente evoluído. New Game+ desbloqueado.', 'icon' => '🏆'],
            ];
            @endphp

            <div class="space-y-12">
                @foreach($timeline as $idx => $t)
                <div data-animate="{{ $idx % 2 === 0 ? 'slide-left' : 'slide-right' }}"
                     class="relative flex items-start gap-6 md:gap-8
                            {{ $idx % 2 === 0 ? '' : 'md:flex-row-reverse md:text-right' }}">
                    {{-- Dot --}}
                    <div class="relative z-10 flex-shrink-0
                                w-12 h-12 rounded-full
                                bg-hud-panel border-2 border-neon-cyan
                                flex items-center justify-center
                                shadow-[0_0_15px_rgba(0,245,255,0.25)]">
                        <span class="text-lg">{{ $t['icon'] }}</span>
                    </div>

                    {{-- Card --}}
                    <div class="flex-1 bg-hud-panel border border-white/5 rounded-sm p-5 panel-inset">
                        <span class="font-mono text-[11px] text-neon-cyan uppercase tracking-wider">{{ $t['week'] }}</span>
                        <h3 class="mt-2 font-heading text-lg font-bold text-white">{{ $t['title'] }}</h3>
                        <p class="mt-2 text-sm text-slate-400 leading-relaxed">{{ $t['desc'] }}</p>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</section>


{{-- ══════════════════════════════════════════════════════
     SECTION 7: DEPOIMENTOS — "Registros do Sistema"
     ══════════════════════════════════════════════════════ --}}
<section id="depoimentos" class="relative py-20 lg:py-32">
    <div class="max-w-7xl mx-auto px-6 lg:px-16">
        {{-- Section Header --}}
        <div data-animate class="text-center mb-16">
            <span class="font-mono text-xs text-rpg-green tracking-[0.2em] uppercase">✓ SYSTEM LOGS</span>
            <h2 class="mt-4 font-heading text-3xl sm:text-4xl lg:text-5xl font-bold text-white">
                Registros do <span class="text-rpg-green">Sistema</span>
            </h2>
            <p class="mt-4 text-slate-400 max-w-xl mx-auto">
                Feedbacks verificados de players que completaram o treinamento.
            </p>
        </div>

        {{-- Testimonial Cards --}}
        @php
        $testimonials = [
            ['name' => 'PlayerKaito', 'level' => 47, 'time' => 'há 3 dias', 'text' => 'Depois de aplicar o Módulo 3, minha produtividade aumentou 300%. Sério, parece hack. Em 2 semanas já tinha resultados que tentei por meses.', 'stars' => 5],
            ['name' => 'SakuraX', 'level' => 32, 'time' => 'há 1 semana', 'text' => 'A gamificação fez toda diferença. Pela primeira vez, estudar virou algo que eu realmente QUERO fazer. A comunidade é incrível também.', 'stars' => 5],
            ['name' => 'DarkBlade99', 'level' => 55, 'time' => 'há 2 semanas', 'text' => 'O módulo de disciplina me transformou. Acordo 5h da manhã agora sem despertador. O sistema funciona se você se comprometer.', 'stars' => 5],
            ['name' => 'LunaRise', 'level' => 28, 'time' => 'há 5 dias', 'text' => 'Estava cética no início, mas o formato RPG torna tudo mais envolvente. Recomendo especialmente pra quem é gamer como eu.', 'stars' => 4],
            ['name' => 'PhoenixAsh', 'level' => 41, 'time' => 'há 1 semana', 'text' => 'Boss Fight na semana 3 foi o turning point. Confrontar meus padrões limitantes pela primeira vez mudou tudo. Gratidão eterna.', 'stars' => 5],
            ['name' => 'ZeroTwo', 'level' => 39, 'time' => 'há 4 dias', 'text' => 'O ROI desse programa é absurdo. Em 1 mês recuperei o investimento aplicando as técnicas de finanças. O módulo 6 vale ouro.', 'stars' => 5],
        ];
        @endphp

        <div data-stagger class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
            @foreach($testimonials as $t)
            <div class="bg-hud-panel border border-white/5 rounded-sm overflow-hidden panel-inset
                        hover:border-rpg-green/20 transition-colors duration-500">
                {{-- Header --}}
                <div class="flex items-center gap-3 p-4 border-b border-white/5">
                    <div class="w-10 h-10 rounded-full bg-gradient-to-br from-neon-cyan/30 to-neon-blue/30
                                ring-2 ring-neon-cyan/20 flex items-center justify-center
                                font-display text-sm font-bold text-neon-cyan">
                        {{ strtoupper(substr($t['name'], 0, 1)) }}
                    </div>
                    <div>
                        <span class="font-mono text-sm text-white font-medium">{{ '@' . $t['name'] }}</span>
                        <div class="flex items-center gap-2 mt-0.5">
                            <span class="text-[10px] font-mono text-rpg-gold">Nível {{ $t['level'] }}</span>
                            <span class="text-[10px] text-slate-500">· {{ $t['time'] }}</span>
                        </div>
                    </div>
                </div>

                {{-- Log Label --}}
                <div class="px-4 py-2 bg-white/[0.02] border-b border-white/5">
                    <span class="font-mono text-[10px] text-neon-cyan/50">
                        > SYSTEM LOG: Feedback registrado
                    </span>
                </div>

                {{-- Text --}}
                <div class="p-4">
                    <p class="text-sm text-slate-300 leading-relaxed italic">
                        "{{ $t['text'] }}"
                    </p>
                    <div class="flex items-center justify-between mt-4 pt-3 border-t border-white/5">
                        <div class="flex gap-0.5 text-rpg-gold text-xs">
                            @for($i = 0; $i < $t['stars']; $i++) ⭐ @endfor
                        </div>
                        <span class="text-[10px] font-mono text-rpg-green flex items-center gap-1">
                            ✓ Compra Verificada
                        </span>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>


{{-- ══════════════════════════════════════════════════════
     SECTION 8: COMUNIDADE — "Guilda"
     ══════════════════════════════════════════════════════ --}}
<section class="relative py-20 lg:py-32">
    <div class="max-w-7xl mx-auto px-6 lg:px-16">
        <div class="grid lg:grid-cols-2 gap-12 items-center">
            {{-- Left --}}
            <div data-animate>
                <span class="font-mono text-xs text-neon-cyan tracking-[0.2em] uppercase">👥 GUILD SYSTEM</span>
                <h2 class="mt-4 font-heading text-3xl sm:text-4xl lg:text-5xl font-bold text-white leading-tight">
                    Junte-se à <br>
                    <span class="bg-gradient-to-r from-neon-cyan to-neon-blue bg-clip-text text-transparent">Guilda</span>
                </h2>
                <p class="mt-6 text-slate-400 leading-relaxed">
                    Nenhum player evolui sozinho. A Guilda é a comunidade exclusiva onde você encontra aliados,
                    troca estratégias e recebe suporte em tempo real.
                </p>

                {{-- Guild Stats --}}
                <div class="mt-8 grid grid-cols-3 gap-4">
                    <div class="bg-hud-panel border border-white/5 rounded-sm p-4 text-center panel-inset">
                        <span class="font-display text-2xl font-bold text-neon-cyan" data-counter="2400" data-counter-suffix="+">0</span>
                        <p class="text-[10px] font-mono text-slate-500 mt-1">Players</p>
                    </div>
                    <div class="bg-hud-panel border border-white/5 rounded-sm p-4 text-center panel-inset">
                        <span class="font-display text-2xl font-bold text-rpg-green" data-counter="98" data-counter-suffix="%">0</span>
                        <p class="text-[10px] font-mono text-slate-500 mt-1">Satisfação</p>
                    </div>
                    <div class="bg-hud-panel border border-white/5 rounded-sm p-4 text-center panel-inset">
                        <span class="font-display text-2xl font-bold text-rpg-gold" data-counter="150" data-counter-suffix="+">0</span>
                        <p class="text-[10px] font-mono text-slate-500 mt-1">Online Agora</p>
                    </div>
                </div>
            </div>

            {{-- Right: Party Members Simulation --}}
            <div data-animate="scale">
                <div class="bg-hud-panel border border-white/5 rounded-sm overflow-hidden panel-inset">
                    {{-- Header --}}
                    <div class="px-5 py-3 border-b border-white/5 flex items-center justify-between">
                        <span class="font-mono text-xs text-neon-cyan tracking-wider uppercase">MEMBROS ONLINE</span>
                        <span class="flex items-center gap-1.5 text-[10px] font-mono text-rpg-green">
                            <span class="w-1.5 h-1.5 bg-rpg-green rounded-full animate-pulse"></span>
                            AO VIVO
                        </span>
                    </div>

                    {{-- Member List --}}
                    <div class="divide-y divide-white/5">
                        @php
                        $members = [
                            ['name' => 'KaitoSenpai', 'role' => 'Mestre', 'level' => 99, 'color' => 'text-rpg-gold'],
                            ['name' => 'SakuraX', 'role' => 'Veterana', 'level' => 32, 'color' => 'text-neon-cyan'],
                            ['name' => 'DarkBlade99', 'role' => 'Elite', 'level' => 55, 'color' => 'text-neon-cyan'],
                            ['name' => 'NoviceLink', 'role' => 'Novato', 'level' => 5, 'color' => 'text-slate-400'],
                            ['name' => 'PhoenixAsh', 'role' => 'Avançado', 'level' => 41, 'color' => 'text-neon-blue'],
                        ];
                        @endphp

                        @foreach($members as $mb)
                        <div class="flex items-center gap-3 px-5 py-3 hover:bg-white/[0.02] transition-colors">
                            <div class="w-8 h-8 rounded-full bg-gradient-to-br from-neon-cyan/20 to-neon-blue/20
                                        flex items-center justify-center
                                        font-mono text-xs font-bold text-neon-cyan">
                                {{ strtoupper(substr($mb['name'], 0, 1)) }}
                            </div>
                            <div class="flex-1 min-w-0">
                                <span class="font-mono text-sm text-white block truncate">{{ $mb['name'] }}</span>
                                <span class="text-[10px] font-mono {{ $mb['color'] }}">{{ $mb['role'] }}</span>
                            </div>
                            <span class="font-mono text-xs text-slate-500">Lv.{{ $mb['level'] }}</span>
                            <span class="w-1.5 h-1.5 bg-rpg-green rounded-full"></span>
                        </div>
                        @endforeach
                    </div>

                    {{-- Footer --}}
                    <div class="px-5 py-3 bg-white/[0.02] border-t border-white/5">
                        <span class="font-mono text-[10px] text-slate-500">
                            + 145 players online neste momento...
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>


{{-- Divider --}}
<div class="divider-neon max-w-xs mx-auto"></div>


{{-- ══════════════════════════════════════════════════════
     SECTION 9: CHECKOUT — "Aceite a Missão Final"
     ══════════════════════════════════════════════════════ --}}
<section id="checkout" class="relative py-20 lg:py-40">
    {{-- Background glow --}}
    <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2
                w-[600px] h-[600px] bg-neon-cyan/[0.04] rounded-full blur-[120px] pointer-events-none"></div>

    <div class="relative max-w-xl mx-auto px-6 lg:px-16">
        {{-- Section Header --}}
        <div data-animate class="text-center mb-12">
            <span class="font-mono text-xs text-rpg-gold tracking-[0.2em] uppercase">⚔ MISSÃO FINAL</span>
            <h2 class="mt-4 font-heading text-3xl sm:text-4xl lg:text-5xl font-bold text-white">
                Aceite a <span class="text-rpg-gold text-glow-gold">Missão</span>
            </h2>
        </div>

        {{-- Pricing Card (Trade Window Style) --}}
        <div data-animate="scale" class="relative max-w-lg mx-auto bg-hud-panel border-2 border-slate-700/50 rounded-lg p-1">
            <div class="bg-hud-dark/90 p-6 border border-white/5 relative">
                {{-- Window Header --}}
                <div class="absolute -top-3 left-4 bg-hud-dark px-2 font-console text-xs text-neon-cyan uppercase tracking-widest border border-neon-cyan/30">
                    TRADE WINDOW
                </div>

                {{-- Item Slot --}}
                <div class="flex gap-4 mb-6">
                    <div class="w-20 h-20 bg-black/40 border border-white/10 flex items-center justify-center text-4xl animate-pulse">
                        ⚔️
                    </div>
                    <div>
                        <h3 class="font-display text-xl text-white">SAO SYSTEM ACCESS</h3>
                        <p class="font-console text-xs text-rpg-gold mt-1">Class: Legendary Item</p>
                        <p class="font-mono text-xs text-slate-500 mt-2 max-w-xs">Grants full access to all training modules and Guild features.</p>
                    </div>
                </div>

                <div class="h-px bg-white/10 my-4"></div>

                {{-- Cost --}}
                <div class="flex justify-between items-end mb-6">
                    <span class="font-console text-sm text-slate-400">COST:</span>
                    <div class="text-right">
                        <span class="block text-slate-500 line-through text-xs">R$ 497</span>
                        <span class="font-display text-3xl text-neon-cyan text-glow-cyan">R$ 197</span>
                    </div>
                </div>

                {{-- Accept Button --}}
                <a href="#" class="block w-full text-center py-3 bg-neon-cyan/20 border border-neon-cyan text-neon-cyan font-bold font-display uppercase tracking-wider hover:bg-neon-cyan/30 transition-all clip-button">
                    ⭕ ACCEPT TRADE
                </a>
            </div>
        </div>

        {{-- Urgency Note --}}
        <div data-animate class="mt-8 text-center">
            <p class="text-xs font-mono text-slate-500">
                <span class="text-rpg-gold">⚠</span> Vagas limitadas para garantir qualidade do suporte na Guilda.
            </p>
        </div>
    </div>
</section>


{{-- ══════════════════════════════════════════════════════
     FOOTER
     ══════════════════════════════════════════════════════ --}}
<footer class="border-t border-white/5 py-12">
    <div class="max-w-7xl mx-auto px-6 lg:px-16">
        <div class="flex flex-col md:flex-row items-center justify-between gap-6">
            {{-- Logo --}}
            <div class="flex items-center gap-2">
                <span class="font-display text-lg font-bold text-neon-cyan tracking-wider">SAO</span>
                <span class="font-mono text-xs text-slate-500">SYSTEM v2.0</span>
            </div>

            {{-- Links --}}
            <div class="flex items-center gap-6">
                <a href="#" class="font-mono text-xs text-slate-500 hover:text-neon-cyan transition-colors">Termos de Uso</a>
                <a href="#" class="font-mono text-xs text-slate-500 hover:text-neon-cyan transition-colors">Política de Privacidade</a>
                <a href="#" class="font-mono text-xs text-slate-500 hover:text-neon-cyan transition-colors">Contato</a>
            </div>

            {{-- Copyright --}}
            <span class="font-mono text-[10px] text-slate-600">
                © {{ date('Y') }} SAO System. Todos os direitos reservados.
            </span>
        </div>
    </div>
</footer>

</body>
</html>
