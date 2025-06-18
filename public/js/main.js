document.addEventListener("DOMContentLoaded", () => {
  // --- Елементи модалки ---
  const modal = document.getElementById("modal");
  const modalContent = document.getElementById("modal-content");
  const openModalBtn = document.getElementById("openModal");
  const closeModalBtn = document.getElementById("closeModal");

  // --- Форми та переключення ---
  const loginForm = document.getElementById("div-login-form");
  const registerForm = document.getElementById("div-register-form");
  const switchToRegister = document.getElementById("switchToRegister");
  const switchToLogin = document.getElementById("switchToLogin");

  // --- Повідомлення ---
  const registerMessage = document.getElementById("message");
  const confirmMessage = document.getElementById("message-confirm");

  // --- Форма підтвердження паролю ---
  const confirmForm = document.getElementById("confirm-form");
  const first_name = document.getElementById("confirm-first-name");
  const last_name = document.getElementById("confirm-last-name");
  const nick_name = document.getElementById("confirm-nick-name");
  const passwordInput1 = document.getElementById("confirm-password");
  const passwordInput2 = document.getElementById("confirm-password2");
  const confirmTokenInput = document.getElementById("confirm-token");

  // Відкриття модалки
  if (openModalBtn) {
    openModalBtn.addEventListener("click", () => {
      modal.style.display = "block";
    });
  }

  // Закриття модалки
  if (closeModalBtn) {
    closeModalBtn.addEventListener("click", () => {
      modal.style.display = "none";
    });
  }

  // Закриття при кліку поза модалкою
  let isDragging = false;

    modal.addEventListener("mousedown", () => {
    isDragging = false;
    });

    modal.addEventListener("mousemove", () => {
    isDragging = true;
    });

    modal.addEventListener("mouseup", (e) => {
    // Якщо курсор був перетягнутий (тобто відбувалось виділення), то не закриваємо
    if (!isDragging && e.target === modal) {
        modal.style.display = "none";
    }
    });

  // Переключення між логіном і реєстрацією
  if (switchToRegister && switchToLogin && loginForm && registerForm) {
    switchToRegister.addEventListener("click", () => {
      loginForm.style.display = "none";
      registerForm.style.display = "block";
      if (registerMessage) registerMessage.style.display = "none";
    });
    switchToLogin.addEventListener("click", () => {
      loginForm.style.display = "block";
      registerForm.style.display = "none";
      if (registerMessage) registerMessage.style.display = "none";
    });
  }

  // Обробка форми логіну
  const loginFormElement = document.querySelector("#div-login-form form");
  if (loginFormElement) {
    loginFormElement.addEventListener("submit", async (e) => {
      e.preventDefault();
      const email = document.getElementById("login-email").value;
      const password = document.getElementById("login-password").value;

      try {
        const response = await fetch("/auth/login", {
          method: "POST",
          headers: { "Content-Type": "application/json" },
          body: JSON.stringify({ email, password }),
        });

        const result = await response.json();
        console.log(result);
        // TODO: Реальна обробка результату
      } catch (err) {
        console.error("Ошибка при логине:", err);
      }
    });
  }

  // Обробка форми реєстрації
  const registerFormElement = document.querySelector("#div-register-form form");
  if (registerFormElement) {
    registerFormElement.addEventListener("submit", async (e) => {
      e.preventDefault();
      const email = document.getElementById("register-email").value;

      try {
        const response = await fetch("/register", {
          method: "POST",
          headers: { "Content-Type": "application/json" },
          body: JSON.stringify({ email }),
        });

        const result = await response.json();
        console.log(result);

        if (result.status === "success") {
          registerForm.style.display = "none";
          const descRegister = document.getElementById("desc-register-form");
          if (descRegister) descRegister.style.display = "none";

          if (registerMessage) {
            registerMessage.style.display = "block";
            registerMessage.innerHTML = result.message;
            registerMessage.style.color = "green";
          }
        } else if (registerMessage) {
          registerMessage.style.display = "block";
          registerMessage.innerHTML = result.message;
          registerMessage.style.color = "red";
        }
      } catch (err) {
        console.error("Ошибка при регистрации:", err);
      }
    });
  }

  // Логіка підтвердження email та паролю через action і token з сервера
  const action = window.pageAction;
  const token = window.pageToken;

  if (
    action === "confirmEmail" &&
    token &&
    confirmForm &&
    passwordInput1 &&
    passwordInput2 &&
    confirmTokenInput
  ) {
    // Ховаємо інші форми
    if (loginForm) loginForm.style.display = "none";
    if (registerForm) registerForm.style.display = "none";

    // Показуємо форму підтвердження пароля
    confirmForm.parentElement.style.display = "block"; // div-confirm-form

    // Встановлюємо токен у приховане поле
    confirmTokenInput.value = token;

    // Відкриваємо модалку
    modal.style.display = "block";

    // Валідація паролів при вводі другого поля
    passwordInput2.addEventListener("input", () => {
      if (
        passwordInput2.value.length > 0 &&
        passwordInput1.value !== passwordInput2.value
      ) {
        confirmMessage.style.display = "block";
        confirmMessage.innerText = "Пароли не совпадают";
        confirmMessage.style.color = "red";
      } else {
        confirmMessage.style.display = "none";
      }
    });

    // Сабміт форми підтвердження пароля
    confirmForm.addEventListener("submit", async (e) => {
      e.preventDefault();

      const firstName = first_name.value;
      const lastName = last_name.value;
      const nickName = nick_name.value;
      const pass1 = passwordInput1.value;
      const pass2 = passwordInput2.value;
      const tokenValue = confirmTokenInput.value;

      confirmMessage.style.display = "block";

      if (pass1 !== pass2) {
        confirmMessage.innerText = "Пароли не совпадают";
        confirmMessage.style.color = "red";
        return;
      }

      const loader = document.getElementById("loader");
      loader.style.display = "block";

      try {
        const response = await fetch("/confirmRegister", {
          method: "POST",
          headers: { "Content-Type": "application/json" },
          body: JSON.stringify({
            token: tokenValue,
            password: pass1,
            first_name: firstName,
            last_name: lastName,
            nick_name: nickName,
          }),
        });

        const result = await response.json();
        loader.style.display = "none";
        confirmMessage.innerText = result.message;
        confirmMessage.style.color =
          response.ok && result.status === "success" ? "green" : "red";

        if (response.ok && result.status === "success") {
          confirmForm.parentElement.style.display = "none";

          // Записуємо куку сесії на 30 хв (30*60 = 1800 секунд)
          document.cookie =
            "sessionValid=true; max-age=1800; path=/; Secure; SameSite=Lax";
          setTimeout(() => {
            window.location.href = "/dashboardUser"; // URL особистого кабінету, зміни якщо потрібно
          }, 1000);
        }
      } catch (err) {
        confirmMessage.innerText = "Ошибка запроса";
        confirmMessage.style.color = "red";
      }
    });
  }

  //Закрытие модального окна с ошибкой
  const closeError = document.getElementById("closeErrorModal");
    if (closeError) {
        closeError.addEventListener("click", function () {
            const overlay = document.querySelector(".error-modal-overlay");
            if (overlay) {
                overlay.style.display = "none";
                window.location.href = '/';
            }
        });
    }
});
