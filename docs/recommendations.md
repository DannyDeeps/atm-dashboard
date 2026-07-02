# Recommendations

## Parse `.dat` Files for Additional Stats

### Performance
- `playerdata/*.dat`: 6–30 KB per player, GZip NBT — trivial parse cost at 5 min intervals
- `data/*.dat`: world-level files, ~52 files in overworld, also negligible
- Active players' `.dat` may be mid-write → truncated reads; wrap each parse in try/catch

### Per-Player Fields Worth Adding (`playerdata/*.dat`)

| Field | Type | Why | Leaderboard / Award |
|---|---|---|---|
| `XpTotal` | monotonic | Lifetime XP earned | **"Most Experienced"** — top XpTotal |
| `seenCredits` | boolean (0/1) | Has beaten the End once | **"The End"** badge on profile |
| `Score` | monotonic | Scoreboard value | **"High Score"** — top score |
| `recipeBook.recipes` count | monotonic | Recipes unlocked | **"Master Craftsman"** — most recipes known |
| `apotheosis:world_tier` | current value | World tier (e.g. "haven") | Display on player profile |
| `attributes` (max_health, armor, armor_toughness, attack_damage, movement_speed) | current snapshot | Combat stats | Snapshot display (changes with gear) |
| `Health` / `foodLevel` / `XpLevel` | current snapshot | Current status | Snapshot display |
| `playerGameType` | current value | Survival/creative/etc | Informational |

### World-Level Files Worth Exploring (`data/*.dat`)

| File | Potential | Notes |
|---|---|---|
| `waystones.dat` | **"Wayfarer"** leaderboard | 1321 entries in example — need to check if it stores ownership per waystone |
| `modern_industrialization_player_stats.dat` | MI production/usage leaderboards | Per-player compounds with producedItems/usedItems — currently empty, will grow |
| `sophisticatedstorage.dat` | Storage capacity display | Tracks storage upgrades |
| `dyson_sphere_progress.dat` | Dyson sphere progress | Empty now (no active spheres in example) |
| `book_progressions.dat` | Redundant with FTB Quests parser | Skip |

### Transient Fields (skip for leaderboards)

Position (`Pos`), dimension, inventory, food/saturation, `HurtByTimestamp`, `PortalCooldown`, `FallFlying` — all change every 5 min, not meaningful for time-series tracking.

## Implementation Strategy

Treat `.dat` parsing as best-effort: try to read, silently skip on GZip or NBT parse errors. Add new columns to `player_snapshots` (monotonic fields like `xp_total`, `score`, `recipes_unlocked`, `seen_credits`) via ALTER TABLE migration, same pattern as `lootr_looted`.

## Waystones Investigate

Check `waystones.dat` NBT structure for per-player ownership data to enable a waystone-count leaderboard.
