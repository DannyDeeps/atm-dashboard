<!DOCTYPE html>
<html lang="en" data-theme="dark">
<?php $headTitle = $headTitle ?? 'Glip Glops - ATM10 Modded Minecraft Server'; require $partialsPath . '/head.php'; ?>
<body>
  <?php require $partialsPath . '/nav.php'; ?>

  <section class="hero relative overflow-hidden">
    <div class="absolute inset-0 bg-cover bg-center blur-[1.5px]" style="background-image: url(/images/hero.webp);"></div>
    <div class="hero-content z-10 w-full py-8 relative">
      <div class="w-full lg:max-w-5xl xl:max-w-6xl mx-auto px-4 grid grid-cols-1 md:grid-cols-3 gap-6 items-center">

        <!-- Column 1: Online widget + Server IP -->
        <div class="flex justify-center">
          <div class="w-full max-w-72">
            <!-- IP copy bar — full-width header with rounded top -->
            <button class="btn btn-primary w-full rounded-t-lg rounded-b-none font-mono text-sm gap-1 group overflow-hidden" value="<?= htmlspecialchars($config['server_hostname'] ?? 'play.glipglops.com') ?>" onclick="copyServer(this)">
              <span class="inline-flex flex-col overflow-hidden h-5 leading-5">
                <span class="transition-transform duration-300 ease-in-out group-hover:-translate-y-1/2">
                  <span class="block whitespace-nowrap"><?= htmlspecialchars($config['server_hostname'] ?? 'play.glipglops.com') ?></span>
                  <span class="block whitespace-nowrap">Click to Copy</span>
                </span>
              </span>
              <svg class="size-3.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
            </button>
            <!-- Widget body: bottom corners only -->
            <div class="bg-base-200/50 backdrop-blur-sm rounded-b-lg px-3">
              <!-- Status -->
              <div class="flex items-center gap-2 pt-2 pb-1.5 text-sm font-semibold">
                <?php if ((int)($onlineCount ?? 0) > 0): ?>
                <span class="size-2 rounded-full bg-success shrink-0"></span>
                <span>Online</span>
                <?php else: ?>
                <span class="size-2 rounded-full bg-neutral shrink-0"></span>
                <span class="text-base-content/50">Offline</span>
                <?php endif; ?>
              </div>
              <!-- Stats -->
              <div class="text-xs flex gap-4 pb-2 border-t border-base-content/10 pt-1.5">
                <span class="text-base-content/60">Registered: <strong class="text-primary"><?= (int)($totalPlayers ?? 0) ?></strong></span>
                <span class="text-base-content/60">Online: <strong class="text-success"><?= (int)($onlineCount ?? 0) ?></strong></span>
              </div>
              <!-- Online players -->
              <div class="pb-3<?= empty($onlinePlayers) ? ' hidden' : '' ?>">
                <div class="space-y-1.5">
                  <?php foreach ($onlinePlayers as $op): ?>
                  <div class="flex items-center gap-2">
                    <div class="avatar">
                      <div class="w-7 rounded-full">
                        <img src="/head-cache.php?uuid=<?= htmlspecialchars($op['id']) ?>" alt="" loading="lazy" />
                      </div>
                    </div>
                    <span class="text-sm truncate"><?= htmlspecialchars($op['name']) ?></span>
                    <span class="size-1.5 rounded-full bg-success shrink-0 ml-auto"></span>
                  </div>
                  <?php endforeach; ?>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Column 2: Animated logo -->
        <div class="flex justify-center">
          <?php require $partialsPath . '/logo-stack.php'; ?>
        </div>

        <!-- Column 3: Discord -->
        <div class="flex justify-center items-center">
          <a href="https://discord.gg/ay8K5G8zCM" target="_blank" class="btn btn-primary btn-sm md:btn-md font-bold gap-2">
            <i class="fa-brands fa-discord text-base md:text-lg"></i>
            Join Discord
          </a>
        </div>

      </div>
    </div>
  </section>

  <!-- Leaderboards -->
  <section class="bg-base-200/30 py-10">
    <div class="w-full lg:max-w-5xl xl:max-w-6xl mx-auto px-4">
      <h2 class="text-center text-5xl md:text-6xl font-minecrafter mb-8">
        <span style="color: #88e23b; filter: drop-shadow(-1px 3px 1px #5c9e1e) drop-shadow(-2px 3px 1px #3d6b12) drop-shadow(-3px 3px 0 #1f3607) drop-shadow(-4px 6px 3px #080808)">Leader</span><span style="margin-left: 0.10em; color: #FCF31F; filter: drop-shadow(-1px 3px 1px #c4bc00) drop-shadow(-2px 3px 1px #8a8400) drop-shadow(-3px 3px 0 #504c00) drop-shadow(-4px 6px 3px #080808)">boards</span>
      </h2>
      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
      <?php foreach ($leaderboards as $lb): ?>
      <div class="flex justify-center">
      <?php require $partialsPath . '/leaderboard.php'; ?>
      </div>
      <?php endforeach; ?>
      </div>
    </div>
  </section>

  <script>
  function copyServer(btn) {
    if (btn.dataset.copied) return;
    navigator.clipboard.writeText(btn.value).then(() => {
      btn.dataset.copied = '1';
      const orig = btn.innerHTML;
      btn.innerHTML = 'Copied! <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"/></svg>';
      setTimeout(() => {
        btn.innerHTML = orig;
        delete btn.dataset.copied;
      }, 1800);
    });
  }
  </script>
<?php require $partialsPath . '/footer.php'; ?>
