<h1>Додати новий курс</h1>

<form enctype="multipart/form-data" class="course-add-form">
    <div class="form-group">
        <label for="title">Назва курсу *</label>
        <input type="text" name="title" id="title" required>
    </div>

    <div class="form-group">
        <label for="slug">URL-дружнє ім’я</label>
        <input type="text" name="slug" id="slug">
    </div>

    <div class="form-group">
        <label for="description">Опис</label>
        <textarea name="description" id="description" rows="6"></textarea>
    </div>

    <div class="form-group">
        <label for="image">Зображення (jpg/png)</label>
        <input type="file" name="image" id="image">
    </div>

    <div class="form-group">
        <label for="status">Статус</label>
        <select name="status" id="status">
            <option value="draft">Чернетка</option>
            <option value="published">Опублікований</option>
            <option value="archived">Архівований</option>
        </select>
    </div>

    <div class="form-group">
        <label for="price">Ціна (₴)</label>
        <input type="number" step="0.01" name="price" id="price">
    </div>

    <div class="form-group">
        <label>
            <input type="checkbox" name="is_featured" value="1">
            Популярний курс
        </label>
    </div>

    <div class="form-group">
        <label for="level">Рівень</label>
        <select name="level" id="level">
            <option value="beginner">Початковий</option>
            <option value="intermediate">Середній</option>
            <option value="advanced">Просунутий</option>
        </select>
    </div>

    <div class="form-group">
        <label for="duration">Тривалість</label>
        <input type="text" name="duration" id="duration" placeholder="Напр. 6 тижнів">
    </div>

    <div class="form-group">
        <label for="language">Мова</label>
        <input type="text" name="language" id="language">
    </div>

    <button type="submit" class="btn btn-primary" id="saveCourse" data-action="create">
        <i class="fas fa-plus"></i> Створити курс
    </button>
</form>
