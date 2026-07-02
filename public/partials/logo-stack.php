<style>
.logo-stack {
  position: relative;
  width: 100%;
  max-width: 406px;
  aspect-ratio: 1 / 1;
  margin: 0 auto;
}
.logo-stack img {
  position: absolute;
  inset: 0;
  width: 100%;
  height: 100%;
  image-rendering: auto;
}
.logo-stack .layer-bg {
  animation: logoPulse 4s ease-in-out infinite;
}
.logo-stack .layer-glops {
  animation: logoFloat 3s ease-in-out infinite;
}
.logo-stack .layer-glip {
  animation: logoFloat 3s ease-in-out 0.3s infinite;
}
.logo-stack .layer-star1 {
  animation: logoWobble 4s ease-in-out infinite;
}
.logo-stack .layer-star2 {
  animation: logoWobble 4s ease-in-out 2s infinite;
}
@keyframes logoWobble {
  0%, 100% { transform: translate(0, 0) rotate(0deg); }
  25% { transform: translate(2px, -3px) rotate(2deg); }
  50% { transform: translate(-1px, -5px) rotate(-1deg); }
  75% { transform: translate(3px, -2px) rotate(1deg); }
}
@keyframes logoPulse {
  0%, 100% { transform: scale(1); opacity: 0.9; }
  50% { transform: scale(1.04); opacity: 1; }
}
@keyframes logoFloat {
  0%, 100% { transform: translateY(0); }
  50% { transform: translateY(-6px); }
}
</style>
<div class="logo-stack">
  <img src="/images/logo-bg.webp" alt="" class="layer-bg" loading="eager">
  <img src="/images/logo-glops.webp" alt="" class="layer-glops" loading="eager">
  <img src="/images/logo-glip.webp" alt="" class="layer-glip" loading="eager">
  <img src="/images/logo-star-1.webp" alt="" class="layer-star1" loading="eager">
  <img src="/images/logo-star-2.webp" alt="" class="layer-star2" loading="eager">
</div>
