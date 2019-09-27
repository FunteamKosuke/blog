<!-- app/View/Users/add.ctp -->
<div id="users__add" class="users form">
<?php echo $this->Form->create('User', array('url' => 'add')); ?>
    <fieldset>
        <legend><?php echo __('ユーザーの追加'); ?></legend>
        <div class="form-group">
            <h3>ユーザー名</h3>
            <?php echo $this->Form->input('username', array('label' => false,
                                                            'class' => 'form-control')); ?>
        </div>
        <div class="form-group">
             <h3>パスワード</h3>
            <?php echo $this->Form->input('password', array('label' => false,
                                                            'class' => 'form-control')); ?>
        </div>
        <h3>郵便番号</h3>
        <small>*郵便番号検索をすることで住所欄に自動入力されます。</small>
        <div class="form-group form-inline">
            <?php echo $this->Form->input('zipcode', array('label' => false, 'id' => 'zipcode', 'class' => 'form-control')); // プルダウンメニュー ?>
            <label  id="address-search" class='btn btn-outline-primary form-control'>検索</label>
        </div>
        <!-- 郵便番号に複数の町域が含まれていた時に使用する。 -->
        <small id="twon_msg"></small><br>
        <div class="form-group form-inline">
            <select id="town-select" class='form-control'></select>
        </div>
        <div class="form-group">
            <h3>住所</h3>
            <?php echo $this->Form->input('address', array('label' => false, 'id' => 'address', 'class' => 'form-control')); ?>
        </div>
        <div class="form-group">
            <h3>ユーザーに付与する権限</h3>
            <?php echo $this->Form->input('role', array(
                'label' => false,
                'options' => array('admin' => 'Admin', 'author' => 'Author'),
                'class' => 'form-control'
            )); ?>
        </div>
    </fieldset>
    <label class='label-submit btn btn-outline-primary btn-block' for="label-submit">
        ユーザーを追加する
    <?php echo $this->Form->end(array('id' => 'label-submit')); ?>
    </label>
</div>
