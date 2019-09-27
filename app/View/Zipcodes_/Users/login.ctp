<div class="users form">
<?php echo $this->Flash->render('auth'); ?>
<?php echo $this->Form->create('User'); ?>
    <fieldset>
        <legend>
            <?php echo __('ユーザー名とパスワードを入力してください。'); ?>
        </legend>
        <div class="form-group">
            <h3>ユーザー名</h3>
            <?php echo $this->Form->input('username', array('label' => false, 'class' => 'form-control')); ?>
        </div>
        <div class="form-group">
            <h3>パスワード</h3>
            <?php echo $this->Form->input('password', array('label' => false, 'class' => 'form-control')); ?>
        </div>
    </fieldset>
    <label class='label-submit btn btn-outline-primary btn-block' for="label-submit">
        ログインする
    <?php echo $this->Form->end(array('id' => 'label-submit')); ?>
    </label>
</div>
