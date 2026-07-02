<!DOCTYPE html>
<html lang="en" data-theme="dark">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>DaisyUI Component Library</title>
  <link href="https://cdn.jsdelivr.net/npm/daisyui@5" rel="stylesheet" type="text/css" />
  <link href="https://cdn.jsdelivr.net/npm/daisyui@5/themes.css" rel="stylesheet" type="text/css" />
  <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
  <style>
    [data-theme="dark"] {
      --color-primary: oklch(0.87 0.31 140);
      --color-primary-content: oklch(0 0 0);
      --radius-box: 0;
      --radius-field: 0;
      --radius-selector: 0;
    }
  </style>
</head>
<body class="p-8">
  <div class="container mx-auto">
    <h1 class="text-3xl font-bold mb-2">DaisyUI Component Library</h1>
    <p class="text-base-content/60 mb-8">Dracula theme, square corners (as configured)</p>

    <!-- ============ BUTTONS ============ -->
    <section class="mb-12">
      <h2 class="text-xl font-bold mb-4 border-b border-base-300 pb-2">Buttons</h2>
      <div class="flex flex-wrap gap-2 mb-4">
        <button class="btn">Default</button>
        <button class="btn btn-primary">Primary</button>
        <button class="btn btn-secondary">Secondary</button>
        <button class="btn btn-accent">Accent</button>
        <button class="btn btn-info">Info</button>
        <button class="btn btn-success">Success</button>
        <button class="btn btn-warning">Warning</button>
        <button class="btn btn-error">Error</button>
        <button class="btn btn-ghost">Ghost</button>
        <button class="btn btn-neutral">Neutral</button>
      </div>
      <div class="flex flex-wrap gap-2">
        <button class="btn btn-outline">Outline</button>
        <button class="btn btn-primary btn-outline">Primary Outline</button>
        <button class="btn btn-primary btn-sm">Small</button>
        <button class="btn btn-primary btn-xs">XS</button>
        <button class="btn btn-primary btn-lg">Large</button>
        <button class="btn btn-primary btn-wide">Wide</button>
        <button class="btn btn-primary btn-square">Sq</button>
        <button class="btn btn-primary btn-circle">O</button>
        <button class="btn" disabled>Disabled</button>
      </div>
    </section>

    <!-- ============ BADGES ============ -->
    <section class="mb-12">
      <h2 class="text-xl font-bold mb-4 border-b border-base-300 pb-2">Badges</h2>
      <div class="flex flex-wrap gap-2 mb-4 items-center">
        <span class="badge">Default</span>
        <span class="badge badge-primary">Primary</span>
        <span class="badge badge-secondary">Secondary</span>
        <span class="badge badge-accent">Accent</span>
        <span class="badge badge-info">Info</span>
        <span class="badge badge-success">Success</span>
        <span class="badge badge-warning">Warning</span>
        <span class="badge badge-error">Error</span>
        <span class="badge badge-neutral">Neutral</span>
        <span class="badge badge-ghost">Ghost</span>
      </div>
      <div class="flex flex-wrap gap-2 items-center">
        <span class="badge badge-outline">Outline</span>
        <span class="badge badge-primary badge-outline">Primary Outline</span>
        <span class="badge badge-xs">XS</span>
        <span class="badge badge-sm">SM</span>
        <span class="badge badge-md">MD</span>
        <span class="badge badge-lg">LG</span>
        <span class="badge" style="border-left: 3px solid #cba6f7;">Colored border</span>
        <span class="badge" style="border-left: 3px solid #a6e3a1;">Green border</span>
      </div>
    </section>

    <!-- ============ CARDS ============ -->
    <section class="mb-12">
      <h2 class="text-xl font-bold mb-4 border-b border-base-300 pb-2">Cards</h2>
      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
        <div class="card bg-base-200 border border-base-300">
          <div class="card-body">
            <h3 class="card-title">Basic Card</h3>
            <p class="text-sm text-base-content/60">Plain card with body.</p>
            <div class="card-actions"><button class="btn btn-primary btn-sm">Action</button></div>
          </div>
        </div>
        <div class="card bg-base-200 border border-base-300">
          <div class="card-body">
            <h3 class="card-title">
              With Badge
              <span class="badge badge-primary badge-sm">NEW</span>
            </h3>
            <p class="text-sm text-base-content/60">Card with a badge in the title.</p>
          </div>
        </div>
        <div class="card bg-base-200 border-t-4 border-base-200" style="border-top-color: #89b4fa;">
          <div class="card-body">
            <h3 class="card-title">Colored Top Border</h3>
            <p class="text-sm text-base-content/60">Like we use for world tiers.</p>
          </div>
        </div>
        <div class="card bg-base-200 border border-base-300">
          <div class="card-body">
            <div class="flex items-center gap-3">
              <div class="w-10 h-10 rounded-none bg-primary/20 flex items-center justify-center text-primary font-bold">A</div>
              <div>
                <div class="font-bold">Avatar + Text</div>
                <span class="badge badge-xs" style="border-left: 3px solid #f38ba8;">Award</span>
              </div>
            </div>
          </div>
        </div>
        <div class="card bg-base-200 border border-base-300">
          <div class="card-body">
            <h3 class="card-title flex items-center gap-2 justify-between">
              Stats Grid
              <span class="tooltip badge badge-xs" data-tip="Tooltip">?</span>
            </h3>
            <div class="grid grid-cols-2 gap-2 text-sm">
              <div><span class="text-base-content/40 text-xs">Stat 1</span><div class="font-semibold">123</div></div>
              <div><span class="text-base-content/40 text-xs">Stat 2</span><div class="font-semibold">456</div></div>
              <div><span class="text-base-content/40 text-xs">Stat 3</span><div class="font-semibold">789</div></div>
              <div><span class="text-base-content/40 text-xs">Stat 4</span><div class="font-semibold">0</div></div>
            </div>
          </div>
        </div>
        <div class="card bg-base-200 border border-base-300 cursor-pointer hover:border-primary hover:top-[-0.125rem] transition-all duration-200 relative">
          <div class="card-body">
            <h3 class="card-title">Hover Card</h3>
            <p class="text-sm text-base-content/60">Lifts on hover with primary border.</p>
          </div>
        </div>
      </div>
    </section>

    <!-- ============ STATS ============ -->
    <section class="mb-12">
      <h2 class="text-xl font-bold mb-4 border-b border-base-300 pb-2">Stats</h2>
      <div class="stats shadow mb-4">
        <div class="stat">
          <div class="stat-title">Total Players</div>
          <div class="stat-value">12</div>
          <div class="stat-desc">Active this week</div>
        </div>
        <div class="stat">
          <div class="stat-title">Playtime</div>
          <div class="stat-value">45d 6h</div>
          <div class="stat-desc text-primary">Most played</div>
        </div>
        <div class="stat">
          <div class="stat-title">Quests</div>
          <div class="stat-value">87%</div>
          <div class="stat-desc">Average completion</div>
        </div>
      </div>
      <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
        <div class="stat bg-base-200 p-4">
          <div class="stat-title text-xs">Small Stat</div>
          <div class="stat-value text-lg">1,234</div>
        </div>
        <div class="stat bg-base-200 p-4">
          <div class="stat-title text-xs">Small Stat</div>
          <div class="stat-value text-lg text-primary">5,678</div>
        </div>
        <div class="stat bg-base-200 p-4">
          <div class="stat-title text-xs">Small Stat</div>
          <div class="stat-value text-lg text-secondary">9,012</div>
        </div>
        <div class="stat bg-base-200 p-4">
          <div class="stat-title text-xs">Small Stat</div>
          <div class="stat-value text-lg text-accent">345</div>
        </div>
      </div>
    </section>

    <!-- ============ PROGRESS ============ -->
    <section class="mb-12">
      <h2 class="text-xl font-bold mb-4 border-b border-base-300 pb-2">Progress Bars</h2>
      <div class="space-y-2 max-w-md">
        <div class="flex justify-between text-xs mb-1"><span>Primary</span><span>75%</span></div>
        <progress class="progress progress-primary w-full" value="75" max="100"></progress>
        <div class="flex justify-between text-xs mb-1"><span>Secondary</span><span>50%</span></div>
        <progress class="progress progress-secondary w-full" value="50" max="100"></progress>
        <div class="flex justify-between text-xs mb-1"><span>Accent</span><span>25%</span></div>
        <progress class="progress progress-accent w-full" value="25" max="100"></progress>
        <div class="flex justify-between text-xs mb-1"><span>Success</span><span>100%</span></div>
        <progress class="progress progress-success w-full" value="100" max="100"></progress>
        <div class="flex justify-between text-xs mb-1"><span>Warning</span><span>60%</span></div>
        <progress class="progress progress-warning w-full" value="60" max="100"></progress>
        <div class="flex justify-between text-xs mb-1"><span>Error</span><span>30%</span></div>
        <progress class="progress progress-error w-full" value="30" max="100"></progress>
      </div>
    </section>

    <!-- ============ TABLES ============ -->
    <section class="mb-12">
      <h2 class="text-xl font-bold mb-4 border-b border-base-300 pb-2">Tables</h2>
      <div class="overflow-x-auto">
        <table class="table table-zebra">
          <thead>
            <tr><th>Player</th><th>Playtime</th><th>Deaths</th><th>Progress</th></tr>
          </thead>
          <tbody>
            <tr>
              <td><a class="link link-hover font-semibold">PlayerOne</a></td>
              <td>12d 4h</td>
              <td>23</td>
              <td><progress class="progress progress-primary w-32" value="80" max="100"></progress></td>
            </tr>
            <tr>
              <td><a class="link link-hover">PlayerTwo</a></td>
              <td>8d 2h</td>
              <td>47</td>
              <td><progress class="progress progress-primary w-32" value="45" max="100"></progress></td>
            </tr>
            <tr>
              <td><a class="link link-hover">PlayerThree</a></td>
              <td>3d 15h</td>
              <td>12</td>
              <td><progress class="progress progress-primary w-32" value="20" max="100"></progress></td>
            </tr>
          </tbody>
        </table>
      </div>

      <div class="overflow-x-auto mt-4">
        <table class="table table-pin-rows">
          <thead>
            <tr><th>Pinned Header</th><th>Scrolls with table</th></tr>
          </thead>
          <tbody>
            <tr><td>Row 1</td><td>Data</td></tr>
            <tr><td>Row 2</td><td>Data</td></tr>
          </tbody>
        </table>
      </div>
    </section>

    <!-- ============ ALERTS ============ -->
    <section class="mb-12">
      <h2 class="text-xl font-bold mb-4 border-b border-base-300 pb-2">Alerts</h2>
      <div class="space-y-2">
        <div class="alert">
          <span>Default alert with some info.</span>
        </div>
        <div class="alert alert-info">
          <span>Info alert with some info.</span>
        </div>
        <div class="alert alert-success">
          <span>Success alert message.</span>
        </div>
        <div class="alert alert-warning">
          <span>Warning alert message.</span>
        </div>
        <div class="alert alert-error">
          <span>Error alert message.</span>
        </div>
      </div>
    </section>

    <!-- ============ TOAST / NOTIFICATION ============ -->
    <section class="mb-12">
      <h2 class="text-xl font-bold mb-4 border-b border-base-300 pb-2">Toast / Notifications</h2>
      <div class="space-y-2">
        <div class="alert shadow-lg bg-base-200">
          <div>
            <span class="text-xl">&#9733;</span>
            <div><span class="font-bold">Notification</span><span class="text-xs block text-base-content/40">2 min ago</span></div>
          </div>
        </div>
        <div class="alert shadow-lg bg-base-200 border-l-4" style="border-left-color: #a6e3a1;">
          <div>
            <span class="font-bold">Colored left border</span>
            <span class="text-xs block text-base-content/40">Like our awards</span>
          </div>
        </div>
      </div>
    </section>

    <!-- ============ TOOLTIPS ============ -->
    <section class="mb-12">
      <h2 class="text-xl font-bold mb-4 border-b border-base-300 pb-2">Tooltips</h2>
      <div class="flex flex-wrap gap-4 items-center">
        <span class="tooltip badge badge-primary" data-tip="Tooltip on top">Top</span>
        <span class="tooltip tooltip-bottom badge badge-primary" data-tip="Tooltip on bottom">Bottom</span>
        <span class="tooltip tooltip-left badge badge-primary" data-tip="Tooltip on left">Left</span>
        <span class="tooltip tooltip-right badge badge-primary" data-tip="Tooltip on right">Right</span>
        <span class="tooltip tooltip-primary badge" data-tip="Primary tooltip">Primary</span>
        <span class="tooltip tooltip-secondary badge" data-tip="Secondary">Secondary</span>
        <span class="tooltip tooltip-accent badge" data-tip="Accent">Accent</span>
        <span class="tooltip tooltip-info badge" data-tip="Info">Info</span>
        <span class="tooltip tooltip-success badge" data-tip="Success">Success</span>
        <span class="tooltip tooltip-warning badge" data-tip="Warning">Warning</span>
        <span class="tooltip tooltip-error badge" data-tip="Error">Error</span>
        <button class="btn btn-sm tooltip" data-tip="Button tooltip">Hover me</button>
      </div>
    </section>

    <!-- ============ MODALS ============ -->
    <section class="mb-12">
      <h2 class="text-xl font-bold mb-4 border-b border-base-300 pb-2">Modals</h2>
      <div class="flex flex-wrap gap-2">
        <a href="#modal-basic" class="btn btn-primary">Open Modal</a>
        <a href="#modal-form" class="btn btn-secondary">Form Modal</a>
      </div>
      <dialog id="modal-basic" class="modal">
        <div class="modal-box">
          <h3 class="font-bold text-lg">Basic Modal</h3>
          <p class="py-4 text-base-content/60">Press ESC or click outside to close.</p>
          <div class="modal-action"><a href="#" class="btn btn-sm">Close</a></div>
        </div>
      </dialog>
      <dialog id="modal-form" class="modal">
        <div class="modal-box">
          <h3 class="font-bold text-lg">Form Modal</h3>
          <div class="py-4 space-y-2">
            <input type="text" placeholder="Name" class="input w-full">
            <input type="text" placeholder="Value" class="input w-full">
          </div>
          <div class="modal-action">
            <a href="#" class="btn btn-sm">Cancel</a>
            <a href="#" class="btn btn-sm btn-primary">Save</a>
          </div>
        </div>
      </dialog>
    </section>

    <!-- ============ COLLAPSE ============ -->
    <section class="mb-12">
      <h2 class="text-xl font-bold mb-4 border-b border-base-300 pb-2">Collapse</h2>
      <div class="max-w-md space-y-2">
        <div class="collapse collapse-arrow bg-base-200">
          <input type="checkbox">
          <div class="collapse-title font-semibold">Collapsible Section</div>
          <div class="collapse-content text-sm text-base-content/60">Content hidden until opened. Can be toggled.</div>
        </div>
        <div class="collapse collapse-arrow bg-base-200">
          <input type="checkbox" checked>
          <div class="collapse-title font-semibold text-primary">Open by Default</div>
          <div class="collapse-content text-sm text-base-content/60">This one starts open.</div>
        </div>
        <div class="collapse collapse-plus bg-base-200">
          <input type="checkbox">
          <div class="collapse-title font-semibold">Plus/Minus Style</div>
          <div class="collapse-content text-sm text-base-content/60">Uses + icon instead of arrow.</div>
        </div>
      </div>
    </section>

    <!-- ============ TABS ============ -->
    <section class="mb-12">
      <h2 class="text-xl font-bold mb-4 border-b border-base-300 pb-2">Tabs</h2>
        <div role="tablist" class="tabs tabs-border mb-4">
        <input type="radio" name="tab-demo" role="tab" class="tab" aria-label="Tab 1" checked>
        <div role="tabpanel" class="tab-content p-4 text-sm text-base-content/60">Content for tab 1.</div>
        <input type="radio" name="tab-demo" role="tab" class="tab" aria-label="Tab 2">
        <div role="tabpanel" class="tab-content p-4 text-sm text-base-content/60">Content for tab 2.</div>
        <input type="radio" name="tab-demo" role="tab" class="tab" aria-label="Tab 3">
        <div role="tabpanel" class="tab-content p-4 text-sm text-base-content/60">Content for tab 3.</div>
      </div>
        <div role="tablist" class="tabs tabs-box">
        <a role="tab" class="tab tab-active">Active</a>
        <a role="tab" class="tab">Inactive</a>
        <a role="tab" class="tab">Inactive</a>
      </div>
    </section>

    <!-- ============ JOIN (Button Groups) ============ -->
    <section class="mb-12">
      <h2 class="text-xl font-bold mb-4 border-b border-base-300 pb-2">Join / Button Groups</h2>
      <div class="join">
        <button class="btn btn-primary join-item">Left</button>
        <button class="btn btn-primary join-item">Center</button>
        <button class="btn btn-primary join-item">Right</button>
      </div>
    </section>

    <!-- ============ AVATAR ============ -->
    <section class="mb-12">
      <h2 class="text-xl font-bold mb-4 border-b border-base-300 pb-2">Avatars</h2>
      <div class="flex flex-wrap gap-4 items-end">
        <div class="avatar">
          <div class="w-8 h-8 bg-primary/20 flex items-center justify-center text-primary font-bold text-xs">A</div>
        </div>
        <div class="avatar">
          <div class="w-10 h-10 bg-secondary/20 flex items-center justify-center text-secondary font-bold">B</div>
        </div>
        <div class="avatar">
          <div class="w-12 h-12 bg-accent/20 flex items-center justify-center text-accent font-bold">C</div>
        </div>
        <div class="avatar avatar-online">
          <div class="w-10 h-10 bg-success/20 flex items-center justify-center text-success font-bold">D</div>
        </div>
        <div class="avatar avatar-offline">
          <div class="w-10 h-10 bg-base-300 flex items-center justify-center font-bold">E</div>
        </div>
      </div>
    </section>

    <!-- ============ INDICATORS ============ -->
    <section class="mb-12">
      <h2 class="text-xl font-bold mb-4 border-b border-base-300 pb-2">Indicators (Positioned Badges)</h2>
      <div class="flex gap-8">
        <div class="indicator">
          <span class="indicator-item badge badge-primary badge-xs">!</span>
          <div class="w-16 h-16 bg-base-200 border border-base-300 flex items-center justify-center text-sm">Box</div>
        </div>
        <div class="indicator">
          <span class="indicator-item indicator-start badge badge-secondary badge-sm">New</span>
          <div class="w-16 h-16 bg-base-200 border border-base-300 flex items-center justify-center text-sm">Box</div>
        </div>
        <div class="indicator">
          <span class="indicator-item indicator-end indicator-bottom badge badge-accent badge-xs">
          </span>
          <div class="w-16 h-16 bg-base-200 border border-base-300 flex items-center justify-center text-sm">Box</div>
        </div>
      </div>
    </section>

    <!-- ============ KBD ============ -->
    <section class="mb-12">
      <h2 class="text-xl font-bold mb-4 border-b border-base-300 pb-2">KBD (Keyboard)</h2>
      <div class="flex gap-2">
        <kbd class="kbd">Ctrl</kbd>
        <kbd class="kbd">+</kbd>
        <kbd class="kbd">C</kbd>
      </div>
    </section>

    <!-- ============ LOADING ============ -->
    <section class="mb-12">
      <h2 class="text-xl font-bold mb-4 border-b border-base-300 pb-2">Loading</h2>
      <div class="flex flex-wrap gap-4 items-center">
        <span class="loading loading-spinner loading-xs"></span>
        <span class="loading loading-spinner loading-sm"></span>
        <span class="loading loading-spinner loading-md"></span>
        <span class="loading loading-spinner loading-lg"></span>
        <span class="loading loading-dots loading-md"></span>
        <span class="loading loading-ring loading-md"></span>
        <span class="loading loading-ball loading-md"></span>
        <span class="loading loading-bars loading-md"></span>
        <span class="loading loading-infinity loading-md"></span>
        <button class="btn btn-primary btn-sm"><span class="loading loading-spinner loading-xs"></span> Loading</button>
      </div>
    </section>

    <!-- ============ DIFF ============ -->
    <section class="mb-12">
      <h2 class="text-xl font-bold mb-4 border-b border-base-300 pb-2">Diff (Side-by-side)</h2>
      <div class="diff aspect-[16/9] max-w-md">
        <div class="diff-item-1 bg-base-200 flex items-center justify-center font-bold text-lg">Before</div>
        <div class="diff-item-2 bg-primary/20 flex items-center justify-center font-bold text-lg">After</div>
        <div class="diff-resizer"></div>
      </div>
    </section>

    <!-- ============ CAROUSEL ============ -->
    <section class="mb-12">
      <h2 class="text-xl font-bold mb-4 border-b border-base-300 pb-2">Carousel</h2>
      <div class="carousel radius-box max-w-md">
        <div class="carousel-item w-48"><div class="bg-base-200 p-8 border border-base-300">Slide 1</div></div>
        <div class="carousel-item w-48"><div class="bg-base-200 p-8 border border-base-300">Slide 2</div></div>
        <div class="carousel-item w-48"><div class="bg-base-200 p-8 border border-base-300">Slide 3</div></div>
      </div>
    </section>

    <!-- ============ COUNTDOWN ============ -->
    <section class="mb-12">
      <h2 class="text-xl font-bold mb-4 border-b border-base-300 pb-2">Countdown</h2>
      <div class="grid grid-flow-col gap-5 text-center auto-cols-max">
        <div class="flex flex-col"><span class="countdown font-mono text-2xl"><span style="--value:15;"></span></span>days</div>
        <div class="flex flex-col"><span class="countdown font-mono text-2xl"><span style="--value:10;"></span></span>hours</div>
        <div class="flex flex-col"><span class="countdown font-mono text-2xl"><span style="--value:45;"></span></span>min</div>
        <div class="flex flex-col"><span class="countdown font-mono text-2xl"><span style="--value:23;"></span></span>sec</div>
      </div>
    </section>

    <!-- ============ DRAWER (Sidebar) ============ -->
    <section class="mb-12">
      <h2 class="text-xl font-bold mb-4 border-b border-base-300 pb-2">Drawer (Sidebar)</h2>
      <div class="drawer">
        <input id="drawer-demo" type="checkbox" class="drawer-toggle">
        <div class="drawer-content">
          <label for="drawer-demo" class="btn btn-primary btn-sm">Open Drawer</label>
        </div>
        <div class="drawer-side z-50">
          <label for="drawer-demo" class="drawer-overlay"></label>
          <ul class="menu p-4 w-60 min-h-full bg-base-200 border-r border-base-300">
            <li><a>Nav Item 1</a></li>
            <li><a>Nav Item 2</a></li>
            <li><a>Nav Item 3</a></li>
          </ul>
        </div>
      </div>
    </section>

    <!-- ============ DROPDOWN ============ -->
    <section class="mb-12">
      <h2 class="text-xl font-bold mb-4 border-b border-base-300 pb-2">Dropdown</h2>
      <div class="dropdown">
        <div tabindex="0" role="button" class="btn btn-primary btn-sm">Dropdown</div>
        <ul tabindex="0" class="dropdown-content menu bg-base-200 radius-box z-50 w-40 p-2 shadow border border-base-300">
          <li><a>Item 1</a></li>
          <li><a>Item 2</a></li>
          <li><a>Item 3</a></li>
        </ul>
      </div>
    </section>

    <!-- ============ MASK (Clip shapes) ============ -->
    <section class="mb-12">
      <h2 class="text-xl font-bold mb-4 border-b border-base-300 pb-2">Mask (Clip Path)</h2>
      <div class="flex gap-4">
        <div class="mask mask-squircle w-16 h-16 bg-primary/20 flex items-center justify-center text-primary font-bold">S</div>
        <div class="mask mask-heart w-16 h-16 bg-secondary/20 flex items-center justify-center text-secondary font-bold">H</div>
        <div class="mask mask-circle w-16 h-16 bg-accent/20 flex items-center justify-center text-accent font-bold">C</div>
        <div class="mask mask-star w-16 h-16 bg-warning/20 flex items-center justify-center text-warning font-bold">S</div>
      </div>
    </section>

    <!-- ============ TIMELINE ============ -->
    <section class="mb-12">
      <h2 class="text-xl font-bold mb-4 border-b border-base-300 pb-2">Timeline</h2>
      <ul class="timeline timeline-vertical">
        <li><div class="timeline-start text-xs text-base-content/40">01:00</div><div class="timeline-middle">&#9679;</div><div class="timeline-end timeline-box bg-base-200 text-sm">First event</div><hr/></li>
        <li><hr/><div class="timeline-start text-xs text-base-content/40">02:00</div><div class="timeline-middle">&#9679;</div><div class="timeline-end timeline-box bg-base-200 text-sm">Second event</div><hr/></li>
        <li><hr/><div class="timeline-start text-xs text-base-content/40">03:00</div><div class="timeline-middle">&#9679;</div><div class="timeline-end timeline-box bg-base-200 text-sm">Third event</div></li>
      </ul>
    </section>

    <!-- ============ INPUTS ============ -->
    <section class="mb-12">
      <h2 class="text-xl font-bold mb-4 border-b border-base-300 pb-2">Form Inputs</h2>
      <div class="flex flex-col gap-3 max-w-sm">
        <input type="text" placeholder="Text input" class="input w-full">
        <textarea class="textarea" placeholder="Textarea"></textarea>
        <select class="select w-full">
          <option disabled selected>Select</option>
          <option>Option 1</option>
          <option>Option 2</option>
        </select>
        <input type="checkbox" class="checkbox checkbox-primary" checked> <span class="text-sm">Checkbox</span>
        <input type="radio" name="radio-demo" class="radio radio-primary" checked> <span class="text-sm">Radio 1</span>
        <input type="radio" name="radio-demo" class="radio radio-primary"> <span class="text-sm">Radio 2</span>
        <div class="flex gap-2 items-center">
          <input type="range" min="0" max="100" value="40" class="range range-primary range-sm flex-1">
          <span class="text-sm w-10">40%</span>
        </div>
        <div class="toggle toggle-primary" tabindex="0" role="switch" aria-checked="true"></div>
      </div>
    </section>

    <!-- ============ FOOTER ============ -->
    <section class="mb-12">
      <h2 class="text-xl font-bold mb-4 border-b border-base-300 pb-2">Footer</h2>
      <footer class="footer bg-base-200 p-4 border border-base-300">
        <nav class="flex gap-4 text-sm">
          <a class="link link-hover">Home</a>
          <a class="link link-hover">Players</a>
          <a class="link link-hover">About</a>
        </nav>
      </footer>
    </section>

    <p class="text-center text-base-content/40 text-sm mt-16 mb-8">DaisyUI v5 — Dracula theme</p>
  </div>
</body>
</html>
