<?php

$mockup = function (string $html) use ($factory): \Psr\Http\Message\ResponseInterface {
    return $factory->createResponse(200)
        ->withHeader('Content-Type', 'text/html; charset=utf-8')
        ->withBody($factory->createStream($html));
};

$color = '#88e23b';

// ── Hero: 3-column row, bg image ──
$hero = function () use ($color) { ob_start(); ?>
  <section class="hero min-h-[30vh] bg-cover bg-center relative" style="background-image: url(/images/hero.webp);">
    <div class="hero-overlay bg-neutral/80"></div>
    <div class="hero-content z-10 w-full py-6">
      <div class="max-w-6xl mx-auto w-full flex items-center justify-between gap-4">
        <div class="flex items-center gap-3">
          <div class="avatar-group -space-x-3">
            <div class="avatar placeholder"><div class="w-9 rounded-full bg-base-300/60 text-base-content/50 text-[10px] font-bold border-2 border-[<?= $color ?>]/30">DD</div></div>
            <div class="avatar placeholder"><div class="w-9 rounded-full bg-base-300/60 text-base-content/50 text-[10px] font-bold border-2 border-[<?= $color ?>]/30">VX</div></div>
            <div class="avatar placeholder"><div class="w-9 rounded-full bg-base-300/60 text-base-content/50 text-[10px] font-bold border-2 border-[<?= $color ?>]/30">CK</div></div>
            <div class="avatar placeholder"><div class="w-9 rounded-full bg-base-300/60 text-[<?= $color ?>] text-[10px] font-bold border-2 border-[<?= $color ?>]/30">+2</div></div>
          </div>
          <div>
            <div class="text-lg font-bold" style="color:<?= $color ?>">5</div>
            <div class="text-[10px] text-base-content/50">players online</div>
          </div>
        </div>
        <div class="text-center">
          <?php require __DIR__ . '/partials/logo-stack.php'; ?>
          <div class="flex items-center gap-2 mt-2 justify-center">
            <span class="size-2 rounded-full bg-success animate-pulse shadow-lg shadow-success/50"></span>
            <button class="text-sm font-mono text-base-content/60 hover:text-[<?= $color ?>] transition-colors cursor-pointer copy-ip-btn" data-ip="atm10.deepstak.uk">
              atm10.deepstak.uk
              <svg class="size-3 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
            </button>
          </div>
        </div>
        <a href="https://discord.gg/ay8K5G8zCM" target="_blank" class="btn btn-sm font-bold gap-2 text-black border-none shadow-lg" style="background:<?= $color ?>;box-shadow:0 0 16px <?= $color ?>40">
          <svg class="size-4" fill="currentColor" viewBox="0 0 24 24"><path d="M22.162 5.656a10.1 10.1 0 01-2.828.828 4.966 4.966 0 002.164-2.7 9.754 9.754 0 01-3.126 1.2 4.942 4.942 0 00-8.42 4.5A14.015 14.015 0 013 4.445a4.93 4.93 0 001.524 6.572 4.878 4.878 0 01-2.236-.634v.06a4.94 4.94 0 003.96 4.82 4.882 4.882 0 01-2.228.084 4.945 4.945 0 004.614 3.414A9.903 9.903 0 012 19.153a13.95 13.95 0 007.548 2.212c9.142 0 14.307-7.721 13.995-14.646A10.05 10.05 0 0022.162 5.656z"/></svg>
          Join Discord
        </a>
      </div>
    </div>
  </section>
<?php return ob_get_clean(); };

// ── Nav ──
$nav = function () { ob_start(); ?>
  <div class="navbar bg-base-300/80 backdrop-blur-sm border-b border-base-300">
    <div class="max-w-6xl mx-auto px-4 flex items-center w-full">
      <ul class="menu menu-horizontal px-0 gap-1 text-sm">
        <li><a href="/" class="text-base-content/60 hover:text-base-content">Home</a></li>
        <li><a href="/players" class="text-base-content/60 hover:text-base-content">Players</a></li>
        <li><a href="/bosses" class="text-base-content/60 hover:text-base-content">Bosses</a></li>
        <li><a href="/map" class="text-base-content/60 hover:text-base-content">Map</a></li>
      </ul>
    </div>
  </div>
<?php return ob_get_clean(); };

// ── Footer ──
$footer = function () { ob_start(); ?>
  <footer class="footer footer-center py-4 text-xs bg-base-300 text-base-content/40 border-t border-base-300">
    <p>&copy; 2026 Danny Huggins &middot; Last collection: Never</p>
  </footer>
<?php return ob_get_clean(); };

