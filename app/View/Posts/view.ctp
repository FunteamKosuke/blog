<!-- File: /app/View/Posts/view.ctp -->

<div id="post__view">
  <h1 id='view-title'><?php echo h($post['Post']['title']); ?></h1>
  <!-- 投稿日と投稿者を表示する。 -->
  <?php $post_date = explode(' ', $post['Post']['created'])[0]; ?>
  <p><small><?php echo __('Post Date'); ?>: <?php echo h($post_date); ?>
            <?php echo __('Contributor'); ?>: <?php echo h($post['User']['username']);?></small></p>
  <!-- カテゴリを表示する -->
  <p><?php echo __('Category'); ?>: <?php echo h($post['Category']['name']); ?></p>
  <!-- タグを表示する -->
  <p><?php echo __('Tag'); ?>:
    <?php foreach ($post['Tag'] as $tag): ?>
      <?php echo h($tag['name']); ?>
      <?php if ($tag !== end($post['Tag'])) {
        echo ",";
      } ?>
    <?php endforeach; ?>
  </p>
  <div class="container-fluid">
    <div class="row">
      <div id="view-body" class="col-7 slide">
        <p><?php echo h($post['Post']['body']); ?></p>
        <div class="back-curtain"></div><!-- スライドショーの背景の暗幕 -->
        <!-- 関連づいてる画像の数だけ表示する -->
        <?php
          foreach ($post['Image'] as $image) { ?>
            <hr>
            <div class="view-image">
                <div class="container-fluid">
                    <div class="row">
                        <div class="image col-12">
                            <?php
                                $image_path = '../files/image/image';
                                $image_path .= '/' . $image['image_dir'];
                                $image_path .= '/' . $image['image'];
                                echo $this->Html->image($image_path);
                            ?>
                        </div>
                        <div class="slide-view">
                            <div class="largeImg">
                                <?php echo $this->Html->image($image_path, array()); ?>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <!-- 画像を削除する。 -->
                        <div class="image-delete col-6">
                            <?php
                                echo $this->Form->postLink(
                                    __('Delete Image'),
                                    array('controller' => 'images',
                                          'action' => 'delete',
                                          $image['id'],
                                          '?' => ['post_id' => $post['Post']['id']]),
                                    array('confirm' => 'Are you sure?',
                                          'class' => 'btn btn-primary btn-block')
                                ); ?>
                        </div>

                        <!-- 画像を編集する。 -->
                        <div class="image-edit col-6">
                            <?php // 画像を差し替えるリンク
                            echo $this->Html->link(
                                __('Edit Image'),
                                array('controller' => 'images',
                                      'action' => 'edit',
                                      $image['id'],
                                      '?' => ['post_id' => $post['Post']['id']]), // 画像差し替え後に表示していた記事に戻るため、記事のIDを渡す。
                                array('class' => 'btn btn-primary btn-block' )
                            ); ?>
                        </div>
                    </div>
                </div>
          </div>
        <?php
        }
        ?>
        <div class="slide-operation">
            <div class="next">
                >
            </div>
            <div class="prev">
                <
            </div>
        </div>
        <script type="text/javascript">

        </script>
        <!-- 記事に画像を投稿するリンクを作成する。 -->
        <p><?php echo $this->Html->link(__('Add Image'), ['controller' => 'Images',
                                                      'action' => 'upload',
                                                      '?' => ['post_id' => $post['Post']['id']]],
                                                  array('class' => 'btn btn-primary btn-block')); ?></p>
      </div>
      <!-- 記事とサイドバーの間隔を開ける -->
      <div class="col-1">
      </div>
      <!-- サイドバーを表示する -->
      <?php include('side-bar.ctp'); ?>
    </div>
  </div>
</div>
