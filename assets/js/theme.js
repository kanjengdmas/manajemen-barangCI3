/**
 * Theme & UI System Management
 * Handles dark/light theme, stock privacy mode, and layout responsiveness
 */

(function () {
  'use strict';

  // 1. Theme Initialization
  const getStoredTheme = () => localStorage.getItem('theme') || 'system';
  const getPreferredTheme = () => {
    const storedTheme = getStoredTheme();
    if (storedTheme === 'system') {
      return window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';
    }
    return storedTheme;
  };

  const setTheme = (theme) => {
    const effectiveTheme = theme === 'system'
      ? (window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light')
      : theme;

    document.documentElement.setAttribute('data-bs-theme', effectiveTheme);

    // Update theme toggle icon
    const themeIcon = document.getElementById('theme-toggle-icon');
    if (themeIcon) {
      if (effectiveTheme === 'dark') {
        themeIcon.className = 'bi bi-moon-stars-fill text-warning';
      } else {
        themeIcon.className = 'bi bi-sun-fill text-amber-500';
      }
    }

    // Synchronize active state of any theme cards present in the DOM
    document.querySelectorAll('.theme-card-option').forEach(card => {
      const cardTheme = card.getAttribute('data-theme') || card.getAttribute('data-theme-val');
      const isMatch = (cardTheme === theme) || (theme !== 'system' && cardTheme === effectiveTheme);
      if (isMatch) {
        card.classList.add('active');
        const checkIcon = card.querySelector('.bi-check-circle-fill');
        if (checkIcon) checkIcon.classList.remove('d-none');
      } else {
        card.classList.remove('active');
        const checkIcon = card.querySelector('.bi-check-circle-fill');
        if (checkIcon) checkIcon.classList.add('d-none');
      }
    });

    window.dispatchEvent(new CustomEvent('theme-changed', { detail: { theme: effectiveTheme, rawTheme: theme } }));
  };

  // Set theme immediately before DOM content loads to avoid flash
  setTheme(getStoredTheme());

  document.addEventListener('DOMContentLoaded', () => {
    setTheme(getStoredTheme());

    // Theme Toggle Button in Header
    const themeToggleBtn = document.getElementById('theme-toggle-btn');
    if (themeToggleBtn) {
      themeToggleBtn.addEventListener('click', () => {
        const currentStored = getStoredTheme();
        const nextTheme = currentStored === 'dark' ? 'light' : 'dark';
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
      });
    }

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

    // Sidebar Interactive Quick Search (Ctrl+K)
    const quickSearchInput = document.getElementById('sidebar-quick-search');
    if (quickSearchInput) {
      // Shortcut Ctrl+K / Cmd+K to focus search
      document.addEventListener('keydown', (e) => {
        if ((e.ctrlKey || e.metaKey) && e.key.toLowerCase() === 'k') {
          e.preventDefault();
          quickSearchInput.focus();
          quickSearchInput.select();
        } else if (e.key === 'Escape' && document.activeElement === quickSearchInput) {
          quickSearchInput.value = '';
          quickSearchInput.dispatchEvent(new Event('input'));
          quickSearchInput.blur();
        }
      });

      // Live filter menu items
      quickSearchInput.addEventListener('input', (e) => {
        const query = e.target.value.trim().toLowerCase();
        const menuItems = document.querySelectorAll('.sidebar-menu-list li');
        const sectionLabels = document.querySelectorAll('.sidebar-section-label');

        if (!query) {
          menuItems.forEach(li => li.style.display = '');
          sectionLabels.forEach(sec => sec.style.display = '');
          return;
        }

        menuItems.forEach(li => {
          const text = li.textContent.toLowerCase();
          const matches = text.includes(query);
          li.style.display = matches ? '' : 'none';
        });

        // Hide section labels if all their sibling items are hidden
        document.querySelectorAll('.sidebar-scrollable').forEach(container => {
          const sections = container.querySelectorAll('.sidebar-section-label');
          sections.forEach(label => {
            const nextList = label.nextElementSibling;
            if (nextList && nextList.classList.contains('sidebar-menu-list')) {
              const visibleItems = nextList.querySelectorAll('li:not([style*="display: none"])');
              label.style.display = visibleItems.length > 0 ? '' : 'none';
            }
          });
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
  });

  // Expose global helper
  window.MBTheme = {
    setTheme,
    getPreferredTheme
  };
})();
