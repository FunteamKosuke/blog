<!-- File: /app/View/Posts/index.ctp -->
<div id="post__index">
  <h1><?php echo __('Kosuke Blog') ?></h1>
  <div class="mobile-align-button">
      <?php if ($login_user) {
          echo $this->Html->link(
                    __('Add Post'),
                    array('action' => 'add'),
                    array('class' => 'btn btn-outline-primary')
                );
      } ?>
  </div>
  <!-- 記事一覧を表示するページを読み込む -->
  <?php echo $this->element('post-list'); ?>
</div><!-- post-index -->
