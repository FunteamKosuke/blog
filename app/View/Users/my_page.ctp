<?php echo __('My Page'); ?>
<!-- 名前を表示する。 -->
<?php echo h($user['User']['username']); ?>
<!-- プロフィール画像を表示する。 -->
<div class="profile-image">
    <?php
      // サムネイルが設定されている記事だけ表示する。
      if ($profile_image = $user['User']['profile_image']) {
          $profile_image_path = '../files/user/profile_image';
          $profile_image_path .= '/' . $user['User']['profile_image_dir'];
          $profile_image_path .= '/' . $user['User']['profile_image'];
          echo $this->Html->image($profile_image_path);
      }
    ?>
</div>
<!-- 住所を表示する。 -->
<?php echo h($user['User']['address']); ?>
<!-- 選択住所を表示する。 -->
<?php echo h($user['User']['sl_address']); ?>
<!-- 投稿した記事を一覧で表示するためのリンク -->
<?php echo $this->Html->link(
            __('User Post Index'),
            array('action' => 'postIndex',
                    $user['User']['id']),
            array('class' => 'btn btn-outline-primary'));
?>
