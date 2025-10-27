document.addEventListener('click', (e)=>{
  const btn = e.target.closest('[data-toggle="password"]');
  if(!btn) return;
  const input = document.querySelector(btn.getAttribute('data-target'));
  if(!input) return;
  if(input.type === 'password'){ input.type = 'text'; btn.querySelector('i')?.classList.replace('bi-eye-slash','bi-eye');}
  else{ input.type = 'password'; btn.querySelector('i')?.classList.replace('bi-eye','bi-eye-slash');}
});
