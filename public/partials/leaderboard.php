<div>
  <div class="px-3 py-2 leading-tight flex items-center justify-center gap-2">
    <img src="https://minecraft.wiki/images/<?= $lb['icon'] ?>" alt="" class="size-8 object-contain" loading="lazy">
    <div>
      <span class="text-lg font-bold text-[<?= $lb['color'] ?>]"><?= htmlspecialchars($lb['title']) ?></span><br>
      <span class="text-xs text-base-content/60"><?= htmlspecialchars($lb['description']) ?></span>
    </div>
  </div>
  <ul class="steps steps-vertical px-3 py-2 overflow-y-visible">
    <?php foreach ($lb['rows'] as $row): ?>
    <li class="step">
      <div class="step-icon overflow-hidden">
        <img src="/head-cache.php?uuid=<?= htmlspecialchars($row['uuid']) ?>" alt="" class="w-full h-full object-cover" loading="lazy">
      </div>
      <div class="flex items-center gap-2 w-full py-0.5">
        <a href="/player/<?= htmlspecialchars($row['uuid']) ?>" class="link link-hover text-base-content truncate"><?= htmlspecialchars($row['name']) ?></a>
        <span class="ml-auto font-semibold text-primary"><?= $row['value'] ?></span>
      </div>
    </li>
    <?php endforeach; ?>
  </ul>
</div>
