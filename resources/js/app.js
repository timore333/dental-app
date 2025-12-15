// Import CSS
import '../css/app.css'

// Initialize Soft-UI Components on DOM Ready
document.addEventListener('DOMContentLoaded', function() {
  initializeTooltips()
  initializePopovers()
  initializeThemeToggle()
})

// Initialize Tooltips
function initializeTooltips() {
  const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
  tooltipTriggerList.map(function(tooltipTriggerEl) {
    if (typeof bootstrap !== 'undefined' && bootstrap.Tooltip) {
      return new bootstrap.Tooltip(tooltipTriggerEl)
    }
  })
}

// Initialize Popovers
function initializePopovers() {
  const popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'))
  popoverTriggerList.map(function(popoverTriggerEl) {
    if (typeof bootstrap !== 'undefined' && bootstrap.Popover) {
      return new bootstrap.Popover(popoverTriggerEl)
    }
  })
}

// Theme Toggle
function initializeThemeToggle() {
  const themeToggle = document.getElementById('theme-toggle')
  if (!themeToggle) return

  const isDarkMode = localStorage.getItem('darkMode') === 'true'
  if (isDarkMode) {
    document.body.classList.add('dark-mode')
    themeToggle.checked = true
  }

  themeToggle.addEventListener('change', function() {
    document.body.classList.toggle('dark-mode')
    localStorage.setItem('darkMode', this.checked)
  })
}

// CSRF Token Setup for AJAX
if (typeof window !== 'undefined') {
  window.axios = window.axios || {}

  if (window.axios.defaults) {
    window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest'
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content
    if (csrfToken) {
      window.axios.defaults.headers.common['X-CSRF-TOKEN'] = csrfToken
    }
  }
}
