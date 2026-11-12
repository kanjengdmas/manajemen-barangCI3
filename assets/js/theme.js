/**
 * Theme & UI System Management
 * Handles dark/light theme, stock privacy mode, and layout responsiveness
 */

(function () {
  'use strict';

  // 1. Theme Configuration & Multi-Palette System
  const THEME_CONFIG = {
    light: { icon: 'bi bi-sun-fill fs-5 text-warning', label: 'Mode Terang (Clean Slate)' },
    dark: { icon: 'bi bi-moon-stars-fill fs-5 text-warning', label: 'Mode Gelap (Deep Slate)' },
    sepia: { icon: 'bi bi-cup-hot-fill fs-5 text-warning', label: 'Kertas Hangat (Sepia)' },
    navy: { icon: 'bi bi-compass-fill fs-5 text-info', label: 'Midnight Navy (Deep Blue)' },
    oled: { icon: 'bi bi-circle-fill fs-5 text-light', label: 'OLED Pure Black (High-Contrast)' },
    forest: { icon: 'bi bi-tree-fill fs-5 text-success', label: 'Forest Emerald (Sage Green)' }
  };
  const THEME_KEYS = Object.keys(THEME_CONFIG);

  const getStoredTheme = () => localStorage.getItem('theme') || 'light';
  const getPreferredTheme = () => {
    const storedTheme = getStoredTheme();
    if (storedTheme === 'system') {
      return window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';
    }
    return storedTheme;
  };

  const setTheme = (theme) => {
    const effectiveTheme = THEME_CONFIG[theme]
      ? theme
      : (theme === 'system' ? (window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light') : 'light');

    document.documentElement.setAttribute('data-bs-theme', effectiveTheme);

    // Update theme toggle icon and button tooltip
    const themeIcon = document.getElementById('theme-toggle-icon');
    const toggleBtn = document.getElementById('theme-toggle-btn');
    const cfg = THEME_CONFIG[effectiveTheme] || THEME_CONFIG.light;
    if (themeIcon) {
      themeIcon.className = cfg.icon;
    }
    if (toggleBtn) {
      toggleBtn.title = 'Tema: ' + cfg.label + ' (Klik untuk ganti)';
    }

    // Synchronize active state of theme dropdown items
    document.querySelectorAll('.theme-dropdown-option').forEach(opt => {
      const optVal = opt.getAttribute('data-theme-val');
      const isMatch = (optVal === effectiveTheme);
      opt.classList.toggle('active', isMatch);
      const checkIcon = opt.querySelector('.theme-check-icon');
      if (checkIcon) checkIcon.classList.toggle('d-none', !isMatch);
    });

    try {
      window.dispatchEvent(new CustomEvent('theme-changed', { detail: { theme: effectiveTheme, rawTheme: theme } }));
    } catch (e) {
      console.warn('theme-changed dispatch error:', e);
    }
  };

  const selectTheme = (nextTheme) => {
    if (!THEME_CONFIG[nextTheme]) nextTheme = 'light';
    localStorage.setItem('theme', nextTheme);
    setTheme(nextTheme);

    // Auto-save to server if endpoint exists
    if (window.BASE_URL) {
      fetch(window.BASE_URL + 'api/settings/save', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-Requested-With': 'XMLHttpRequest'
        },
        body: JSON.stringify({ theme: nextTheme })
      }).catch(() => {});
    }

    return nextTheme;
  };

  const toggleTheme = () => {
    const currentEffective = document.documentElement.getAttribute('data-bs-theme') || 'light';
    const currentIndex = THEME_KEYS.indexOf(currentEffective);
    const nextTheme = THEME_KEYS[(currentIndex + 1) % THEME_KEYS.length];
    return selectTheme(nextTheme);
  };

  // Expose global helper immediately
  window.MBTheme = {
    setTheme: selectTheme,
    getPreferredTheme,
    toggleTheme,
    selectTheme,
    THEMES: THEME_CONFIG
  };

  // Set theme immediately before DOM content loads to avoid flash
  setTheme(getStoredTheme());

  const initThemeUI = () => {
    setTheme(getStoredTheme());

    // Bind click events on theme palette dropdown options
    document.querySelectorAll('.theme-dropdown-option').forEach(opt => {
      if (!opt.dataset.bound) {
        opt.dataset.bound = 'true';
        opt.addEventListener('click', function (e) {
          e.preventDefault();
          const targetTheme = this.getAttribute('data-theme-val');
          if (targetTheme) {
            selectTheme(targetTheme);
          }
        });
      }
    });

    // Mobile Sidebar Toggle
    const sidebarToggleBtn = document.getElementById('sidebar-toggle-btn');
    const sidebarCloseBtn = document.getElementById('sidebar-close-btn');
    const sidebar = document.querySelector('.app-sidebar');
    const backdrop = document.querySelector('.sidebar-backdrop');

    if (sidebar) {
      const toggleSidebar = () => {
        const isOpening = !sidebar.classList.contains('show');
        sidebar.classList.toggle('show');
        if (backdrop) backdrop.classList.toggle('show');
        if (isOpening) {
          document.body.style.overflow = 'hidden'; // Prevent background scrolling on mobile
        } else {
          document.body.style.overflow = '';
        }
      };

      const closeSidebar = () => {
        sidebar.classList.remove('show');
        if (backdrop) backdrop.classList.remove('show');
        document.body.style.overflow = '';
      };

      const bottomNavMenuBtn = document.getElementById('bottom-nav-menu-btn');

      if (sidebarToggleBtn) sidebarToggleBtn.addEventListener('click', toggleSidebar);
      if (sidebarCloseBtn) sidebarCloseBtn.addEventListener('click', closeSidebar);
      if (bottomNavMenuBtn) bottomNavMenuBtn.addEventListener('click', toggleSidebar);
      if (backdrop) backdrop.addEventListener('click', closeSidebar);

      // Close mobile sidebar on Escape key
      document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape' && sidebar.classList.contains('show')) {
          closeSidebar();
        }
      });

      // Close sidebar when clicking a nav link on mobile (width < 992px)
      sidebar.querySelectorAll('.sidebar-nav-link').forEach(link => {
        link.addEventListener('click', () => {
          if (window.innerWidth < 992) {
            closeSidebar();
          }
        });
      });
    }

    // Ensure all stock numbers display normally
    document.querySelectorAll('[data-stock-value]').forEach(el => {
      const rawVal = el.getAttribute('data-stock-value');
      if (rawVal) el.textContent = rawVal;
    });
    localStorage.removeItem('show_stock');

    // Listen to system theme change
    window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', () => {
      const stored = getStoredTheme();
      if (stored === 'system') {
        setTheme('system');
      }
    });

    // Live Real-Time Clock & Header Seconds Ticker
    const startRealtimeClock = () => {
      const secondsEl = document.getElementById('header-live-seconds');
      const updatedEl = document.getElementById('header-last-updated');
      const clockEl = document.getElementById('header-live-clock');

      if (!secondsEl && !updatedEl && !clockEl) return;

      const tick = () => {
        const now = new Date();
        const h = String(now.getHours()).padStart(2, '0');
        const m = String(now.getMinutes()).padStart(2, '0');
        const s = String(now.getSeconds()).padStart(2, '0');
        const timeStr = `${h}:${m}:${s}`;

        if (secondsEl) {
          secondsEl.textContent = timeStr;
        } else if (updatedEl) {
          updatedEl.textContent = `Pembaruan: ${timeStr}`;
        }

        if (clockEl) {
          const datePart = now.toLocaleDateString('id-ID', { weekday: 'short', day: 'numeric', month: 'short', year: 'numeric' });
          clockEl.innerHTML = `<i class="bi bi-clock me-1.5"></i> ${datePart} · ${timeStr} WIB`;
        }
      };

      tick();
      setInterval(tick, 1000);
    };

    startRealtimeClock();

    // Manual Refresh Button fallback for non-dashboard pages
    const manualRefreshBtn = document.getElementById('btn-manual-refresh');
    if (manualRefreshBtn && !document.getElementById('movementChartCanvas')) {
      manualRefreshBtn.addEventListener('click', () => {
        manualRefreshBtn.classList.add('spinning');
        window.location.reload();
      });
    }
  };

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initThemeUI);
  } else {
    initThemeUI();
  }
})();
