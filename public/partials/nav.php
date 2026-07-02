<div class="navbar bg-base-300/80 border-b border-base-300">
  <div class="w-full lg:max-w-5xl xl:max-w-6xl mx-auto px-4 flex items-center gap-1">
    <a href="/" class="btn btn-ghost px-3">
      <img src="/images/logo-mini.webp" alt="Glip Glops" class="h-8">
    </a>
    <ul class="menu menu-horizontal px-1">
      <li><a href="/"<?= $currentPage === 'index' ? ' class="font-bold"' : '' ?>>Home</a></li>
      <li><a href="/players"<?= $currentPage === 'players' || $currentPage === 'player' ? ' class="font-bold"' : '' ?>>Players</a></li>
      <li><a href="/bosses"<?= $currentPage === 'bosses' ? ' class="font-bold"' : '' ?>>Bosses</a></li>
      <li><a href="/armory"<?= $currentPage === 'armory' ? ' class="font-bold"' : '' ?>>Armory</a></li>
      <li><a href="/map"<?= $currentPage === 'map' ? ' class="font-bold"' : '' ?>>Map</a></li>
    </ul>
  </div>
</div>
