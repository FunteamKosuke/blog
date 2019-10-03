<?php
  class Tag extends AppModel {
    // public $hasAndBelongsToMany = 'Post';
    public $hasAndBelongsToMany = array(
    'Post' => array(
        'className' => 'Post',
        'joinTable' => 'posts_tags',
        'foreignKey' => 'tag_id',
        'associationForeignKey' => 'post_id',
        'unique' => true,
        'conditions' => '',
        'fields' => '',
        'with' => 'PostsTag',
        'order' => '',
        'limit' => '',
        'offset' => '',
        ),
      );
    public $validate = array(
        'name' => array(
            'rule' => 'notBlank'
        ),
        'Tag' => array(
            'rule' => array('multiple', array('min' => 1, 'max' => 3)),
            'message'  => '興味のある物を選択してください（1個～3個）',
        )
    );
  }
?>
