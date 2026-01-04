/**
 * Main JavaScript for Bibliotheque
 */

document.addEventListener("DOMContentLoaded", function () {
  // Auto-hide alerts after 5 seconds
  const alerts = document.querySelectorAll(".alert:not(.alert-permanent)");
  alerts.forEach((alert) => {
    setTimeout(() => {
      const bsAlert = new bootstrap.Alert(alert);
      bsAlert.close();
    }, 5000);
  });

  // Confirm delete actions
  const deleteButtons = document.querySelectorAll("[data-confirm-delete]");
  deleteButtons.forEach((button) => {
    button.addEventListener("click", function (e) {
      if (!confirm("Êtes-vous sûr de vouloir supprimer cet élément ?")) {
        e.preventDefault();
      }
    });
  });

  // Form validation
  const forms = document.querySelectorAll(".needs-validation");
  forms.forEach((form) => {
    form.addEventListener("submit", function (e) {
      if (!form.checkValidity()) {
        e.preventDefault();
        e.stopPropagation();
      }
      form.classList.add("was-validated");
    });
  });

  // Password strength indicator
  const passwordInput = document.getElementById("new_password");
  if (passwordInput) {
    passwordInput.addEventListener("input", function () {
      const strength = checkPasswordStrength(this.value);
      updatePasswordStrengthIndicator(strength);
    });
  }

  // Image preview
  const imageInputs = document.querySelectorAll(
    'input[type="file"][accept*="image"]'
  );
  imageInputs.forEach((input) => {
    input.addEventListener("change", function (e) {
      const file = e.target.files[0];
      if (file) {
        const reader = new FileReader();
        reader.onload = function (e) {
          const preview = document.getElementById("image-preview");
          if (preview) {
            preview.innerHTML = `<img src="${e.target.result}" class="img-fluid rounded mt-2" alt="Preview">`;
          }
        };
        reader.readAsDataURL(file);
      }
    });
  });

  // Tooltip initialization
  const tooltipTriggerList = [].slice.call(
    document.querySelectorAll('[data-bs-toggle="tooltip"]')
  );
  tooltipTriggerList.map(function (tooltipTriggerEl) {
    return new bootstrap.Tooltip(tooltipTriggerEl);
  });

  // Popover initialization
  const popoverTriggerList = [].slice.call(
    document.querySelectorAll('[data-bs-toggle="popover"]')
  );
  popoverTriggerList.map(function (popoverTriggerEl) {
    return new bootstrap.Popover(popoverTriggerEl);
  });
});

/**
 * Check password strength
 */
function checkPasswordStrength(password) {
  let strength = 0;

  if (password.length >= 8) strength++;
  if (password.match(/[a-z]/)) strength++;
  if (password.match(/[A-Z]/)) strength++;
  if (password.match(/[0-9]/)) strength++;
  if (password.match(/[^a-zA-Z0-9]/)) strength++;

  return strength;
}

/**
 * Update password strength indicator
 */
function updatePasswordStrengthIndicator(strength) {
  const indicator = document.getElementById("password-strength");
  if (!indicator) return;

  const labels = ["Très faible", "Faible", "Moyen", "Fort", "Très fort"];
  const colors = ["danger", "warning", "info", "success", "success"];

  indicator.className = `badge bg-${colors[strength - 1]}`;
  indicator.textContent = labels[strength - 1] || "";
}

/**
 * Debounce function for search inputs
 */
function debounce(func, wait) {
  let timeout;
  return function executedFunction(...args) {
    const later = () => {
      clearTimeout(timeout);
      func(...args);
    };
    clearTimeout(timeout);
    timeout = setTimeout(later, wait);
  };
}
