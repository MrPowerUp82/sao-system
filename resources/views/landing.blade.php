<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>SAO System — Interface de Aincrad</title>
    <meta name="description" content="O sistema que vai transformar sua vida. Desbloqueie seu potencial com módulos de treinamento estilo RPG.">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Rajdhani:wght@400;500;600;700&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">

    @vite(['resources/css/landing.css', 'resources/js/landing.js'])
</head>
<body class="antialiased">

{{-- ══════════════════════════════════════════════════════
     HEADER — Player HUD (Fixed Top)
     ══════════════════════════════════════════════════════ --}}
<header class="fixed top-0 left-0 right-0 z-50 py-3 px-4 md:px-8 flex items-center justify-between pointer-events-none">
    {{-- Player Info (Left) --}}
    <div class="sao-panel px-4 py-2.5 flex items-center gap-3 pointer-events-auto">
        <div class="w-9 h-9 rounded-full bg-gradient-to-br from-orange-200 to-orange-400 border-2 border-white flex items-center justify-center text-white font-bold text-sm shadow-inner">
            P1
        </div>
        <div>
            <p class="font-bold text-base leading-none text-gray-800 tracking-wide">Kirito</p>
            <p class="text-xs text-sao-orange font-semibold mt-0.5">LV. 1 <span class="text-gray-400 font-normal">// AWAITING LINK</span></p>
        </div>
    </div>

    {{-- HP / XP Bar (Center-Right) --}}
    <div class="sao-panel px-4 py-2.5 w-60 md:w-80 pointer-events-auto">
        <div class="flex justify-between items-center mb-1.5">
            <span class="text-[11px] font-bold text-gray-500 tracking-wider uppercase">HP</span>
            <span id="hp-text" class="text-[11px] font-bold text-gray-600 tabular-nums">0 / 12500</span>
        </div>
        <div class="hp-bar-container">
            <div class="hp-bar-fill" id="hp-bar"></div>
        </div>
    </div>
</header>

{{-- ══════════════════════════════════════════════════════
     FLOATING VERTICAL MENU (Right Edge)
     ══════════════════════════════════════════════════════ --}}
<nav class="fixed top-1/2 right-4 md:right-6 -translate-y-1/2 z-50 flex flex-col gap-3">
    @foreach([
        ['icon' => '⌂', 'label' => 'Início', 'target' => '#hero'],
        ['icon' => '⚔', 'label' => 'Skill Tree', 'target' => '#modules'],
        ['icon' => '📊', 'label' => 'Status', 'target' => '#status'],
        ['icon' => '🗺', 'label' => 'Jornada', 'target' => '#jornada'],
        ['icon' => '✉', 'label' => 'Registros', 'target' => '#depoimentos'],
        ['icon' => '⊕', 'label' => 'Aceitar Missão', 'target' => '#checkout'],
    ] as $nav)
    <a href="{{ $nav['target'] }}"
       class="sao-nav-btn relative group"
       title="{{ $nav['label'] }}">
        <span>{{ $nav['icon'] }}</span>
        <div class="absolute right-16 top-1/2 -translate-y-1/2 opacity-0 group-hover:opacity-100
                    transition-opacity duration-200
                    sao-panel px-3 py-1.5 text-xs font-bold text-gray-600 whitespace-nowrap">
            {{ $nav['label'] }}
        </div>
    </a>
    @endforeach
</nav>

{{-- ══════════════════════════════════════════════════════
     MAIN CONTENT
     ══════════════════════════════════════════════════════ --}}
