<div id="posts__edit-draft">
    <h1><?php echo __('Edit Draft'); ?></h1>

    <!-- サムネイルを編集する。 -->
    <h3><?php echo __('Edit Thumbnail'); ?></h3>
    <div id="edit-thumbnail">
        <?php
          // サムネイルが設定されている記事だけ表示する。
          if ($thumbnail = $draft_post['Thumbnail']) {
              $thumbnail_path = '../files/thumbnail/thumbnail';
              $thumbnail_path .= '/' . $thumbnail['thumbnail_dir'];
              $thumbnail_path .= '/' . $thumbnail['thumbnail'];
              echo $this->Html->image($thumbnail_path);
          }
        ?>
        <div class="image-edit">
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
    <!-- 投稿された画像を編集する。 -->
    <h3><?php echo __('Edit Image'); ?></h3>
    <div id="edit-image" class="container-fluid">
        <div class="row">
            <?php foreach ($draft_post['Image'] as $image) { ?>
            <div class="view-image col-3">
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
                                    __('Delete'),
                                    array('controller' => 'images',
                                          'action' => 'delete',
                                          $image['id'],
                                          '?' => array('post_id' => $draft_post['Post']['id'],
                                                        'redirect_view' => 'editDraft')),
                                    array('confirm' => 'Are you sure?',
                                          'class' => 'btn emphasis-color-low btn-block')
                                ); ?>
                        </div>
                        <!-- 画像を編集する。 -->
                        <div class="image-edit col-6">
                            <?php // 画像を差し替えるリンク
                            echo $this->Html->link(
                                __('Edit'),
                                array('controller' => 'images',
                                      'action' => 'edit',
                                      $image['id'],
                                      '?' => array('post_id' => $draft_post['Post']['id'],
                                                    'redirect_view' => 'editDraft')), // 画像差し替え後に表示していた記事に戻るため、記事のIDを渡す。
                                array('class' => 'btn emphasis-color-normal btn-block' )
                            ); ?>
                        </div>
                    </div>
                </div>
            </div>
    <?php } ?>
        </div>
    </div>
    <div id="edit-image-mobile" class="container-fluid">
        <div class="row">
            <?php foreach ($draft_post['Image'] as $image) { ?>
            <div class="view-image">
                <div class="container-fluid">
                    <div class="row">
                        <div class="image">
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
                        <div class="image-delete">
                            <?php
                                echo $this->Form->postLink(
                                    __('Delete'),
                                    array('controller' => 'images',
                                          'action' => 'delete',
                                          $image['id'],
                                          '?' => array('post_id' => $draft_post['Post']['id'],
                                                        'redirect_view' => 'editDraft')),
                                    array('confirm' => 'Are you sure?',
                                          'class' => 'btn emphasis-color-low btn-block')
                                ); ?>
                        </div>
                        <!-- 画像を編集する。 -->
                        <div class="image-edit">
                            <?php // 画像を差し替えるリンク
                            echo $this->Html->link(
                                __('Edit'),
                                array('controller' => 'images',
                                      'action' => 'edit',
                                      $image['id'],
                                      '?' => array('post_id' => $draft_post['Post']['id'],
                                                    'redirect_view' => 'editDraft')), // 画像差し替え後に表示していた記事に戻るため、記事のIDを渡す。
                                array('class' => 'btn emphasis-color-normal btn-block' )
                            ); ?>
                        </div>
                    </div>
                </div>
            </div>
    <?php } ?>
        </div>
    </div>
    <!-- 記事に画像を追加する。 -->
    <p><?php echo $this->Html->link(__('Add Image'), array('controller' => 'Images',
                                              'action' => 'upload',
                                              '?' => array('post_id' => $draft_post['Post']['id'],
                                                            'redirect_view' => 'editDraft')),
                                          array('class' => 'btn emphasis-color-high font-white btn-block')); ?></p>

    <?php echo $this->Form->create('Post'); ?>
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
                                                    'value' => $draft_post['Post']['body'])); ?>
    </div>
    <!-- カテゴリを編集する。 -->
    <div class="form-group">
        <h3><?php echo __('Category'); ?></h3>
        <?php echo $this->Form->input( 'Category.category', array(
                                                                'type' => 'select',
                                                                'options' => $categories,
                                                                'label' => false,
                                                                'class' => 'form-control')); ?>
    </div>
    <!-- タグを編集する。 -->
    <h3><?php echo __('Tag'); ?></h3>
    <?php
        $default_tags = array();
        foreach ($draft_post['Tag'] as $tag) {
            $default_tags[] = $tag['id'];
        }
    ?>
    <?php echo $this->Form->input( 'Tag.Tag', array(
        'type' => 'select',
        'multiple'=> 'checkbox',
        'options' => $tags,
        'selected' => $default_tags)); ?>

        <div class="container-fluid">
            <div class="row">
                <!-- 記事を追加するか下書き保存するか分ける。 -->
                <div id="add" class="col-6 padi_width_5px">
                    <?php echo $this->Form->button(__('Publish'), array(
                        'div' => false,
                        'class' => 'btn btn-primary btn-block',
                        'name' => 'publish_flg',
                        'value' => '1')); ?>
                </div>
                <div id="draft" class="col-6 padi_width_5px">
                    <?php echo $this->Form->button(__('Draft'), array(
                        'div' => false,
                        'class' => 'btn btn-secondary btn-block',
                        'name' => 'publish_flg',
                        'value' => '0')); ?>
                </div>
            </div>
        </div>
        <?php echo $this->Form->end(); ?>



    <?php echo $this->Form->end(); ?>
</div>
