document.addEventListener('DOMContentLoaded', () => {
  const toggleBtn = document.getElementById('nav-form-toggle-btn');
  const formContainer = document.getElementById('nav-form-container');
  const cancelBtn = document.getElementById('nav-form-cancel-btn');

  // toggle open/close
  toggleBtn.addEventListener('click', () => {
    const isVisible = formContainer.style.display === 'block';
    formContainer.style.display = isVisible ? 'none' : 'block';
  });

  // cancel button closes it
  cancelBtn.addEventListener('click', () => {
    formContainer.style.display = 'none';
  });

  // optional: close when clicking outside
  document.addEventListener('click', (e) => {
    if (!formContainer.contains(e.target) && e.target !== toggleBtn) {
      formContainer.style.display = 'none';
    }
  });
});

