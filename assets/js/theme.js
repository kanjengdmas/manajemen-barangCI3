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

    window.dispatchEvent(new CustomEvent('theme-changed', { detail: { theme: effectiveTheme } }));
  };

  // Set theme immediately before DOM content loads to avoid flash
  setTheme(getStoredTheme());

  document.addEventListener('DOMContentLoaded', () => {
    setTheme(getStoredTheme());

    // Theme Toggle Button
    const themeToggleBtn = document.getElementById('theme-toggle-btn');
    if (themeToggleBtn) {
      themeToggleBtn.addEventListener('click', () => {
        const currentStored = getStoredTheme();
        const nextTheme = currentStored === 'dark' ? 'light' : 'dark';
        localStorage.setItem('theme', nextTheme);
        setTheme(nextTheme);
      });
    }

    // Mobile Sidebar Toggle
    const sidebarToggleBtn = document.getElementById('sidebar-toggle-btn');
    const sidebarCloseBtn = document.getElementById('sidebar-close-btn');
    const sidebar = document.querySelector('.app-sidebar');
    const backdrop = document.querySelector('.sidebar-backdrop');

    if (sidebar) {
      const toggleSidebar = () => {
        sidebar.classList.toggle('show');
        if (backdrop) backdrop.classList.toggle('show');
      };

      const closeSidebar = () => {
        sidebar.classList.remove('show');
        if (backdrop) backdrop.classList.remove('show');
      };

      if (sidebarToggleBtn) sidebarToggleBtn.addEventListener('click', toggleSidebar);
      if (sidebarCloseBtn) sidebarCloseBtn.addEventListener('click', closeSidebar);
      if (backdrop) backdrop.addEventListener('click', closeSidebar);
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

    // Stock Privacy Mode Toggle
    const stockPrivacyBtn = document.getElementById('stock-privacy-toggle');
    const applyStockPrivacy = (showStock) => {
      const stockElements = document.querySelectorAll('[data-stock-value]');
      stockElements.forEach(el => {
        const rawVal = el.getAttribute('data-stock-value');
        if (showStock) {
          el.textContent = rawVal;
        } else {
          el.textContent = '••••••';
        }
      });

      const icon = document.getElementById('stock-privacy-icon');
      if (icon) {
        icon.className = showStock ? 'bi bi-eye' : 'bi bi-eye-slash';
      }
    };

    let showStock = localStorage.getItem('show_stock') !== '0';
    applyStockPrivacy(showStock);

    if (stockPrivacyBtn) {
      stockPrivacyBtn.addEventListener('click', () => {
        showStock = !showStock;
        localStorage.setItem('show_stock', showStock ? '1' : '0');
        applyStockPrivacy(showStock);
      });
    }

    // Listen to system theme change
    window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', () => {
      const stored = getStoredTheme();
      if (stored === 'system') {
        setTheme('system');
      }
    });
  });

  // Expose global helper
  window.MBTheme = {
    setTheme,
    getPreferredTheme
  };
})();
