/**
 * Dashboard Real-Time & Analytics Controller
 * Handles Chart.js visualization, silent auto-refresh polling, and interactive period switching.
 */

(function () {
  'use strict';

  let movementChart = null;
  let kategoriChart = null;
  let currentPeriod = '7d';
  let refreshTimer = null;
  let isFetching = false;

  // Chart Theme Helpers
  const getChartColors = () => {
    const isDark = document.documentElement.getAttribute('data-bs-theme') === 'dark';
    return {
      textColor: isDark ? '#94a3b8' : '#64748b',
      gridColor: isDark ? 'rgba(255, 255, 255, 0.05)' : 'rgba(0, 0, 0, 0.04)',
      tooltipBg: isDark ? '#1e293b' : '#0f172a',
      masukColor: '#10b981',
      masukBg: isDark ? 'rgba(16, 185, 129, 0.75)' : '#10b981',
      keluarColor: '#ef4444',
      keluarBg: isDark ? 'rgba(239, 68, 68, 0.75)' : '#ef4444'
    };
  };

  /**
   * 1. Initialize Movement Bar Chart
   */
  const initMovementChart = (initialData) => {
    const ctx = document.getElementById('movementChartCanvas');
    if (!ctx) return;

    const colors = getChartColors();
    const labels = initialData?.days || [];
    const masuk = initialData?.masuk || [];
    const keluar = initialData?.keluar || [];

    movementChart = new Chart(ctx, {
      type: 'bar',
      data: {
        labels: labels,
        datasets: [
          {
            label: 'Barang Masuk',
            data: masuk,
            backgroundColor: colors.masukBg,
            borderRadius: 4,
            barThickness: labels.length > 15 ? 12 : 20,
            maxBarThickness: 28
          },
          {
            label: 'Barang Keluar',
            data: keluar,
            backgroundColor: colors.keluarBg,
            borderRadius: 4,
            barThickness: labels.length > 15 ? 12 : 20,
            maxBarThickness: 28
          }
        ]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        animation: { duration: 400 },
        plugins: {
          legend: {
            display: true,
            position: 'top',
            align: 'end',
            labels: {
              boxWidth: 10,
              boxHeight: 10,
              usePointStyle: true,
              pointStyle: 'circle',
              color: colors.textColor,
              font: { family: "'Plus Jakarta Sans', sans-serif", size: 12, weight: 600 }
            }
          },
          tooltip: {
            backgroundColor: colors.tooltipBg,
            titleFont: { family: "'Plus Jakarta Sans', sans-serif", size: 12, weight: 700 },
            bodyFont: { family: "'JetBrains Mono', monospace", size: 12 },
            padding: 10,
            cornerRadius: 8,
            callbacks: {
              label: function (ctx) {
                return ` ${ctx.dataset.label}: ${ctx.parsed.y.toLocaleString()} Unit`;
              }
            }
          }
        },
        scales: {
          x: {
            grid: { display: false },
            ticks: {
              color: colors.textColor,
              font: { family: "'Plus Jakarta Sans', sans-serif", size: 11 }
            }
          },
          y: {
            beginAtZero: true,
            grid: { color: colors.gridColor },
            ticks: {
              color: colors.textColor,
              font: { family: "'JetBrains Mono', monospace", size: 11 },
              callback: function (val) {
                return Number.isInteger(val) ? val.toLocaleString() : null;
              }
            }
          }
        }
      }
    });
  };

  /**
   * 2. Initialize Category Distribution Doughnut Chart
   */
  const initKategoriChart = (initialCategories) => {
    const ctx = document.getElementById('kategoriChartCanvas');
    if (!ctx) return;

    const colors = getChartColors();
    const categories = initialCategories || [];
    const labels = categories.map(c => c.nama_kategori);
    const values = categories.map(c => parseInt(c.total_stok, 10) || 0);

    const palette = [
      '#dc2626', '#3b82f6', '#10b981', '#f59e0b', '#8b5cf6',
      '#06b6d4', '#ec4899', '#64748b'
    ];

    kategoriChart = new Chart(ctx, {
      type: 'doughnut',
      data: {
        labels: labels,
        datasets: [{
          data: values,
          backgroundColor: palette.slice(0, labels.length),
          borderWidth: 2,
          borderColor: document.documentElement.getAttribute('data-bs-theme') === 'dark' ? '#111827' : '#ffffff',
          hoverOffset: 4
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        cutout: '72%',
        animation: { duration: 500 },
        plugins: {
          legend: {
            display: false // We render custom legend below chart for precise control
          },
          tooltip: {
            backgroundColor: colors.tooltipBg,
            titleFont: { family: "'Plus Jakarta Sans', sans-serif", size: 12, weight: 700 },
            bodyFont: { family: "'JetBrains Mono', monospace", size: 12 },
            padding: 10,
            cornerRadius: 8,
            callbacks: {
              label: function (ctx) {
                const total = ctx.dataset.data.reduce((a, b) => a + b, 0);
                const pct = total > 0 ? ((ctx.parsed / total) * 100).toFixed(1) : 0;
                return ` ${ctx.label}: ${ctx.parsed.toLocaleString()} Unit (${pct}%)`;
              }
            }
          }
        }
      }
    });
  };

  /**
   * 3. Switch Movement Chart Period
   */
  const switchPeriod = async (period) => {
    currentPeriod = period;

    // Update active tab button style
    document.querySelectorAll('.period-tab').forEach(btn => {
      btn.classList.toggle('active', btn.getAttribute('data-period') === period);
    });

    try {
      const res = await fetch(`${window.BASE_URL || ''}api/dashboard/movement-chart?days=${period}`, {
        headers: { 'Accept': 'application/json' }
      });
      if (res.ok) {
        const json = await res.json();
        if (json.status && json.data) {
          updateMovementChartData(json.data);
        }
      }
    } catch (err) {
      console.error('Failed to switch movement chart period:', err);
    }
  };

  const updateMovementChartData = (data) => {
    if (!movementChart) return;

    movementChart.data.labels = data.days || [];
    movementChart.data.datasets[0].data = data.masuk || [];
    movementChart.data.datasets[1].data = data.keluar || [];
    movementChart.update('active');

    // Update summary pills
    const totalInEl = document.getElementById('period-total-in');
    const totalOutEl = document.getElementById('period-total-out');
    if (totalInEl) totalInEl.textContent = Number(data.total_in || 0).toLocaleString();
    if (totalOutEl) totalOutEl.textContent = Number(data.total_out || 0).toLocaleString();
  };

  /**
   * 4. Silent Background Data Refresh
   */
  const refreshDashboardData = async (isManual = false) => {
    if (isFetching) return;
    isFetching = true;

    const refreshBtn = document.getElementById('btn-manual-refresh');
    if (refreshBtn && isManual) {
      refreshBtn.classList.add('spinning');
    }

    try {
      const baseUrl = window.BASE_URL || '';
      const [statsRes, movementRes, categoryRes, lowStockRes, activitiesRes, expiringRes] = await Promise.allSettled([
        fetch(`${baseUrl}api/dashboard/stats`, { headers: { 'Accept': 'application/json' } }),
        fetch(`${baseUrl}api/dashboard/movement-chart?days=${currentPeriod}`, { headers: { 'Accept': 'application/json' } }),
        fetch(`${baseUrl}api/dashboard/stok-chart`, { headers: { 'Accept': 'application/json' } }),
        fetch(`${baseUrl}api/dashboard/low-stock?limit=5`, { headers: { 'Accept': 'application/json' } }),
        fetch(`${baseUrl}api/dashboard/recent-activities?limit=15`, { headers: { 'Accept': 'application/json' } }),
        fetch(`${baseUrl}api/batch/expiring?days=30`, { headers: { 'Accept': 'application/json' } })
      ]);

      // 4.1 Stats Update
      if (statsRes.status === 'fulfilled' && statsRes.value.ok) {
        const res = await statsRes.value.json();
        if (res.data) {
          updateStatCard('stat-total-produk', res.data.total_produk);
          updateStatCard('stat-total-stok', res.data.total_stok, true);
          updateStatCard('stat-stok-menipis', res.data.produk_stok_menipis);
          updateStatCard('stat-stok-habis', res.data.produk_stok_habis);
        }
      }

      // 4.2 Movement Chart Update
      if (movementRes.status === 'fulfilled' && movementRes.value.ok) {
        const res = await movementRes.value.json();
        if (res.data) updateMovementChartData(res.data);
      }

      // 4.3 Category Chart Update
      if (categoryRes.status === 'fulfilled' && categoryRes.value.ok) {
        const res = await categoryRes.value.json();
        if (res.data && kategoriChart) {
          kategoriChart.data.labels = res.data.labels || [];
          kategoriChart.data.datasets[0].data = res.data.values || [];
          kategoriChart.update('active');
        }
      }

      // 4.4 Low Stock Table Update
      if (lowStockRes.status === 'fulfilled' && lowStockRes.value.ok) {
        const res = await lowStockRes.value.json();
        if (Array.isArray(res.data)) renderLowStockTable(res.data);
      }

      // 4.5 Recent Activities Update
      if (activitiesRes.status === 'fulfilled' && activitiesRes.value.ok) {
        const res = await activitiesRes.value.json();
        if (Array.isArray(res.data)) renderRecentActivities(res.data);
      }

      // 4.6 Expiring Batches (FEFO) Update
      if (expiringRes.status === 'fulfilled' && expiringRes.value.ok) {
        const res = await expiringRes.value.json();
        if (Array.isArray(res.data)) renderExpiringBatches(res.data);
      }

      // Update Timestamp
      updateLastUpdatedTimestamp();

    } catch (err) {
      console.error('Silent refresh failed:', err);
    } finally {
      isFetching = false;
      if (refreshBtn && isManual) {
        setTimeout(() => refreshBtn.classList.remove('spinning'), 600);
      }
    }
  };

  const updateStatCard = (id, value, isStock = false) => {
    const el = document.getElementById(id);
    if (!el) return;
    const formatted = Number(value || 0).toLocaleString();
    el.setAttribute('data-stock-value', formatted);

    const showStock = localStorage.getItem('show_stock') !== '0';
    if (isStock && !showStock) {
      el.textContent = '••••••';
    } else {
      el.textContent = formatted;
    }
  };

  const updateLastUpdatedTimestamp = () => {
    const el = document.getElementById('header-last-updated');
    if (!el) return;
    const now = new Date();
    const timeStr = now.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit', second: '2-digit' }).replace('.', ':');
    el.textContent = `Pembaruan: ${timeStr}`;
  };

  const renderLowStockTable = (items) => {
    const tbody = document.getElementById('low-stock-tbody');
    if (!tbody) return;

    if (items.length === 0) {
      tbody.innerHTML = `
        <tr>
          <td colspan="5" class="text-center py-4 text-muted">
            <i class="bi bi-check-circle text-success me-1"></i> Semua stok produk dalam batas aman.
          </td>
        </tr>`;
      return;
    }

    tbody.innerHTML = items.map((item, idx) => {
      const isHabis = Number(item.stok) <= 0;
      const badgeClass = isHabis ? 'badge-soft-danger' : 'badge-soft-warning';
      const dotClass = isHabis ? 'bg-danger' : 'bg-warning';
      const badgeText = isHabis ? 'Habis' : 'Menipis';

      return `
        <tr>
          <td class="text-muted text-center tabular-nums fw-semibold">${idx + 1}</td>
          <td>
            <div class="fw-semibold text-truncate" style="max-width: 170px; color: var(--mb-text-primary); font-size: 0.85rem;">${escapeHtml(item.nama_produk)}</div>
            <span class="sku-badge mt-0.5" style="font-size: 0.68rem; padding: 1px 5px;">${escapeHtml(item.sku || '-')}</span>
          </td>
          <td class="text-end">
            <span class="fw-bold tabular-nums font-monospace ${isHabis ? 'text-danger' : 'text-warning'}">${Number(item.stok).toLocaleString()}</span>
          </td>
          <td class="text-end text-muted tabular-nums font-monospace" style="font-size: 0.8rem;">${Number(item.stok_minimal).toLocaleString()}</td>
          <td class="text-center">
            <span class="badge-soft ${badgeClass}" style="font-size: 0.68rem; padding: 2px 7px;">
              <span class="badge-dot ${dotClass}"></span> ${badgeText}
            </span>
          </td>
        </tr>`;
    }).join('');
  };

  const initActivityFilter = () => {
    const filterPills = document.querySelectorAll('#activity-type-filter .dashboard-filter-btn');
    if (!filterPills.length) return;

    filterPills.forEach(btn => {
      btn.addEventListener('click', function() {
        filterPills.forEach(b => b.classList.remove('active'));
        this.classList.add('active');
        applyActivityFilter(this.getAttribute('data-filter'));
      });
    });
  };

  const applyActivityFilter = (filterType) => {
    const rows = document.querySelectorAll('#recent-activities-tbody .activity-row');
    const filterEmptyRow = document.getElementById('filter-empty-row');
    let visibleCount = 0;

    rows.forEach(row => {
      const rowType = row.getAttribute('data-type');
      if (filterType === 'all' || rowType === filterType) {
        row.classList.remove('d-none');
        visibleCount++;
      } else {
        row.classList.add('d-none');
      }
    });

    if (filterEmptyRow) {
      if (rows.length > 0 && visibleCount === 0) {
        filterEmptyRow.classList.remove('d-none');
      } else {
        filterEmptyRow.classList.add('d-none');
      }
    }
  };

  const renderRecentActivities = (items) => {
    const tbody = document.getElementById('recent-activities-tbody');
    if (!tbody) return;

    const countAllBadge = document.getElementById('filter-count-all');
    if (countAllBadge) countAllBadge.textContent = items.length;

    if (items.length === 0) {
      tbody.innerHTML = `
        <tr id="empty-activity-row">
          <td colspan="6">
            <div class="table-empty-state py-4">
              <div class="table-empty-icon" style="width: 40px; height: 40px; font-size: 1.1rem;">
                <i class="bi bi-clock-history"></i>
              </div>
              <h6 class="fw-bold mb-1" style="color: var(--mb-text-primary); font-size: 0.88rem;">Belum Ada Mutasi</h6>
              <p class="text-muted small mb-0" style="font-size: 0.75rem;">Belum ada aktivitas transaksi mutasi barang tercatat.</p>
            </div>
          </td>
        </tr>`;
      return;
    }

    const currentFilter = document.querySelector('#activity-type-filter .dashboard-filter-btn.active')?.getAttribute('data-filter') || 'all';

    tbody.innerHTML = items.map(item => {
      const isMasuk = item.tipe === 'masuk';
      const badgeClass = isMasuk ? 'badge-soft-success' : 'badge-soft-danger';
      const dotClass = isMasuk ? 'bg-success' : 'bg-danger';
      const icon = isMasuk ? 'bi-arrow-down-left' : 'bi-arrow-up-right';
      const sign = isMasuk ? '+' : '-';
      const qtyClass = isMasuk ? 'text-success' : 'text-danger';

      const d = item.tanggal ? new Date(item.tanggal) : new Date();
      const dateStr = d.toLocaleDateString('id-ID', { day: '2-digit', month: 'short', year: 'numeric' });
      const timeStr = d.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' }).replace('.', ':');
      const relativeTime = formatRelativeTime(item.tanggal);

      const userName = item.user_name || 'Petugas';
      const words = userName.trim().split(' ');
      const initials = ((words[0]?.charAt(0) || '') + (words[1]?.charAt(0) || '')).toUpperCase();

      const isHidden = (currentFilter !== 'all' && item.tipe !== currentFilter) ? 'd-none' : '';

      return `
        <tr class="activity-row ${isHidden}" data-type="${escapeHtml(item.tipe)}">
          <td>
            <div class="fw-semibold tabular-nums" style="font-size: 0.82rem; color: var(--mb-text-primary);">
              ${escapeHtml(dateStr)}
            </div>
            <div class="text-muted tabular-nums d-flex align-items-center gap-1" style="font-size: 0.72rem;">
              <i class="bi bi-clock"></i> ${escapeHtml(timeStr)}
              <span class="text-muted opacity-75">· ${escapeHtml(relativeTime)}</span>
            </div>
          </td>
          <td class="text-center">
            <span class="badge-soft ${badgeClass}">
              <span class="badge-dot ${dotClass}"></span>
              <i class="bi ${icon}"></i> ${isMasuk ? 'Masuk' : 'Keluar'}
            </span>
          </td>
          <td>
            <div class="fw-semibold text-truncate" style="max-width: 260px; color: var(--mb-text-primary); font-size: 0.85rem;" title="${escapeHtml(item.nama_produk || 'Produk')}">
              ${escapeHtml(item.nama_produk || 'Produk')}
            </div>
            <div class="mt-0.5">
              <span class="sku-badge" style="font-size: 0.68rem; padding: 1px 6px;">
                <i class="bi bi-upc-scan text-muted" style="font-size: 0.65rem;"></i>
                ${escapeHtml(item.sku || '-')}
              </span>
            </div>
          </td>
          <td>
            ${item.nomor_batch ? `
              <div class="sku-badge font-monospace" style="font-size: 0.72rem; padding: 2px 7px;">
                <i class="bi bi-box-seam text-muted" style="font-size: 0.68rem;"></i>
                ${escapeHtml(item.nomor_batch)}
              </div>
              ${item.lokasi_rak ? `
                <div class="text-muted mt-0.5 d-flex align-items-center gap-1" style="font-size: 0.68rem;">
                  <i class="bi bi-geo-alt"></i> Rak: ${escapeHtml(item.lokasi_rak)}
                </div>
              ` : ''}
            ` : '<span class="text-muted" style="font-size: 0.75rem;">Non-Batch</span>'}
          </td>
          <td class="text-end">
            <span class="fw-bold tabular-nums font-monospace ${qtyClass}" style="font-size: 0.92rem;">
              ${sign}${Number(item.jumlah).toLocaleString()}
            </span>
            <span class="text-muted small" style="font-size: 0.72rem;">unit</span>
          </td>
          <td>
            <div class="d-flex align-items-center gap-1.5 mb-0.5">
              <div class="mini-avatar-initials" title="${escapeHtml(userName)}">
                ${escapeHtml(initials)}
              </div>
              <span class="fw-semibold text-truncate small" style="max-width: 130px; font-size: 0.8rem; color: var(--mb-text-primary);">
                ${escapeHtml(userName)}
              </span>
            </div>
            <div class="text-muted text-truncate" style="max-width: 160px; font-size: 0.72rem;" title="${escapeHtml(item.catatan || '')}">
              ${item.catatan ? escapeHtml(item.catatan) : '<span class="opacity-50">Tanpa catatan</span>'}
            </div>
          </td>
        </tr>`;
    }).join('') + `
      <tr id="filter-empty-row" class="d-none">
        <td colspan="6">
          <div class="table-empty-state py-4">
            <div class="table-empty-icon" style="width: 40px; height: 40px; font-size: 1.1rem;">
              <i class="bi bi-funnel"></i>
            </div>
            <h6 class="fw-bold mb-1" style="color: var(--mb-text-primary); font-size: 0.88rem;">Tidak Ditemukan</h6>
            <p class="text-muted small mb-0" style="font-size: 0.75rem;">Tidak ada transaksi mutasi sesuai filter ini pada 10 aktivitas terakhir.</p>
          </div>
        </td>
      </tr>
    `;

    applyActivityFilter(currentFilter);
  };

  const renderExpiringBatches = (items) => {
    const container = document.getElementById('expiring-batches-container');
    if (!container) return;

    if (items.length === 0) {
      container.innerHTML = `
        <div class="text-center py-4 text-muted">
          <i class="bi bi-shield-check text-success fs-4 d-block mb-1"></i>
          <span class="small">Semua batch lot dalam kondisi aman.</span>
        </div>`;
      return;
    }

    container.innerHTML = items.map(b => {
      const daysLeft = parseInt(b.days_left, 10);
      const isExpired = daysLeft < 0;
      const badgeClass = isExpired ? 'bg-danger text-white' : (daysLeft <= 14 ? 'bg-warning text-dark' : 'bg-secondary text-white');
      const badgeLabel = isExpired ? 'Expired' : `H-${daysLeft}`;

      return `
        <div class="p-2 mb-2 rounded border border-subtle d-flex align-items-center justify-content-between" style="background-color: var(--mb-card-raised);">
          <div>
            <div class="fw-bold small text-truncate" style="max-width: 170px;">${escapeHtml(b.nama_produk)}</div>
            <div class="text-muted small tabular-nums">${escapeHtml(b.nomor_batch)} · Rak: ${escapeHtml(b.lokasi_rak || '-')}</div>
          </div>
          <div class="text-end">
            <span class="badge ${badgeClass} rounded-pill">${badgeLabel}</span>
            <div class="text-muted small tabular-nums mt-0.5">${Number(b.stok_saat_ini).toLocaleString()} unit</div>
          </div>
        </div>`;
    }).join('');
  };

  const formatRelativeTime = (dateStr) => {
    if (!dateStr) return '-';
    try {
      const d = new Date(dateStr);
      const now = new Date();
      const diffSec = Math.floor((now - d) / 1000);

      if (diffSec < 60) return 'Baru saja';
      if (diffSec < 3600) return `${Math.floor(diffSec / 60)} mnt lalu`;
      if (diffSec < 86400) return `${Math.floor(diffSec / 3600)} jam lalu`;
      return d.toLocaleDateString('id-ID', { day: 'numeric', month: 'short' });
    } catch {
      return dateStr;
    }
  };

  const escapeHtml = (str) => {
    if (!str) return '';
    return String(str)
      .replace(/&/g, '&amp;')
      .replace(/</g, '&lt;')
      .replace(/>/g, '&gt;')
      .replace(/"/g, '&quot;')
      .replace(/'/g, '&#039;');
  };

  /**
   * 5. Setup Live Time Clock
   */
  const startLiveClock = () => {
    const clockEl = document.getElementById('header-live-clock');
    if (!clockEl) return;

    const tick = () => {
      const now = new Date();
      const datePart = now.toLocaleDateString('id-ID', { weekday: 'short', day: 'numeric', month: 'short', year: 'numeric' });
      const timePart = now.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' }).replace('.', ':');
      clockEl.textContent = `${datePart} · ${timePart} WIB`;
    };

    tick();
    setInterval(tick, 30000);
  };

  // 6. Setup Theme Change Listener on Charts
  window.addEventListener('theme-changed', () => {
    const colors = getChartColors();
    if (movementChart) {
      movementChart.options.scales.x.ticks.color = colors.textColor;
      movementChart.options.scales.y.ticks.color = colors.textColor;
      movementChart.options.scales.y.grid.color = colors.gridColor;
      movementChart.options.plugins.legend.labels.color = colors.textColor;
      movementChart.update();
    }
    if (kategoriChart) {
      kategoriChart.data.datasets[0].borderColor = document.documentElement.getAttribute('data-bs-theme') === 'dark' ? '#111827' : '#ffffff';
      kategoriChart.update();
    }
  });

  // DOM Ready Entrypoint
  document.addEventListener('DOMContentLoaded', () => {
    const initialMovement = window.__INITIAL_MOVEMENT_DATA__ || null;
    const initialCategories = window.__INITIAL_CATEGORY_DATA__ || null;

    initMovementChart(initialMovement);
    initKategoriChart(initialCategories);
    initActivityFilter();
    startLiveClock();
    updateLastUpdatedTimestamp();

    // Period switcher buttons
    document.querySelectorAll('.period-tab').forEach(btn => {
      btn.addEventListener('click', () => {
        const period = btn.getAttribute('data-period');
        if (period && period !== currentPeriod) {
          switchPeriod(period);
        }
      });
    });

    // Manual Refresh Button
    const refreshBtn = document.getElementById('btn-manual-refresh');
    if (refreshBtn) {
      refreshBtn.addEventListener('click', () => refreshDashboardData(true));
    }

    // Auto-refresh interval (Default 5 min = 300,000ms, or dynamic from settings)
    const intervalMinutes = parseInt(window.__AUTO_REFRESH_INTERVAL__ || 5, 10);
    const isAutoRefreshEnabled = window.__AUTO_REFRESH_ENABLED__ !== false;

    if (isAutoRefreshEnabled && intervalMinutes > 0) {
      refreshTimer = setInterval(() => {
        refreshDashboardData(false);
      }, intervalMinutes * 60 * 1000);
    }
  });
})();
