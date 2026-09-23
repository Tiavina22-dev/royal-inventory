document.addEventListener('DOMContentLoaded', function () {
  const sidebar = document.querySelector('[data-sidebar]');
  const toggle = document.querySelector('[data-toggle-sidebar]');
  const closers = document.querySelectorAll('[data-close-sidebar]');
  const backdrop = document.querySelector('.drawer-backdrop');

  function closeSidebar() {
    if (sidebar) sidebar.classList.remove('open');
    if (backdrop) backdrop.classList.remove('open');
  }

  function openSidebar() {
    if (sidebar) sidebar.classList.add('open');
    if (backdrop) backdrop.classList.add('open');
  }

  if (toggle && sidebar) {
    toggle.addEventListener('click', function () {
      if (sidebar.classList.contains('open')) {
        closeSidebar();
      } else {
        openSidebar();
      }
    });
  }

  closers.forEach(function (closer) {
    closer.addEventListener('click', closeSidebar);
  });

  if (sidebar) {
    sidebar.querySelectorAll('a').forEach(function (link) {
      link.addEventListener('click', closeSidebar);
    });
  }

  document.addEventListener('click', function (event) {
    if (!sidebar || !sidebar.classList.contains('open')) return;
    if (sidebar.contains(event.target) || (toggle && toggle.contains(event.target))) return;
    closeSidebar();
  });
});
