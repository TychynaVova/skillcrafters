<h1>Редагування користувача #<?= htmlspecialchars($user['id']) ?></h1>

<form method="POST" action="/user/update" class="user-edit-form">
    <input type="hidden" name="id" value="<?= htmlspecialchars($user['id']) ?>" />

    <div class="form-group">
        <label for="first_name">Ім'я</label>
        <input id="first_name" type="text" name="first_name" value="<?= htmlspecialchars($user['first_name']) ?>" required />
    </div>

    <div class="form-group">
        <label for="last_name">Прізвище</label>
        <input id="last_name" type="text" name="last_name" value="<?= htmlspecialchars($user['last_name']) ?>" required />
    </div>

    <div class="form-group">
        <label for="nick_name">Нікнейм</label>
        <input id="nick_name" type="text" name="nick_name" value="<?= htmlspecialchars($user['nick_name']) ?>" />
    </div>

    <div class="form-group">
        <label for="email">Email</label>
        <input id="email" type="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" required />
    </div>

    <div class="form-group">
        <label for="role_id">Роль</label>
        <select id="role_id" name="role_id" required>
            <option value="1" <?= $user['role_id'] == '1' ? 'selected' : '' ?>>Адмін</option>
            <option value="2" <?= $user['role_id'] == '2' ? 'selected' : '' ?>>Модератор</option>
            <option value="3" <?= $user['role_id'] == '3' ? 'selected' : '' ?>>Користувач</option>
            <!-- Додай інші ролі, якщо потрібно -->
        </select>
    </div>

    <div class="form-group">
        <label for="status">Статус</label>
        <select id="status" name="status" required>
            <option value="active" <?= $user['status'] === 'active' ? 'selected' : '' ?>>Активний</option>
            <option value="blocked" <?= $user['status'] === 'blocked' ? 'selected' : '' ?>>Заблокований</option>
        </select>
    </div>

    <div class="form-group">
        <label for="blocked_reason">Причина блокування</label>
        <textarea id="blocked_reason" name="blocked_reason" rows="4"><?= htmlspecialchars($user['blocked_reason'] ?? '') ?></textarea>
    </div>

    <div class="form-buttons">
        <button type="submit" class="btn btn-primary">Зберегти</button>
        <a href="/admin/load?action=users" class="nav-link btn btn-secondary" data-action="users">
            <span class="link_name">Повернутися до списку</span>
        </a>
    </div>
</form>
