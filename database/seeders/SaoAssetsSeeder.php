<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Http;
use App\Models\Monster;
use App\Models\ShopItem;

class SaoAssetsSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Compile Monsters List
        $monstersData = [
            // Common (Andares 1-10)
            [
                'name' => 'Frenzy Boar',
                'wiki_title' => 'Frenzy Boar',
                'category' => 'common',
                'floor_min' => 1,
                'floor_max' => 10,
                'specific_floor' => null,
                'description' => 'Javali enfurecido clássico. Representa pequenas dívidas e gastos por impulso que atacam no início do jogo.',
                'icon' => '🐗',
                'xp_reward' => 100,
            ],
            [
                'name' => 'Ruin Kobold Trooper',
                'wiki_title' => 'Ruin Kobold Trooper',
                'category' => 'common',
                'floor_min' => 1,
                'floor_max' => 10,
                'specific_floor' => null,
                'description' => 'Guerreiro kobold das ruínas. Representa a procrastinação e tarefas diárias adiadas.',
                'icon' => '👺',
                'xp_reward' => 120,
            ],
            [
                'name' => 'Ruin Kobold Sentinel',
                'wiki_title' => 'Ruin Kobold Sentinel',
                'category' => 'common',
                'floor_min' => 1,
                'floor_max' => 10,
                'specific_floor' => null,
                'description' => 'Sentinela das ruínas de Aincrad. Representa compromissos menores esquecidos.',
                'icon' => '🛡️',
                'xp_reward' => 130,
            ],
            [
                'name' => 'Little Nepenthes',
                'wiki_title' => 'Little Nepenthes',
                'category' => 'common',
                'floor_min' => 1,
                'floor_max' => 10,
                'specific_floor' => null,
                'description' => 'Planta carnívora jovem. Representa gastos recorrentes desnecessários e assinaturas esquecidas que sugam seus Cols.',
                'icon' => '🪴',
                'xp_reward' => 110,
            ],
            [
                'name' => 'Scavenge Toad',
                'wiki_title' => 'Scavenge Toad',
                'category' => 'common',
                'floor_min' => 1,
                'floor_max' => 10,
                'specific_floor' => null,
                'description' => 'Sapo coletor. Representa oportunidades de cashback e descontos perdidos por falta de atenção.',
                'icon' => '🐸',
                'xp_reward' => 100,
            ],
            [
                'name' => 'Dire Wolf',
                'wiki_title' => 'Dire Wolf',
                'category' => 'common',
                'floor_min' => 1,
                'floor_max' => 10,
                'specific_floor' => null,
                'description' => 'Lobo feroz de Aincrad. Representa pequenas emergências financeiras surpresa no dia a dia.',
                'icon' => '🐺',
                'xp_reward' => 140,
            ],
            [
                'name' => 'Giant Ant',
                'wiki_title' => 'Giant Ant',
                'category' => 'common',
                'floor_min' => 1,
                'floor_max' => 10,
                'specific_floor' => null,
                'description' => 'Formiga gigante que ataca em bando. Pequenos gastos diários invisíveis que acumulam sorrateiramente.',
                'icon' => '🐜',
                'xp_reward' => 100,
            ],
            [
                'name' => 'GeoCrawler',
                'wiki_title' => 'GeoCrawler',
                'category' => 'common',
                'floor_min' => 1,
                'floor_max' => 10,
                'specific_floor' => null,
                'description' => 'Rastejador de rochas. Tarefas e relatórios burocráticos chatos que travam seu progresso.',
                'icon' => '🐛',
                'xp_reward' => 110,
            ],

            // Intermediate (Andares 11-50)
            [
                'name' => 'Baran the General Taurus',
                'wiki_title' => 'Baran the General Taurus',
                'category' => 'intermediate',
                'floor_min' => 11,
                'floor_max' => 50,
                'specific_floor' => null,
                'description' => 'General Touro do segundo andar de Aincrad. Representa despesas pesadas e descontrole financeiro temporário.',
                'icon' => '🐃',
                'xp_reward' => 250,
            ],
            [
                'name' => 'Black Minotaurus',
                'wiki_title' => 'Black Minotaurus',
                'category' => 'intermediate',
                'floor_min' => 11,
                'floor_max' => 50,
                'specific_floor' => null,
                'description' => 'Minotauro Negro. Um debuff persistente que exige foco para ser derrotado.',
                'icon' => '😈',
                'xp_reward' => 260,
            ],
            [
                'name' => 'Golden Minotaurus',
                'wiki_title' => 'Golden Minotaurus',
                'category' => 'intermediate',
                'floor_min' => 11,
                'floor_max' => 50,
                'specific_floor' => null,
                'description' => 'Minotauro Dourado. Um monstro de cobiça que atrai gastos de luxo insustentáveis.',
                'icon' => '🪙',
                'xp_reward' => 280,
            ],
            [
                'name' => 'Killer Mantis',
                'wiki_title' => 'Killer Mantis',
                'category' => 'intermediate',
                'floor_min' => 11,
                'floor_max' => 50,
                'specific_floor' => null,
                'description' => 'Louva-a-deus assassino. Gastos cortantes e inesperados na manutenção de equipamentos essenciais.',
                'icon' => '🦗',
                'xp_reward' => 240,
            ],
            [
                'name' => 'Trembling Ox',
                'wiki_title' => 'Trembling Ox',
                'category' => 'intermediate',
                'floor_min' => 11,
                'floor_max' => 50,
                'specific_floor' => null,
                'description' => 'Boi Trêmulo. Representa a instabilidade financeira e a falta de uma reserva sólida para imprevistos.',
                'icon' => '🐂',
                'xp_reward' => 230,
            ],
            [
                'name' => 'Thicket Spider',
                'wiki_title' => 'Thicket Spider',
                'category' => 'intermediate',
                'floor_min' => 11,
                'floor_max' => 50,
                'specific_floor' => null,
                'description' => 'Aranha de teia grossa. Representa contratos de longo prazo complexos e difíceis de rescindir.',
                'icon' => '🕷️',
                'xp_reward' => 250,
            ],
            [
                'name' => 'Granite Elemental',
                'wiki_title' => 'Granite Elemental',
                'category' => 'intermediate',
                'floor_min' => 26,
                'floor_max' => 50,
                'specific_floor' => null,
                'description' => 'Elemental de Granito. Representa investimentos em ativos muito rígidos ou de baixíssima liquidez.',
                'icon' => '🪨',
                'xp_reward' => 300,
            ],
            [
                'name' => 'Demonic Servant',
                'wiki_title' => 'Demonic Servant',
                'category' => 'intermediate',
                'floor_min' => 26,
                'floor_max' => 50,
                'specific_floor' => null,
                'description' => 'Servo Demoníaco. Representa a tentação constante de compras supérfluas e marketing agressivo de consumo.',
                'icon' => '👿',
                'xp_reward' => 290,
            ],
            [
                'name' => 'Land Anemone',
                'wiki_title' => 'Land Anemone',
                'category' => 'intermediate',
                'floor_min' => 26,
                'floor_max' => 50,
                'specific_floor' => null,
                'description' => 'Anêmona Terrestre. Suga seus Cols passivamente com taxas administrativas e tarifas bancárias ocultas.',
                'icon' => '🪸',
                'xp_reward' => 280,
            ],
            [
                'name' => 'Nephila Regina',
                'wiki_title' => 'Nephila Regina',
                'category' => 'intermediate',
                'floor_min' => 26,
                'floor_max' => 50,
                'specific_floor' => null,
                'description' => 'Rainha Aranha Tecelã. Teias de dependência geradas pelo uso descontrolado de empréstimos.',
                'icon' => '🕷️',
                'xp_reward' => 320,
            ],
            [
                'name' => 'Wadjet the Flaming Serpent',
                'wiki_title' => 'Wadjet the Flaming Serpent',
                'category' => 'intermediate',
                'floor_min' => 26,
                'floor_max' => 50,
                'specific_floor' => null,
                'description' => 'Serpente Flamejante. Despesas voláteis que queimam o caixa rapidamente (como festas ou veículos).',
                'icon' => '🐍',
                'xp_reward' => 350,
            ],

            // Elite (Andares 51-75)
            [
                'name' => 'The Four-Armed Giant',
                'wiki_title' => 'The Four-Armed Giant',
                'category' => 'elite',
                'floor_min' => 51,
                'floor_max' => 75,
                'specific_floor' => null,
                'description' => 'Gigante de quatro braços. Representa a sobrecarga de multitarefas ou conciliar múltiplas rendas extras.',
                'icon' => '🤖',
                'xp_reward' => 500,
            ],

            // Master (Andares 76-100)
            [
                'name' => 'Armachthys',
                'wiki_title' => 'Armachthys',
                'category' => 'master',
                'floor_min' => 76,
                'floor_max' => 100,
                'specific_floor' => null,
                'description' => 'Monstro couraçado de alto nível. Representa grandes movimentações de carteira e rebalanceamento de ações.',
                'icon' => '🦂',
                'xp_reward' => 800,
            ],
            [
                'name' => 'Thrym',
                'wiki_title' => 'Thrym',
                'category' => 'master',
                'floor_min' => 76,
                'floor_max' => 100,
                'specific_floor' => null,
                'description' => 'Rei dos Gigantes de Gelo de Jötunheimr. Representa ciclos macroeconômicos de recessão e a inflação corrosiva.',
                'icon' => '❄️',
                'xp_reward' => 900,
            ],

            // Specific Floor Bosses
            [
                'name' => 'Illfang the Kobold Lord',
                'wiki_title' => 'Illfang the Kobold Lord',
                'category' => 'boss',
                'floor_min' => 1,
                'floor_max' => 1,
                'specific_floor' => 1,
                'description' => 'Chefe do 1º andar de Aincrad. O primeiro grande desafio financeiro que destrava sua jornada.',
                'icon' => '👹',
                'xp_reward' => 1000,
            ],
            [
                'name' => 'Kagachi the Samurai Lord',
                'wiki_title' => 'Kagachi the Samurai Lord',
                'category' => 'boss',
                'floor_min' => 10,
                'floor_max' => 10,
                'specific_floor' => 10,
                'description' => 'Lorde Samurai veloz e letal do andar 10. Representa a consolidação dos primeiros hábitos de poupança.',
                'icon' => '⚔️',
                'xp_reward' => 1200,
            ],
            [
                'name' => 'Asterius the Taurus King',
                'wiki_title' => 'Asterius the Taurus King',
                'category' => 'boss',
                'floor_min' => 22,
                'floor_max' => 22,
                'specific_floor' => 22,
                'description' => 'Chefe touro gigante do andar 22. Representa o desafio de acumular o capital inicial para metas residenciais.',
                'icon' => '🐂',
                'xp_reward' => 1500,
            ],
            [
                'name' => 'Wyrm of the Snow',
                'wiki_title' => "X'rphan the White Wyrm",
                'category' => 'boss',
                'floor_min' => 48,
                'floor_max' => 48,
                'specific_floor' => 48,
                'description' => 'O dragão das neves do andar 48, onde Kirito e Lisbeth encontraram o cristalito. Representa a consolidação de investimentos.',
                'icon' => '🐉',
                'xp_reward' => 2000,
            ],
            [
                'name' => 'The Fatal Scythe',
                'wiki_title' => 'The Fatal Scythe',
                'category' => 'boss',
                'floor_min' => 74,
                'floor_max' => 74,
                'specific_floor' => 74,
                'description' => 'O temível ceifador subterrâneo. Representa o risco iminente de colapso por falta de diversificação de ativos.',
                'icon' => '☠️',
                'xp_reward' => 2500,
            ],
            [
                'name' => 'The Gleam Eyes',
                'wiki_title' => 'The Gleam Eyes',
                'category' => 'boss',
                'floor_min' => 75,
                'floor_max' => 75,
                'specific_floor' => 75,
                'description' => 'O demônio de olhos brilhantes azulados. O maior teste de consistência financeira da linha de frente.',
                'icon' => '👹',
                'xp_reward' => 3000,
            ],
            [
                'name' => 'The Skull Reaper',
                'wiki_title' => 'The Skull Reaper',
                'category' => 'boss',
                'floor_min' => 75,
                'floor_max' => 75,
                'specific_floor' => 75,
                'description' => 'O terrível ceifador do andar 75. Representa imprevistos macro que exigem união da guilda para vencer.',
                'icon' => '🦂',
                'xp_reward' => 3500,
            ],
            [
                'name' => 'Kraken the Abyss Lord',
                'wiki_title' => 'Kraken the Abyss Lord',
                'category' => 'boss',
                'floor_min' => 80,
                'floor_max' => 80,
                'specific_floor' => 80,
                'description' => 'O colosso das profundezas aquáticas. Representa a proteção de capital contra crises macroeconômicas.',
                'icon' => '🐙',
                'xp_reward' => 4000,
            ],
            [
                'name' => 'An Incarnation of the Radius',
                'wiki_title' => 'An Incarnation of the Radius',
                'category' => 'boss',
                'floor_min' => 100,
                'floor_max' => 100,
                'specific_floor' => 100,
                'description' => 'A encarnação divina no topo de Aincrad. O ápice da independência financeira total.',
                'icon' => '👑',
                'xp_reward' => 5000,
            ],
            [
                'name' => 'Heathcliff',
                'wiki_title' => 'Heathcliff',
                'category' => 'boss',
                'floor_min' => 100,
                'floor_max' => 100,
                'specific_floor' => 100,
                'description' => 'O comandante supremo e criador do mundo. O teste absoluto de disciplina e consistência de Aincrad.',
                'icon' => '🛡️',
                'xp_reward' => 5000,
            ],
        ];

        // 2. Compile Items List
        $itemsData = [
            // Weapons
            [
                'name' => 'Annealed Blade',
                'wiki_title' => 'Annealed Blade',
                'category' => 'cosmetic',
                'price' => 50,
                'icon' => '🗡️',
                'rarity' => 'common',
                'description' => 'Espada de ferro temperado básica. A arma inicial de todo iniciante em Aincrad.',
                'stock' => null,
            ],
            [
                'name' => 'Elucidator',
                'wiki_title' => 'Elucidator',
                'category' => 'cosmetic',
                'price' => 300,
                'icon' => '🖤',
                'rarity' => 'legendary',
                'description' => 'A espada preta icônica empunhada por Kirito. Atribuída a grandes conquistas.',
                'stock' => 1,
            ],
            [
                'name' => 'Dark Repulser',
                'wiki_title' => 'Dark Repulser',
                'category' => 'cosmetic',
                'price' => 250,
                'icon' => '⚔️',
                'rarity' => 'epic',
                'description' => 'Espada gêmea forjada por Lisbeth a partir de um lingote de cristalito.',
                'stock' => 1,
            ],
            [
                'name' => 'Lambent Light',
                'wiki_title' => 'Lambent Light',
                'category' => 'cosmetic',
                'price' => 250,
                'icon' => '⚡',
                'rarity' => 'epic',
                'description' => 'A rapier branca e dourada de Asuna, famosa por sua velocidade mortal.',
                'stock' => 1,
            ],
            [
                'name' => 'Blue Rose Sword',
                'wiki_title' => 'Blue Rose Sword',
                'category' => 'cosmetic',
                'price' => 280,
                'icon' => '❄️',
                'rarity' => 'epic',
                'description' => 'Espada de gelo eterno adornada com uma rosa azul. Símbolo de lealdade.',
                'stock' => 1,
            ],
            [
                'name' => 'Fragrant Olive Sword',
                'wiki_title' => 'Fragrant Olive Sword',
                'category' => 'cosmetic',
                'price' => 320,
                'icon' => '🌿',
                'rarity' => 'legendary',
                'description' => 'A espada da oliveira divina, imutável e destrutiva. Um tesouro de longo prazo.',
                'stock' => 1,
            ],

            // Armors
            [
                'name' => 'Coat of Midnight',
                'wiki_title' => 'Coat of Midnight',
                'category' => 'cosmetic',
                'price' => 80,
                'icon' => '🧥',
                'rarity' => 'uncommon',
                'description' => 'Casaco escuro que oferece camuflagem e proteção básica em andares iniciais.',
                'stock' => null,
            ],
            [
                'name' => 'Breastplate of Steel',
                'wiki_title' => 'Steel Breastplate',
                'category' => 'cosmetic',
                'price' => 100,
                'icon' => '🛡️',
                'rarity' => 'rare',
                'description' => 'Peitoral de aço sólido para mitigar os danos de gastos excessivos.',
                'stock' => null,
            ],
            [
                'name' => 'Chestguard of Silver',
                'wiki_title' => 'Silver Chestguard',
                'category' => 'cosmetic',
                'price' => 150,
                'icon' => '🪙',
                'rarity' => 'rare',
                'description' => 'Peitoral de prata que atua como barreira contra imprevistos financeiros.',
                'stock' => null,
            ],
            [
                'name' => 'Cardboard Armor',
                'wiki_title' => 'Cardboard Armor',
                'category' => 'cosmetic',
                'price' => 10,
                'icon' => '📦',
                'rarity' => 'common',
                'description' => 'Armadura de papelão humorística. Oferece proteção psicológica nula.',
                'stock' => null,
            ],

            // Consumables
            [
                'name' => 'Potion of Healing',
                'wiki_title' => 'Healing Potion',
                'category' => 'consumable',
                'price' => 30,
                'icon' => '🧪',
                'rarity' => 'common',
                'description' => 'Poção de cura básica para aliviar o estresse financeiro e restaurar 20% do HP.',
                'stock' => 99,
            ],
            [
                'name' => 'Teleport Crystal',
                'wiki_title' => 'Teleport Crystal',
                'category' => 'consumable',
                'price' => 100,
                'icon' => '💎',
                'rarity' => 'rare',
                'description' => 'Cristal azul de teletransporte instantâneo para se salvar de situações críticas.',
                'stock' => 10,
            ],
            [
                'name' => 'Food Buff (Ragout Meat)',
                'wiki_title' => "Ragout Rabbit's Meat",
                'category' => 'consumable',
                'price' => 45,
                'icon' => '🍗',
                'rarity' => 'uncommon',
                'description' => 'Comida especial (Carne de Ragout Rabbit) cozinhada pela Asuna, oferecendo bônus de XP.',
                'stock' => 5,
            ],
            [
                'name' => 'Scroll of Skill',
                'wiki_title' => 'Skill Scroll',
                'category' => 'consumable',
                'price' => 80,
                'icon' => '📜',
                'rarity' => 'rare',
                'description' => 'Pergaminho contendo técnicas de combate para acelerar o desenvolvimento de competências.',
                'stock' => 5,
            ],
        ];

        // 3. Query Fandom API for all images in batches of 15
        $allTitles = collect($monstersData)->pluck('wiki_title')
            ->concat(collect($itemsData)->pluck('wiki_title'))
            ->unique()
            ->values();

        $imageMapping = [];
        $batches = $allTitles->chunk(15);

        foreach ($batches as $batch) {
            $titlesString = $batch->implode('|');
            try {
                $response = Http::timeout(5)->get('https://swordartonline.fandom.com/api.php', [
                    'action' => 'query',
                    'prop' => 'pageimages',
                    'titles' => $titlesString,
                    'pithumbsize' => 500,
                    'format' => 'json',
                ]);

                if ($response->successful()) {
                    $json = $response->json();
                    $pages = $json['query']['pages'] ?? [];
                    
                    // The API normalized titles might map from X to Y
                    $normalized = collect($json['query']['normalized'] ?? []);

                    foreach ($pages as $page) {
                        $title = $page['title'] ?? null;
                        $url = $page['thumbnail']['source'] ?? null;
                        
                        if ($title && $url) {
                            $imageMapping[$title] = $url;
                            
                            // Also map back to the original queried title if normalized
                            $normFrom = $normalized->firstWhere('to', $title);
                            if ($normFrom) {
                                $imageMapping[$normFrom['from']] = $url;
                            }
                        }
                    }
                }
            } catch (\Exception $e) {
                // Ignore and proceed to fallback
            }
        }

        // 4. Seed Monsters
        foreach ($monstersData as $m) {
            $imageUrl = $imageMapping[$m['wiki_title']] ?? null;
            
            // Static fallbacks for popular characters if API fails or page title slightly differs
            if (!$imageUrl) {
                $imageUrl = $this->getStaticFallback($m['wiki_title']);
            }

            Monster::updateOrCreate(
                ['name' => $m['name']],
                [
                    'category' => $m['category'],
                    'floor_min' => $m['floor_min'],
                    'floor_max' => $m['floor_max'],
                    'specific_floor' => $m['specific_floor'],
                    'description' => $m['description'],
                    'icon' => $m['icon'],
                    'xp_reward' => $m['xp_reward'],
                    'image_url' => $imageUrl,
                ]
            );
        }

        // 5. Seed expanded Shop Items
        foreach ($itemsData as $it) {
            $imageUrl = $imageMapping[$it['wiki_title']] ?? null;
            
            if (!$imageUrl) {
                $imageUrl = $this->getStaticFallback($it['wiki_title']);
            }

            ShopItem::updateOrCreate(
                ['name' => $it['name']],
                [
                    'description' => $it['description'],
                    'category' => $it['category'],
                    'price' => $it['price'],
                    'icon' => $it['icon'],
                    'image_url' => $imageUrl,
                    'rarity' => $it['rarity'],
                    'stock' => $it['stock'],
                    'is_active' => true,
                ]
            );
        }
    }

    private function getStaticFallback(string $title): ?string
    {
        // Predefined fallback hashes for key monsters/items
        $fallbacks = [
            'Frenzy Boar' => 'https://static.wikia.nocookie.net/swordartonline/images/4/4a/Frenzy_Boar.png/revision/latest/scale-to-width-down/500',
            'Illfang the Kobold Lord' => 'https://static.wikia.nocookie.net/swordartonline/images/b/bc/Illfang_the_Kobold_Lord_Anime.png/revision/latest/scale-to-width-down/500',
            'The Gleam Eyes' => 'https://static.wikia.nocookie.net/swordartonline/images/c/c5/The_Gleam_Eyes_Anime.png/revision/latest/scale-to-width-down/500',
            'The Skull Reaper' => 'https://static.wikia.nocookie.net/swordartonline/images/c/cd/Skull_Reaper.png/revision/latest/scale-to-width-down/500',
            'Elucidator' => 'https://static.wikia.nocookie.net/swordartonline/images/8/88/Elucidator.png/revision/latest/scale-to-width-down/500',
            'Dark Repulser' => 'https://static.wikia.nocookie.net/swordartonline/images/6/6f/Dark_Repulser.png/revision/latest/scale-to-width-down/500',
            'Lambent Light' => 'https://static.wikia.nocookie.net/swordartonline/images/e/ea/Lambent_Light_Anime.png/revision/latest/scale-to-width-down/500',
            'Blue Rose Sword' => 'https://static.wikia.nocookie.net/swordartonline/images/8/8f/Blue_Rose_Sword_Anime.png/revision/latest/scale-to-width-down/500',
            'Fragrant Olive Sword' => 'https://static.wikia.nocookie.net/swordartonline/images/8/87/Fragrant_Olive_Sword_Anime.png/revision/latest/scale-to-width-down/500',
            'Coat of Midnight' => 'https://static.wikia.nocookie.net/swordartonline/images/2/22/Coat_of_Midnight.png/revision/latest/scale-to-width-down/500',
            'Heathcliff' => 'https://static.wikia.nocookie.net/swordartonline/images/e/e0/Heathcliff_color.png/revision/latest/scale-to-width-down/500',
            'Steel Breastplate' => 'https://static.wikia.nocookie.net/swordartonline/images/1/1b/Breastplate_of_Steel.png/revision/latest/scale-to-width-down/500',
            'Teleport Crystal' => 'https://static.wikia.nocookie.net/swordartonline/images/3/36/Teleport_Crystal.png/revision/latest/scale-to-width-down/500',
            'Healing Potion' => 'https://static.wikia.nocookie.net/swordartonline/images/0/07/Potion.png/revision/latest/scale-to-width-down/500',
        ];

        return $fallbacks[$title] ?? null;
    }
}
