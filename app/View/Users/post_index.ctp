<h1><?php echo h($username) ?> : <?php echo __('Post Index'); ?></h1>

<?php $this->log($posts); ?>
<!-- 記事一覧を表示するページを読み込む -->
<?php echo $this->element('post-list'); ?>
