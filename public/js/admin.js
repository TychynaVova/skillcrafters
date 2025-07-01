// Вихід з акаунту
function logout() {
  fetch("/logout", { method: "POST" })
    .then(() => (window.location.href = "/"))
    .catch(() => alert("Помилка при виході."));
}

// Ініціалізація фільтрів в таблиці користувачів
function initUserTableFilters() {
  const filterInputs = document.querySelectorAll(".filter-input");
  const dataRows = document.querySelectorAll(
    ".user-grid .user-grid-row:not(.user-grid-header):not(.user-grid-filter)"
  );

  if (!filterInputs.length || !dataRows.length) return;

  filterInputs.forEach((input) => {
    input.addEventListener("input", () => {
      const filters = Array.from(filterInputs).map((filter) => ({
        index: parseInt(filter.dataset.col, 10),
        value: filter.value.trim().toLowerCase(),
      }));

      dataRows.forEach((row) => {
        const cells = row.querySelectorAll("div");
        let visible = true;

        filters.forEach((filter) => {
          const cell = cells[filter.index];
          if (!cell || !cell.textContent.toLowerCase().includes(filter.value)) {
            visible = false;
          }
        });

        row.style.display = visible ? "" : "none";
      });
    });
  });
}

document.addEventListener("click", function (e) {
  const navLink = e.target.closest(".nav-link");
  const editLink = e.target.closest(".action-icons a");
  const btnUser = e.target.closest("#updateUser");
  const btnCourse = e.target.closest("#updateCourse");
  const btnCreateCourse = e.target.closest(".toolbar a");
  const btnSaveCourse = e.target.closest("#saveCourse");
  const actionBtn = e.target.closest(".modules-header a");
  const toggleBtn = e.target.closest(".fa-chevron-down");
  const paginationBtn = e.target.closest(".pagination a");

  // Обробка навігаційних лінків з data-action
  if (navLink && navLink.dataset.action) {
    e.preventDefault();

    const action = navLink.dataset.action;

    fetch(`/admin/load?action=${action}`)
      .then((res) => res.text())
      .then((html) => {
        const mainArea = document.querySelector(".main-area");
        if (mainArea) {
          mainArea.innerHTML = html;
          if (action === "users") {
            initUserTableFilters();
          }
        }
      })
      .catch((err) => console.error("Помилка завантаження:", err));
    return;
  }

  if (btnCreateCourse) {
    e.preventDefault();

    fetch(btnCreateCourse.href)
      .then((res) => res.text())
      .then((html) => {
        const mainArea = document.querySelector(".main-area");
        if (mainArea) mainArea.innerHTML = html;
      })
      .catch(console.error);
  }

  if (editLink) {
    e.preventDefault();

    fetch(editLink.href)
      .then((res) => res.text())
      .then((html) => {
        const mainArea = document.querySelector(".main-area");
        if (mainArea) mainArea.innerHTML = html;
      })
      .catch(console.error);
  }

  if (btnUser) {
    e.preventDefault();
    const form = btn.closest("form");
    const formData = new FormData(form);
    fetch("/admin/update?action=updateUser", {
      method: "POST",
      body: formData,
    })
      .then((res) => res.text())
      .then((html) => {
        const mainArea = document.querySelector(".main-area");
        if (mainArea) {
          mainArea.innerHTML = html;
          initUserTableFilters();
        }
      })
      .catch((err) => console.error("Помилка оновлення:", err));
  }

  if (btnCourse) {
    e.preventDefault();
    const form = btnCourse.closest("form");
    const formData = new FormData(form);
    fetch("/admin/update?action=updateCourse", {
      method: "POST",
      body: formData,
    })
      .then((res) => res.text())
      .then((html) => {
        const mainArea = document.querySelector(".main-area");
        if (mainArea) {
          mainArea.innerHTML = html;
          initUserTableFilters();
        }
      })
      .catch((err) => console.error("Помилка оновлення:", err));
  }

  if (btnSaveCourse) {
    e.preventDefault();
    const form = btnSaveCourse.closest("form");
    const formData = new FormData(form);
    fetch("/admin/update?action=saveNewCourse", {
      method: "POST",
      body: formData,
    })
      .then((res) => res.text())
      .then((html) => {
        const mainArea = document.querySelector(".main-area");
        if (mainArea) {
          mainArea.innerHTML = html;
        }
      })
      .catch((err) => console.error("Помилка створення курсу:", err));
  }

  if (actionBtn) {
    e.preventDefault();

    fetch(actionBtn.href)
      .then((res) => res.text())
      .then((html) => {
        const mainArea = document.querySelector(".main-area");
        if (mainArea) mainArea.innerHTML = html;
      })
      .catch(console.error);
  }

  if(toggleBtn) {
    e.preventDefault();
    const card = toggleBtn.closest(".module-card");
    const isActive = card.classList.contains("active");
    document.querySelectorAll(".module-card.active").forEach((openCard) => {
      if (openCard !== card) {
        openCard.classList.remove("active");
      }
    });
    card.classList.toggle("active");
  }

  if (paginationBtn) {
    e.preventDefault();

    fetch(paginationBtn.href)
      .then((res) => res.text())
      .then((html) => {
        const mainArea = document.querySelector(".main-area");
        if (mainArea) mainArea.innerHTML = html;
      })
      .catch(console.error);
  }
});

initUserTableFilters();
