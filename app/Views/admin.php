<!DOCTYPE html>
<html lang="uk">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Admin Dashboard - SkillCrafters</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" />
    <link rel="stylesheet" href="../../public/css/admin.css">
</head>

<body>

    <div class="sidebar">
        <h2 class="de">Меню</h2>
        <h2 class="mo">М</h2>
        <nav class="nav_menu">
            <ul class="nav__list">
                <li>
                    <a href="?action=users" class="nav-link" data-action="users">
                        <i class="fas fa-users"></i>
                        <span class="link_name">Користувачі</span>
                    </a>
                    <span class="tooltip">Користувачі</span>
                </li>
                <!-- Додаткові пункти можна додати пізніше -->
            </ul>
        </nav>

    </div>

    <div class="content">
        <div class="header">
            <div class="logo">
                <img src="/public/img/logo_black.svg" alt="SkillCrafters" height="30">
            </div>
            <button class="logout-btn" onclick="logout()" title="Вийти">
                <i class="fas fa-sign-out-alt"></i>
            </button>
        </div>

        <div class="main-area">
            <h1>Панель адміністратора</h1>
            <p>Оберіть дію з меню ліворуч.</p>
        </div>
    </div>

    <script src="../../public/js/admin.js"></script>

</body>

</html>