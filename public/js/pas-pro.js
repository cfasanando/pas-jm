
(function(){
  const el = document.getElementById('clock-now');
  function tick(){
    if(!el) return;
    const opts = { dateStyle:'medium', timeStyle:'medium'};
    el.textContent = new Intl.DateTimeFormat('es-PE', opts).format(new Date());
  }
  tick(); setInterval(tick, 1000);
})();
