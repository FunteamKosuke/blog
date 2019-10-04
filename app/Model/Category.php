<?php
  class Category extends AppModel {
    public $hasMany = 'Post';
    public $validate = array(
        'name' => array(
            'rule1' => array(
                'rule' => 'notBlank',
                'message' => 'カテゴリ名は必ず入力してください。'
            ),
            'rule2' => array(
                'rule' => 'isUnique',
                'message' => 'そのカテゴリ名はすでに使用されています。'
            ),
        )
    );
  }
?>
