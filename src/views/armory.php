<!DOCTYPE html>
<html lang="en" data-theme="dark">
<?php $headTitle = $headTitle ?? 'Armory - Glip Glops ATM10'; require $partialsPath . '/head.php'; ?>
<body>
  <?php require $partialsPath . '/nav.php'; ?>

  <section class="bg-base-200/30 py-10">
    <div class="w-full lg:max-w-5xl xl:max-w-6xl mx-auto px-4">
      <div class="text-center mb-8">
        <h1 class="text-5xl md:text-6xl font-minecrafter">
          <span style="color: #88e23b; filter: drop-shadow(-1px 3px 1px #5c9e1e) drop-shadow(-2px 3px 1px #3d6b12) drop-shadow(-3px 3px 0 #1f3607) drop-shadow(-4px 6px 3px #080808)">Arm</span><span style="margin-left: 0.4em; color: #FCF31F; filter: drop-shadow(-1px 3px 1px #c4bc00) drop-shadow(-2px 3px 1px #8a8400) drop-shadow(-3px 3px 0 #504c00) drop-shadow(-4px 6px 3px #080808)">ory</span>
        </h1>
        <p class="text-base text-base-content/60 mt-4">Currently equipped gear — armor, weapons, curios, artifacts &amp; relics</p>
      </div>

      <?php foreach ($players as $pi => $player): ?>
      <div class="card bg-base-200 border border-base-300 mb-6 rounded-b-2xl rounded-t-none overflow-visible">
        <div class="card-body p-5">
          <div class="flex items-center gap-3 mb-4">
            <img src="/head-cache.php?uuid=<?= htmlspecialchars($player['uuid']) ?>" alt="" class="w-10 h-10 shrink-0 bg-base-300 rounded-full">
            <div>
              <a href="/player/<?= htmlspecialchars($player['uuid']) ?>" class="link link-hover font-bold text-lg"><?= htmlspecialchars($player['name']) ?></a>
              <div class="text-xs text-base-content/40"><?= count($player['slots']) ?> equipped items</div>
            </div>
          </div>

          <?php foreach ([
            ['label' => 'Armor', 'slots' => ['head', 'chest', 'legs', 'feet']],
            ['label' => 'Weapons', 'slots' => ['mainhand', 'offhand']],
          ] as $group): ?>
          <div class="mb-3">
            <div class="text-xs font-semibold tracking-wider text-base-content/50 uppercase mb-2"><?= $group['label'] ?></div>
            <div class="flex flex-wrap gap-2">
              <?php foreach ($group['slots'] as $slot):
                $item = $player['slots'][$slot] ?? null; if (!$item) continue; ?>
              <div class="flex-1 min-w-32">
                <button type="button" data-item-detail="<?= $pi ?>-<?= $slot ?>" class="w-full text-left item-armory-btn">
                  <div class="bg-base-300/50 rounded-lg p-2.5 border border-base-300/50 hover:border-base-content/20 transition-colors cursor-pointer">
                    <div class="flex items-center gap-2">
                      <img src="<?= itemIcon($item['icon']) ?>" alt="" class="size-8 object-contain shrink-0" loading="lazy" onerror="this.src='data:image/svg+xml,<svg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 32 32%22><rect width=%2232%22 height=%2232%22 fill=%22%23333%22 rx=%224%22/><text x=%2216%22 y=%2222%22 text-anchor=%22middle%22 font-size=%2218%22 fill=%22%23777%22>?</text></svg>'">
                      <div class="min-w-0">
                        <div class="text-sm font-semibold truncate" style="color: <?= $item['color'] ?>"><?= htmlspecialchars($item['name']) ?></div>
                        <?php $shown = 0; foreach ($item['enchants'] as $ename => $elevel):
                          if ($shown >= 2) break; $shown++; ?>
                        <div class="text-[10px] text-base-content/50"><?= htmlspecialchars($ename) ?> <?= roman($elevel) ?></div>
                        <?php endforeach; ?>
                      </div>
                    </div>
                  </div>
                </button>
              </div>
              <?php endforeach; ?>
            </div>
          </div>
          <?php endforeach; ?>

          <?php $curios = array_filter($player['slots'], fn($k) => str_starts_with($k, 'curio_'), ARRAY_FILTER_USE_KEY); ?>
          <?php if ($curios): ?>
          <div>
            <div class="text-xs font-semibold tracking-wider text-base-content/50 uppercase mb-2">Curios, Artifacts &amp; Relics</div>
            <div class="flex flex-wrap gap-2">
              <?php foreach ($curios as $slot => $item): ?>
              <div class="flex-1 min-w-32">
                <button type="button" data-item-detail="<?= $pi ?>-<?= $slot ?>" class="w-full text-left item-armory-btn">
                  <div class="bg-base-300/50 rounded-lg p-2.5 border border-base-300/50 hover:border-base-content/20 transition-colors cursor-pointer">
                    <div class="flex items-center gap-2">
                      <img src="<?= itemIcon($item['icon']) ?>" alt="" class="size-8 object-contain shrink-0" loading="lazy" onerror="this.src='data:image/svg+xml,<svg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 32 32%22><rect width=%2232%22 height=%2232%22 fill=%22%23333%22 rx=%224%22/><text x=%2216%22 y=%2222%22 text-anchor=%22middle%22 font-size=%2218%22 fill=%22%23777%22>?</text></svg>'">
                      <div class="min-w-0">
                        <div class="text-sm font-semibold truncate" style="color: <?= $item['color'] ?>"><?= htmlspecialchars($item['name']) ?></div>
                        <div class="text-[10px] text-base-content/50"><?= htmlspecialchars($item['stats']['Type'] ?? 'Equippable') ?></div>
                      </div>
                    </div>
                  </div>
                </button>
              </div>
              <?php endforeach; ?>
            </div>
          </div>
          <?php endif; ?>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
  </section>

  <!-- Detail modal -->
  <dialog id="armoryModal" class="modal">
    <div class="modal-box max-w-md" id="armoryModalBox"></div>
    <form method="dialog" class="modal-backdrop"><button>close</button></form>
  </dialog>

  <!-- Hidden detail templates -->
  <?php foreach ($players as $pi => $player):
    foreach ($player['slots'] as $slot => $item):
      $id = "$pi-$slot"; ?>
  <div id="detail-<?= $id ?>" class="hidden">
    <form method="dialog"><button class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2">✕</button></form>
    <div class="flex items-center gap-3 mb-4">
      <img src="<?= itemIcon($item['icon']) ?>" alt="" class="size-12 object-contain" loading="lazy" onerror="this.src='data:image/svg+xml,<svg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 32 32%22><rect width=%2232%22 height=%2232%22 fill=%22%23333%22 rx=%224%22/><text x=%2216%22 y=%2222%22 text-anchor=%22middle%22 font-size=%2218%22 fill=%22%23777%22>?</text></svg>'">
      <div>
        <h3 class="text-lg font-bold" style="color: <?= $item['color'] ?>"><?= htmlspecialchars($item['name']) ?></h3>
        <span class="badge badge-xs font-bold" style="background: <?= $item['color'] ?>20; color: <?= $item['color'] ?>; border-color: <?= $item['color'] ?>40"><?= ucfirst($item['rarity']) ?></span>
      </div>
    </div>
    <?php if ($item['enchants']): ?>
    <div class="mb-3">
      <div class="text-xs font-semibold tracking-wider text-base-content/50 uppercase mb-1.5">Enchantments</div>
      <div class="flex flex-wrap gap-x-1.5">
        <?php $i = 0; $total = count($item['enchants']); foreach ($item['enchants'] as $ename => $elevel): ?>
        <span class="text-sm" style="color: <?= $item['color'] ?>"><?= htmlspecialchars($ename) ?> <?= roman($elevel) ?></span><?php if (++$i < $total): ?><span class="text-base-content/20">·</span><?php endif; ?>
        <?php endforeach; ?>
      </div>
    </div>
    <?php endif; ?>
    <?php if ($item['stats']): ?>
    <div>
      <div class="text-xs font-semibold tracking-wider text-base-content/50 uppercase mb-1.5">Attributes</div>
      <div class="space-y-1">
        <?php foreach ($item['stats'] as $skey => $sval): ?>
        <div class="flex justify-between text-sm">
          <span class="text-base-content/60"><?= htmlspecialchars($skey) ?></span>
          <span class="font-medium"><?= htmlspecialchars($sval) ?></span>
        </div>
        <?php endforeach; ?>
      </div>
    </div>
    <?php endif; ?>
  </div>
  <?php endforeach;
  endforeach; ?>

  <script>
  document.addEventListener('click', function(e) {
    var btn = e.target.closest('.item-armory-btn');
    if (!btn) return;
    var key = btn.getAttribute('data-item-detail');
    var tmpl = document.getElementById('detail-' + key);
    if (tmpl) {
      var box = document.getElementById('armoryModalBox');
      box.innerHTML = tmpl.innerHTML;
      document.getElementById('armoryModal').showModal();
    }
  });
  </script>

