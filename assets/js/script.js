


/* Alternância de tema light/dark com persistência em localStorage (hs_theme) */
(function () {
  function getPreferredTheme() {
    try {
      var stored = localStorage.getItem('hs_theme');
      if (stored === 'light' || stored === 'dark') return stored;
    } catch (e) {}
    return window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';
  }

  function show(el) {
    if (!el) return;
    el.classList.remove('hidden');
    el.classList.add('block');
  }

  function hide(el) {
    if (!el) return;
    el.classList.add('hidden');
    el.classList.remove('block');
  }

  function syncToggleButtons(theme) {
    var btnToDark = document.querySelector('[data-hs-theme-click-value="dark"]');
    var btnToLight = document.querySelector('[data-hs-theme-click-value="light"]');
    if (theme === 'dark') {
      hide(btnToDark);
      show(btnToLight);
    } else {
      show(btnToDark);
      hide(btnToLight);
    }
  }

  function applyTheme(theme) {
    var html = document.documentElement;
    html.classList.remove('light', 'dark');
    html.classList.add(theme);
    try { localStorage.setItem('hs_theme', theme); } catch (e) {}
    syncToggleButtons(theme);
  }

  function setupListeners() {
    var btnToDark = document.querySelector('[data-hs-theme-click-value="dark"]');
    var btnToLight = document.querySelector('[data-hs-theme-click-value="light"]');
    if (btnToDark) btnToDark.addEventListener('click', function () { applyTheme('dark'); });
    if (btnToLight) btnToLight.addEventListener('click', function () { applyTheme('light'); });

    // Menu mobile
    var openBtn = document.getElementById('openMenu');
    var closeBtn = document.getElementById('closeMenu');
    var menu = document.getElementById('mobileMenu');

        // ----> ADICIONE ESTAS LINHAS PARA DEBUGAR <----
    console.log(">>>> Script está tentando encontrar os elementos do menu <<<<");
    console.log("Botão de abrir (procurando por id='openMenu'):", openBtn);
    console.log("Botão de fechar (procurando por id='closeMenu'):", closeBtn);
    console.log("Div do menu (procurando por id='mobileMenu'):", menu);
    // ----------------------------------------------

    function openMenu() {
      if (!menu) return;
      menu.classList.remove('translate-x-full');
      menu.classList.add('translate-x-0');
      menu.setAttribute('aria-hidden', 'false');
      if (openBtn) openBtn.setAttribute('aria-expanded', 'true');
      // Evitar scroll no body enquanto o menu está aberto
      document.body.style.overflow = 'hidden';
    }

    function closeMenu() {
      if (!menu) return;
      menu.classList.add('translate-x-full');
      menu.classList.remove('translate-x-0');
      menu.setAttribute('aria-hidden', 'true');
      if (openBtn) openBtn.setAttribute('aria-expanded', 'false');
      document.body.style.overflow = '';
    }

    if (openBtn) openBtn.addEventListener('click', openMenu);
    if (closeBtn) closeBtn.addEventListener('click', closeMenu);

    // Fechar ao clicar em um link do menu
    if (menu) {
      var links = menu.querySelectorAll('a[href^="#"]');
      links.forEach(function (link) {
        link.addEventListener('click', function () {
          closeMenu();
        });
      });
    }

    // Fechar com tecla ESC
    document.addEventListener('keydown', function (ev) {
      if (ev.key === 'Escape') {
        closeMenu();
      }
    });

    // Fechar ao clicar fora (overlay)
    if (menu) {
      menu.addEventListener('click', function (ev) {
        if (ev.target === menu) {
          closeMenu();
        }
      });
    }
  }

  function init() {
    var initial = getPreferredTheme();
    applyTheme(initial);
    setupListeners();
  }

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', init);
  } else {
    init();
  }
})();


AOS.init();