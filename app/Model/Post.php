<?php
  class Post extends AppModel {
    public $order = array('Post.id DESC');
    public $hasOne = 'Thumbnail';
    public $belongsTo = array('Category', 'User');
    public $hasMany = 'Image';
    // public $hasMany = array(
    // 'Image' => array(
    //   'className' => 'Attachment',
    //   'foreignKey' => 'foreign_key',
    //   'conditions' => array(
    //     'Image.model' => 'Post',
    //   ),
    // ),
  // );
    // 使用するBehaviorの設定。
    public $actsAs = array(
        'Search.Searchable',
        'Containable',
        'SoftDelete'
    );
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
        'title' => array(
            'rule' => 'notBlank',
            'message'  => 'This is a required input item.',
        ),
        'body' => array(
            'rule' => 'notBlank',
            'message'  => 'This is a required input item.',
        ),
        'Tag' => array(
            'rule' => array('multiple', array( 'min' => 2, 'max' => 4)),
            'message'  => 'Please select a tag (2-4 pieces)',
        ),
        'thumbnail' => array(

            // // ルール：uploadError => errorを検証 (2.2 以降)
            // 'upload-file' => array(
            //     'rule' => array( 'uploadError'),
            //     'message' => array( 'ファイルのアップロードに失敗しました。')
            //     // 'required' => false
            // ),

            // ルール：extension => pathinfoを使用して拡張子を検証
            'extension' => array(
                'rule' => array( 'extension', array(
                    'jpeg', 'jpg')  // 拡張子を配列で定義
                ),
                'message' => array( 'Only jpg and jpeg file extensions can be specified.')
            ),

            // ルール：mimeType =>
            // finfo_file(もしくは、mime_content_type)でファイルのmimeを検証 (2.2 以降)
            // 2.5 以降 - MIMEタイプを正規表現(文字列)で設定可能に
            'mimetype' => array(
                'rule' => array( 'mimeType', array(
                    'image/jpeg')  // MIMEタイプを配列で定義
                ),
                'message' => array( 'MIME type can be specified only for image / jpeg.')
            ),

            // ルール：fileSize => filesizeでファイルサイズを検証(2GBまで設定可能)  (2.3 以降)
            'size' => array(
                'maxFileSize' => array(
                    'rule' => array( 'fileSize', '<=', '10MB'),  // 10M以下
                    'message' => array( 'Only 1 ~ 10MB file size can be specified.')
                ),
                'minFileSize' => array(
                    'rule' => array( 'fileSize', '>',  0),    // 0バイトより大
                    'message' => array( 'Only 1 ~ 10MB file size can be specified.')
                ),
            ),
        ),
    );

    function beforeValidate($options = array()) {
        // $this->log($this->hasAndBelongsToMany);
        // $this->log('before');
        // $this->log($this->data);
        // $this->log($this->alias);
        foreach($this->hasAndBelongsToMany as $k=>$v) {
            if(isset($this->data[$k][$k])) {
                $this->data[$this->alias][$k] = $this->data[$k][$k];
            }
        }
        return true;
    }

    public function isOwnedBy($post, $user) {
      return $this->field('id', array('id' => $post, 'user_id' => $user)) !== false;
    }

    // Searchプラグインを使用するのに必要な設定

    public $filterArgs = array(
        array('name' => 'keyword', 'type' => 'subquery', 'method' => 'search', 'field' => 'Post.id'),
        // 'category_id' => array('type' => 'value'),
        // Tag OR検索
        // array('name' => 'tag_id', 'type' => 'subquery', 'method' => 'searchTagOr', 'field' => 'Post.id'),
        // Tag AND検索
        // array('name' => 'tag_id', 'type' => 'subquery', 'method' => 'searchTagAnd', 'field' => 'Post.id'),
    );
    function search($data = array()){
        $this->Behaviors->attach('Containable', array('autoFields' => false));
        $this->Behaviors->attach('Search.Searchable');

        // $this->PostsTag->Behaviors->attach('Containable', array('autoFields' => false));
        // $this->PostsTag->Behaviors->attach('Search.Searchable');

        $this->log($data['keyword']);

        $query = $this->getQuery('all', array(
  			'conditions' => array(
  				'title LIKE' => "%".$data['keyword']."%"
  			),
            'fields' => array('Post.id'),
  		));

        // $this->log($this->find('all', array(
  		// 	'conditions' => array(
  		// 		'title' => $data['keyword']
  		// 	)
  		// )));
        $this->log($query);
        return $query;
    }

    // タグのOR検索するメソッド
    function searchTagOr($data = array()) {

      $this->PostsTag->Behaviors->attach('Containable', array('autoFields' => false));
      $this->PostsTag->Behaviors->attach('Search.Searchable');
      $this->log('呼ばれてるよ');

      // タグのIDを取得する。
      $tags = $this->Tag->find('all', array('conditions' => array('name' => $data['keyword'])));
      $tag_ids = array();
      foreach ($tags as $tag) {
          $tag_ids[] = $tag['Tag']['id'];
      }

      // getQueryを使用してsql文を作成する。
      $query = $this->PostsTag->getQuery('all', array(
			'conditions' => array(
				'tag_id' => $tag_ids
			),
			'fields' => array(
				'post_id'
			),
			'contain' => array(
				'Tag'
			)
		));

        $this->log($query);



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
