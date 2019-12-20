<!-- 論理削除した記事を一覧で表示するページ -->
<div id="posts__index_delete_posts">
    <h1><?php echo __('Index Delete Post'); ?></h1>
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <table class="table">
                  <thead>
                    <tr>
                      <th scope="col"><?php echo __('Title'); ?></th>
                      <th scope="col"><?php echo __('Action'); ?></th>
                    </tr>
                  </thead>
                  <tbody>
                <?php foreach ($delete_posts as $post): ?>
                    <tr>
                        <td><?php echo $post['Post']['title']; ?></td>
                        <td>
                            <div class="revive">
                                <?php echo $this->Form->postLink(
                                    __('Revive'),
                                    array('controller' => 'posts',
                                          'action' => 'revivePost',
                                          $post['Post']['id']),
                                    array('confirm' => __('Are you sure you want to revive the article?'),
                                          'class' => 'btn btn-outline-primary')
                                ); ?>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
                    </tbody>
                </table>
                <?php echo $this->element('bootstrap-paginate'); ?>
          </div>
    </div><!-- row -->
    </div><!-- contener -->
</div>
