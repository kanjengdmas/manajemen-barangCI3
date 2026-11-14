/**
 * Manajemen Barang — CRUD Pop-up & Notification Handler
 * Provides SweetAlert2 popups for all CRUD operations:
 * - Success/Error flash notifications on Create, Update, Delete
 * - Interactive Delete Confirmation modal dialogs
 * - Form submit loading feedback to prevent duplicate submissions
 * - Theme-adaptive styling (Crimson, Sepia, Dark, Navy, OLED, Forest)
 */

(function () {
  'use strict';

  // Helper to format notification options with theme awareness
  function getSwalBaseConfig() {
    return {
      customClass: {
        popup: 'swal2-custom-popup',
        confirmButton: 'btn btn-danger px-4 py-2 fw-semibold rounded-pill',
        cancelButton: 'btn btn-secondary px-4 py-2 fw-semibold rounded-pill',
        actions: 'gap-2'
      },
      buttonsStyling: false
    };
  }

  // 1. Process server-side flashdata on page load
  function showFlashAlerts() {
    if (typeof Swal === 'undefined') {
      console.warn('SweetAlert2 is not loaded.');
      return;
    }

    const flash = window.FLASH_DATA || {};

    if (flash.success) {
      Swal.fire({
        ...getSwalBaseConfig(),
        icon: 'success',
        title: 'Berhasil!',
        text: flash.success,
        timer: 3500,
        timerProgressBar: true,
        confirmButtonText: '<i class="bi bi-check2 me-1"></i> Selesai',
        customClass: {
          popup: 'swal2-custom-popup',
          confirmButton: 'btn btn-danger px-4 py-2 fw-semibold rounded-pill'
        }
      });
    } else if (flash.error) {
      Swal.fire({
        ...getSwalBaseConfig(),
        icon: 'error',
        title: 'Perhatian / Terjadi Kesalahan',
        text: flash.error,
        confirmButtonText: 'Periksa Kembali',
        customClass: {
          popup: 'swal2-custom-popup',
          confirmButton: 'btn btn-danger px-4 py-2 fw-semibold rounded-pill'
        }
      });
    } else if (flash.warning) {
      Swal.fire({
        ...getSwalBaseConfig(),
        icon: 'warning',
        title: 'Peringatan Sistem',
        text: flash.warning,
        confirmButtonText: 'Mengerti',
        customClass: {
          popup: 'swal2-custom-popup',
          confirmButton: 'btn btn-warning px-4 py-2 fw-semibold rounded-pill text-dark'
        }
      });
    } else if (flash.info) {
      Swal.fire({
        ...getSwalBaseConfig(),
        icon: 'info',
        title: 'Informasi',
        text: flash.info,
        confirmButtonText: 'Tutup',
        customClass: {
          popup: 'swal2-custom-popup',
          confirmButton: 'btn btn-primary px-4 py-2 fw-semibold rounded-pill'
        }
      });
    }
  }

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', showFlashAlerts);
  } else {
    showFlashAlerts();
  }

  // 2. Universal Delete Confirmation Modal Handler
  document.addEventListener('click', function (e) {
    const deleteBtn = e.target.closest('.btn-delete-confirm, .btn-delete, [data-confirm-delete], a[href*="/delete/"]');
    if (!deleteBtn) return;

    // Prevent default anchor navigation
    e.preventDefault();
    const deleteUrl = deleteBtn.getAttribute('href');
    if (!deleteUrl || deleteUrl === '#' || deleteUrl.startsWith('javascript:')) return;

    const itemName = deleteBtn.getAttribute('data-confirm-name') || deleteBtn.getAttribute('data-name') || '';
    const customText = deleteBtn.getAttribute('data-confirm-text');
    const itemType = deleteBtn.getAttribute('data-confirm-type') || 'data';

    let messageHtml = '';
    if (customText) {
      messageHtml = `<p class="mb-0 text-muted" style="font-size: 0.95rem;">${customText}</p>`;
    } else if (itemName) {
      messageHtml = `
        <p class="mb-1 text-muted" style="font-size: 0.95rem;">
          Apakah Anda yakin ingin menghapus <strong>${itemName}</strong>?
        </p>
        <small class="text-danger d-block">
          <i class="bi bi-exclamation-triangle me-1"></i> Data yang dihapus tidak dapat dipulihkan kembali.
        </small>
      `;
    } else {
      messageHtml = `
        <p class="mb-1 text-muted" style="font-size: 0.95rem;">
          Apakah Anda yakin ingin menghapus ${itemType} ini?
        </p>
        <small class="text-danger d-block">
          <i class="bi bi-exclamation-triangle me-1"></i> Data yang dihapus tidak dapat dipulihkan kembali.
        </small>
      `;
    }

    if (typeof Swal === 'undefined') {
      if (confirm('Apakah Anda yakin ingin menghapus data ini?')) {
        window.location.href = deleteUrl;
      }
      return;
    }

    Swal.fire({
      ...getSwalBaseConfig(),
      title: 'Konfirmasi Hapus Data',
      html: `<div class="text-center py-2">${messageHtml}</div>`,
      icon: 'warning',
      showCancelButton: true,
      confirmButtonText: '<i class="bi bi-trash-fill me-1"></i> Ya, Hapus!',
      cancelButtonText: 'Batal',
      reverseButtons: true,
      focusCancel: true,
      allowOutsideClick: false
    }).then((result) => {
      if (result.isConfirmed) {
        // Show loading state while redirecting
        Swal.fire({
          ...getSwalBaseConfig(),
          title: 'Menghapus Data...',
          text: 'Mohon tunggu sebentar, sistem sedang memproses.',
          allowOutsideClick: false,
          allowEscapeKey: false,
          showConfirmButton: false,
          didOpen: () => {
            Swal.showLoading();
          }
        });
        window.location.href = deleteUrl;
      }
    });
  });

  // 3. Form Submission Loading Pop-up (for standard POST CRUD forms)
  document.addEventListener('submit', function (e) {
    if (e.defaultPrevented) return; // Skip if handled via AJAX

    const form = e.target;
    if (!form || form.tagName !== 'FORM') return;

    // Skip GET forms (search, filter), forms with data-no-popup, or AJAX forms
    if (form.method && form.method.toLowerCase() === 'get') return;
    if (form.classList.contains('no-loading-popup') || form.hasAttribute('data-no-popup')) return;
    if (form.classList.contains('ajax-form')) return;

    // Check validity first
    if (form.checkValidity && !form.checkValidity()) {
      return; // Let browser HTML5 validation show native prompt
    }

    if (typeof Swal !== 'undefined') {
      Swal.fire({
        ...getSwalBaseConfig(),
        title: 'Menyimpan Data...',
        text: 'Mohon tunggu sebentar, sistem sedang memproses.',
        allowOutsideClick: false,
        allowEscapeKey: false,
        showConfirmButton: false,
        didOpen: () => {
          Swal.showLoading();
        }
      });
    }
  });

  // Global helper if other modules want to invoke popups manually
  window.mbAlert = {
    success: function (title, message) {
      if (typeof Swal === 'undefined') return alert(message || title);
      Swal.fire({
        ...getSwalBaseConfig(),
        icon: 'success',
        title: title || 'Berhasil!',
        text: message || '',
        timer: 3000,
        timerProgressBar: true,
        confirmButtonText: 'Selesai'
      });
    },
    error: function (title, message) {
      if (typeof Swal === 'undefined') return alert(message || title);
      Swal.fire({
        ...getSwalBaseConfig(),
        icon: 'error',
        title: title || 'Perhatian / Terjadi Kesalahan',
        text: message || '',
        confirmButtonText: 'Tutup'
      });
    },
    warning: function (title, message) {
      if (typeof Swal === 'undefined') return alert(message || title);
      Swal.fire({
        ...getSwalBaseConfig(),
        icon: 'warning',
        title: title || 'Peringatan',
        text: message || '',
        confirmButtonText: 'Mengerti'
      });
    }
  };
})();
