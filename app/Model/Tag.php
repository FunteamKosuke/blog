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
            'rule1' => array(
                'rule' => 'notBlank',
                'message' => 'This is a required input item.'
            ),
            'rule2' => array(
                'rule' => 'isUnique',
                'message' => 'The input value is already in use.'
            ),
        ),
    );
  }
?>
