<h1>Редагування курса #<?= htmlspecialchars($course['id']) ?></h1>

<form enctype="multipart/form-data" class="course-edit-form">
    <input type="hidden" name="id" value="<?= htmlspecialchars($course['id']) ?>">

    <div class="form-group">
        <label for="title">Назва курсу *</label>
        <input type="text" name="title" id="title" required value="<?= htmlspecialchars($course['title']) ?>">
    </div>

    <div class="form-group">
        <label for="slug">URL-дружнє ім’я</label>
        <input type="text" name="slug" id="slug" value="<?= htmlspecialchars($course['slug']) ?>">
    </div>

    <div class="form-group">
        <label for="description">Опис</label>
        <textarea name="description" id="description" rows="6"><?= htmlspecialchars($course['description']) ?></textarea>
    </div>

    <div class="form-group">
        <label for="image">Зображення (jpg/png)</label>
        <?php if (!empty($course['image'])): ?>
            <div class="course-image-preview">
                <img src="/public<?= htmlspecialchars($course['image']) ?>" alt="Зображення курсу" style="max-width: 200px;">
            </div>
        <?php endif; ?>
        <input type="file" name="image" id="image">
    </div>

    <div class="form-group">
        <label for="status">Статус</label>
        <select name="status" id="status">
            <option value="draft" <?= $course['status'] === 'draft' ? 'selected' : '' ?>>Чернетка</option>
            <option value="published" <?= $course['status'] === 'published' ? 'selected' : '' ?>>Опублікований</option>
            <option value="archived" <?= $course['status'] === 'archived' ? 'selected' : '' ?>>Архівований</option>
        </select>
    </div>

    <div class="form-group">
        <label for="price">Ціна (₴)</label>
        <input type="number" step="0.01" name="price" id="price" value="<?= htmlspecialchars($course['price']) ?>">
    </div>

    <div class="form-group">
        <label>
            <input type="checkbox" name="is_featured" value="1" <?= $course['is_featured'] ? 'checked' : '' ?>>
            Популярний курс
        </label>
    </div>

    <div class="form-group">
        <label for="level">Рівень</label>
        <select name="level" id="level">
            <option value="beginner" <?= $course['level'] === 'beginner' ? 'selected' : '' ?>>Початковий</option>
            <option value="intermediate" <?= $course['level'] === 'intermediate' ? 'selected' : '' ?>>Середній</option>
            <option value="advanced" <?= $course['level'] === 'advanced' ? 'selected' : '' ?>>Просунутий</option>
        </select>
    </div>

    <div class="form-group">
        <label for="duration">Тривалість</label>
        <input type="text" name="duration" id="duration" placeholder="Напр. 6 тижнів" value="<?= htmlspecialchars($course['duration']) ?>">
    </div>

    <div class="form-group">
        <label for="language">Мова</label>
        <input type="text" name="language" id="language" value="<?= htmlspecialchars($course['language']) ?>">
    </div>

    <button type="submit" class="btn btn-primary" id="updateCourse" data-action="update">
        <i class="fas fa-save"></i> Зберегти курс
    </button>
</form>
