
// Sidebar state (remember collapsed if you want later)
// Live clock (America/Lima)
(function() {
  function tick() {
    const el = document.getElementById('clock-now');
    if (!el) return;
    const now = new Date();
    const fmt = new Intl.DateTimeFormat('es-PE', {
      dateStyle: 'medium',
      timeStyle: 'medium'
    });
    el.textContent = fmt.format(now);
  }
  setInterval(tick, 1000);
  tick();
})();
