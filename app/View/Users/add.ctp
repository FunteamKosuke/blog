<!-- app/View/Users/add.ctp -->
<div id="users__add" class="users form">
<?php echo $this->Form->create('User', array('url' => 'add', 'type'=>'file')); ?>
    <fieldset>
        <h1><?php echo __('Sign Up User'); ?></h1>
        <?php
        $user = $this->Session->read('User');
        if (isset($validate)) { //バリデーションエラーがあった場合
            $image_name = '未選択'; //画像の名前を渡しても実データはformに渡ってないので、未選択とする。
            $username   = $validate['User']['username'] ? $validate['User']['username'] : '';
            $email      = $validate['User']['email'] ? $validate['User']['email'] : '';
            $zipcode    = $validate['User']['zipcode'] ? $validate['User']['zipcode'] : '';
            $address    = $validate['User']['address'] ? $validate['User']['address'] : '';
            $sl_address = $validate['User']['sl_address'] ? $validate['User']['sl_address'] : '';
            $role       = $validate['User']['role'] ? $validate['User']['role'] : '';
        } elseif (isset($user)) { //セッションデータがある場合。(確認から修正した場合)
            $image_name = $user['User']['profile_image']['name'] ? $user['User']['profile_image']['name'] : '未選択';
            $username   = $user['User']['username'] ? $user['User']['username'] : '';
            $email      = $user['User']['email'] ? $user['User']['email'] : '';
            $zipcode    = $user['User']['zipcode'] ? $user['User']['zipcode'] : '';
            $address    = $user['User']['address'] ? $user['User']['address'] : '';
            $sl_address = $user['User']['sl_address'] ? $user['User']['sl_address'] : '';
            $role       = $user['User']['role'] ? $user['User']['role'] : '';
        } else { //そのほか
            $image_name = '未選択';
            $username   = '';
            $email      = '';
            $zipcode    = '';
            $address    = '';
            $sl_address = '';
            $role       = '';
        }

        ?>
        <div class="form-group">
            <h5><?php echo __('Add Profile Image'); ?></h5>
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
                <input type="text" id="file-name-profile-image" class="form-control file-name-input" readonly="readonly" placeholder="<?php echo $image_name; ?>">
            </div>
        </div>
        <div class="form-group">
            <h5><?php echo __('Huri Hira'); ?></h5>
            <div class="form-group form-inline">
                性
                <?php echo $this->Form->input('huri_hira_sei', array('label' => false,
                                                                'class' => 'form-control',
                                                                'size'  =>  19,
                                                                'value' => $username)); ?>
                名
                <?php echo $this->Form->input('huri_hira_mei', array('label' => false,
                                                                'class' => 'form-control',
                                                                'size'  =>  19,
                                                                'value' => $username)); ?>
            </div>
        </div>
        <div class="form-group">
            <h5><?php echo __('Huri Kata'); ?></h5>
            <div class="form-group form-inline">
                性
                <?php echo $this->Form->input('huri_kata_sei', array('label' => false,
                                                                'class' => 'form-control',
                                                                'size'  =>  19,
                                                                'value' => $username)); ?>
                名
                <?php echo $this->Form->input('huri_kata_mei', array('label' => false,
                                                                'class' => 'form-control',
                                                                'size'  =>  19,
                                                                'value' => $username)); ?>
            </div>
        </div>
        <div class="form-group">
            <h5><?php echo __('Name'); ?></h5>
            <div class="form-group form-inline">
                性
                <?php echo $this->Form->input('huri_kanzi_sei', array('label' => false,
                                                                'class' => 'form-control',
                                                                'size'  =>  19,
                                                                'value' => $username)); ?>
                名
                <?php echo $this->Form->input('huri_kanzi_mei', array('label' => false,
                                                                'class' => 'form-control',
                                                                'size'  =>  19,
                                                                'value' => $username)); ?>
            </div>
        </div>
        <div class="form-group">
            <h5><?php echo __('User Name'); ?></h5>
            <?php echo $this->Form->input('username', array('label' => false,
                                                            'class' => 'form-control',
                                                            'value' => $username)); ?>
        </div>
        <div class="form-group">
            <h5><?php echo __('E-Mail'); ?></h5>
            <?php echo $this->Form->input('email', array('label' => false,
                                                        'class' => 'form-control',
                                                        'value' => $email)); ?>
        </div>
        <div class="form-group">
             <h5><?php echo __('Password'); ?></h5>
            <?php echo $this->Form->input('password', array('label' => false,
                                                            'class' => 'form-control')); ?>
        </div>
        <div class="form-group">
             <h5><?php echo __('Password Confirm'); ?></h5>
            <?php echo $this->Form->input('password_confirm', array('label' => false,
                                                                    'type' => 'password',
                                                            'class' => 'form-control')); ?>
        </div>
        <h5><?php echo __('Zipcode'); ?></h5>
        <small>*<?php echo __('It is automatically entered in the address field by performing a postal code search.'); ?></small>
        <div class="form-group">
            <?php echo $this->Form->input('zipcode', array('label' => false,
                                                            'id' => 'zipcode',
                                                            'class' => 'form-control',
                                                            'value' => $zipcode)); // プルダウンメニュー ?>
        </div>
        <!-- 郵便番号に複数の町域が含まれていた時に使用する。 -->
        <small id="address_msg"></small>
        <div class="form-group form-inline">
            <select id="address-select" class='form-control'></select>
        </div>
        <div class="form-group">
            <h5><?php echo __('Address'); ?></h5>
            <?php echo $this->Form->input('address', array('label' => false,
                                                            'id' => 'address',
                                                            'class' => 'form-control',
                                                            'value' => $address)); ?>
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
            <?php echo $this->Form->input('sl_address', array('label' => false,
                                                                'id' => 'select-address',
                                                                'class' => 'form-control',
                                                                'value' => $sl_address)); ?>
        </div>
        <div class="form-group">
            <h5><?php echo __('User Authority'); ?></h5>
            <?php echo $this->Form->input('role', array(
                'label' => false,
                'options' => array('admin' => 'Admin', 'author' => 'Author'),
                'class' => 'form-control',
                'value' => $role
            )); ?>
        </div>
    </fieldset>
    <div class="form-group">
        <?php echo $this->Form->button(__('Confirm'), array(
            'type' => 'submit',
            'name' => 'mode',
            'value' => 'confirm',
            'class' => 'btn btn-outline-primary btn-block'
        )); ?>
    </div>
</div>
