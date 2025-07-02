<?php
$courses = $courses ?? [];
$selectedCourseId = $id ?? null;
?>

<h2>Додати новий модуль</h2>

<div class="module-form-container">
    <form method="POST" action="/modules/add" class="module-form">
        <div class="form-group">
            <label for="course_id">Курс:</label>
            <select id="course_id" name="course_id" required class="form-control">
                <option value="">-- Оберіть курс --</option>
                <?php foreach ($courses as $course): ?>
                    <option value="<?= $course['id'] ?>" 
                        <?= $course['id'] == $selectedCourseId ? 'selected' : '' ?>>
                        <?= htmlspecialchars($course['title']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        
        <div class="form-group">
            <label for="title">Назва модуля:</label>
            <input type="text" id="title" name="title" required class="form-control"
                   value="<?= htmlspecialchars($_POST['title'] ?? '') ?>">
        </div>
        
        <div class="form-group">
            <label for="description">Опис модуля:</label>
            <textarea id="description" name="description" rows="5" class="form-control"><?= 
                htmlspecialchars($_POST['description'] ?? '') 
            ?></textarea>
        </div>
        
        <div class="form-group">
            <label for="position">Позиція (необов'язково):</label>
            <input type="number" id="position" name="position" class="form-control"
                   value="<?= htmlspecialchars($_POST['position'] ?? '') ?>">
            <small class="form-text">Залиште порожнім для автоматичного додавання в кінець</small>
        </div>
        
        <div class="form-actions">
            <button type="submit" class="btn btn-primary">Додати модуль</button>
            <a href="<?= $selectedCourseId ? "/courses/{$selectedCourseId}/modules" : "/courses" ?>" 
               class="btn btn-secondary">Скасувати</a>
        </div>
    </form>
</div>

<style>
.module-form-container {
    max-width: 800px;
    margin: 0 auto;
    padding: 20px;
    background: #fff;
    border-radius: 8px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.module-form {
    display: grid;
    gap: 20px;
}

.form-group {
    display: flex;
    flex-direction: column;
    gap: 8px;
}

.form-group label {
    font-weight: 600;
    color: #333;
}

.form-control {
    padding: 10px 12px;
    border: 1px solid #ddd;
    border-radius: 4px;
    font-size: 16px;
}

.form-control:focus {
    border-color: #007bff;
    outline: none;
    box-shadow: 0 0 0 2px rgba(0,123,255,0.25);
}

.form-actions {
    display: flex;
    gap: 10px;
    justify-content: flex-end;
    margin-top: 20px;
}

.btn {
    padding: 10px 20px;
    border-radius: 4px;
    cursor: pointer;
    font-size: 16px;
    transition: all 0.3s;
}

.btn-primary {
    background: #007bff;
    color: white;
    border: none;
}

.btn-primary:hover {
    background: #0069d9;
}

.btn-secondary {
    background: #6c757d;
    color: white;
    border: none;
}

.btn-secondary:hover {
    background: #5a6268;
}
</style>