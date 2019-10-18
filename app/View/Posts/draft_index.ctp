<h1><?php echo __('Draft Index'); ?></h1>
<div class="container-fluid">
    <div class="row">
        <div class="col-5">
            <table class="table">
              <thead>
                <tr>
                  <th scope="col"><?php echo __('Title'); ?></th>
                  <th scope="col"><?php echo __('Action'); ?></th>
                </tr>
              </thead>
              <tbody>
            <?php foreach ($draft_posts as $post): ?>
                <tr>
                    <td><?php echo $post['Post']['title']; ?></td>
                    <td>
                        <div class="delete">
                            <?php echo $this->Form->postLink(
                                __('Delete'),
                                array('controller' => 'posts',
                                      'action' => 'delete',
                                      $post['Post']['id']),
                                array('confirm' => 'Are you sure?',
                                      'class' => 'btn btn-outline-primary')
                            ); ?>
                        </div>
                        <div class="publish">
                            <?php echo $this->Form->postLink(
                                __('Publish'),
                                array('controller' => 'posts',
                                      'action' => 'publishDraft',
                                      $post['Post']['id']),
                                array('confirm' => __('Do you want to release it?'),
                                      'class' => 'btn btn-outline-primary')
                            ); ?>
                        </div>
                        <div class="edit">
                            <?php echo $this->html->link(
                                __('Edit'),
                                array('controller' => 'posts',
                                      'action' => 'editDraft',
                                      $post['Post']['id']),
                                array('class' => 'btn btn-outline-primary')
                            ); ?>
                        </div>
                    </td>
                </tr>
            <?php endforeach; ?>
                </tbody>
            </table>
            <div class="paginate">
              <?php echo $this->Paginator->prev(); ?>&nbsp;
              <?php echo $this->Paginator->numbers(); ?>&nbsp;
              <?php echo $this->Paginator->next(); ?>
            </div><!-- paginate -->
      </div>
    <!-- 下書き一覧とサイドバーの間を開ける -->
    <div class="col-1">
    </div>
    <?php include('side-bar.ctp') ?>
</div><!-- row -->
</div><!-- contener -->
