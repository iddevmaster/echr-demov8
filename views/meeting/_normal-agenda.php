<?php foreach ($mas as $ma): ?>
    <?php if (isset($ma->parent_id)) : ?>
        <p><strong><?= $ma->fullTitle ?></strong></p>
    <?php else: ?>
        <p><strong><u><?= Yii::t('app', 'วาระที่ {0}', [$ma->sort_label]) ?></u></strong>&nbsp;<span><?= $ma->title ?></span></p>
    <?php endif; ?>
    <?php if (isset($ma->description)): ?>
        <span><?= $ma->description ?></span>
    <?php endif; ?>
    <?php if (isset($ma->resolution)): ?>
        <span><?= $ma->resolution ?></span>
    <?php endif; ?>
    <?php if (isset($ma->conclusion)): ?>
        <span><?= $ma->conclusion ?></span>
    <?php endif; ?>
    <?php if (isset($ma->summary)): ?>
        <span><?= $ma->summary ?></span>
    <?php endif; ?>
<?php endforeach; ?>