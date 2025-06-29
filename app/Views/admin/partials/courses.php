<div class="course-grid">
    <div class="course-grid-row course-grid-header">
        <div>ID</div>
        <div>Назва</div>
        <div>Статус</div>
        <div>Ціна</div>
        <div>Дії</div>
    </div>

    <?php foreach ($courses as $course): ?>
        <div class="course-grid-row">
            <div><?= htmlspecialchars($course['id']) ?></div>
            <div><?= htmlspecialchars($course['title']) ?></div>
            <div><?= htmlspecialchars($course['status']) ?></div>
            <div><?= htmlspecialchars($course['price']) ?> ₴</div>
            <div class="action-icons">
                <a href="/admin/load?action=editCourse&id=<?= htmlspecialchars($course['id']) ?>">
                    <i class="fas fa-edit" title="Редагувати курс"></i>
                </a>
            </div>
        </div>
    <?php endforeach; ?>
</div>
