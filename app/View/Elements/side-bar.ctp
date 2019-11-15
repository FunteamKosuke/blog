<div id="side-bar" class="col-4">
    <h1>サイドバー</h1>
    <p>これはサイドバーです。これはサイドバーです。これはサイドバーです。これはサイドバーです。
    これはサイドバーです。これはサイドバーです。これはサイドバーです。これはサイドバーです。
    これはサイドバーです。これはサイドバーです。これはサイドバーです。これはサイドバーです。
    これはサイドバーです。これはサイドバーです。これはサイドバーです。これはサイドバーです。
    これはサイドバーです。これはサイドバーです。これはサイドバーです。これはサイドバーです。
    これはサイドバーです。これはサイドバーです。これはサイドバーです。これはサイドバーです。
    これはサイドバーです。これはサイドバーです。これはサイドバーです。これはサイドバーです。</p>

    <div id="popular_post">
        <h1><?php echo __('Popular Post'); ?></h1>
        <?php foreach ($side_popular_posts as $post): ?>
            <p><?php echo $this->Html->link(
                      $post['Post']['title'],
                      array('controller' => 'posts',
                            'action' => 'view',
                            $post['Post']['id'])); ?></p>
        <?php endforeach; ?>
    </div>

    <div id="new_post">
        <h1><?php echo __('New Post'); ?></h1>
        <?php foreach ($side_new_posts as $post): ?>
            <p><?php echo $this->Html->link(
                      $post['Post']['title'],
                      array('controller' => 'posts',
                            'action' => 'view',
                            $post['Post']['id'])); ?></p>
        <?php endforeach; ?>
    </div>

    <div id="attention_post">
        <h1><?php echo __('Attention Post'); ?></h1>
        <?php foreach ($side_attention_posts as $post): ?>
            <p><?php echo $this->Html->link(
                      $post['Post']['title'],
                      array('controller' => 'posts',
                            'action' => 'view',
                            $post['Post']['id'])); ?></p>
        <?php endforeach; ?>
    </div>
</div>
