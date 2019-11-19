<div id="side-bar" class="col-4 list-design-husen-blue">
    <div id="popular_post">
        <h1><?php echo __('Popular Post'); ?></h1>
        <ul>
            <?php foreach ($side_popular_posts as $post): ?>
                <li><?php echo $this->Html->link(
                          $post['Post']['title'],
                          array('controller' => 'posts',
                                'action' => 'view',
                                $post['Post']['id'])); ?></li>
            <?php endforeach; ?>
        </ul>
    </div>

    <div id="new_post">
        <h1><?php echo __('New Post'); ?></h1>
        <ul>
            <?php foreach ($side_new_posts as $post): ?>
                <li><?php echo $this->Html->link(
                          $post['Post']['title'],
                          array('controller' => 'posts',
                                'action' => 'view',
                                $post['Post']['id'])); ?></li>
            <?php endforeach; ?>
        </ul>
    </div>

    <div id="attention_post">
        <h1><?php echo __('Attention Post'); ?></h1>
        <ul>
            <?php foreach ($side_attention_posts as $post): ?>
                <li><?php echo $this->Html->link(
                          $post['Post']['title'],
                          array('controller' => 'posts',
                                'action' => 'view',
                                $post['Post']['id'])); ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
</div>
