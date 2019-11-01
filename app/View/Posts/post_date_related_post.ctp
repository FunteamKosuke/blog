<h1><?php echo h($post_date); ?> : <?php echo __('Related Post'); ?></h1>

<!-- 記事一覧を表示するページを読み込む -->
<?php echo $this->element('post-list'); ?>
