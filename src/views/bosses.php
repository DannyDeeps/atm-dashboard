<!DOCTYPE html>
<html lang="en" data-theme="dark">
<?php $headTitle = $headTitle ?? 'Boss Kills - Glip Glops ATM10'; require $partialsPath . '/head.php'; ?>
<body>
  <?php require $partialsPath . '/nav.php'; ?>

  <section class="bg-base-200/30 py-10">
    <div class="w-full lg:max-w-5xl xl:max-w-6xl mx-auto px-4">

      <div class="text-center mb-8">
        <h1 class="text-5xl md:text-6xl font-minecrafter">
          <span style="color: #88e23b; filter: drop-shadow(-1px 3px 1px #5c9e1e) drop-shadow(-2px 3px 1px #3d6b12) drop-shadow(-3px 3px 0 #1f3607) drop-shadow(-4px 6px 3px #080808)">Boss</span><span style="margin-left: 0.4em; color: #FCF31F; filter: drop-shadow(-1px 3px 1px #c4bc00) drop-shadow(-2px 3px 1px #8a8400) drop-shadow(-3px 3px 0 #504c00) drop-shadow(-4px 6px 3px #080808)">Kills</span>
        </h1>
        <p class="text-base text-base-content/60 mt-4">Who slayed what — total tracked boss kills server-wide</p>
      </div>

      <?php if (empty($bossKills)): ?>
      <div class="text-center py-16">
        <p class="text-lg text-base-content/40">No boss kill data yet. Run the collector first.</p>
      </div>
      <?php else: ?>
      <div class="flex flex-col gap-3">
        <?php foreach ($bossKills as $entity => $killedBy):
          $total = array_sum(array_column($killedBy, 'count'));
          usort($killedBy, fn($a, $b) => $b['count'] <=> $a['count']);
        ?>
        <div class="bg-base-200/50 backdrop-blur-sm rounded-lg flex items-center gap-4 px-4 py-3">
          <div class="shrink-0 flex items-center gap-2 w-48">
            <img src="<?= htmlspecialchars($bossImages[$entity] ?? '/images/bosses/Invicon_Wither_Skeleton_Skull.png') ?>" alt="" class="size-8 object-contain shrink-0" loading="lazy">
            <div class="min-w-0">
              <span class="text-lg font-bold text-primary truncate block"><?= htmlspecialchars($bosses[$entity] ?? $entity) ?></span>
              <span class="text-xs text-base-content/60"><?= number_format($total) ?> total · <?= count($killedBy) ?> player<?= count($killedBy) !== 1 ? 's' : '' ?></span>
            </div>
          </div>
          <div class="overflow-x-auto flex-1">
            <ul class="steps steps-horizontal min-w-max">
              <?php foreach ($killedBy as $kb): ?>
              <li class="step">
                <div class="step-icon overflow-hidden">
                  <img src="/head-cache.php?uuid=<?= htmlspecialchars($kb['uuid']) ?>" alt="" class="w-full h-full object-cover" loading="lazy">
                </div>
                <div class="flex flex-col items-center gap-0 py-0.5 max-w-16">
                  <a href="/player/<?= htmlspecialchars($kb['uuid']) ?>" class="link link-hover text-xs text-base-content truncate w-full text-center"><?= htmlspecialchars($kb['name']) ?></a>
                  <span class="font-semibold text-xs text-primary"><?= number_format((int) $kb['count']) ?></span>
                </div>
              </li>
              <?php endforeach; ?>
            </ul>
          </div>
        </div>
        <?php endforeach; ?>
      </div>
      <?php endif; ?>
    </div>
  </section>

<?php require $partialsPath . '/footer.php'; ?>
