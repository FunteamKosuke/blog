<div class="post-list">
    <hr>
    <!-- ここで $posts 配列をループして、投稿情報を表示 -->
    <div class="container-fluid">
      <div class="row">
        <div id="post-list" class='col-7'>
          <?php foreach ($posts as $post): ?>
            <p class="title"><?php echo h($post['Post']['title']); ?></p>
            <div class="thumbnail">
                <?php
                  // サムネイルが設定されている記事だけ表示する。
                  if ($thumbnail = $post['Thumbnail']) {
                      $thumbnail_path = '../files/thumbnail/thumbnail';
                      $thumbnail_path .= '/' . $thumbnail['thumbnail_dir'];
                      $thumbnail_path .= '/' . $thumbnail['thumbnail'];
                      echo $this->Html->image($thumbnail_path);
                  }
                ?>
            </div>
            <?php $post_date = $post['Post']['created']; ?>
            <p class="post-date"><?php echo __('Post Date'); ?>：<?php echo h($post_date); ?></p>
            <div class="body">
              <!-- 記事の内容を１００文字まで表示する。 -->
              <?php $body = mb_substr($post['Post']['body'], 0, 100); ?>
              <?php if (mb_strlen($post['Post']['body']) > 100) {
                $body .= '...';
              } ?>
              <?php echo h($body); ?>
            </div>
            <div class="read-next">
                <?php echo $this->Html->link(
                    __('Read Post'),
                    array('controller' => 'posts',
                          'action' => 'view',
                          $post['Post']['id']),
                    array('class' => 'btn btn-outline-primary')
                ); ?>
            </div>
            <?php if ($post['Post']['user_id'] == $user) {?>
                <div class="delete">
                    <?php echo $this->Form->postLink(
                        __('Delete Post'),
                        array('controller' => 'posts','action' => 'delete', $post['Post']['id']),
                        array('confirm' => 'Are you sure?','class' => 'btn btn-outline-danger')
                    ); ?>
                </div>
            <?php } ?>
            <hr>
          <?php endforeach; ?>
          <?php echo $this->element('bootstrap-paginate'); ?>
        </div><!-- post-list -->
        <div class="col-1">
        </div>
        <?php echo $this->element('side-bar'); ?>
      </div><!-- row -->
    </div><!-- contener -->
</div><!-- post-list -->
