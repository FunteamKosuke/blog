<div id="msg-modal">
<div class="background"></div>
<div class="container"></div>
</div><!-- modal -->

<div id="contacts__index">
    <h1><?php echo __('Contact Index'); ?></h1>
        <table class="table">
          <thead>
            <tr>
              <th scope="col"><?php echo __('Name'); ?></th>
              <th scope="col"><?php echo __('Body'); ?></th>
              <th scope="col"><?php echo __('Action'); ?></th>
            </tr>
          </thead>
          <tbody>
        <?php foreach ($contacts as $contact): ?>
            <tr>
                <td><?php echo $contact['Contact']['name']; ?></td>
                <td><?php echo $contact['Contact']['body']; ?></td>
                <td>
                    <div class="msg-send">
                        <?php $sendContactUrl = '/Contacts/sendContact/' . $contact['Contact']['id']; ?>
                        <a href=<?php echo $sendContactUrl; ?> class="msg-modal btn btn-outline-primary"><?php echo __('Send Contact'); ?></a>
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
