<div id="footer" >
    <div class="blog-middle">
        <div class="container-fluid">
            <div class="row">
                <div id="category" class="list-design-husen-yellow col-6">
                    <h1><?php echo __('Category'); ?></h1>
                    <ul>
                        <?php foreach ($footer_categories as $category): ?>
                            <li><?php echo $this->Html->link(
                                      $category['Category']['name'],
                                      array('controller' => 'categories',
                                            'action' => 'related_post_index',
                                            $category['Category']['id'])); ?></li>
                        <?php endforeach; ?>
                    </ul>

                </div>
                <div id="tag" class="list-design-husen-yellow col-6">
                    <h1><?php echo __('Tag'); ?></h1>
                    <ul>
                        <?php foreach ($footer_tags as $tag): ?>
                                <li><?php echo $this->Html->link(
                                      $tag['Tag']['name'],
                                      array('controller' => 'tags',
                                            'action' => 'related_post_index',
                                            $tag['Tag']['id'])); ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>
        </div>
        <p class='copyright'>Copyright © cakephp_blog All Rights Reserved.</p>
    </div>
</div>
