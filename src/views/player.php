<!DOCTYPE html>
<html lang="en" data-theme="dark">
<?php $headTitle = $headTitle ?? 'Player - ATM10 Dashboard'; require $partialsPath . '/head.php'; ?>
<body>
  <?php require $partialsPath . '/nav.php'; ?>

  <div class="container mx-auto px-4 pt-20 pb-8">
    <h1 class="text-3xl font-bold mb-1">
      <?= htmlspecialchars($player['name']) ?>
      <?php if ($player['world_tier'] && isset($tierConfig[$player['world_tier']])): $t = $tierConfig[$player['world_tier']]; ?>
      <span class="tooltip" data-tip="Apotheosis: <?= htmlspecialchars($t['label']) ?> World Tier">
        <span class="text-lg font-bold px-2 py-0.5 align-middle inline-block" style="background: <?= $t['color'] ?>; color: #1e1e2e;"><?= htmlspecialchars($t['label']) ?></span>
      </span>
      <?php endif; ?>
    </h1>
    <?php if ($playerAwards): ?>
    <div class="flex flex-wrap gap-2 mb-2">
      <?php foreach ($playerAwards as $award): ?>
       <span class="badge font-bold tooltip rounded-none" data-tip="<?= htmlspecialchars($award['desc']) ?>" style="border-left: 3px solid <?= htmlspecialchars($award['color']) ?>"><?= htmlspecialchars($award['title']) ?></span>
      <?php endforeach; ?>
    </div>
    <?php endif; ?>

    <div class="card bg-base-200 mb-8 rounded-b-2xl rounded-t-none">
      <div class="card-body">
        <h2 class="card-title">Quest Progress</h2>
        <div class="stats">
          <div class="stat">
            <div class="stat-title text-xs">Completed</div>
            <div class="stat-value text-lg"><?= number_format($completedQuests) ?></div>
          </div>
          <div class="stat">
            <div class="stat-title text-xs">Started</div>
            <div class="stat-value text-lg"><?= number_format($startedQuests) ?></div>
          </div>
          <div class="stat">
            <div class="stat-title text-xs">Overall</div>
            <div class="stat-value text-lg"><?= $totalQuests > 0 ? number_format($completedQuests / $totalQuests * 100, 1) : 0 ?>%</div>
          </div>
        </div>
        <progress class="progress progress-primary w-full" value="<?= $totalQuests > 0 ? (int)round($completedQuests / $totalQuests * 100) : 0 ?>" max="100"></progress>
      </div>
    </div>

    <?php if ($statBoxes): ?>
    <h2 class="text-xl font-bold mb-4">Stats</h2>
    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-5 gap-3 mb-8">
      <?php foreach ($statBoxes as $s): ?>
      <div class="stat bg-base-200 radius-box p-4">
        <div class="stat-title text-xs"><?= $s['title'] ?></div>
        <div class="stat-value text-lg<?= isset($s['extra']) ? ' ' . $s['extra'] : '' ?>"><?= $s['value'] ?></div>
      </div>
      <?php endforeach; ?>
    </div>
    <?php endif; ?>

  </div>
<?php require $partialsPath . '/footer.php'; ?>
