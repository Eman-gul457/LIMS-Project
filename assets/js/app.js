document.addEventListener('DOMContentLoaded', () => {
  const alerts = document.querySelectorAll('.alert.success');
  alerts.forEach((el) => {
    setTimeout(() => {
      el.style.transition = 'opacity .35s';
      el.style.opacity = '0';
      setTimeout(() => el.remove(), 400);
    }, 2800);
  });
});
