<!-- app/View/Users/add.ctp -->
<div id="users__add" class="users form">
<?php echo $this->Form->create('User', array('url' => 'add')); ?>
    <fieldset>
        <legend><?php echo __('ユーザーの登録'); ?></legend>
        <div class="form-group">
            <h5>ユーザー名</h5>
            <?php echo $this->Form->input('username', array('label' => false,
                                                            'class' => 'form-control')); ?>
        </div>
        <div class="form-group">
             <h5>パスワード</h5>
            <?php echo $this->Form->input('password', array('label' => false,
                                                            'class' => 'form-control')); ?>
        </div>
        <h5>郵便番号</h5>
        <small>*郵便番号検索をすることで住所欄に自動入力されます。</small>
        <div class="form-group">
            <?php echo $this->Form->input('zipcode', array('label' => false, 'id' => 'zipcode', 'class' => 'form-control')); // プルダウンメニュー ?>
        </div>
        <!-- 郵便番号に複数の町域が含まれていた時に使用する。 -->
        <small id="address_msg"></small>
        <div class="form-group form-inline">
            <select id="address-select" class='form-control'></select>
        </div>
        <div class="form-group">
            <h5>住所</h5>
            <?php echo $this->Form->input('address', array('label' => false, 'id' => 'address', 'class' => 'form-control')); ?>
        </div>
        <div class="container-fluid">
            <div class="form-group row">
                <!-- 地方選択ボックス -->
                <select id="region-select" class='form-control col-5'></select>
                <div class="col-1"></div>
                <!-- 都道府県選択ボックス -->
                <select id="pref-select" class='form-control col-5'></select>
            </div>
            <div class="form-group row">
                <!-- 市区町村選択ボックス -->
                <select id="city-select" class='form-control col-5'></select>
                <div class="col-1"></div>
                <!-- 町域選択ボックス -->
                <select id="town-select" class='form-control col-5'></select>
            </div>
        </div>

        <div class="form-group">
            <h5>選択住所</h5>
            <?php echo $this->Form->input('sl_address', array('label' => false, 'id' => 'select-address', 'class' => 'form-control')); ?>
        </div>
        <div class="form-group">
            <h5>ユーザーに付与する権限</h5>
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
