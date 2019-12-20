<!-- モーダルダイアログを表示する為に必要。 -->
<div id="msg-modal">
<div class="background"></div>
<div class="container"></div>
</div><!-- modal -->

<div id="users__index">
    <h1><?php echo __('User Index'); ?></h1>
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
                <?php echo $this->element('bootstrap-paginate'); ?>
                </div><!-- paginate -->
          </div>
    </div><!-- row -->
    </div><!-- contener -->
</div>
