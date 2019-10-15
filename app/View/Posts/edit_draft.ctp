<?php $this->log($draft_post); $this->log('ちゃんt'); ?>
<h3>Edit Draft</h3>
<?php echo $this->Form->create('Post'); ?>

<!-- サムネイルを編集する。 -->
<h3><?php echo __('Edit Thumbnail'); ?></h3>
<div class="thumbnail">
    <?php
      // サムネイルが設定されている記事だけ表示する。
      if ($thumbnail = $draft_post['Thumbnail']) {
          $thumbnail_path = '../files/thumbnail/thumbnail';
          $thumbnail_path .= '/' . $thumbnail['thumbnail_dir'];
          $thumbnail_path .= '/' . $thumbnail['thumbnail'];
          echo $this->Html->image($thumbnail_path);
      }
    ?>
    <div class="image-edit col-6">
        <?php // 画像を差し替えるリンク
        echo $this->Html->link(
            __('Edit Thumbnail'),
            array('controller' => 'thumbnails',
                  'action' => 'edit',
                  $thumbnail['id'],
                  '?' => array('post_id' => $draft_post['Post']['id'],
                                'redirect_view' => 'editDraft')), // 画像差し替え後に表示していた記事に戻るため、記事のIDを渡す。
            array('class' => 'btn btn-primary btn-block' )
        ); ?>
    </div>
</div>

<!-- タイトルを編集する -->
<div class="form-group">
    <h3><?php echo __('Title'); ?></h3>
    <?php echo $this->Form->input('title', array('label' => false,
                                                 'class' => 'form-control',
                                                 'value' => $draft_post['Post']['title'])); ?>
</div>
<!-- 内容を編集する -->
<div class="form-group">
    <h3><?php echo __('Body'); ?></h3>
    <?php echo $this->Form->input('body', array('label' => false,
                                                'rows' => '6',
                                                'class' => 'form-control',
                                                'value' => $draft_post['Post']['title'])); ?>
</div>
<!-- カテゴリを編集する。 -->
<div class="form-group">
    <h3><?php echo __('Category'); ?></h3>
    <?php echo $this->Form->input( 'Category.category', array(
                                                            'type' => 'select',
                                                            'options' => $select1,
                                                            'label' => false,
                                                            'class' => 'form-control')); ?>
</div>
<!-- タグを編集する。 -->
<h3><?php echo __('Tag'); ?></h3>

<!-- 投稿された画像を編集する。 -->
<h3><?php echo __('Edit Image'); ?></h3>
<?php foreach ($draft_post['Image'] as $image) { ?>
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
                                  '?' => array('post_id' => $draft_post['Post']['id'],
                                                'redirect_view' => 'editDraft')),
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
                              '?' => array('post_id' => $draft_post['Post']['id'],
                                            'redirect_view' => 'editDraft')), // 画像差し替え後に表示していた記事に戻るため、記事のIDを渡す。
                        array('class' => 'btn btn-primary btn-block' )
                    ); ?>
                </div>
            </div>
        </div>
  </div>
<?php } ?>

<!-- 記事に画像を追加する。 -->
<p><?php echo $this->Html->link(__('Add Image'), array('controller' => 'Images',
                                              'action' => 'upload',
                                              '?' => array('post_id' => $draft_post['Post']['id'],
                                                            'redirect_view' => 'editDraft')),
                                          array('class' => 'btn btn-primary btn-block')); ?></p>

<?php echo $this->Form->end(); ?>
