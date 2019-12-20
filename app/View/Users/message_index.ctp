
<div id="users__message-index">
    <h1><?php echo __('Message Index'); ?></h1>
    <table class="table">
      <thead>
        <tr>
          <th scope="col"><?php echo __('ID'); ?></th>
          <th scope="col"><?php echo __('Message'); ?></th>
        </tr>
      </thead>
      <tbody>
    <?php foreach ($messages as $message): ?>
        <tr>
            <td><?php echo $message['Message']['id']; ?></td>
            <td><?php echo $message['Message']['body']; ?></td>
        </tr>
    <?php endforeach; ?>
        </tbody>
    </table>
    <?php echo $this->element('bootstrap-paginate'); ?>
</div>
