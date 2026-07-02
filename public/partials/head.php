<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= htmlspecialchars($headTitle) ?></title>
  <link rel="icon" href="/favicon.ico" sizes="32x32">
  <link href="https://cdn.jsdelivr.net/npm/daisyui@5" rel="stylesheet" type="text/css" />
  <link href="https://cdn.jsdelivr.net/npm/daisyui@5/themes.css" rel="stylesheet" type="text/css" />
  <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4">
  </script>
  <script src="https://unpkg.com/htmx.org@2.0.3"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
  <style>
    @font-face { font-family: 'Lato'; src: url('/fonts/Lato-300.woff2') format('woff2'); font-weight: 300; font-style: normal; font-display: swap; }
    @font-face { font-family: 'Lato'; src: url('/fonts/Lato-400.woff2') format('woff2'); font-weight: 400; font-style: normal; font-display: swap; }
    @font-face { font-family: 'Lato'; src: url('/fonts/Lato-700.woff2') format('woff2'); font-weight: 700; font-style: normal; font-display: swap; }
    @font-face { font-family: 'Lato'; src: url('/fonts/Lato-900.woff2') format('woff2'); font-weight: 900; font-style: normal; font-display: swap; }
    @font-face { font-family: 'Minecrafter'; src: url('/fonts/Minecrafter.Reg.ttf') format('truetype'); font-weight: normal; font-style: normal; }
    @font-face { font-family: 'Minecrafter'; src: url('/fonts/Minecrafter.Alt.ttf') format('truetype'); font-weight: bold; font-style: normal; }
    html { font-family: 'Lato', sans-serif; }
    [data-theme="dark"] {
      --color-primary: oklch(0.87 0.31 140);
      --color-primary-content: oklch(0 0 0);
    }
    .font-minecrafter { font-family: 'Minecrafter', sans-serif; letter-spacing: 0.02em; }
    .tooltip[data-tip]:before { z-index: 9999 !important; }
    .hero-content h1, .hero-content p { text-shadow: 0 2px 12px rgba(0,0,0,0.7); }
    .step-icon:has(img) { background: none !important; border: none !important; }
    .steps-vertical .step { min-height: 3rem; }
    .steps-vertical .step::before { background: oklch(0.4 0 0) !important; }
  </style>
</head>
