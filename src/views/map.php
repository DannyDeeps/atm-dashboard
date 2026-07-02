<!DOCTYPE html>
<html lang="en" data-theme="dark">
<?php $headTitle = $headTitle ?? 'Map - Glip Glops ATM10'; require $partialsPath . '/head.php'; ?>
<body class="overflow-hidden">
  <?php require $partialsPath . '/nav.php'; ?>

  <div style="height: 100vh;">
    <iframe src="<?= htmlspecialchars($config['webmap_url']) ?>" class="w-full h-full border-0" allowfullscreen loading="lazy"></iframe>
  </div>
<?php require $partialsPath . '/footer.php'; ?>
