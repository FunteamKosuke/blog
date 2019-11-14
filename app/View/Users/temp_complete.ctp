<h1><?php echo __('Temporary registration is complete. An e-mail for registration has been sent.'); ?></h1>
<?php echo $this->Html->link(
    __('Back to top'),
    array('controller' => 'posts',
          'action' => 'index'),
    array('class' => 'btn btn-outline-primary btn-block')
); ?>
