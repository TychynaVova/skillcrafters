<?php

session_start();
if (isset($_SESSION['flash_message'])):
?>
<script>
  alert(<?= json_encode($_SESSION['flash_message']) ?>);
</script>
<?php
unset($_SESSION['flash_message']);
endif;

$status = $status ?? null;

switch ($status) {
    case 'error':
        $error = true;
        break;
    case 'success':
        $error = false;
        break;
}

?>
<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>SkillCrafters | Onlin Education</title>

    <link rel="stylesheet" href="../../public/css/main.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script>
        window.pageAction = <?= isset($action) ? json_encode($action) : 'null' ?>;
        window.pageToken = <?= isset($token) ? json_encode($token) : 'null' ?>;
    </script>

</head>

<body>

    <header>
        <div class="logo">
            <img src="/public/img/logo_black.svg" alt="Header Logo" width="300" height="50">
        </div>
        <nav class="menu">
            <a href="#section1">Секция 1</a>
            <a href="#section2">Секция 2</a>
            <a href="#section3">Секция 3</a>
        </nav>
        <div class="icons">
            <a class="icon" id="openModal"><i class="fas fa-user"></i></a>
        </div>
    </header>

    <div id="loader" style="display:none;">Завантаження...</div>
    <!-- Секции -->
    <section id="section1">
        <h2>Секция 1</h2>
        <p>Контент секции 1...</p>
    </section>
    <section id="section2">
        <h2>Секция 2</h2>
        <p>Контент секции 2...</p>
    </section>
    <section id="section3">
        <h2>Секция 3</h2>
        <p>Контент секции 3...</p>
    </section>


    <!-- Форма ошибки -->
    <?php if (isset($error) && $error): ?>
        <div class="error-modal-overlay">
            <div class="error-modal">
                <span class="error-close" id="closeErrorModal">&times;</span>
                <div class="error-modal-content"><?= htmlspecialchars($message); ?></div>
            </div>
        </div>
    <?php endif; ?>

    <!-- Модальное окно -->
    <div id="modal" class="modal">
        <div class="modal-content" id="modal-content">
            <span class="close-btn" id="closeModal">&times;</span>
            <div id="modal-form">

                <!-- Форма логина -->
                <div id="div-login-form">
                    <h2>Логин</h2>
                    <div id="message-login" style="display: none;" class="form-message error"></div>
                    <form action="/login" method="POST">
                        <input type="hidden" id="loginForm" name="action" value="login">
                        <label for="login-email">Email</label>
                        <input type="email" id="login-email" placeholder="Введите ваш email" required>
                        <label for="login-password">Пароль</label>
                        <input type="password" id="login-password" placeholder="Введите ваш пароль" required>
                        <button type="submit">Войти</button>
                    </form>
                    <p>Нет аккаунта? <span id="switchToRegister">Зарегистрируйтесь</span></p>
                </div>

                <!-- Форма регистрации -->
                <div id="div-register-form" style="display: none;">
                    <h2>Регистрация</h2>
                    <div id="message" style="display: none;"></div>
                    <form method="POST" id="register-form">
                        <label for="register-email">Введите ваш email:</label>
                        <input type="email" id="register-email" name="email" required>
                        <button type="submit">Зарегистрироваться</button>
                    </form>

                    <p id="desc-register-form">Уже есть аккаунт? <span id="switchToLogin">Войти</span></p>
                </div>

                <div id="div-confirm-form" style="display: none;">
                    <h2 style="padding-bottom: 20px;">Завершение регистрации</h2>
                    <div id="message-confirm" style="display: none;" class="form-message error"></div>
                    <form method="POST" id="confirm-form" class="styled-form">
                        <input type="hidden" id="confirm-token" name="token" value="">

                        <div class="form-group">
                            <div class="input-wrapper">
                                <i class="fas fa-user"></i>
                                <input type="text" id="confirm-first-name" name="first_name" placeholder="Прізвище (латиницей)" pattern="[A-Za-z]+" required>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="input-wrapper">
                                <i class="fas fa-user"></i>
                                <input type="text" id="confirm-last-name" name="last_name" placeholder="Ім'я (латиницей)" pattern="[A-Za-z]+" required>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="input-wrapper">
                                <i class="fas fa-id-badge"></i>
                                <input type="text" id="confirm-nick-name" name="nick_name" placeholder="Nickname">
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="input-wrapper">
                                <i class="fas fa-lock"></i>
                                <input type="password" id="confirm-password" name="password" placeholder="Введите ваш пароль" required>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="input-wrapper">
                                <i class="fas fa-lock"></i>
                                <input type="password" id="confirm-password2" name="password2" placeholder="Повторите ваш пароль" required>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="role_id"><i class="fas fa-users"></i> Выберите роль:</label>
                            <select name="role_id" id="role_id" required>
                                <option value="4">Обычный пользователь</option>
                                <option value="2">Тест</option>
                                <option value="3">Ментор</option>
                            </select>
                        </div>

                        <button type="submit" class="form-button"><i class="fas fa-check-circle"></i> Подтвердить</button>
                    </form>

                </div>

            </div>
        </div>
    </div>

    <script src="../../public/js/main.js"></script>
</body>

</html>