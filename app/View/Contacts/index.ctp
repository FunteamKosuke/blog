<div id="msg-modal">
<div class="background"></div>
<div class="container"></div>
</div><!-- modal -->

<div id="contacts__index">
    <h1><?php echo __('Contact Index'); ?></h1>
    <div id="normal-index">
        <table class="table">
          <thead>
            <tr>
              <th scope="col" width="150"><?php echo __('Name'); ?></th>
              <th scope="col"><?php echo __('Body'); ?></th>
              <th scope="col" width="150"><?php echo __('Action'); ?></th>
            </tr>
          </thead>
          <tbody>
        <?php foreach ($contacts as $contact): ?>
            <tr>
                <td><?php echo h($contact['Contact']['name']); ?></td>
                <td><?php echo h($contact['Contact']['body']); ?></td>
                <td>
                    <div class="msg-send">
                        <?php $sendContactUrl = '/Contacts/sendContact/' . $contact['Contact']['id']; ?>
                        <a href=<?php echo $sendContactUrl; ?> class="msg-modal btn btn-outline-primary"><?php echo __('Send Back'); ?></a>
                    </div>
                    <div class="delete">
                        <?php echo $this->Form->postLink(
                            __('Delete'),
                            array('controller' => 'contacts',
                                  'action' => 'delete',
                                  $contact['Contact']['id']),
                            array('confirm' => 'Are you sure?',
                                  'class' => 'btn btn-outline-danger')
                        ); ?>
                    </div>
                </td>
            </tr>
        <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <div id="mobile-index">
        <hr>
        <?php foreach ($contacts as $contact): ?>
            <h3><?php echo __('Name'); ?></h3>
            <p><?php echo h($contact['Contact']['name']); ?></p>
            <h3><?php echo __('Body'); ?></h3>
            <p><?php echo h($contact['Contact']['body']); ?></p>
            <div class="container-fluid">
                <div class="row">
                    <div class="msg-send col-6">
                        <?php $sendContactUrl = '/Contacts/sendContact/' . $contact['Contact']['id']; ?>
                        <a href=<?php echo $sendContactUrl; ?> class="msg-modal btn btn-outline-primary btn-block"><?php echo __('Send Back'); ?></a>
                    </div>
                    <div class="delete col-6">
                        <?php echo $this->Form->postLink(
                            __('Delete'),
                            array('controller' => 'contacts',
                                  'action' => 'delete',
                                  $contact['Contact']['id']),
                            array('confirm' => 'Are you sure?',
                                  'class' => 'btn btn-outline-danger btn-block')
                        ); ?>
                    </div>
                </div>
            </div>
            <hr>
        <?php endforeach; ?>
    </div>
        <?php echo $this->element('bootstrap-paginate'); ?>
</div>
