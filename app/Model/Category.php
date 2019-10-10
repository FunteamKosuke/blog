<?php
  class Category extends AppModel {
    public $hasMany = 'Post';
    public $validate = array(
        'name' => array(
            'rule1' => array(
                'rule' => 'notBlank',
                'message' => 'This is a required input item.'
            ),
            'rule2' => array(
                'rule' => 'isUnique',
                'message' => 'The input value is already in use.'
            ),
        )
    );
  }
?>