<?php require $partialsPath . '/footer.php'; ?>
</body>
</html>

<?php
function itemIcon(string $key): string
{
    static $map = [
        'awakened_supremium_helmet' => 'https://minecraft.wiki/images/Invicon_Netherite_Helmet.png',
        'awakened_supremium_chestplate' => 'https://minecraft.wiki/images/Invicon_Netherite_Chestplate.png',
        'awakened_supremium_leggings' => 'https://minecraft.wiki/images/Invicon_Netherite_Leggings.png',
        'awakened_supremium_boots' => 'https://minecraft.wiki/images/Invicon_Netherite_Boots.png',
        'awakened_supremium_sword' => 'https://minecraft.wiki/images/Invicon_Netherite_Sword.png',
        'solace' => 'https://minecraft.wiki/images/Invicon_Enchanted_Golden_Apple.png',
        'soul_of_the_warden' => 'https://minecraft.wiki/images/Invicon_Sculk.png',
        'magnet_ring' => 'https://minecraft.wiki/images/Invicon_Iron_Ingot.png',
        'cloud_pendant' => 'https://minecraft.wiki/images/Invicon_Feather.png',
        'the_one_ring' => 'https://minecraft.wiki/images/Invicon_Gold_Ingot.png',
        'supremium_helmet' => 'https://minecraft.wiki/images/Invicon_Netherite_Helmet.png',
        'supremium_chestplate' => 'https://minecraft.wiki/images/Invicon_Netherite_Chestplate.png',
        'supremium_leggings' => 'https://minecraft.wiki/images/Invicon_Netherite_Leggings.png',
        'supremium_boots' => 'https://minecraft.wiki/images/Invicon_Netherite_Boots.png',
        'supremium_sword' => 'https://minecraft.wiki/images/Invicon_Netherite_Sword.png',
        'allthemodium_boots' => 'https://minecraft.wiki/images/Invicon_Netherite_Boots.png',
        'netherite_shield' => 'https://minecraft.wiki/images/Invicon_Shield.png',
        'angel_blessing' => 'https://minecraft.wiki/images/Invicon_Totem_of_Undying.png',
        'heart_amulet' => 'https://minecraft.wiki/images/Invicon_Rotten_Flesh.png',
        'hunter_cloak' => 'https://minecraft.wiki/images/Invicon_Leather_Chestplate.png',
        'sojourner_sash' => 'https://minecraft.wiki/images/Invicon_Furnace.png',
        'mekasuit_helmet' => 'https://minecraft.wiki/images/Invicon_Diamond_Helmet.png',
        'mekasuit_chestplate' => 'https://minecraft.wiki/images/Invicon_Diamond_Chestplate.png',
        'mekasuit_leggings' => 'https://minecraft.wiki/images/Invicon_Diamond_Leggings.png',
        'mekasuit_boots' => 'https://minecraft.wiki/images/Invicon_Diamond_Boots.png',
        'meka_tool' => 'https://minecraft.wiki/images/Invicon_Diamond_Pickaxe.png',
        'atomic_disassembler' => 'https://minecraft.wiki/images/Invicon_Iron_Pickaxe.png',
        'infinity_hammer' => 'https://minecraft.wiki/images/Invicon_Anvil.png',
        'bottled_fae' => 'https://minecraft.wiki/images/Invicon_Experience_Bottle.png',
        'ankh_shield' => 'https://minecraft.wiki/images/Invicon_Shield.png',
        'netherite_helmet' => 'https://minecraft.wiki/images/Invicon_Netherite_Helmet.png',
        'netherite_chestplate' => 'https://minecraft.wiki/images/Invicon_Netherite_Chestplate.png',
        'netherite_leggings' => 'https://minecraft.wiki/images/Invicon_Netherite_Leggings.png',
        'netherite_boots' => 'https://minecraft.wiki/images/Invicon_Netherite_Boots.png',
        'netherite_sword' => 'https://minecraft.wiki/images/Invicon_Netherite_Sword.png',
        'shield' => 'https://minecraft.wiki/images/Invicon_Shield.png',
        'glacier_pendant' => 'https://minecraft.wiki/images/Invicon_Ice.png',
        'dragonsteel_chestplate' => 'https://minecraft.wiki/images/Invicon_Netherite_Chestplate.png',
        'vibranium_sword' => 'https://minecraft.wiki/images/Invicon_Netherite_Sword.png',
        'ender_necklace' => 'https://minecraft.wiki/images/Invicon_Ender_Pearl.png',
        'rune_power' => 'https://minecraft.wiki/images/Invicon_Nether_Star.png',
        'rune_defense' => 'https://minecraft.wiki/images/Invicon_Nether_Star.png',
    ];
    return $map[$key] ?? 'data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 32 32"><rect width="32" height="32" fill="%23333" rx="4"/><text x="16" y="22" text-anchor="middle" font-size="18" fill="%23777">?</text></svg>';
}

function roman(int $n): string
{
    $map = [10 => 'X', 9 => 'IX', 5 => 'V', 4 => 'IV', 1 => 'I'];
    $r = '';
    foreach ($map as $v => $l) {
        while ($n >= $v) { $r .= $l; $n -= $v; }
    }
    return $r;
}
