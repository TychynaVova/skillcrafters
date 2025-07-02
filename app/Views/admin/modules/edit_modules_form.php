<?php
$module = $module ?? [];
$courses = $courses ?? [];
$selectedCourseId = $module['course_id'] ?? ($_POST['course_id'] ?? null);
?>

<div class="module-form-container">
    <h2>Редагувати модуль</h2>
    
    <form method="POST" action="/modules/update" class="module-edit-form">
        <input type="hidden" name="id" value="<?= htmlspecialchars($module['id']) ?>">

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
                   value="<?= htmlspecialchars($module['title']) ?>">
        </div>
        
        <div class="form-group">
            <label for="description">Опис модуля:</label>
            <textarea id="description" name="description" rows="6"><?= htmlspecialchars($module['description']) ?></textarea>
        </div>
        
        <div class="form-group">
            <label for="position">Позиція <span style="color:red; font-size:12px; font-weight:300;">(необов'язково)</span>:</label>
            <input type="number" id="position" name="position" class="form-control"
                   value="<?= htmlspecialchars($module['position']) ?>">
            <small class="form-text" style="color:red; font-size:12px;">*Залиште порожнім для збереження існуючої позиції або автоматичного заповнення</small>
        </div>
        
        <div class="form-actions">
            <button type="submit" class="btn btn-primary">Зберегти модуль</button>
            <a href="/modules/list?action=getAll&id=<?= $module['course_id'] ?>" class="nav-link btn btn-secondary" data-action="courses">
                <i class="fas fa-arrow-left"></i> До списку модулів
            </a>
        </div>
    </form>
</div>
    

    
