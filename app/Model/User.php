<?php
  App::uses('AppModel', 'Model');
  App::uses('BlowfishPasswordHasher', 'Controller/Component/Auth');

  class User extends AppModel {
    public $hasMany = array('Post', 'Message');

    public $actsAs = array(
        'Upload.Upload' => array(
            'profile_image' => array(
                'fields' => array(
                    'dir' => 'profile_image_dir'
                ),
                'path' => '{ROOT}webroot{DS}files{DS}{model}{DS}{field}{DS}',
                'mode' => 0777,
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

    public function getActivationHash() {
        // ユーザIDの有無確認
        if (!isset($this->id)) {
            return false;
        }
        // 更新日時をハッシュ化
        return Security::hash( $this->field('modified'), 'md5', true);
    }
  }
?>
