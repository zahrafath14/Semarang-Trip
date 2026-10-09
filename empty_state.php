<?php /** @var string $title @var string $message @var string $action_label @var string $action_url */ ?>
<div class="empty">
    <span class="empty-ico"><?= ikon('search', 30) ?></span>
    <h2><?= e($title) ?></h2>
    <p><?= e($message) ?></p>
    <a href="<?= e($action_url) ?>" class="btn btn-primary"><?= e($action_label) ?></a>
</div>
