<div id="msg-modal">
<div class="background"></div>
<div class="container"></div>
</div><!-- modal -->

<div id="users__index">
    <h1><?php echo __('User Index'); ?></h1>
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
                <?php foreach ($users as $user): ?>
                    <tr>
                        <td><?php echo $user['User']['username']; ?></td>
                        <td>
                            <div class="msg-send">
                                <?php $sendMsgUrl = '/users/sendMsg/' . $user['User']['id']; ?>
                                <a href=<?php echo $sendMsgUrl; ?> class="msg-modal btn btn-outline-primary"><?php echo __('Send Message'); ?></a>
                                <!-- <?php echo $this->Html->link(
                                    __('Message Send'),
                                    array('controller' => 'users',
                                          'action' => 'msgSend',
                                            $user['User']['id']),
                                    array('class' => 'btn btn-outline-primary')
                                ); ?> -->
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
        <!-- ユーザー一覧とサイドバーの間を開ける -->
        <div class="col-1">
        </div>
        <?php echo $this->element('side-bar'); ?>
    </div><!-- row -->
    </div><!-- contener -->
</div>
