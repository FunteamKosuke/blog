<?php
  class Post extends AppModel {
    public $order = array('Post.id DESC');
    public $hasOne = 'Thumbnail';
    public $belongsTo = array('Category', 'User');
    public $hasMany = 'Image';
    public $hasAndBelongsToMany = array(
    'Tag' => array(
        'className' => 'Tag',
        'joinTable' => 'posts_tags',
        'foreignKey' => 'post_id',
        'associationForeignKey' => 'tag_id',
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
        // 'title' => array(
        //     'rule' => 'notBlank'
        // ),
        // 'body' => array(
        //     'rule' => 'notBlank'
        // ),
        'body' => array(
            'rule' => array('multiple', array('min' => 1, 'max' => 3)),
            'message'  => '興味のある物を選択してください（1個～3個）',
        ),
        'Tag.Tag' => array(
            'rule' => array('multiple', array('min' => 1, 'max' => 3)),
            'message'  => '興味のある物を選択してください（1個～3個）',
        )
    );

    public function isOwnedBy($post, $user) {
      return $this->field('id', array('id' => $post, 'user_id' => $user)) !== false;
    }

    // Searchプラグインを使用するのに必要な設定
    public $actsAs = array(
        'Search.Searchable',
        'Containable'
    );
    public $filterArgs = array(
        'title' => array('type' => 'like'),
        // 'category_id' => array('type' => 'value'),
        // Tag OR検索
        // array('name' => 'tag_id', 'type' => 'subquery', 'method' => 'searchTagOr', 'field' => 'Post.id'),
        // Tag AND検索
        // array('name' => 'tag_id', 'type' => 'subquery', 'method' => 'searchTagAnd', 'field' => 'Post.id'),
    );

    // タグのOR検索するメソッド
    function searchTagOr($data = array()) {

      $this->PostsTag->Behaviors->attach('Containable', array('autoFields' => false));
      // $this->PostsTag->Behaviors->attach('Search.Searchable');

      // getQueryを使用してsql文を作成する。
      $query = $this->PostsTag->getQuery('all', array(
			'conditions' => array(
				'tag_id' => $data['tag_id']
			),
			'fields' => array(
				'post_id'
			),
			'contain' => array(
				'Tag'
			)
		));

    	/*  タグをOR検索するためのqueryを作成する */
    	// $query = "SELECT PostsTag.post_id FROM cakephp_blog.posts_tags AS PostsTag LEFT JOIN cakephp_blog.tags AS Tag ON (PostsTag.tag_id = Tag.id)  WHERE ";

      // tagの検索条件を指定する。
    	// foreach($data['tag_id'] as $tag){
      //   $query .= "Tag.id = ";
      //   $query .= $tag;
      //   if($tag !== end($data['tag_id'])){
      //     $query .= ' OR ';
      //   }
      // }
      return $query;
    }

    // タグのAND検索するメソッド
    function searchTagAnd($data = array()) {
      $this->PostsTag->Behaviors->attach('Containable', array('autoFields' => false));
      $this->PostsTag->Behaviors->attach('Search.Searchable');

      // getQueryを使用してsql文を作成する。
      $query = $this->PostsTag->getQuery('all', array(
			'conditions' => array(
				'tag_id' => $data['tag_id']
			),
			'fields' => array(
				'post_id'
			),
			'contain' => array(
				'Tag'
			)
		));
      // 取得した記事をgroup化し、取得できた記事の数がタグの数と同じ記事だけ取得できるようにする。
      if (( $c = count ( $data['tag_id'] )) !== 1 ){
         $query .= ' GROUP BY PostsTag.post_id HAVING COUNT(PostsTag.post_id) = '.$c;
      }
      // 作成したqueryを返却する。
      return $query;
    }
  }
?>
