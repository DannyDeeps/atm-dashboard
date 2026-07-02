<!DOCTYPE html>
<html lang="en" data-theme="dark">
<?php $headTitle = $headTitle ?? 'Players - Glip Glops ATM10'; require $partialsPath . '/head.php'; ?>
<body>
  <?php require $partialsPath . '/nav.php'; ?>

  <div class="container mx-auto px-4 pt-20 pb-8">
    <h1 class="text-3xl font-bold mb-6">Players <span class="font-normal text-base-content/40">(<?= count($players) ?>)</span></h1>

    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-3">
      <?php foreach ($players as $player):
        $completed = $questCounts[$player['uuid']] ?? 0;
        $pct = $totalQuests > 0 ? round($completed / $totalQuests * 100) : 0;
      ?>
      <a href="/player/<?= htmlspecialchars($player['uuid']) ?>" class="card bg-base-200 border border-base-300 overflow-visible transition-all duration-200 hover:top-[-0.125rem] cursor-pointer relative rounded-b-2xl rounded-t-none<?php if ($player['world_tier'] && isset($tierConfig[$player['world_tier']])): ?> border-t-4" style="border-top-color: <?= $tierConfig[$player['world_tier']]['color'] ?><?php endif; ?>">
        <?php if ($player['world_tier'] && isset($tierConfig[$player['world_tier']])): $t = $tierConfig[$player['world_tier']]; ?>
        <div class="absolute -top-2 right-2 tooltip tooltip-left" data-tip="Apotheosis: <?= htmlspecialchars($t['label']) ?> World Tier">
          <span class="text-xs font-bold px-1.5 py-0.5 leading-none inline-block" style="background: <?= $t['color'] ?>; color: #1e1e2e;"><?= htmlspecialchars($t['short']) ?></span>
        </div>
        <?php endif; ?>
        <div class="card-body p-4 gap-3">
          <div class="flex items-center gap-3">
            <img src="/head-cache.php?uuid=<?= htmlspecialchars($player['uuid']) ?>" alt="" class="w-10 h-10 shrink-0 bg-base-300">
            <div class="flex flex-col min-w-0">
              <span class="font-bold truncate"><?= htmlspecialchars($player['name']) ?></span>
              <?php $awards = $playerAwards[$player['uuid']] ?? []; ?>
              <?php if ($awards): $visible = array_slice($awards, 0, 2); $extra = array_slice($awards, 2); ?>
              <div class="flex flex-wrap gap-1 mt-0.5">
                <?php foreach ($visible as $award): ?>
                <span class="tooltip tooltip-top badge badge-xs font-medium rounded-none" data-tip="<?= htmlspecialchars($award['desc']) ?>" style="border-left: 3px solid <?= htmlspecialchars($award['color']) ?>"><?= htmlspecialchars($award['title']) ?></span>
                <?php endforeach; ?>
                <?php if ($extra): ?>
                <span class="tooltip tooltip-top text-[0.625rem] leading-none font-medium px-1 py-0.5" data-tip="<?= htmlspecialchars(implode(', ', array_column($extra, 'title'))) ?>">+<?= count($extra) ?></span>
                <?php endif; ?>
              </div>
              <?php endif; ?>
            </div>
          </div>

          <div>
            <div class="flex justify-between text-xs mb-1">
              <span class="text-base-content/40">Quests</span>
              <span class="font-semibold"><?= $completed ?>/<?= $totalQuests ?></span>
            </div>
            <progress class="progress progress-primary w-full" value="<?= $completed ?>" max="<?= $totalQuests ?>"></progress>
          </div>

          <div class="grid grid-cols-2 gap-x-3 gap-y-1.5 text-sm">
            <div>
              <div class="text-xs text-base-content/40">Playtime</div>
              <div class="font-semibold"><?= formatTicks((int)$player['max_playtime']) ?></div>
            </div>
            <div>
              <div class="text-xs text-base-content/40">Deaths</div>
              <div class="font-semibold"><?= number_format((int)$player['max_deaths']) ?></div>
            </div>
            <div>
              <div class="text-xs text-base-content/40">Distance</div>
              <div class="font-semibold"><?= formatCm((int)$player['max_distance']) ?></div>
            </div>
            <div>
              <div class="text-xs text-base-content/40">Mobs Killed</div>
              <div class="font-semibold"><?= number_format((int)$player['max_mobs_killed']) ?></div>
            </div>
            <div>
              <div class="text-xs text-base-content/40">Lootr Crates</div>
              <div class="font-semibold"><?= number_format((int)$player['max_lootr_looted']) ?></div>
            </div>
          </div>
        </div>
      </a>
      <?php endforeach; ?>
    </div>
  </div>
<?php require $partialsPath . '/footer.php'; ?>
