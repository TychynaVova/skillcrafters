<?php
$modules = $modules ?? [];
$pagination = $pagination ?? ['current' => 1, 'total' => 1, 'url' => ''];
$courseId = $courseId ?? 0;
$isAdmin = $isAdmin ?? false;
?>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
<h1>Модулі</h1>
<div class="modules-container">
    <div class="modules-header">
        <a href="/admin/load?action=courses" class="btn btn-back">
            <i class="fas fa-arrow-left"></i> До списку курсів
        </a>
        <?php if ($isAdmin): ?>
            <a href="/modules/add?id=<?= $courseId ?>" class="btn btn-primary">
                <i class="fas fa-plus"></i> Додати модуль
            </a>
        <?php endif; ?>
    </div>
    <?php if (!empty($modules)): ?>
        <div class="modules-list">
            <?php foreach ($modules as $module): ?>
                <div class="module-card">
                    <div class="module-card-header">
                        <h3><?= htmlspecialchars($module['title']) ?></h3>
                        <div class="module-actions">
                            <i class="module-toggle fas fa-chevron-down"></i>
                            <?php if ($isAdmin): ?>
                                <a href="/courses/<?= $courseId ?>/modules/<?= $module['id'] ?>/edit" 
                                   class="module-edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="module-card-content">
                        <?= nl2br(htmlspecialchars($module['description'])) ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <?php if ($pagination['total'] > 1): ?>
            <div class="pagination">
                <?php for ($i = 1; $i <= $pagination['total']; $i++): ?>
                    <a href="<?= $pagination['url'] . $i ?>" 
                       class="<?= $i == $pagination['current'] ? 'active' : '' ?>">
                        <?= $i ?>
                    </a>
                <?php endfor; ?>
            </div>
        <?php endif; ?>
    <?php else: ?>
        <div class="empty-state">
            <p>Цей курс ще не має модулів.</p>
            <?php if ($isAdmin): ?>
                <a href="/courses/<?= $courseId ?>/modules/new" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Додати перший модуль
                </a>
            <?php endif; ?>
        </div>
    <?php endif; ?>
</div>

<style>
/* Base styles */
:root {
    --primary: #2563eb;
    --primary-hover: #1d4ed8;
    --secondary: #6b7280;
    --secondary-hover: #4b5563;
    --border: #e5e7eb;
    --bg: #f9fafb;
    --text: #111827;
    --text-light: #6b7280;
}

.modules-container {
    max-width: 100%;
    margin: 0 auto;
    padding: 5px 10px;
    background: white;
    border-radius: 0.5rem;
    box-shadow: 0 1px 3px rgba(0,0,0,0.1);
    /**/
    display: flex;
    flex-direction: column;
    min-height: calc(100vh - 140px); /* Підлаштуйте відповідно до вашого хедера */
    position: relative;
}

/* Header */
.modules-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1.5rem;
    gap: 1rem;
}

.modules-title {
    font-size: 1.5rem;
    font-weight: 600;
    color: var(--text);
    margin-bottom: 1.5rem;
}

/* Buttons */
.btn {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.5rem 1rem;
    border-radius: 0.375rem;
    font-weight: 500;
    text-decoration: none;
    transition: all 0.2s;
}

.btn-back {
    background: var(--secondary);
    color: white;
}

.btn-back:hover {
    background: var(--secondary-hover);
}

.btn-primary {
    background: var(--primary);
    color: white;
}

.btn-primary:hover {
    background: var(--primary-hover);
}

/* Module cards */
.modules-list {
    display: flex;
    flex-direction: column;
    gap: 0.75rem;
    /** */
    flex: 1;
}

.module-card {
    border: 1px solid var(--border);
    border-radius: 0.5rem;
    overflow: hidden;
    transition: box-shadow 0.2s;
}

.module-card:hover {
    box-shadow: 0 1px 3px rgba(0,0,0,0.1);
}

.module-card-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: .7rem;
    background: var(--bg);
    cursor: pointer;
}

.module-card-header h3 {
    margin: 0;
    font-size: 1.125rem;
    font-weight: 500;
    color: var(--text);
}

.module-actions {
    display: flex;
    align-items: center;
    gap: 1rem;
}

.module-toggle {
    color: var(--text-light);
    transition: transform 0.2s;
}

.module-edit {
    color: var(--primary);
    text-decoration: none;
}

.module-edit:hover {
    color: var(--primary-hover);
}

.module-card-content {
    padding: 0 1rem;
    max-height: 0;
    overflow: hidden;
    transition: max-height 0.3s ease, padding 0.3s ease;
}

.module-card.active .module-card-content {
    padding: 1rem;
    max-height: 500px;
}

.module-card.active .module-toggle {
    transform: rotate(180deg);
}

/* Empty state */
.empty-state {
    text-align: center;
    padding: 2rem;
    background: var(--bg);
    border-radius: 0.5rem;
}

.empty-state p {
    margin-bottom: 1rem;
    color: var(--text-light);
}

/* Pagination */
.pagination {
    display: flex;
    justify-content: center;
    gap: 0.5rem;
    margin-top: 2rem;
    /** */
    display: flex;
    justify-content: center;
    gap: 0.5rem;
    margin-top: 2rem;
    padding: 1rem 0;
    position: sticky;
    bottom: 0;
    background: white;
    border-top: 1px solid var(--border);
    z-index: 10;
}

.pagination a {
    padding: 0.5rem 0.75rem;
    border: 1px solid var(--border);
    border-radius: 0.375rem;
    color: var(--primary);
    text-decoration: none;
    transition: all 0.2s;
}

.pagination a:hover {
    background: var(--bg);
}

.pagination a.active {
    background: var(--primary);
    color: white;
    border-color: var(--primary);
}

/* Responsive */
@media (max-width: 640px) {
    .modules-header {
        flex-direction: column;
        align-items: flex-start;
    }
    
    .module-card-header {
        flex-direction: column;
        align-items: flex-start;
        gap: 0.5rem;
    }
    
    .module-actions {
        align-self: flex-end;
    }
}
</style>
