<?php

namespace AtmDashboard\Controllers;

use Psr\Http\Message\ServerRequestInterface;

class ArmoryController extends BaseController
{
    public function __invoke(ServerRequestInterface $request): \Psr\Http\Message\ResponseInterface
    {
        $players = $this->mockGear();
        return $this->render('armory', [
            'players' => $players,
            'currentPage' => 'armory',
            'headTitle' => 'Armory - Glip Glops ATM10',
        ]);
    }

    private function mockGear(): array
    {
        return [
            [
                'uuid' => 'd51e4c4284a84d52972b6554ddbf5164',
                'name' => 'DannyDeeps',
                'slots' => [
                    'head' => $this->item('awakened_supremium_helmet', 'Awakened Supremium Helmet', 'epic', [
                        'Protection' => 7, 'Unbreaking' => 5, 'Mending' => 1,
                    ], [
                        'Armor' => '+5', 'Toughness' => '+3',
                    ]),
                    'chest' => $this->item('awakened_supremium_chestplate', 'Awakened Supremium Chestplate', 'epic', [
                        'Protection' => 7, 'Unbreaking' => 5, 'Mending' => 1,
                    ], ['Armor' => '+8', 'Toughness' => '+3']),
                    'legs' => $this->item('awakened_supremium_leggings', 'Awakened Supremium Leggings', 'epic', [
                        'Protection' => 7, 'Unbreaking' => 5, 'Mending' => 1,
                    ], ['Armor' => '+6', 'Toughness' => '+3']),
                    'feet' => $this->item('awakened_supremium_boots', 'Awakened Supremium Boots', 'epic', [
                        'Protection' => 7, 'Feather Falling' => 7, 'Unbreaking' => 5, 'Mending' => 1,
                    ], ['Armor' => '+5', 'Toughness' => '+3']),
                    'mainhand' => $this->item('awakened_supremium_sword', 'Awakened Supremium Sword', 'epic', [
                        'Sharpness' => 9, 'Looting' => 5, 'Sweeping Edge' => 4,
                        'Fire Aspect' => 3, 'Mending' => 1,
                    ], ['Damage' => '+14', 'Speed' => '1.6']),
                    'offhand' => $this->item('solace', 'Solace', 'relic', [], [
                        'Effect' => 'Heals 1❤ on hit',
                        'Type' => 'Relic — Passive',
                    ]),
                    'curio_1' => $this->item('soul_of_the_warden', 'Soul of the Warden', 'relic', [], [
                        'Effect' => 'Sonic Boom on attack',
                        'Type' => 'Relic — Offensive',
                    ]),
                    'curio_2' => $this->item('magnet_ring', 'Magnet Ring', 'rare', [], [
                        'Range' => '7 blocks',
                        'Type' => 'Curio — Utility',
                    ]),
                    'curio_3' => $this->item('cloud_pendant', 'Cloud Pendant', 'relic', [], [
                        'Effect' => 'Double Jump',
                        'Type' => 'Relic — Mobility',
                    ]),
                    'curio_4' => $this->item('the_one_ring', 'The One Ring', 'artifact', [], [
                        'Effect' => '+20% all stats',
                        'Type' => 'Artifact — Buff',
                    ]),
                ],
            ],
            [
                'uuid' => 'c81a35397b184ab39a577fa3a843fa1d',
                'name' => 'Vexchia',
                'slots' => [
                    'head' => $this->item('supremium_helmet', 'Supremium Helmet', 'rare', [
                        'Protection' => 6, 'Unbreaking' => 4, 'Mending' => 1,
                    ], ['Armor' => '+4', 'Toughness' => '+2']),
                    'chest' => $this->item('supremium_chestplate', 'Supremium Chestplate', 'rare', [
                        'Protection' => 6, 'Unbreaking' => 4, 'Mending' => 1,
                    ], ['Armor' => '+7', 'Toughness' => '+2']),
                    'legs' => $this->item('supremium_leggings', 'Supremium Leggings', 'rare', [
                        'Protection' => 6, 'Unbreaking' => 4,
                    ], ['Armor' => '+5', 'Toughness' => '+2']),
                    'feet' => $this->item('allthemodium_boots', 'Allthemodium Boots', 'rare', [
                        'Protection' => 6, 'Feather Falling' => 5, 'Unbreaking' => 4,
                    ], ['Armor' => '+4', 'Toughness' => '+2']),
                    'mainhand' => $this->item('supremium_sword', 'Supremium Sword', 'rare', [
                        'Sharpness' => 8, 'Looting' => 4, 'Sweeping Edge' => 3,
                    ], ['Damage' => '+11', 'Speed' => '1.6']),
                    'offhand' => $this->item('netherite_shield', 'Netherite Shield', 'uncommon', [
                        'Unbreaking' => 3,
                    ], ['Block' => '100% damage']),
                    'curio_1' => $this->item('angel_blessing', "Angel's Blessing", 'rare', [], [
                        'Effect' => 'Keep inventory on death',
                        'Type' => 'Curio — Charm',
                    ]),
                    'curio_2' => $this->item('heart_amulet', 'Heart Amulet', 'rare', [], [
                        'Effect' => '+6 max❤ (3 hearts)',
                        'Type' => 'Curio — Vitality',
                    ]),
                    'curio_3' => $this->item('hunter_cloak', "Hunter's Cloak", 'relic', [], [
                        'Effect' => 'Camouflage when sneaking',
                        'Type' => 'Relic — Stealth',
                    ]),
                    'curio_4' => $this->item('sojourner_sash', "Sojourner's Sash", 'relic', [], [
                        'Effect' => 'Auto-smelt mined blocks',
                        'Type' => 'Relic — Utility',
                    ]),
                ],
            ],
            [
                'uuid' => 'c5e474be769f4279ba3aaf914aa8888b',
                'name' => 'Cookio',
                'slots' => [
                    'head' => $this->item('mekasuit_helmet', 'MekaSuit Helmet', 'epic', [], [
                        'Energy' => '4M FE',
                        'Modules' => 'Inhalation Purification, Solar Recharging',
                    ]),
                    'chest' => $this->item('mekasuit_chestplate', 'MekaSuit Bodyarmor', 'epic', [], [
                        'Energy' => '8M FE',
                        'Modules' => 'Diamond Absorption, Nutritional Injection',
                    ]),
                    'legs' => $this->item('mekasuit_leggings', 'MekaSuit Pants', 'epic', [], [
                        'Energy' => '6M FE',
                        'Modules' => 'Locomotive, Jump Boost',
                    ]),
                    'feet' => $this->item('mekasuit_boots', 'MekaSuit Boots', 'epic', [], [
                        'Energy' => '4M FE',
                        'Modules' => 'Hydrostatic, Frost Walker, Jetpack',
                    ]),
                    'mainhand' => $this->item('meka_tool', 'Meka-Tool', 'epic', [], [
                        'Energy' => '4M FE',
                        'Modes' => 'Pickaxe, Shovel, Axe, Hoe, Paxel',
                        'Vein Mining' => '64 blocks',
                    ]),
                    'offhand' => $this->item('atomic_disassembler', 'Atomic Disassembler', 'rare', [], [
                        'Energy' => '1M FE',
                        'Modes' => 'Mining, Blade, Shovel',
                    ]),
                    'curio_1' => $this->item('infinity_hammer', 'Infinity Hammer', 'artifact', [], [
                        'Effect' => 'One-click repair all items',
                        'Type' => 'Artifact — Utility',
                    ]),
                    'curio_2' => $this->item('bottled_fae', 'Bottled Fae', 'rare', [], [
                        'Effect' => '+50% XP gain',
                        'Type' => 'Curio — Experience',
                    ]),
                    'curio_3' => $this->item('ankh_shield', 'Ankh Shield', 'artifact', [], [
                        'Effects' => 'Immunity: Poison, Wither, Blindness, Slowness, Hunger',
                        'Type' => 'Artifact — Protection',
                    ]),
                ],
            ],
            [
                'uuid' => '665272d0283f45b092698c457005abc0',
                'name' => 'StrayPandora27',
                'slots' => [
                    'head' => $this->item('netherite_helmet', 'Netherite Helmet', 'uncommon', [
                        'Protection' => 4, 'Unbreaking' => 3, 'Mending' => 1,
                    ], ['Armor' => '+3', 'Toughness' => '+1']),
                    'chest' => $this->item('netherite_chestplate', 'Netherite Chestplate', 'uncommon', [
                        'Protection' => 4, 'Unbreaking' => 3, 'Mending' => 1,
                    ], ['Armor' => '+6', 'Toughness' => '+1']),
                    'legs' => $this->item('netherite_leggings', 'Netherite Leggings', 'uncommon', [
                        'Protection' => 4, 'Unbreaking' => 3,
                    ], ['Armor' => '+4', 'Toughness' => '+1']),
                    'feet' => $this->item('netherite_boots', 'Netherite Boots', 'uncommon', [
                        'Protection' => 4, 'Feather Falling' => 4, 'Unbreaking' => 3,
                    ], ['Armor' => '+3', 'Toughness' => '+1']),
                    'mainhand' => $this->item('netherite_sword', 'Netherite Sword', 'uncommon', [
                        'Sharpness' => 5, 'Looting' => 3, 'Fire Aspect' => 2,
                    ], ['Damage' => '+8', 'Speed' => '1.6']),
                    'offhand' => $this->item('shield', 'Shield', 'common', [
                        'Unbreaking' => 3,
                    ], ['Block' => '100% damage']),
                    'curio_1' => $this->item('magnet_ring', 'Copper Magnet', 'uncommon', [], [
                        'Range' => '5 blocks',
                        'Type' => 'Curio — Utility',
                    ]),
                    'curio_2' => $this->item('glacier_pendant', 'Glacier Pendant', 'relic', [], [
                        'Effect' => 'Freeze nearby enemies on damaged',
                        'Type' => 'Relic — Defensive',
                    ]),
                ],
            ],
            [
                'uuid' => 'fb3f0cad2eaf4b7db93b15cc01f0f0ad',
                'name' => 'Astromancer',
                'slots' => [
                    'head' => $this->item('supremium_helmet', 'Supremium Helmet', 'rare', [
                        'Protection' => 5, 'Unbreaking' => 4,
                    ], ['Armor' => '+4', 'Toughness' => '+2']),
                    'chest' => $this->item('dragonsteel_chestplate', 'Dragonsteel Chestplate', 'epic', [
                        'Protection' => 6, 'Unbreaking' => 4, 'Mending' => 1,
                    ], ['Armor' => '+7', 'Toughness' => '+3']),
                    'legs' => $this->item('supremium_leggings', 'Supremium Leggings', 'rare', [
                        'Protection' => 5, 'Unbreaking' => 4,
                    ], ['Armor' => '+5', 'Toughness' => '+2']),
                    'feet' => $this->item('supremium_boots', 'Supremium Boots', 'rare', [
                        'Protection' => 5, 'Feather Falling' => 5, 'Unbreaking' => 4,
                    ], ['Armor' => '+4', 'Toughness' => '+2']),
                    'mainhand' => $this->item('vibranium_sword', 'Vibranium Sword', 'epic', [
                        'Sharpness' => 8, 'Looting' => 4, 'Mending' => 1,
                    ], ['Damage' => '+12', 'Speed' => '1.6']),
                    'offhand' => $this->item('solace', 'Solace', 'relic', [], [
                        'Effect' => 'Heals 1❤ on hit',
                        'Type' => 'Relic — Passive',
                    ]),
                    'curio_1' => $this->item('ender_necklace', 'Ender Necklace', 'relic', [], [
                        'Effect' => 'Teleport on hit target',
                        'Type' => 'Relic — Mobility',
                    ]),
                    'curio_2' => $this->item('rune_power', 'Rune of Power', 'epic', [], [
                        'Effect' => '+20% attack damage',
                        'Type' => 'Artifact — Offensive',
                    ]),
                    'curio_3' => $this->item('rune_defense', 'Rune of Defense', 'epic', [], [
                        'Effect' => '-20% incoming damage',
                        'Type' => 'Artifact — Defensive',
                    ]),
                ],
            ],
        ];
    }

    private function item(string $icon, string $name, string $rarity, array $enchants, array $stats): array
    {
        $rarityColors = [
            'common' => '#d1d5db',
            'uncommon' => '#FFDF00',
            'rare' => '#00ffff',
            'epic' => '#ff55ff',
            'relic' => '#ff4444',
            'artifact' => '#FFD700',
        ];
        return [
            'icon' => $icon,
            'name' => $name,
            'rarity' => $rarity,
            'color' => $rarityColors[$rarity] ?? '#d1d5db',
            'enchants' => $enchants,
            'stats' => $stats,
        ];
    }
}
