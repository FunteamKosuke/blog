<div id="post__find">
    <h1>[<?php echo $keyword; ?>]:<?php echo __('Search Post Result'); ?></h1>
    <!-- 記事一覧を表示するページを読み込む -->
    <?php echo $this->element('post-list'); ?>
</div>
