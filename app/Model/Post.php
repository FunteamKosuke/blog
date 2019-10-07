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
        'title' => array(
            'rule' => 'notBlank'
        ),
        'body' => array(
            'rule' => 'notBlank',
            'message'  => '記事の内容を入力してください。',
        ),
        'Tag' => array(
            'rule' => array('multiple', array( 'min' => 2, 'max' => 4)),
            'message'  => 'タグを選択してください(2~4個)',
        ),
        'thumbnail' => array(

            // ルール：uploadError => errorを検証 (2.2 以降)
            'upload-file' => array(
                'rule' => array( 'uploadError'),
                'message' => array( 'ファイルのアップロードに失敗しました。')
                // 'required' => false
            ),

            // ルール：extension => pathinfoを使用して拡張子を検証
            'extension' => array(
                'rule' => array( 'extension', array(
                    'jpeg', 'jpg')  // 拡張子を配列で定義
                ),
                'message' => array( 'ファイルの拡張子はjpgとjpegのみ指定可能です。')
            ),

            // ルール：mimeType =>
            // finfo_file(もしくは、mime_content_type)でファイルのmimeを検証 (2.2 以降)
            // 2.5 以降 - MIMEタイプを正規表現(文字列)で設定可能に
            'mimetype' => array(
                'rule' => array( 'mimeType', array(
                    'image/jpeg')  // MIMEタイプを配列で定義
                ),
                'message' => array( 'MIME typeはimage/jpegのみ指定可能です。')
            ),

            // ルール：fileSize => filesizeでファイルサイズを検証(2GBまで設定可能)  (2.3 以降)
            'size' => array(
                'maxFileSize' => array(
                    'rule' => array( 'fileSize', '<=', '10MB'),  // 10M以下
                    'message' => array( 'ファイルサイズは1~10MBのみ指定可能です。')
                ),
                'minFileSize' => array(
                    'rule' => array( 'fileSize', '>',  0),    // 0バイトより大
                    'message' => array( 'ファイルサイズは1~10MBのみ指定可能です。')
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
    public $actsAs = array(
        'Search.Searchable',
        'Containable',
        'SoftDelete'
    );
    public $filterArgs = array(
        array('name' => 'keyword', 'type' => 'like'),
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
