<?php

$roleNames = [
    1 => 'Admin',
    2 => 'Тестова роль', // якщо буде використовуватись
    3 => 'Mentor',
    4 => 'User',
];

?>

<div class="user-grid">
    <div class="user-grid-row user-grid-header">
        <div>ID</div>
        <div>Прізвище</div>
        <div>Ім'я</div>
        <div>Nickname</div>
        <div>Email</div>
        <div>Роль</div>
        <div>Дії</div>
    </div>

    <div class="user-grid-row user-grid-filter">
        <input class="filter-input" data-col="0" placeholder="ID">
        <input class="filter-input" data-col="1" placeholder="Прізвище">
        <input class="filter-input" data-col="2" placeholder="Ім’я">
        <input class="filter-input" data-col="3" placeholder="Nickname">
        <input class="filter-input" data-col="4" placeholder="Email">
        <input class="filter-input" data-col="5" placeholder="Роль">
        <div></div>
    </div>

    <?php foreach ($users as $user): ?>
        <div class="user-grid-row">
            <div><?= htmlspecialchars($user['id']) ?></div>
            <div><?= htmlspecialchars($user['first_name']) ?></div>
            <div><?= htmlspecialchars($user['last_name']) ?></div>
            <div><?= htmlspecialchars($user['nick_name']) ?></div>
            <div class="email-cell"><?= htmlspecialchars($user['email']) ?></div>
            <div><?= $roleNames[$user['role_id']] ?? 'Невідома' ?></div>
            <div class="action-icons">
                <a href="/admin/load?action=editUser&id=<?= htmlspecialchars($user['id']) ?>"><i class="fas fa-edit" title="Редагувати"></i></a>
                <i class="fas fa-ban" title="Заблокувати"></i>
                <i class="fas fa-trash" title="Видалити"></i>
                <i class="fas fa-user-shield" title="Змінити роль"></i>
            </div>
        </div>
    <?php endforeach; ?>
</div>
