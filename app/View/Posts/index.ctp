<!-- File: /app/View/Posts/index.ctp -->
<div id="post__index">
  <h1>Blog posts</h1>
  <?php echo $this->Html->link(
      '記事を追加する',
      array('action' => 'add'),
      array('class' => 'btn btn-outline-primary')
  ); ?>
  <!-- 記事一覧を表示するページを読み込む -->
  <?php include('post-list.ctp') ?>
</div><!-- post-index -->
