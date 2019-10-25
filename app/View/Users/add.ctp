<!-- app/View/Users/add.ctp -->
<div id="users__add" class="users form">
<?php echo $this->Form->create('User', array('url' => 'add', 'type'=>'file')); ?>
    <fieldset>
        <legend><?php echo __('Sign Up User'); ?></legend>
        <div class="form-group">
            <h3><?php echo __('Add Profile Image'); ?></h3>
            <label class="label-file btn btn-outline-primary" for="label-file-profile-image">
            <?php echo __('Select Image File'); ?>
            <?php /// サムネイルを設定する。
            echo $this->Form->input('profile_image', array(
                                                                'type' => 'file',
                                                                'id' => 'label-file-profile-image',
                                                                'class' => 'form-control-file label-file-name',
                                                                'error' => false)); ?>
            </label>
            <div class="form-group">
                <input type="text" id="file-name-profile-image" class="form-control file-name-input" readonly="readonly" placeholder="<?php echo __('No Select'); ?>">
            </div>
        </div>
        <div class="form-group">
            <h5><?php echo __('User Name'); ?></h5>
            <?php echo $this->Form->input('username', array('label' => false,
                                                            'class' => 'form-control')); ?>
        </div>
        <div class="form-group">
             <h5><?php echo __('Password'); ?></h5>
            <?php echo $this->Form->input('password', array('label' => false,
                                                            'class' => 'form-control')); ?>
        </div>
        <h5><?php echo __('Zipcode'); ?></h5>
        <small>*<?php echo __('It is automatically entered in the address field by performing a postal code search.'); ?></small>
        <div class="form-group">
            <?php echo $this->Form->input('zipcode', array('label' => false, 'id' => 'zipcode', 'class' => 'form-control')); // プルダウンメニュー ?>
        </div>
        <!-- 郵便番号に複数の町域が含まれていた時に使用する。 -->
        <small id="address_msg"></small>
        <div class="form-group form-inline">
            <select id="address-select" class='form-control'></select>
        </div>
        <div class="form-group">
            <h5><?php echo __('Address'); ?></h5>
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
            <h5><?php echo __('Select Address'); ?></h5>
            <?php echo $this->Form->input('sl_address', array('label' => false, 'id' => 'select-address', 'class' => 'form-control')); ?>
        </div>
        <div class="form-group">
            <h5><?php echo __('User Authority'); ?></h5>
            <?php echo $this->Form->input('role', array(
                'label' => false,
                'options' => array('admin' => 'Admin', 'author' => 'Author'),
                'class' => 'form-control'
            )); ?>
        </div>
    </fieldset>
    <label class='label-submit btn btn-outline-primary btn-block' for="label-submit">
        <?php echo __('Add'); ?>
    <?php echo $this->Form->end(array('id' => 'label-submit')); ?>
    </label>
</div>
