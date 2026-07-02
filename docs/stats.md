# Available Stats

All stats come from per-player `world/stats/<uuid>.json` files. Minecraft writes stats into two kinds of entries:

- **Scalar** (`minecraft:custom`) — single integer values (e.g. `play_time: 472557`)
- **Per-item** (e.g. `minecraft:mined`) — maps of item/block IDs to counts (e.g. `minecraft:stone: 42`)

---

## minecraft:custom — Scalar Stats

These are single-value counters straight from the JSON. See [StatsParser.php](../src/Parsers/StatsParser.php) for how each is mapped.

| Stat | Displayed | Description |
|------|:---------:|-------------|
| `minecraft:play_time` | ✅ Playtime | Total ticks played (1 tick = 1/20 sec) |
| `minecraft:deaths` | ✅ | Number of deaths |
| `minecraft:walk_one_cm` | ✅ Distance | Distance walked in cm |
| `minecraft:fly_one_cm` | ✅ Distance | Distance flown in cm |
| `minecraft:swim_one_cm` | ✅ Distance | Distance swum in cm |
| `minecraft:fall_one_cm` | ❌ | Distance fallen in cm |
| `minecraft:climb_one_cm` | ❌ | Distance climbed (ladders/vines) in cm |
| `minecraft:crouch_one_cm` | ❌ | Distance crouched in cm |
| `minecraft:sprint_one_cm` | ❌ | Distance sprinted in cm |
| `minecraft:walk_on_water_one_cm` | ❌ | Distance walked on water in cm |
| `minecraft:walk_under_water_one_cm` | ❌ | Distance walked underwater in cm |
| `minecraft:boat_one_cm` | ❌ | Distance traveled by boat in cm |
| `minecraft:horse_one_cm` | ❌ | Distance traveled by horse in cm |
| `minecraft:minecart_one_cm` | ❌ | Not present in any example file |
| `minecraft:jump` | ✅ | Number of jumps |
| `minecraft:damage_dealt` | ✅ | Damage dealt (÷10 = hearts) |
| `minecraft:damage_taken` | ✅ | Damage taken (÷10 = hearts) |
| `minecraft:damage_resisted` | ❌ | Damage resisted |
| `minecraft:damage_dealt_absorbed` | ❌ | Damage dealt while absorbed |
| `minecraft:damage_dealt_resisted` | ❌ | Damage dealt that was resisted |
| `minecraft:damage_absorbed` | ❌ | Damage absorbed by absorption hearts |
| `minecraft:mob_kills` | ✅ | Total mobs killed |
| `minecraft:undead_killed` | ❌ | Undead mobs killed specifically |
| `minecraft:raider_killed` | ❌ | Raider mobs killed (pillagers, etc.) |
| `minecraft:player_kills` | ❌ | PvP kills |
| `minecraft:drop` | ❌ | Items dropped |
| `minecraft:bell_ring` | ❌ | Times bell rung |
| `minecraft:open_chest` | ✅ | Combined as Chests Opened |
| `minecraft:open_barrel` | ✅ | Combined as Chests Opened |
| `minecraft:interact_with_crafting_table` | ❌ | Times opened crafting table UI |
| `minecraft:interact_with_furnace` | ❌ | Times opened furnace UI |
| `minecraft:interact_with_blast_furnace` | ❌ | Times opened blast furnace UI |
| `minecraft:interact_with_smoker` | ❌ | Times opened smoker UI |
| `minecraft:interact_with_brewingstand` | ❌ | Times opened brewing stand UI |
| `minecraft:interact_with_stonecutter` | ❌ | Times opened stonecutter UI |
| `minecraft:interact_with_cartography_table` | ❌ | Times opened cartography table UI |
| `minecraft:interact_with_loom` | ❌ | Times opened loom UI |
| `minecraft:interact_with_smithing_table` | ❌ | Times opened smithing table UI |
| `minecraft:interact_with_lectern` | ❌ | Times opened lectern UI |
| `minecraft:interact_with_anvil` | ❌ | Times opened anvil UI |
| `minecraft:interact_with_campfire` | ❌ | Times interacted with campfire |
| `minecraft:inspect_dispenser` | ❌ | Times opened dispenser UI |
| `minecraft:inspect_hopper` | ❌ | Times opened hopper UI |
| `minecraft:sleep_in_bed` | ❌ | Times slept in bed |
| `minecraft:talked_to_villager` | ❌ | Times talked to villager |
| `minecraft:traded_with_villager` | ❌ | Times traded with villager |
| `minecraft:time_since_death` | ✅ | Ticks since last death (resets on death) |
| `minecraft:time_since_rest` | ❌ | Ticks since last sleep in bed |
| `minecraft:total_world_time` | ❌ | Total world ticks player has existed |
| `minecraft:leave_game` | ❌ | Times left the game |
| `minecraft:sneak_time` | ❌ | Ticks spent sneaking |
| `minecraft:eat_cake_slice` | ❌ | Cake slices eaten |
| `minecraft:play_record` | ❌ | Music discs played |
| `minecraft:trigger_trapped_chest` | ❌ | Trapped chests triggered |
| `minecraft:tune_noteblock` | ❌ | Note blocks tuned |
| `minecraft:elyra_diary` | ❌ | Mod-specific (not a standard MC stat) |

### Mod-specific custom stats (examples found in files)

| Stat | Displayed | Description |
|------|:---------:|-------------|
| `lootr:looted_stat` | ✅ Loot Goblin | Lootr crates looted |
| `waystones:waystone_activated` | ❌ | Waystones activated |
| `constructionstick:use_stick` | ❌ | Construction stick uses |
| `apotheosis:world_tiers_activated` | ❌ | Apotheosis world tiers |
| `minecraft:rite_of_silent_bond` | ❌ | From Ars Nouveau / Eidolon |

---

## Per-item Category Stats

These are maps of `<item_id>: <count>`. The StatsParser currently sums `mined`, `crafted`, and `used` into `blocks_mined`, `items_crafted`, and `items_used`/`blocks_placed`.

| Category | Tracked | Description | Total items in example |
|----------|:-------:|-------------|:----------------------:|
| `minecraft:mined` | ✅ `blocks_mined` (sum) | Per-block break counts | 104 |
| `minecraft:crafted` | ✅ `items_crafted` (sum) | Per-item craft counts | 69 |
| `minecraft:used` | ✅ `items_used` / `blocks_placed` (sum) | Per-item use counts (right-click actions) | 85 |
| `minecraft:picked_up` | ❌ | Per-item pickup counts | 180 |
| `minecraft:dropped` | ❌ | Per-item drop counts | 8 |
| `minecraft:broken` | ❌ | Per-item break counts (tools/armor) | 5 |
| `minecraft:killed` | ❌ | Per-mob kill counts | 3 |
| `minecraft:killed_by` | ❌ | Per-mob death cause counts | 1 |

---

## How Stats Are Collected

1. `bin/collect.php` runs every 5 minutes via cron
2. `StatsCollector` reads `world/stats/<uuid>.json` for each player
3. `StatsParser` extracts scalar values from `minecraft:custom` and sums per-item categories (`mined`, `crafted`, `used`)
4. A new row is inserted into `player_snapshots` (append-only)
5. `QuestCollector` reads `world/ftbquests/*.snbt` for quest completion data

All parsed stats are stored in the `player_snapshots` table columns matching the key names from `StatsParser::parse()`.
