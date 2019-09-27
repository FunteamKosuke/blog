<?php
  class Category extends AppModel {
    public $hasMany = 'Post';
    public $validate = array(
        'name' => array(
            'rule' => 'notBlank'
        )
    );
  }
?>