// ── Data ──
$p = function($i, $n, $v) { return ['i' => $i, 'n' => $n, 'v' => $v]; };
$lbP = [$p('DD','DannyDeeps','60h'),$p('VX','Vexchia','29h'),$p('CK','Cookio','28h'),$p('SP','StrayPandora27','12h'),$p('AS','Astromancer','9h')];
$lbD = [$p('VX','Vexchia','47'),$p('DD','DannyDeeps','32'),$p('CK','Cookio','28'),$p('SP','StrayPandora27','15'),$p('AS','Astromancer','11')];
$lbDi = [$p('CK','Cookio','142km'),$p('DD','DannyDeeps','98km'),$p('VX','Vexchia','67km'),$p('AS','Astromancer','43km'),$p('SP','StrayPandora27','29km')];
$lbM = [$p('DD','DannyDeeps','8.4K'),$p('VX','Vexchia','5.9K'),$p('CK','Cookio','4.1K'),$p('SP','StrayPandora27','2.8K'),$p('AS','Astromancer','1.7K')];
$lbB = [$p('DD','DannyDeeps','234K'),$p('CK','Cookio','187K'),$p('VX','Vexchia','142K'),$p('AS','Astromancer','89K'),$p('SP','StrayPandora27','56K')];
$lbC = [$p('DD','DannyDeeps','56K'),$p('CK','Cookio','41K'),$p('VX','Vexchia','33K'),$p('SP','StrayPandora27','18K'),$p('AS','Astromancer','12K')];

// ── Copy JS ──
$js = '<script>document.addEventListener("click",function(e){var b=e.target.closest(".copy-ip-btn");if(!b||b.dataset.copied)return;b.dataset.copied="1";var o=b.innerHTML;navigator.clipboard.writeText(b.dataset.ip).then(function(){b.innerHTML="Copied! <svg class=\"size-3 inline\" fill=\"none\" stroke=\"currentColor\" viewBox=\"0 0 24 24\"><path d=\"M20 6 9 17l-5-5\"/></svg>";setTimeout(function(){b.innerHTML=o;delete b.dataset.copied},1800)})})</script>';

