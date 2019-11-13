<?php
  App::uses('AppModel', 'Model');
  App::uses('BlowfishPasswordHasher', 'Controller/Component/Auth');

  class User extends AppModel {
    const NORMAL_USER_REGISTER = 'add';
    public $hasMany = array('Post', 'Message');

    public $actsAs = array(
        'Upload.Upload' => array(
            'profile_image' => array(
                'fields' => array(
                    'dir' => 'profile_image_dir'
                ),
                // 'path' => '{ROOT}webroot{DS}files{DS}{model}{DS}{field}{DS}',
                // 'mode' => 0777,
            )
        )
    );

    public $validate = array(
        'username' => array(
            'rule1' => array(
                'rule' => 'notBlank',
                'message' => 'This is a required input item.'
            ),
            'rule2' => array(
                'rule' => 'isUniqueUsername',
                'message' => 'The input value is already in use.'
            ),
        ),
        'email' => array(
            'rule1' => array(
                'rule' => 'notBlank',
                'message' => 'This is a required input item.'
            ),
            'rule2' => array(
                'rule' => 'isUnique',
                'message' => 'The input value is already in use.'
            ),
            // メールアドレスであること。
            'validEmail' => array( 'rule' => array( 'email', true), 'message' => 'Please enter your e-mail address'),
        ),
        'password' => array(
            'rule1' => array(
                'rule' => 'notBlank',
                'message' => 'This is a required input item.'
            ),
            'rule2' => array(
                'rule' => '/^[A-Z][0-9a-zA-Z]{7}/',
                'message' => 'Please enter a password with a minimum of 8 alphanumeric characters.'
            ),
            'rule3' => array(
                'rule' => 'passwordConfirm',
                'message' => 'It does not match the confirmation password.'
            ),
        ),
        'password_confirm' => array(
            array(
                'rule' => 'notBlank',
                'message' => 'This is a required input item.'
            ),
        ),
        'zipcode' => array(
            'rule' => '/\d{7}/',
            'message' => 'Enter the postal code as a 7-digit number.'
        ),
        'address' => array(
            'required' => array(
                'rule' => 'notBlank',
                'message' => 'This is a required input item.'
            )
        ),
        'role' => array(
            'valid' => array(
                'rule' => array('inList', array('admin', 'author')),
                'message' => 'Please enter a valid role',
                'allowEmpty' => false
            )
        )
    );

    public function beforeSave($options = array()) {
      if (isset($this->data[$this->alias]['password'])) {
          $passwordHasher = new BlowfishPasswordHasher();
          $this->data[$this->alias]['password'] = $passwordHasher->hash(
              $this->data[$this->alias]['password']
          );
      }
      return true;
    }

    public function passwordConfirm($check){
        //２つのパスワードフィールドが一致する事を確認する
        if($this->data['User']['password'] === $this->data['User']['password_confirm']){
            return true;
        }else{
            return false;
        }
    }

    // そのままisUniqueを使用すると、通常登録以外のユーザー名も判定をしてしまうので、通常登録のみ適用できる様にする。
    public function isUniqueUsername(){
        $return = true;
        if (isset($this->data['User']['action']) && $this->data['User']['action'] === self::NORMAL_USER_REGISTER) {
            $username = $this->data['User']['username'];
            $user = $this->find('first', array('conditions' => array('username' => $username,
                                                                    'provider' => 'normal')));
            if ($user) {
                $return = false;
            }
        }
        return $return;
    }

    public function getActivationToken() {
        // ユーザIDの有無確認
        if (!isset($this->id)) {
            return false;
        }
        // 推測できなさそうなトークンを作成する。
        $hash = date("YmdHis").$this->id.$this->id.date("YmdHis");
        return $hash;
    }
  }
?>
