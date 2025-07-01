<form id="editModuleForm" method="POST">
    <input type="hidden" name="module_id" value="<?= $module['id'] ?>">
    
    <div class="form-group">
        <label for="title">Назва модуля:</label>
        <input type="text" id="title" name="title" value="<?= htmlspecialchars($module['title']) ?>" required>
    </div>
    
    <div class="form-group">
        <label for="description">Опис:</label>
        <textarea id="description" name="description"><?= htmlspecialchars($module['description']) ?></textarea>
    </div>
    
    <div class="form-group">
        <label for="position">Позиція:</label>
        <input type="number" id="position" name="position" value="<?= $module['position'] ?>">
    </div>
    
    <button type="submit">Зберегти</button>
</form>

<script>
document.getElementById('editModuleForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    fetch(this.action, {
        method: 'POST',
        body: new FormData(this)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Зміни збережено!');
            window.location.reload();
        } else {
            alert('Помилка при збереженні');
        }
    });
});
</script>