<h1>Курси</h1>

<div class="toolbar">
    <a href="/admin/load?action=createCourse" class="btn btn-success" id="createCourse">
        <i class="fas fa-plus"></i> Додати курс
    </a>
</div>

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
                <a href="/admin/load?action=editCourse&id=<?= htmlspecialchars($course['id']) ?>" title="Редагувати курс">
                    <i class="fas fa-edit"></i>
                </a>
                <a href="modules/list?action=getAll&id=<?= $course['id'] ?>" title="Модулі">
                    <i class="fas fa-layer-group"></i>
                </a>
                <a href="/admin/load?action=lessons&id=<?= $course['id'] ?>" title="Уроки">
                    <i class="fas fa-book disabled"></i>
                </a>
                <a href="/admin/load?action=quizzes&id=<?= $course['id'] ?>" title="Тести">
                    <i class="fas fa-question-circle disabled"></i>
                </a>
            </div>
        </div>
    <?php endforeach; ?>
</div>
