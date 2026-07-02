<?php

function formatTicks(int $ticks): string {
  $seconds = intdiv($ticks, 20);
  $days = intdiv($seconds, 86400);
  $hours = intdiv($seconds % 86400, 3600);
  $minutes = intdiv($seconds % 3600, 60);
  if ($days > 0) return "{$days}d {$hours}h";
  if ($hours > 0) return "{$hours}h {$minutes}m";
  return "{$minutes}m";
}

function formatCm(int $cm): string {
  $meters = $cm / 100;
  if ($meters >= 1000) {
    return number_format($meters / 1000, 1) . ' km';
  }
  return number_format($meters, 0) . ' m';
}
