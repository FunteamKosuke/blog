<?php
  App::uses('AppModel', 'Model');
  App::uses('BlowfishPasswordHasher', 'Controller/Component/Auth');

  class User extends AppModel {
    public $hasMany = 'Post';
    public $validate = array(
        'username' => array(
            'rule1' => array(
                'rule' => 'notBlank',
                'message' => 'ユーザー名は必ず入力してください。'
            ),
            'rule2' => array(
                'rule' => 'isUnique',
                'message' => 'そのユーザー名はすでに使われています。'
            ),
        ),
        'password' => array(
            'rule1' => array(
                'rule' => 'notBlank',
                'message' => 'A password is required'
            ),
            'rule2' => array(
                'rule' => '/^[A-Z][0-9a-zA-Z]{7}/',
                'message' => 'パスワードは半角英数字の先頭大文字、最低８文字で設定してください。'
            ),
        ),
        'zipcode' => array(
            'rule' => '/\d{7}/',
            'message' => '郵便番号は7桁の数字で入力してください。'
        ),
        'address' => array(
            'required' => array(
                'rule' => 'notBlank',
                'message' => 'A address is required'
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
  }
?>
