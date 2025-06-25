// Вихід з акаунту
function logout() {
  fetch("/logout", { method: "POST" })
    .then(() => (window.location.href = "/"))
    .catch(() => alert("Помилка при виході."));
}

// Ініціалізація фільтрів в таблиці користувачів
function initUserTableFilters() {
  const filterInputs = document.querySelectorAll('.filter-input');
  const dataRows = document.querySelectorAll('.user-grid .user-grid-row:not(.user-grid-header):not(.user-grid-filter)');

  if (!filterInputs.length || !dataRows.length) return;

  filterInputs.forEach(input => {
    input.addEventListener('input', () => {
      const filters = Array.from(filterInputs).map(filter => ({
        index: parseInt(filter.dataset.col, 10),
        value: filter.value.trim().toLowerCase()
      }));

      dataRows.forEach(row => {
        const cells = row.querySelectorAll('div');
        let visible = true;

        filters.forEach(filter => {
          const cell = cells[filter.index];
          if (!cell || !cell.textContent.toLowerCase().includes(filter.value)) {
            visible = false;
          }
        });

        row.style.display = visible ? '' : 'none';
      });
    });
  });
}

// Делегування кліків по .nav-link і .action-icons a
document.addEventListener('click', function(e) {
  const navLink = e.target.closest('.nav-link');
  const editLink = e.target.closest('.action-icons a');

  // Обробка навігаційних лінків з data-action
  if (navLink && navLink.dataset.action) {
    e.preventDefault();

    const action = navLink.dataset.action;

    fetch(`/admin/load?action=${action}`)
      .then(res => res.text())
      .then(html => {
        const mainArea = document.querySelector('.main-area');
        if (mainArea) {
          mainArea.innerHTML = html;
          if (action === 'users') {
            initUserTableFilters();
          }
        }
      })
      .catch(err => console.error('Помилка завантаження:', err));
    return;
  }

  // Обробка переходу на сторінку редагування
  if (editLink) {
    e.preventDefault();

    fetch(editLink.href)
      .then(res => res.text())
      .then(html => {
        const mainArea = document.querySelector('.main-area');
        if (mainArea) mainArea.innerHTML = html;
      })
      .catch(console.error);
  }
});

// Початкова ініціалізація (наприклад, для фільтрів якщо одразу показані юзери)
initUserTableFilters();
