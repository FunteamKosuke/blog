<h1><?php echo __('An article has been added.'); ?></h1>
<?php echo $this->Html->link(
    __('Back to top'),
    array('controller' => 'posts',
          'action' => 'index'),
    array('class' => 'btn btn-outline-primary btn-block')
); ?>
