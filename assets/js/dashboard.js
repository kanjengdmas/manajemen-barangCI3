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
    const currentTheme = document.documentElement.getAttribute('data-bs-theme');
    const isDarkish = ['dark', 'navy', 'oled', 'forest'].includes(currentTheme);
    const isSepia = currentTheme === 'sepia';

    let textColor = '#64748b';
    let gridColor = 'rgba(0, 0, 0, 0.04)';
    let tooltipBg = '#0f172a';

    if (isDarkish) {
      textColor = '#94a3b8';
      gridColor = 'rgba(255, 255, 255, 0.06)';
      tooltipBg = currentTheme === 'oled' ? '#121214' : (currentTheme === 'navy' ? '#131f3d' : (currentTheme === 'forest' ? '#0d251d' : '#1e293b'));
    } else if (isSepia) {
      textColor = '#6e6255';
      gridColor = 'rgba(45, 38, 30, 0.06)';
      tooltipBg = '#2d261e';
    }

    return {
      textColor: textColor,
      gridColor: gridColor,
      tooltipBg: tooltipBg,
      masukColor: '#10b981',
      masukBg: isDarkish ? 'rgba(16, 185, 129, 0.75)' : '#10b981',
      keluarColor: '#ef4444',
      keluarBg: isDarkish ? 'rgba(239, 68, 68, 0.75)' : '#ef4444'
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

  const updateStatCard = (id, value) => {
    const el = document.getElementById(id);
    if (!el) return;
    const formatted = Number(value || 0).toLocaleString();
    el.setAttribute('data-stock-value', formatted);
    el.textContent = formatted;
  };

  const updateLastUpdatedTimestamp = () => {
    const badge = document.getElementById('header-live-badge');
    if (!badge) return;

    const now = new Date();
    const h = String(now.getHours()).padStart(2, '0');
    const m = String(now.getMinutes()).padStart(2, '0');
    const s = String(now.getSeconds()).padStart(2, '0');
    const timeStr = `${h}:${m}:${s}`;

    badge.setAttribute('title', `Auto-refresh background polling aktif (${window.__AUTO_REFRESH_INTERVAL__ || 5}m) · Data disinkronkan: ${timeStr}`);

    const dot = badge.querySelector('.live-dot');
    if (dot) {
      dot.classList.add('live-dot-sync');
      setTimeout(() => dot.classList.remove('live-dot-sync'), 1200);
    }
  };

  const renderLowStockTable = (items) => {
    const tbody = document.getElementById('low-stock-tbody');
    const mobileContainer = document.getElementById('low-stock-mobile-cards');
    if (!tbody && !mobileContainer) return;

    if (items.length === 0) {
      const emptyMsg = `
        <div class="text-center py-4 text-muted small">
          <i class="bi bi-check-circle text-success me-1"></i> Semua stok produk dalam batas aman.
        </div>`;
      if (tbody) {
        tbody.innerHTML = `<tr><td colspan="5">${emptyMsg}</td></tr>`;
      }
      if (mobileContainer) {
        mobileContainer.innerHTML = emptyMsg;
      }
      return;
    }

    if (tbody) {
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
    }

    if (mobileContainer) {
      const baseUrl = window.BASE_URL || '';
      mobileContainer.innerHTML = items.map(item => {
        const isHabis = Number(item.stok) <= 0;
        const badgeClass = isHabis ? 'badge-soft-danger' : 'badge-soft-warning';
        const dotClass = isHabis ? 'bg-danger' : 'bg-warning';
        const badgeText = isHabis ? 'Habis' : 'Menipis';

        return `
          <div class="mobile-data-card p-3 mb-0">
            <div class="d-flex align-items-start justify-content-between gap-2 mb-2">
              <div style="min-width: 0;">
                <div class="fw-bold text-truncate" style="color: var(--mb-text-primary); font-size: 0.88rem;">
                  ${escapeHtml(item.nama_produk)}
                </div>
                <span class="sku-badge mt-1" style="font-size: 0.68rem; padding: 1px 6px;">
                  <i class="bi bi-upc-scan me-0.5"></i> ${escapeHtml(item.sku || '-')}
                </span>
              </div>
              <span class="badge-soft ${badgeClass} flex-shrink-0">
                <span class="badge-dot ${dotClass}"></span> ${badgeText}
              </span>
            </div>
            <div class="d-flex align-items-center justify-content-between pt-2 border-top border-subtle">
              <div class="small text-muted">
                Sisa: <strong class="tabular-nums font-monospace ${isHabis ? 'text-danger' : 'text-warning'}">${Number(item.stok).toLocaleString()}</strong>
                <span class="opacity-50">/ min ${Number(item.stok_minimal).toLocaleString()}</span>
              </div>
              <a href="${baseUrl}stok/masuk?produk_id=${item.id}" class="btn btn-sm btn-outline-success py-1 px-2.5 fw-semibold d-inline-flex align-items-center gap-1" style="font-size: 0.74rem;">
                <i class="bi bi-arrow-down-left"></i> Restock
              </a>
            </div>
          </div>`;
      }).join('');
    }
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
    const cards = document.querySelectorAll('#recent-activities-mobile-cards .activity-card-item');
    const filterEmptyRow = document.getElementById('filter-empty-row');
    const filterEmptyMobile = document.getElementById('filter-empty-mobile');

    let visibleRowCount = 0;
    rows.forEach(row => {
      const rowType = row.getAttribute('data-type');
      if (filterType === 'all' || rowType === filterType) {
        row.classList.remove('d-none');
        visibleRowCount++;
      } else {
        row.classList.add('d-none');
      }
    });

    let visibleCardCount = 0;
    cards.forEach(card => {
      const cardType = card.getAttribute('data-type');
      if (filterType === 'all' || cardType === filterType) {
        card.classList.remove('d-none');
        visibleCardCount++;
      } else {
        card.classList.add('d-none');
      }
    });

    if (filterEmptyRow) {
      if (rows.length > 0 && visibleRowCount === 0) {
        filterEmptyRow.classList.remove('d-none');
      } else {
        filterEmptyRow.classList.add('d-none');
      }
    }

    if (filterEmptyMobile) {
      if (cards.length > 0 && visibleCardCount === 0) {
        filterEmptyMobile.classList.remove('d-none');
      } else {
        filterEmptyMobile.classList.add('d-none');
      }
    }
  };

  const renderRecentActivities = (items) => {
    const tbody = document.getElementById('recent-activities-tbody');
    const mobileContainer = document.getElementById('recent-activities-mobile-cards');
    if (!tbody && !mobileContainer) return;

    const countAllBadge = document.getElementById('filter-count-all');
    if (countAllBadge) countAllBadge.textContent = items.length;

    if (items.length === 0) {
      const emptyHtml = `
        <div class="table-empty-state py-4 text-center">
          <div class="table-empty-icon mb-2">
            <i class="bi bi-clock-history fs-2 text-muted"></i>
          </div>
          <h6 class="fw-bold mb-1" style="color: var(--mb-text-primary);">Belum Ada Mutasi</h6>
          <p class="text-muted small mb-0">Belum ada aktivitas transaksi mutasi barang tercatat.</p>
        </div>`;
      if (tbody) {
        tbody.innerHTML = `<tr id="empty-activity-row"><td colspan="6">${emptyHtml}</td></tr>`;
      }
      if (mobileContainer) {
        mobileContainer.innerHTML = emptyHtml;
      }
      return;
    }

    const currentFilter = document.querySelector('#activity-type-filter .dashboard-filter-btn.active')?.getAttribute('data-filter') || 'all';

    if (tbody) {
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
              <p class="text-muted small mb-0" style="font-size: 0.75rem;">Tidak ada transaksi mutasi sesuai filter ini pada 15 aktivitas terakhir.</p>
            </div>
          </td>
        </tr>
      `;
    }

    if (mobileContainer) {
      mobileContainer.innerHTML = items.map(item => {
        const isMasuk = item.tipe === 'masuk';
        const iconClass = isMasuk ? 'in' : 'out';
        const icon = isMasuk ? 'bi-arrow-down-left' : 'bi-arrow-up-right';
        const sign = isMasuk ? '+' : '-';
        const qtyClass = isMasuk ? 'in' : 'out';

        const d = item.tanggal ? new Date(item.tanggal) : new Date();
        const timeStr = d.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' }).replace('.', ':');
        const userName = item.user_name || 'Petugas';
        const isHidden = (currentFilter !== 'all' && item.tipe !== currentFilter) ? 'd-none' : '';

        return `
          <div class="mobile-mutation-card activity-card-item ${isHidden}" data-type="${escapeHtml(item.tipe)}">
            <div class="mobile-mutation-icon ${iconClass}">
              <i class="bi ${icon}"></i>
            </div>
            <div class="mobile-mutation-details">
              <div class="mobile-mutation-prod">${escapeHtml(item.nama_produk || 'Produk')}</div>
              <div class="mobile-mutation-meta">
                <span class="badge-soft badge-soft-secondary font-monospace" style="font-size: 0.68rem;">${escapeHtml(item.sku || '-')}</span>
                ${item.nomor_batch ? `<span class="badge-soft badge-soft-warning font-monospace" style="font-size: 0.68rem;">Lot: ${escapeHtml(item.nomor_batch)}</span>` : ''}
                <span>· ${escapeHtml(userName)}</span>
              </div>
              ${item.catatan ? `<div class="text-muted small mt-1 text-truncate" style="font-size: 0.72rem;"><i class="bi bi-chat-left-text me-1"></i>${escapeHtml(item.catatan)}</div>` : ''}
            </div>
            <div class="mobile-mutation-qty">
              <div class="mobile-qty-pill ${qtyClass}">${sign}${Number(item.jumlah).toLocaleString()}</div>
              <div class="mobile-mutation-time tabular-nums text-muted mt-0.5" style="font-size: 0.68rem;">
                ${escapeHtml(timeStr)}
              </div>
            </div>
          </div>`;
      }).join('') + `
        <div id="filter-empty-mobile" class="d-none table-empty-state py-4 text-center">
          <div class="table-empty-icon mb-2">
            <i class="bi bi-funnel fs-2 text-muted"></i>
          </div>
          <h6 class="fw-bold mb-1" style="color: var(--mb-text-primary);">Tidak Ditemukan</h6>
          <p class="text-muted small mb-0">Tidak ada transaksi mutasi sesuai filter ini pada 15 aktivitas terakhir.</p>
        </div>
      `;
    }

    applyActivityFilter(currentFilter);
  };

  const renderExpiringBatches = (items) => {
    const container = document.getElementById('expiring-batches-container');
    if (!container) return;

    if (items.length === 0) {
      container.innerHTML = `
        <div class="text-center py-4 text-muted">
          <i class="bi bi-shield-check text-success fs-4 d-block mb-1"></i>
          <span class="small">Belum ada antrean batch aktif.</span>
        </div>`;
      return;
    }

    container.innerHTML = items.map(b => {
      const days = parseInt(b.days_in_warehouse !== undefined ? b.days_in_warehouse : (b.days_left || 0), 10);
      const badgeText = b.days_in_warehouse !== undefined
        ? (days > 0 ? `${days} hr di rak` : 'Hari ini')
        : (days < 0 ? 'Expired' : `H-${days}`);

      return `
        <div class="p-2 mb-2 rounded border border-subtle d-flex align-items-center justify-content-between" style="background-color: var(--mb-card-raised);">
          <div>
            <div class="fw-bold small text-truncate" style="max-width: 170px;" title="${escapeHtml(b.nama_produk)}">${escapeHtml(b.nama_produk)}</div>
            <div class="text-muted small tabular-nums font-monospace">${escapeHtml(b.nomor_batch)} · Rak: ${escapeHtml(b.lokasi_rak || '-')}</div>
          </div>
          <div class="text-end">
            <span class="badge-soft badge-soft-danger font-monospace" style="font-size: 0.7rem; padding: 2px 7px;">${badgeText}</span>
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
   * 5. Setup Live Time Clock (Handled globally by theme.js in real-time)
   */
  const startLiveClock = () => {
    // Realtime 1-second clock is now handled globally in theme.js
  };

  // 6. Setup Theme Change Listener on Charts
  window.addEventListener('theme-changed', () => {
    try {
      const colors = getChartColors();
      if (movementChart) {
        movementChart.options.scales.x.ticks.color = colors.textColor;
        movementChart.options.scales.y.ticks.color = colors.textColor;
        movementChart.options.scales.y.grid.color = colors.gridColor;
        movementChart.options.plugins.legend.labels.color = colors.textColor;
        movementChart.update();
      }
      if (kategoriChart) {
        const curTheme = document.documentElement.getAttribute('data-bs-theme');
        let doughnutBorder = '#ffffff';
        if (curTheme === 'oled') doughnutBorder = '#0c0c0e';
        else if (curTheme === 'navy') doughnutBorder = '#131f3d';
        else if (curTheme === 'forest') doughnutBorder = '#0d251d';
        else if (curTheme === 'dark') doughnutBorder = '#111827';
        else if (curTheme === 'sepia') doughnutBorder = '#fffdf9';

        kategoriChart.data.datasets[0].borderColor = doughnutBorder;
        kategoriChart.update();
      }
    } catch (e) {
      console.warn('Chart theme update error ignored:', e);
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