<main class="relative z-10 pt-28 pb-20">

    {{-- ─── HERO ─── --}}
    <section id="hero" class="min-h-[85vh] flex items-center justify-center px-6">
        <div data-animate class="sao-panel p-10 md:p-16 max-w-3xl mx-auto text-center">
            <p class="text-sm font-semibold text-sao-orange tracking-[0.2em] uppercase mb-4">SYSTEM NOTIFICATION</p>
            <h1 class="sao-title text-5xl sm:text-6xl md:text-7xl font-black leading-[0.95] text-gray-800">
                <span class="bracket">「</span>LINK START<span class="bracket">」</span>
            </h1>
            <p class="mt-6 text-lg md:text-xl text-gray-600 max-w-xl mx-auto leading-relaxed font-medium">
                Bem-vindo a Aincrad. O sistema detectou potencial em você.
                Aceite a missão e desbloqueie sua evolução.
            </p>

            <div class="mt-10 flex flex-col sm:flex-row items-center justify-center gap-4">
                <a href="#checkout" class="sao-btn">
                    <span class="icon-circle">⊕</span>
                    ACEITAR MISSÃO
                </a>
            </div>

            {{-- Trust --}}
            <div class="mt-8 flex flex-wrap justify-center gap-6 text-xs text-gray-500 font-medium">
                <span>✓ +2.400 Players</span>
                <span>★ 4.9/5 Rating</span>
                <span>🛡 Garantia 7 Dias</span>
            </div>
        </div>
    </section>

    <hr class="sao-divider max-w-xs mx-auto">

    {{-- ─── PROBLEMA ─── --}}
    <section class="py-16 md:py-24 px-6">
        <div class="max-w-6xl mx-auto">
            <div data-animate class="text-center mb-12">
                <h2 class="sao-title text-3xl md:text-4xl font-bold">
                    <span class="bracket">「</span>DEBUFFS DETECTADOS<span class="bracket">」</span>
                </h2>
                <p class="mt-3 text-gray-500 max-w-lg mx-auto">Status negativos travando sua evolução.</p>
            </div>

            @php
            $debuffs = [
                ['icon' => '🔥', 'title' => 'Burnout', 'desc' => 'Energia mental esgotada.', 'stat' => '-40% Energia'],
                ['icon' => '😶‍🌫️', 'title' => 'Procrastinação', 'desc' => 'Paralisia por análise travando ações.', 'stat' => '-60% Ação'],
                ['icon' => '📉', 'title' => 'Estagnação', 'desc' => 'Sem progresso visível nas skills.', 'stat' => '-80% XP'],
                ['icon' => '🌀', 'title' => 'Falta de Foco', 'desc' => 'Distrações constantes, zero resultados.', 'stat' => '-50% Precisão'],
                ['icon' => '😰', 'title' => 'Impostor', 'desc' => 'Medo de agir por insegurança.', 'stat' => '-70% Confiança'],
                ['icon' => '⏰', 'title' => 'Sem Rotina', 'desc' => 'Dias caóticos sem estrutura.', 'stat' => '-55% Disciplina'],
            ];
            @endphp

            <div data-animate class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
                @foreach($debuffs as $d)
                <div class="sao-panel p-5 group">
                    <div class="flex items-center justify-between mb-3">
                        <span class="text-2xl">{{ $d['icon'] }}</span>
                        <span class="text-xs font-bold text-red-500 tracking-wider">{{ $d['stat'] }}</span>
                    </div>
                    <h3 class="font-bold text-lg text-gray-800 group-hover:text-sao-orange transition-colors">{{ $d['title'] }}</h3>
                    <p class="mt-1 text-sm text-gray-500">{{ $d['desc'] }}</p>
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
                    <h2 class="sao-title text-3xl md:text-4xl font-bold">
                        <span class="bracket">「</span>O SISTEMA<span class="bracket">」</span>
                    </h2>
                    <p class="mt-4 text-gray-600 leading-relaxed text-lg">
                        O SAO System é um programa de transformação pessoal gamificado.
                        Cada módulo é uma skill. Cada semana é um nível.
                        Você não estuda — você <strong>treina, evolui e sobe de nível</strong>.
                    </p>
                    <ul class="mt-6 space-y-3">
                        @foreach(['8 Módulos de treinamento', 'Sistema de XP e progressão', 'Comunidade exclusiva (Guilda)', 'Suporte direto do Mestre'] as $item)
                        <li class="flex items-center gap-3 text-gray-700">
                            <span class="w-6 h-6 rounded-full bg-sao-orange text-white flex items-center justify-center text-xs font-bold shadow-sm">✓</span>
                            <span class="font-medium">{{ $item }}</span>
                        </li>
                        @endforeach
                    </ul>
                </div>

                {{-- Stats Card --}}
                <div data-animate class="sao-panel p-8 text-center">
                    <div class="text-5xl mb-4">⚔️</div>
                    <h3 class="sao-title text-xl font-bold">
                        <span class="bracket">「</span>SAO SYSTEM<span class="bracket">」</span>
                    </h3>
                    <p class="text-xs text-sao-orange font-semibold mt-1">PROGRAMA COMPLETO DE EVOLUÇÃO</p>
                    <div class="mt-6 grid grid-cols-2 gap-3">
                        @foreach([
                            ['val' => '8', 'label' => 'Módulos'],
                            ['val' => '50+', 'label' => 'Aulas'],
                            ['val' => '2.4k+', 'label' => 'Players'],
                            ['val' => '4 sem', 'label' => 'Duração'],
                        ] as $stat)
                        <div class="bg-white/50 rounded-lg p-3 border border-gray-200">
                            <span class="text-2xl font-black text-gray-800">{{ $stat['val'] }}</span>
                            <p class="text-[10px] text-gray-500 font-semibold mt-0.5 uppercase tracking-wider">{{ $stat['label'] }}</p>
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
                <h2 class="sao-title text-3xl md:text-4xl font-bold">
                    <span class="bracket">「</span>SKILL TREE<span class="bracket">」</span>
                </h2>
                <p class="mt-3 text-gray-500 max-w-lg mx-auto">Desbloqueie cada skill para atingir o nível máximo.</p>
            </div>

            @php
            $modules = [
                ['num' => '01', 'icon' => '🧠', 'title' => 'Mentalidade de Jogador', 'desc' => 'Reprograme sua mente para operar como um Player de elite.', 'xp' => 250, 'lv' => 7],
                ['num' => '02', 'icon' => '🎯', 'title' => 'Missões & Objetivos', 'desc' => 'Transforme metas em missões executáveis.', 'xp' => 300, 'lv' => 8],
                ['num' => '03', 'icon' => '⚡', 'title' => 'Produtividade Extrema', 'desc' => 'Técnicas de alto rendimento para multiplicar seu output.', 'xp' => 350, 'lv' => 6],
                ['num' => '04', 'icon' => '🗡️', 'title' => 'Disciplina Forjada', 'desc' => 'Hábitos no piloto automático. Sem motivação, só sistema.', 'xp' => 400, 'lv' => 7],
                ['num' => '05', 'icon' => '📖', 'title' => 'Aprendizado Acelerado', 'desc' => 'Domine qualquer skill 3x mais rápido.', 'xp' => 300, 'lv' => 5],
                ['num' => '06', 'icon' => '💰', 'title' => 'Finanças do Player', 'desc' => 'Gold management aplicado à vida real.', 'xp' => 350, 'lv' => 6],
                ['num' => '07', 'icon' => '🤝', 'title' => 'Social Engineering', 'desc' => 'Comunicação, networking e influência.', 'xp' => 300, 'lv' => 5],
                ['num' => '08', 'icon' => '🏆', 'title' => 'Boss Final', 'desc' => 'Integre tudo. Projeto final com mentoria exclusiva.', 'xp' => 500, 'lv' => 6],
            ];
            @endphp

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-5">
                @foreach($modules as $m)
                <div data-animate class="sao-quest-panel p-5 flex flex-col">
                    {{-- Quest Header --}}
                    <div class="flex items-center justify-between mb-3">
                        <span class="text-xs font-bold text-sao-orange tracking-wider">QUEST {{ $m['num'] }}</span>
                        <span class="text-[10px] font-bold text-gray-400">+{{ $m['xp'] }} EXP</span>
                    </div>
                    {{-- Icon & Title --}}
                    <div class="text-3xl mb-2">{{ $m['icon'] }}</div>
                    <h4 class="font-bold text-base text-gray-800 leading-snug">{{ $m['title'] }}</h4>
                    <p class="text-xs text-gray-500 mt-1 flex-1">{{ $m['desc'] }}</p>
                    {{-- Level Requirement --}}
                    <div class="mt-4 pt-3 border-t border-gray-200 flex items-center justify-between">
                        <span class="text-[10px] font-bold text-gray-400 uppercase">{{ $m['lv'] }} Aulas</span>
                        <span class="text-sao-orange text-lg font-bold">⊕</span>
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
                    <h2 class="sao-title text-3xl md:text-4xl font-bold">
                        <span class="bracket">「</span>CHARACTER STATUS<span class="bracket">」</span>
                    </h2>
                    <p class="mt-4 text-gray-600 leading-relaxed">
                        Projeção de evolução baseada nos resultados médios dos players que completaram o treinamento.
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

                    @foreach($stats as $s)
                    <div>
                        <div class="flex justify-between mb-1">
                            <span class="text-xs font-bold text-gray-600 tracking-wider uppercase">{{ $s['name'] }}</span>
                            <span class="text-xs font-bold text-sao-orange">{{ $s['val'] }}%</span>
                        </div>
                        <div class="hp-bar-container">
                            <div class="hp-bar-fill status-fill" style="--target-width: {{ $s['val'] }}%"></div>
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
                <h2 class="sao-title text-3xl md:text-4xl font-bold">
                    <span class="bracket">「</span>QUEST MAP<span class="bracket">」</span>
                </h2>
            </div>

            <div class="relative">
                <div class="absolute left-6 md:left-1/2 md:-translate-x-1/2 top-0 bottom-0 w-0.5 bg-gradient-to-b from-sao-orange/60 via-sao-orange/30 to-transparent"></div>

                @php
                $quests = [
                    ['week' => 'Semana 1', 'title' => 'Despertar', 'desc' => 'Conecte-se ao sistema. Diagnóstico completo e definição da build ideal.', 'icon' => '🌅'],
                    ['week' => 'Semana 2', 'title' => 'Primeira Missão', 'desc' => 'Execute sua primeira quest. Implemente os fundamentos.', 'icon' => '⚔️'],
                    ['week' => 'Semana 3', 'title' => 'Boss Fight', 'desc' => 'Quebre padrões limitantes. Avance para a fase avançada.', 'icon' => '🐉'],
                    ['week' => 'Semana 4', 'title' => 'Ascensão', 'desc' => 'Integração total. New Game+ desbloqueado.', 'icon' => '🏆'],
                ];
                @endphp

                <div class="space-y-10">
                    @foreach($quests as $idx => $q)
                    <div data-animate class="relative flex items-start gap-5 {{ $idx % 2 !== 0 ? 'md:flex-row-reverse md:text-right' : '' }}">
                        <div class="z-10 flex-shrink-0 w-12 h-12 sao-panel flex items-center justify-center rounded-full text-xl shadow-lg">
                            {{ $q['icon'] }}
                        </div>
                        <div class="flex-1 sao-panel p-5">
                            <span class="text-xs font-bold text-sao-orange tracking-wider">{{ $q['week'] }}</span>
                            <h3 class="mt-1 font-bold text-lg text-gray-800">{{ $q['title'] }}</h3>
                            <p class="mt-1 text-sm text-gray-500">{{ $q['desc'] }}</p>
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
                <h2 class="sao-title text-3xl md:text-4xl font-bold">
                    <span class="bracket">「</span>SYSTEM LOGS<span class="bracket">」</span>
                </h2>
                <p class="mt-3 text-gray-500 max-w-lg mx-auto">Feedbacks verificados dos players.</p>
            </div>

            @php
            $testimonials = [
                ['name' => 'PlayerKaito', 'lv' => 47, 'text' => 'Minha produtividade aumentou 300%. Em 2 semanas já tinha resultados que tentei por meses.', 'stars' => 5],
                ['name' => 'SakuraX', 'lv' => 32, 'text' => 'A gamificação fez toda diferença. Pela primeira vez, estudar virou algo que eu QUERO fazer.', 'stars' => 5],
                ['name' => 'DarkBlade99', 'lv' => 55, 'text' => 'O módulo de disciplina me transformou. Acordo 5h da manhã sem despertador.', 'stars' => 5],
                ['name' => 'LunaRise', 'lv' => 28, 'text' => 'O formato RPG torna tudo envolvente. Recomendo pra quem é gamer como eu.', 'stars' => 4],
                ['name' => 'PhoenixAsh', 'lv' => 41, 'text' => 'Boss Fight na semana 3 foi o turning point. Mudou tudo.', 'stars' => 5],
                ['name' => 'ZeroTwo', 'lv' => 39, 'text' => 'Em 1 mês recuperei o investimento. O módulo 6 vale ouro.', 'stars' => 5],
            ];
            @endphp

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
                @foreach($testimonials as $t)
                <div data-animate class="sao-panel p-5">
                    <div class="flex items-center gap-3 mb-3">
                        <div class="w-9 h-9 rounded-full bg-gradient-to-br from-orange-200 to-orange-400 flex items-center justify-center text-white text-sm font-bold">
                            {{ strtoupper(substr($t['name'], 0, 1)) }}
                        </div>
                        <div>
                            <span class="font-bold text-sm text-gray-800">{{ '@' . $t['name'] }}</span>
                            <span class="block text-[10px] text-sao-orange font-semibold">Nível {{ $t['lv'] }}</span>
                        </div>
                    </div>
                    <p class="text-sm text-gray-600 italic leading-relaxed">"{{ $t['text'] }}"</p>
                    <div class="mt-3 pt-3 border-t border-gray-200 flex justify-between items-center">
                        <div class="text-yellow-500 text-xs">@for($i = 0; $i < $t['stars']; $i++)⭐@endfor</div>
                        <span class="text-[10px] text-green-600 font-bold">✓ Verificado</span>
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
                <h2 class="sao-title text-3xl md:text-4xl font-bold">
                    <span class="bracket">「</span>MISSÃO FINAL<span class="bracket">」</span>
                </h2>
            </div>

            <div data-animate class="sao-panel p-1.5">
                <div class="bg-white/60 backdrop-blur-sm p-6 md:p-8 rounded-[10px] border border-gray-200/50">
                    {{-- Item --}}
                    <div class="flex items-start gap-5 mb-6">
                        <div class="w-20 h-20 bg-gradient-to-br from-orange-50 to-orange-100 rounded-xl border-2 border-sao flex items-center justify-center shadow-inner flex-shrink-0">
                            <span class="text-4xl">📜</span>
                        </div>
                        <div>
                            <h3 class="font-bold text-xl text-gray-800">SAO SYSTEM ACCESS</h3>
                            <p class="text-xs text-sao-orange font-bold mt-0.5">CLASS: UNIQUE ITEM</p>
                            <p class="text-xs text-gray-500 mt-2 leading-relaxed">Acesso completo a todos os módulos, Guilda, suporte e atualizações vitalícias.</p>
                        </div>
                    </div>

                    <hr class="sao-divider">

                    {{-- Features --}}
                    <ul class="space-y-2.5 mb-6 text-sm">
                        @foreach(['8 Módulos de treinamento', 'Guilda (comunidade vitalícia)', 'Suporte direto do Mestre', 'Missões Extras (bônus)', 'Atualizações vitalícias', 'Certificado de Conclusão'] as $f)
                        <li class="flex items-center gap-2.5 text-gray-700">
                            <span class="w-5 h-5 rounded-full bg-sao-orange text-white flex items-center justify-center text-[10px] font-bold flex-shrink-0">✓</span>
                            {{ $f }}
                        </li>
                        @endforeach
                    </ul>

                    <hr class="sao-divider">

                    {{-- Price --}}
                    <div class="flex items-end justify-between mb-6">
                        <span class="text-sm font-bold text-gray-500 uppercase tracking-wider">Cost:</span>
                        <div class="text-right">
                            <span class="block text-sm text-gray-400 line-through">R$ 497</span>
                            <span class="text-4xl font-black text-gray-800">R$ 197</span>
                            <p class="text-[10px] text-gray-500 mt-0.5">ou 12x de R$ 19,70</p>
                        </div>
                    </div>

                    {{-- CTA --}}
                    <a href="#" class="sao-btn w-full justify-center text-lg py-4">
                        <span class="icon-circle text-xl">⊕</span>
                        ACCEPT TRADE
                    </a>

                    {{-- Guarantee --}}
                    <p class="mt-4 text-center text-xs text-gray-500">
                        🛡️ Garantia de 7 dias — Sem risco. Devolvemos 100%.
                    </p>
                </div>
            </div>
        </div>
    </section>

</main>

{{-- ─── FOOTER ─── --}}
<footer class="text-center py-10 text-xs text-gray-400 font-medium">
    <p>SAO System Interface // Aincrad Floor 1</p>
    <p class="mt-1">© {{ date('Y') }} SAO System. Todos os direitos reservados.</p>
</footer>

</body>
</html>
