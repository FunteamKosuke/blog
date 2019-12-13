<!-- File: /app/View/Posts/view.ctp -->

<div id="post__view">
  <h1 id='view-title'><?php echo h($post['Post']['title']); ?></h1>
  <!-- 投稿日と投稿者を表示する。 -->
  <?php $post_date = explode(' ', $post['Post']['created'])[0]; ?>
  <p><small><?php echo __('Post Date'); ?>:
            <?php echo $this->Html->link(
                      h($post_date),
                      array('controller' => 'posts',
                          'action' => 'postDateRelatedPost',
                              $post['Post']['id']));
            ?>
            <?php echo __('Contributor'); ?>:
            <?php echo $this->Html->link(
                        h($post['User']['username']),
                        array('controller' => 'users',
                            'action' => 'postIndex',
                                $post['User']['id']));
            ?></small></p>
  <!-- カテゴリを表示する -->
  <p><?php echo __('Category'); ?>:
      <?php // カテゴリに関連する記事を一覧で表示できる様にする。
      echo $this->Html->link(
          $post['Category']['name'],
          array('controller' => 'categories',
                'action' => 'related_post_index',
                $post['Category']['id']
      )); ?>
　</p>
  <!-- タグを表示する -->
  <p><?php echo __('Tag'); ?>:
    <?php foreach ($post['Tag'] as $tag): ?>
        <?php // タグに関連する記事を一覧で表示できる様にする。
        echo $this->Html->link(
            $tag['name'],
            array('controller' => 'tags',
                  'action' => 'related_post_index',
                  $tag['id']
        )); ?>
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
        <?php foreach ($post['Image'] as $image) { ?>
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
                </div>
          </div>
        <?php } ?>
        <!-- スライドショー のナビゲーション -->
        <div class="slide-nav">
            <div class="elem">
            </div>
            <div class="pos">

            </div>
        </div>
        <!-- 通常用のスライドショー の次へ前へボタン -->
        <div class="slide-operation">
            <div class="next">
                >
            </div>
            <div class="prev">
                <
            </div>
        </div>
        <!-- モバイル用のスライドショー の次へ前へボタン -->
        <div class="mobile-slide-operation">
            <div class="container-fluid">
                <div class="row">
                    <div class="prev col-6">
                        <label class="btn btn-light btn-block">前の画像</label>
                    </div>
                    <div class="next col-6">
                        <label class="btn btn-light btn-block">次の画像</label>
                    </div>
                </div>
            </div>
        </div>
        <div class="container-fluid">
            <div class="row">
                <!-- 次の記事のリンク -->
                <div id="post-next" class="col-3">
                    <?php if (isset($next_id)) {
                        echo $this->Html->link(
                            __('Next'),
                            array('controller' => 'posts',
                                  'action' => 'view',
                                  $next_id),
                            array('class' => 'btn btn-outline-primary')
                        );
                    } ?>
                </div>
                <div class="col-6"></div>
                <!-- 前の記事のリンク -->
                <div id="post-prev" class="col-3">
                    <?php if (isset($prev_id)) {
                        echo $this->Html->link(
                            __('Prev'),
                            array('controller' => 'posts',
                                  'action' => 'view',
                                  $prev_id),
                            array('class' => 'btn btn-outline-primary')
                        );
                    } ?>
                </div>
            </div>
        </div>
        <!-- 関連記事を表示する。 -->
        <div id="related_post">
            <h3><?php echo __('Related Post'); ?></h3>
            <hr>
            <?php foreach ($related_post as $rltpost) { ?>
            <!-- タイトルを表示する -->
            <h3> <?php echo h($rltpost['Post']['title']); ?></h3>
            <?php
                // サムネイルが設定されている記事だけ表示する。
                if ($thumbnail = $rltpost['Thumbnail']) {
                    $thumbnail_path = '../files/thumbnail/thumbnail';
                    $thumbnail_path .= '/' . $thumbnail['thumbnail_dir'];
                    $thumbnail_path .= '/' . $thumbnail['thumbnail'];
                    echo $this->Html->image($thumbnail_path, array('div' => false));
                }
                // 文章を表示する。
                $body = mb_substr($rltpost['Post']['body'], 0, 100);
                if (mb_strlen($rltpost['Post']['body']) > 100) {
                  $body .= '...';
                }
                ?>
                <p><?php echo h($body); ?></p>
                <!-- 記事を読むボタン -->
                <div class="read-next">
                    <?php echo $this->Html->link(
                        __('Read Post'),
                        array('controller' => 'posts',
                              'action' => 'view',
                              $post['Post']['id']),
                        array('class' => 'btn btn-outline-primary')
                    ); ?>
                </div>
                <hr>
            <?php } ?>
        </div>
      </div>
      <!-- 記事とサイドバーの間隔を開ける -->
      <div class="col-1">
      </div>
      <!-- サイドバーを表示する -->
      <?php echo $this->element('side-bar'); ?>
    </div>
  </div>
</div>