// ═══════════════════════════════════════════════════════════
// Mockup 1 — 3-column grid
// ═══════════════════════════════════════════════════════════
$router->get('/mockup1', function () use ($mockup, $hero, $nav, $footer, $js, $color, $lbP, $lbD, $lbDi) {
    ob_start(); ?><!DOCTYPE html>
<html lang="en" data-theme="dark">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Mockup 1 — 3-Column Grid</title>
  <link href="https://cdn.jsdelivr.net/npm/daisyui@5" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/daisyui@5/themes.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
  <style>
    [data-theme="dark"]{--color-primary:#88e23b;--color-primary-content:#000}
    .lb-card{background:rgba(255,255,255,.025);border:1px solid rgba(255,255,255,.06);border-radius:1rem;padding:1.25rem}
    .lb-card:hover{border-color:rgba(136,226,59,.15)}
  </style>
</head>
<body>
  <?= $hero() ?>
  <?= $nav() ?>
  <main class="max-w-6xl mx-auto px-4 py-8">
    <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
      <?php foreach ([
        ['t'=>'⏱ The No-Lifer','c'=>'#cba6f7','r'=>$lbP],
        ['t'=>'💀 Fragile','c'=>'#f38ba8','r'=>$lbD],
        ['t'=>'🗺 Trailblazer','c'=>'#89b4fa','r'=>$lbDi],
      ] as $lb): ?>
      <div class="lb-card">
        <h3 class="text-sm font-bold mb-3" style="color:<?= $lb['c'] ?>"><?= $lb['t'] ?></h3>
        <ul class="steps steps-vertical overflow-y-visible">
          <?php $i=1; foreach ($lb['r'] as $r): ?>
          <li class="step" data-content="<?= $i++ ?>">
            <div class="step-icon overflow-hidden"><div class="w-8 h-8 rounded-full bg-base-300 text-xs font-bold text-base-content/40 flex items-center justify-center"><?= $r['i'] ?></div></div>
            <div class="flex items-center gap-2 w-full py-0.5">
              <span class="text-sm text-base-content"><?= $r['n'] ?></span>
              <span class="ml-auto font-semibold text-xs" style="color:<?= $lb['c'] ?>"><?= $r['v'] ?></span>
            </div>
          </li>
          <?php endforeach; ?>
        </ul>
      </div>
      <?php endforeach; ?>
    </div>
  </main>
  <?= $footer() ?>
  <?= $js ?>
</body></html>
<?php return $mockup(ob_get_clean()); });

// ═══════════════════════════════════════════════════════════
// Mockup 2 — 2-column wide
// ═══════════════════════════════════════════════════════════
$router->get('/mockup2', function () use ($mockup, $hero, $nav, $footer, $js, $color, $lbP, $lbD, $lbDi, $lbM) {
    ob_start(); ?><!DOCTYPE html>
<html lang="en" data-theme="dark">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Mockup 2 — 2-Column Wide</title>
  <link href="https://cdn.jsdelivr.net/npm/daisyui@5" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/daisyui@5/themes.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
  <style>
    [data-theme="dark"]{--color-primary:#88e23b;--color-primary-content:#000}
    .lb-card{background:rgba(255,255,255,.025);border:1px solid rgba(255,255,255,.06);border-radius:1rem;padding:1.25rem;border-left:3px solid var(--accent)}
  </style>
</head>
<body>
  <?= $hero() ?>
  <?= $nav() ?>
  <main class="max-w-6xl mx-auto px-4 py-8">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
      <?php foreach ([
        ['t'=>'⏱ The No-Lifer','c'=>'#cba6f7','r'=>$lbP],
        ['t'=>'💀 Fragile','c'=>'#f38ba8','r'=>$lbD],
        ['t'=>'🗺 Trailblazer','c'=>'#89b4fa','r'=>$lbDi],
        ['t'=>'💥 Mob Masher','c'=>'#f5c2e7','r'=>$lbM],
      ] as $lb): ?>
      <div class="lb-card" style="--accent:<?= $lb['c'] ?>;border-left-color:<?= $lb['c'] ?>">
        <h3 class="text-sm font-bold mb-3" style="color:<?= $lb['c'] ?>"><?= $lb['t'] ?></h3>
        <ul class="steps steps-vertical overflow-y-visible">
          <?php $i=1; foreach ($lb['r'] as $r): ?>
          <li class="step" data-content="<?= $i++ ?>">
            <div class="step-icon overflow-hidden"><div class="w-8 h-8 rounded-full bg-base-300 text-xs font-bold text-base-content/40 flex items-center justify-center"><?= $r['i'] ?></div></div>
            <div class="flex items-center gap-2 w-full py-0.5">
              <span class="text-sm text-base-content"><?= $r['n'] ?></span>
              <span class="ml-auto font-semibold text-xs" style="color:<?= $lb['c'] ?>"><?= $r['v'] ?></span>
            </div>
          </li>
          <?php endforeach; ?>
        </ul>
      </div>
      <?php endforeach; ?>
    </div>
  </main>
  <?= $footer() ?>
  <?= $js ?>
</body></html>
<?php return $mockup(ob_get_clean()); });

// ═══════════════════════════════════════════════════════════
// Mockup 3 — Stacked full-width
// ═══════════════════════════════════════════════════════════
$router->get('/mockup3', function () use ($mockup, $hero, $nav, $footer, $js, $color, $lbP, $lbD, $lbDi) {
    ob_start(); ?><!DOCTYPE html>
<html lang="en" data-theme="dark">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Mockup 3 — Stacked</title>
  <link href="https://cdn.jsdelivr.net/npm/daisyui@5" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/daisyui@5/themes.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
  <style>
    [data-theme="dark"]{--color-primary:#88e23b;--color-primary-content:#000}
    .lb-card{background:linear-gradient(135deg,rgba(255,255,255,.03),transparent);border:1px solid rgba(255,255,255,.06);border-radius:1rem;padding:1.25rem}
    .lb-card:hover{border-color:rgba(136,226,59,.15)}
  </style>
</head>
<body>
  <?= $hero() ?>
  <?= $nav() ?>
  <main class="max-w-3xl mx-auto px-4 py-8 space-y-5">
    <?php foreach ([
      ['t'=>'⏱ The No-Lifer','c'=>'#cba6f7','r'=>$lbP],
      ['t'=>'💀 Fragile','c'=>'#f38ba8','r'=>$lbD],
      ['t'=>'🗺 Trailblazer','c'=>'#89b4fa','r'=>$lbDi],
    ] as $lb): ?>
    <div class="lb-card">
      <h3 class="text-lg font-bold mb-3" style="color:<?= $lb['c'] ?>"><?= $lb['t'] ?></h3>
      <ul class="steps steps-vertical overflow-y-visible">
        <?php $i=1; foreach ($lb['r'] as $r): ?>
        <li class="step" data-content="<?= $i++ ?>">
          <div class="step-icon overflow-hidden"><div class="w-8 h-8 rounded-full bg-base-300 text-xs font-bold text-base-content/40 flex items-center justify-center"><?= $r['i'] ?></div></div>
          <div class="flex items-center gap-2 w-full py-0.5">
            <span class="text-sm text-base-content font-medium"><?= $r['n'] ?></span>
            <span class="ml-auto font-semibold text-sm" style="color:<?= $lb['c'] ?>"><?= $r['v'] ?></span>
          </div>
        </li>
        <?php endforeach; ?>
      </ul>
    </div>
    <?php endforeach; ?>
  </main>
  <?= $footer() ?>
  <?= $js ?>
</body></html>
<?php return $mockup(ob_get_clean()); });

// ═══════════════════════════════════════════════════════════
// Mockup 4 — 2-col asymmetric
// ═══════════════════════════════════════════════════════════
$router->get('/mockup4', function () use ($mockup, $hero, $nav, $footer, $js, $color, $lbP, $lbD, $lbDi) {
    ob_start(); ?><!DOCTYPE html>
<html lang="en" data-theme="dark">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Mockup 4 — Asymmetric</title>
  <link href="https://cdn.jsdelivr.net/npm/daisyui@5" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/daisyui@5/themes.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
  <style>
    [data-theme="dark"]{--color-primary:#88e23b;--color-primary-content:#000}
    .lb-card{background:rgba(255,255,255,.025);border:1px solid rgba(255,255,255,.06);border-radius:1rem;padding:1.25rem}
    .lb-card:hover{border-color:rgba(136,226,59,.15)}
  </style>
</head>
<body>
  <?= $hero() ?>
  <?= $nav() ?>
  <main class="max-w-6xl mx-auto px-4 py-8">
    <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
      <div class="md:col-span-1 space-y-5">
        <?php foreach ([
          ['t'=>'⏱ The No-Lifer','c'=>'#cba6f7','r'=>$lbP],
          ['t'=>'💀 Fragile','c'=>'#f38ba8','r'=>$lbD],
        ] as $lb): ?>
        <div class="lb-card">
          <h3 class="text-sm font-bold mb-3" style="color:<?= $lb['c'] ?>"><?= $lb['t'] ?></h3>
          <ul class="steps steps-vertical overflow-y-visible">
            <?php $i=1; foreach ($lb['r'] as $r): ?>
            <li class="step" data-content="<?= $i++ ?>">
              <div class="step-icon overflow-hidden"><div class="w-8 h-8 rounded-full bg-base-300 text-xs font-bold text-base-content/40 flex items-center justify-center"><?= $r['i'] ?></div></div>
              <div class="flex items-center gap-2 w-full py-0.5">
                <span class="text-sm text-base-content"><?= $r['n'] ?></span>
                <span class="ml-auto font-semibold text-xs" style="color:<?= $lb['c'] ?>"><?= $r['v'] ?></span>
              </div>
            </li>
            <?php endforeach; ?>
          </ul>
        </div>
        <?php endforeach; ?>
      </div>
      <div class="md:col-span-2">
        <div class="lb-card h-full">
          <h3 class="text-sm font-bold mb-3" style="color:#89b4fa">🗺 Trailblazer</h3>
          <ul class="steps steps-vertical overflow-y-visible">
            <?php $i=1; foreach ($lbDi as $r): ?>
            <li class="step" data-content="<?= $i++ ?>">
              <div class="step-icon overflow-hidden"><div class="w-8 h-8 rounded-full bg-base-300 text-xs font-bold text-base-content/40 flex items-center justify-center"><?= $r['i'] ?></div></div>
              <div class="flex items-center gap-2 w-full py-0.5">
                <span class="text-sm text-base-content"><?= $r['n'] ?></span>
                <span class="ml-auto font-semibold text-xs" style="color:#89b4fa"><?= $r['v'] ?></span>
              </div>
            </li>
            <?php endforeach; ?>
          </ul>
        </div>
      </div>
    </div>
  </main>
  <?= $footer() ?>
  <?= $js ?>
</body></html>
<?php return $mockup(ob_get_clean()); });

// ═══════════════════════════════════════════════════════════
// Mockup 5 — 3x2 full grid
// ═══════════════════════════════════════════════════════════
$router->get('/mockup5', function () use ($mockup, $hero, $nav, $footer, $js, $color, $lbP, $lbD, $lbDi, $lbM, $lbB, $lbC) {
    ob_start(); ?><!DOCTYPE html>
<html lang="en" data-theme="dark">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Mockup 5 — Full Grid</title>
  <link href="https://cdn.jsdelivr.net/npm/daisyui@5" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/daisyui@5/themes.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
  <style>
    [data-theme="dark"]{--color-primary:#88e23b;--color-primary-content:#000}
    .lb-card{background:rgba(255,255,255,.025);border:1px solid rgba(255,255,255,.06);border-radius:1rem;padding:1.25rem;position:relative;overflow:hidden}
    .lb-card::before{content:'';position:absolute;top:0;left:0;right:0;height:3px;background:var(--accent)}
  </style>
</head>
<body>
  <?= $hero() ?>
  <?= $nav() ?>
  <main class="max-w-6xl mx-auto px-4 py-8">
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
      <?php foreach ([
        ['t'=>'⏱ The No-Lifer','c'=>'#cba6f7','r'=>$lbP],
        ['t'=>'💀 Fragile','c'=>'#f38ba8','r'=>$lbD],
        ['t'=>'🗺 Trailblazer','c'=>'#89b4fa','r'=>$lbDi],
        ['t'=>'💥 Mob Masher','c'=>'#f5c2e7','r'=>$lbM],
        ['t'=>'⛏️ Strip Miner','c'=>'#fab387','r'=>$lbB],
        ['t'=>'🏭 One-man Factory','c'=>'#89dceb','r'=>$lbC],
      ] as $lb): ?>
      <div class="lb-card" style="--accent:<?= $lb['c'] ?>">
        <h3 class="text-sm font-bold mb-3" style="color:<?= $lb['c'] ?>"><?= $lb['t'] ?></h3>
        <ul class="steps steps-vertical overflow-y-visible">
          <?php $i=1; foreach ($lb['r'] as $r): ?>
          <li class="step" data-content="<?= $i++ ?>">
            <div class="step-icon overflow-hidden"><div class="w-8 h-8 rounded-full bg-base-300 text-xs font-bold text-base-content/40 flex items-center justify-center"><?= $r['i'] ?></div></div>
            <div class="flex items-center gap-2 w-full py-0.5">
              <span class="text-sm text-base-content"><?= $r['n'] ?></span>
              <span class="ml-auto font-semibold text-xs" style="color:<?= $lb['c'] ?>"><?= $r['v'] ?></span>
            </div>
          </li>
          <?php endforeach; ?>
        </ul>
      </div>
      <?php endforeach; ?>
    </div>
  </main>
  <?= $footer() ?>
  <?= $js ?>
</body></html>
<?php return $mockup(ob_get_clean()); });

// ═══════════════════════════════════════════════════════════
// Logo animation test page
// ═══════════════════════════════════════════════════════════
$router->get('/logo', function () use ($mockup, $color) {
    ob_start(); ?><!DOCTYPE html>
<html lang="en" data-theme="dark">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Logo Animation Test</title>
  <link href="https://cdn.jsdelivr.net/npm/daisyui@5" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/daisyui@5/themes.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
  <style>[data-theme="dark"]{--color-primary:#88e23b;--color-primary-content:#000}body{display:flex;flex-direction:column;align-items:center;justify-content:center;min-height:100vh;gap:2rem}</style>
</head>
<body>
  <h1 class="text-xl font-bold text-base-content">Stacked Logo Animation</h1>
  <?php require __DIR__ . '/partials/logo-stack.php'; ?>
  <p class="text-xs text-base-content/40">Pulse (bg) + Float (glip, glops) + Spin (stars)</p>
</body></html>
<?php return $mockup(ob_get_clean()); });

// ═══════════════════════════════════════════════════════════
// Landing page mockup — logo-focused with tabbed leaderboards
// ═══════════════════════════════════════════════════════════
$router->get('/landing', function () use ($mockup, $color, $js, $lbP, $lbD, $lbDi, $lbM) {
    ob_start(); ?><!DOCTYPE html>
<html lang="en" data-theme="dark">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Glip Glops — Landing</title>
  <link href="https://cdn.jsdelivr.net/npm/daisyui@5" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/daisyui@5/themes.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
  <style>
    [data-theme="dark"]{--color-primary:#88e23b;--color-primary-content:#000}
    .tab-content ul.steps{min-height:18rem}
  </style>
</head>
<body>
  <!-- Accent bar -->
  <div style="background:linear-gradient(90deg,<?= $color ?>,#5cb820,<?= $color ?>);height:3px"></div>

  <!-- Hero -->
  <section class="hero min-h-[50vh] bg-cover bg-center relative" style="background-image: url(/images/hero.webp);">
    <div class="hero-overlay bg-neutral/80"></div>
    <div class="hero-content z-10 w-full py-8">
      <div class="max-w-6xl mx-auto w-full flex flex-col md:flex-row items-center justify-between gap-6">
        <!-- Left: Players online -->
        <div class="flex items-center gap-3">
          <div class="avatar-group -space-x-3">
            <div class="avatar placeholder"><div class="w-9 rounded-full bg-base-300/60 text-base-content/50 text-[10px] font-bold border-2 border-[<?= $color ?>]/30">DD</div></div>
            <div class="avatar placeholder"><div class="w-9 rounded-full bg-base-300/60 text-base-content/50 text-[10px] font-bold border-2 border-[<?= $color ?>]/30">VX</div></div>
            <div class="avatar placeholder"><div class="w-9 rounded-full bg-base-300/60 text-base-content/50 text-[10px] font-bold border-2 border-[<?= $color ?>]/30">CK</div></div>
            <div class="avatar placeholder"><div class="w-9 rounded-full bg-base-300/60 text-[<?= $color ?>] text-[10px] font-bold border-2 border-[<?= $color ?>]/30">+2</div></div>
          </div>
          <div>
            <div class="text-lg font-bold" style="color:<?= $color ?>">5</div>
            <div class="text-[10px] text-base-content/50">players online</div>
          </div>
        </div>
        <!-- Center: Logo -->
        <?php require __DIR__ . '/partials/logo-stack.php'; ?>
        <!-- Right: Discord CTA -->
        <a href="https://discord.gg/ay8K5G8zCM" target="_blank" class="btn btn-sm md:btn-md font-bold gap-2 text-black border-none shadow-lg" style="background:<?= $color ?>;box-shadow:0 0 20px <?= $color ?>50">
          <svg class="size-4 md:size-5" fill="currentColor" viewBox="0 0 24 24"><path d="M22.162 5.656a10.1 10.1 0 01-2.828.828 4.966 4.966 0 002.164-2.7 9.754 9.754 0 01-3.126 1.2 4.942 4.942 0 00-8.42 4.5A14.015 14.015 0 013 4.445a4.93 4.93 0 001.524 6.572 4.878 4.878 0 01-2.236-.634v.06a4.94 4.94 0 003.96 4.82 4.882 4.882 0 01-2.228.084 4.945 4.945 0 004.614 3.414A9.903 9.903 0 012 19.153a13.95 13.95 0 007.548 2.212c9.142 0 14.307-7.721 13.995-14.646A10.05 10.05 0 0022.162 5.656z"/></svg>
          Join Discord
        </a>
      </div>
    </div>
  </section>

  <!-- Nav -->
  <div class="navbar bg-base-300/80 backdrop-blur-sm border-b border-base-300">
    <div class="max-w-6xl mx-auto px-4 flex items-center w-full">
      <ul class="menu menu-horizontal px-0 gap-1 text-sm">
        <li><a href="/" class="text-base-content/60 hover:text-base-content">Home</a></li>
        <li><a href="/players" class="text-base-content/60 hover:text-base-content">Players</a></li>
        <li><a href="/bosses" class="text-base-content/60 hover:text-base-content">Bosses</a></li>
        <li><a href="/map" class="text-base-content/60 hover:text-base-content">Map</a></li>
      </ul>
    </div>
  </div>

  <!-- Leaderboards -->
  <main class="max-w-5xl mx-auto px-4 py-10">
    <div class="text-center mb-8">
      <h2 class="text-2xl font-bold text-base-content">Leaderboards</h2>
      <p class="text-sm text-base-content/40 mt-1">Top players across all categories</p>
    </div>

    <div role="tablist" class="tabs tabs-bordered justify-center gap-2 mb-6">
      <?php $tabs = [
        ['label'=>'⏱ Playtime', 'c'=>'#cba6f7', 'r'=>$lbP],
        ['label'=>'💀 Deaths',   'c'=>'#f38ba8', 'r'=>$lbD],
        ['label'=>'🗺 Distance', 'c'=>'#89b4fa', 'r'=>$lbDi],
        ['label'=>'💥 Mobs',     'c'=>'#f5c2e7', 'r'=>$lbM],
      ]; foreach ($tabs as $i => $t): ?>
      <input type="radio" name="lb_tabs" role="tab" class="tab text-sm" aria-label="<?= $t['label'] ?>" <?= $i === 0 ? 'checked="checked"' : '' ?> />
      <div role="tabpanel" class="tab-content w-full max-w-2xl mx-auto">
        <div class="card bg-base-200/30 border border-base-300/30">
          <div class="card-body p-5">
            <ul class="steps steps-vertical overflow-y-visible">
              <?php $j=1; foreach ($t['r'] as $r): ?>
              <li class="step" data-content="<?= $j++ ?>">
                <div class="step-icon overflow-hidden"><div class="w-9 h-9 rounded-full bg-base-300 text-sm font-bold text-base-content/40 flex items-center justify-center"><?= $r['i'] ?></div></div>
                <div class="flex items-center gap-2 w-full py-1">
                  <span class="text-base font-medium text-base-content"><?= $r['n'] ?></span>
                  <span class="ml-auto font-bold text-sm" style="color:<?= $t['c'] ?>"><?= $r['v'] ?></span>
                </div>
              </li>
              <?php endforeach; ?>
            </ul>
          </div>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
  </main>

  <!-- Footer -->
  <footer class="footer footer-center py-4 text-xs bg-base-300 text-base-content/40 border-t border-base-300">
    <p>&copy; 2026 Danny Huggins &middot; Last collection: Never</p>
  </footer>
  <?= $js ?>
</body></html>
<?php return $mockup(ob_get_clean()); });

// ═══════════════════════════════════════════════════════════
// Widget Styles Mockup — Who's Online + Discord variations
// ═══════════════════════════════════════════════════════════
$router->get('/mockup-widgets', function () use ($mockup, $color) {
    ob_start(); ?><!DOCTYPE html>
<html lang="en" data-theme="dark">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Widget Style Mockups</title>
  <link href="https://cdn.jsdelivr.net/npm/daisyui@5" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/daisyui@5/themes.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
  <style>
    [data-theme="dark"]{--color-primary:#88e23b;--color-primary-content:#000}
    body{background:#1e1e2e;padding:2rem}
  </style>
</head>
<body>
  <div class="max-w-5xl mx-auto space-y-12">

    <!-- ═══ WHO'S ONLINE ═══ -->
    <div>
      <h2 class="text-xs font-semibold tracking-widest uppercase text-base-content/40 border-b border-base-content/10 pb-2 mb-4">Who's Online — Style Variations</h2>
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">

        <!-- V1: Glass card (current) -->
        <div class="bg-base-200/50 backdrop-blur-sm rounded-lg">
          <div class="px-3 py-2 text-sm font-semibold flex items-center gap-2"><span class="size-2 rounded-full bg-success shrink-0"></span>Online Now — 5</div>
          <div class="px-3 py-2 text-xs flex gap-4 border-t border-base-content/10">
            <span class="text-base-content/60">Registered: <strong class="text-primary">42</strong></span>
            <span class="text-base-content/60">Online: <strong class="text-success">5</strong></span>
          </div>
          <div class="p-3 pt-0 space-y-1.5">
            <div class="flex items-center gap-2"><div class="avatar"><div class="w-7 rounded-full"><img src="/head-cache.php?uuid=303dbe1b-8cd0-438f-87b9-c584375ad84f" alt="" loading="lazy"></div></div><span class="text-sm truncate">DannyDeeps</span><span class="size-1.5 rounded-full bg-success shrink-0 ml-auto"></span></div>
            <div class="flex items-center gap-2"><div class="avatar"><div class="w-7 rounded-full"><img src="/head-cache.php?uuid=c5e474be-769f-4279-ba3a-af914aa8888b" alt="" loading="lazy"></div></div><span class="text-sm truncate">Cookio</span><span class="size-1.5 rounded-full bg-success shrink-0 ml-auto"></span></div>
            <div class="flex items-center gap-2"><div class="avatar"><div class="w-7 rounded-full"><img src="/head-cache.php?uuid=c81a3539-7b18-4ab3-9a57-7fa3a843fa1d" alt="" loading="lazy"></div></div><span class="text-sm truncate">Vexchia</span><span class="size-1.5 rounded-full bg-success shrink-0 ml-auto"></span></div>
          </div>
        </div>

        <!-- V2: Compact status bar -->
        <div class="bg-base-300/40 rounded-lg p-3 flex items-center gap-3">
          <span class="size-2.5 rounded-full bg-success animate-pulse shrink-0 shadow-lg shadow-success/30"></span>
          <div class="flex-1 min-w-0">
            <div class="text-xs font-semibold text-base-content">5 / 42 players</div>
            <div class="text-[10px] text-base-content/40 truncate">DannyDeeps, Cookio, Vexchia +2</div>
          </div>
          <div class="avatar-group -space-x-2">
            <div class="avatar"><div class="w-6 rounded-full"><img src="/head-cache.php?uuid=303dbe1b-8cd0-438f-87b9-c584375ad84f" alt=""></div></div>
            <div class="avatar"><div class="w-6 rounded-full"><img src="/head-cache.php?uuid=c5e474be-769f-4279-ba3a-af914aa8888b" alt=""></div></div>
            <div class="avatar placeholder"><div class="w-6 rounded-full bg-base-300 text-[8px] font-bold text-base-content/40">+2</div></div>
          </div>
        </div>

        <!-- V3: Accent left border + avatar grid -->
        <div class="bg-base-300/20 rounded-lg border-l-4 border-[<?= $color ?>] pl-3 pr-4 py-3">
          <div class="flex items-center justify-between mb-2">
            <div class="flex items-center gap-2"><span class="size-2 rounded-full bg-success"></span><span class="text-sm font-bold">Online</span></div>
            <span class="badge badge-xs font-bold text-[<?= $color ?>] border-[<?= $color ?>]/30" style="background:<?= $color ?>15">5 players</span>
          </div>
          <div class="flex flex-wrap gap-1.5">
            <div class="avatar"><div class="w-7 rounded-full tooltip" data-tip="DannyDeeps"><img src="/head-cache.php?uuid=303dbe1b-8cd0-438f-87b9-c584375ad84f" alt=""></div></div>
            <div class="avatar"><div class="w-7 rounded-full tooltip" data-tip="Cookio"><img src="/head-cache.php?uuid=c5e474be-769f-4279-ba3a-af914aa8888b" alt=""></div></div>
            <div class="avatar"><div class="w-7 rounded-full tooltip" data-tip="Vexchia"><img src="/head-cache.php?uuid=c81a3539-7b18-4ab3-9a57-7fa3a843fa1d" alt=""></div></div>
            <div class="avatar placeholder"><div class="w-7 rounded-full bg-base-300 text-xs font-bold tooltip" data-tip="+2 more">+2</div></div>
          </div>
        </div>

      </div>
    </div>

    <!-- ═══ DISCORD ═══ -->
    <div>
      <h2 class="text-xs font-semibold tracking-widest uppercase text-base-content/40 border-b border-base-content/10 pb-2 mb-4">Discord — Style Variations</h2>
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">

        <!-- V1: Brand button -->
        <div class="flex flex-col items-start gap-2">
          <span class="text-[10px] font-semibold tracking-wider text-base-content/40 uppercase">Join our community</span>
          <a href="#" class="btn font-bold gap-2 text-white border-none shadow-lg" style="background:#5865F2"><i class="fa-brands fa-discord text-lg"></i> Join Discord</a>
        </div>

        <!-- V2: Glow button -->
        <div class="flex flex-col items-start gap-2">
          <span class="text-[10px] font-semibold tracking-wider text-base-content/40 uppercase">Join our community</span>
          <a href="#" class="btn font-bold gap-2 text-white border-none" style="background:#5865F2;box-shadow:0 0 24px #5865F260"><i class="fa-brands fa-discord text-lg"></i> Join Discord</a>
        </div>

        <!-- V3: Invite card -->
        <div class="bg-base-300/20 rounded-lg p-3 border border-base-content/5 flex items-center gap-3">
          <div class="size-10 rounded-full bg-[#5865F2] flex items-center justify-center text-white shrink-0"><i class="fa-brands fa-discord text-xl"></i></div>
          <div class="flex-1 min-w-0">
            <div class="text-sm font-bold">Discord</div>
            <div class="text-xs text-base-content/40">Chat, events &amp; support</div>
          </div>
          <a href="#" class="btn btn-sm btn-primary font-bold">Join</a>
        </div>

      </div>
    </div>

    <!-- ═══ COMBINED ═══ -->
    <div>
      <h2 class="text-xs font-semibold tracking-widest uppercase text-base-content/40 border-b border-base-content/10 pb-2 mb-4">Combined in Hero Columns</h2>
      <div class="grid grid-cols-1 md:grid-cols-2 gap-8">

        <!-- Combo A: Stacked online + Discord card -->
        <div class="space-y-3">
          <div class="bg-base-200/50 backdrop-blur-sm rounded-lg">
            <div class="px-3 py-2 text-sm font-semibold flex items-center gap-2"><span class="size-2 rounded-full bg-success shrink-0"></span>Online Now — 5</div>
            <div class="px-3 py-2 text-xs flex gap-4 border-t border-base-content/10"><span class="text-base-content/60">Registered: <strong class="text-primary">42</strong></span><span class="text-base-content/60">Online: <strong class="text-success">5</strong></span></div>
            <div class="p-3 pt-0 space-y-1.5">
              <div class="flex items-center gap-2"><div class="avatar"><div class="w-7 rounded-full"><img src="/head-cache.php?uuid=303dbe1b-8cd0-438f-87b9-c584375ad84f" alt="" loading="lazy"></div></div><span class="text-sm truncate">DannyDeeps</span><span class="size-1.5 rounded-full bg-success shrink-0 ml-auto"></span></div>
              <div class="flex items-center gap-2"><div class="avatar"><div class="w-7 rounded-full"><img src="/head-cache.php?uuid=c5e474be-769f-4279-ba3a-af914aa8888b" alt="" loading="lazy"></div></div><span class="text-sm truncate">Cookio</span><span class="size-1.5 rounded-full bg-success shrink-0 ml-auto"></span></div>
            </div>
          </div>
          <div class="bg-[#5865F2]/10 rounded-lg p-3 flex items-center gap-3 border border-[#5865F2]/20">
            <i class="fa-brands fa-discord text-2xl text-[#5865F2]"></i>
            <div class="flex-1 text-sm"><span class="font-bold">Discord</span><br><span class="text-xs text-base-content/40">Chat, events &amp; support</span></div>
            <a href="#" class="btn btn-sm font-bold" style="background:#5865F2;color:white;border-color:#5865F2">Join</a>
          </div>
        </div>

        <!-- Combo B: Single unified card -->
        <div class="bg-base-200/50 backdrop-blur-sm rounded-lg self-start">
          <div class="px-3 py-2 text-sm font-semibold flex items-center gap-2 border-b border-base-content/10">
            <span class="size-2 rounded-full bg-success shrink-0"></span>
            <span>5 online</span>
            <span class="text-base-content/30 mx-1">·</span>
            <i class="fa-brands fa-discord text-[#5865F2]"></i>
            <span>Discord</span>
            <a href="#" class="btn btn-xs btn-primary font-bold ml-auto">Join</a>
          </div>
          <div class="p-3 flex items-center gap-3">
            <div class="avatar-group -space-x-2">
              <div class="avatar"><div class="w-7 rounded-full"><img src="/head-cache.php?uuid=303dbe1b-8cd0-438f-87b9-c584375ad84f" alt=""></div></div>
              <div class="avatar"><div class="w-7 rounded-full"><img src="/head-cache.php?uuid=c5e474be-769f-4279-ba3a-af914aa8888b" alt=""></div></div>
              <div class="avatar"><div class="w-7 rounded-full"><img src="/head-cache.php?uuid=c81a3539-7b18-4ab3-9a57-7fa3a843fa1d" alt=""></div></div>
              <div class="avatar placeholder"><div class="w-7 rounded-full bg-base-300 text-xs font-bold text-base-content/40">+2</div></div>
            </div>
            <div class="text-xs text-base-content/40">42 registered</div>
          </div>
        </div>

      </div>
    </div>

  </div>
</body></html>
<?php return $mockup(ob_get_clean()); });
