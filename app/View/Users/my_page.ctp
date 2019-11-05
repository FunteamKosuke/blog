<h1><?php echo __('My Page'); ?></h1>
<!-- 名前を表示する。 -->
<p><?php echo __('User Name'); ?>：<?php echo h($user['User']['username']); ?></p>

<!-- プロフィール画像を表示する。 -->
<h3><?php echo __('Profile Image'); ?></h3>
<div id="profile-image" class="image">
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
<p><?php echo __('Address'); ?>：<?php echo h($user['User']['address']); ?></p>
<!-- 選択住所を表示する。 -->
<p><?php echo __('Select Address'); ?>：<?php echo h($user['User']['sl_address']); ?></p>

<!-- 投稿した記事を一覧で表示するためのリンク -->
<?php echo $this->Html->link(
            __('Post Index'),
            array('action' => 'postIndex',
                    $user['User']['id']),
            array('class' => 'btn btn-outline-primary'));
?>

<!-- ユーザーに送信されたメッセージを一覧で表示するページ -->
<?php echo $this->Html->link(
            __('Message Index'),
            array('action' => 'messageIndex',
                    $user['User']['id']),
            array('class' => 'btn btn-outline-primary'));
?>
