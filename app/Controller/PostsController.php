<?php

  class PostsController extends AppController {

    // Postモデル以外のモデルを使用できるようにする。
    public $uses = array('Post', 'Category', 'Tag','Image', 'Thumbnail');
    public $helpers = array('Html', 'Form');
    public $components = array('Search.Prg');
    public $presetVars = true;

    public function beforeFilter(){
        parent::beforeFilter();
        $this->Auth->allow('view', 'index', 'postDateRelatedPost', 'find');
    }

    public function index() {

        $this->paginate = array(
            'conditions' => array('publish_flg' => parent::PUBLISH), // 検索する条件を設定する。
            'limit' => parent::POST_LIST_LIMIT, // 検索結果を４件ごとに表示する。
        );
        // 一覧表示をpaginate機能で表示させる。
        $this->set('posts', $this->paginate());
    }

    public function view($id = null) {
      self::checkId($id);

      $post = $this->Post->findById($id);
      // 記事のアクセス数をカウントする。
      ++$post['Post']['access'];
      $this->Post->save($post);

      $this->set('post', $post);

      // 記事の最大数を取得する。
      $posts_max = $this->Post->find('count', array('conditions' => array('publish_flg' => parent::PUBLISH)));

      // 次の記事のidを設定する。
      if ($posts_max > $id) {
          $this->set('prev_id', $id+1);
      }

      // 前の記事のidを設定する。
      if ($id > 1) {
          $this->set('next_id', $id-1);
      }

      // 関連記事として表示するための記事を取得する。
      $this->set('related_post', $this->Post->find('all', array(
          'conditions' => array('category_id' => $post['Post']['category_id']), // 検索する条件を設定する。
          'limit' => parent::RELATED_POST_LIST_LIMIT,
      )));


    }

    public function add(){
      // Viewでカテゴリをプルダウンメニューで表示するためにタグのデータを全て取得する。
      $this->set('categories',$this->Category->find('list', array('fields'=>array('id','name'))));
      // Viewでタグをチェックボックスで表示するためにタグのデータをリストで全て取得する。
      $this->set( 'tags', $this->Tag->find( 'list', array('fields' => array( 'id', 'name'))));

      // 記事の追加処理
      if ($this->request->is('post')) {
        // 記事の情報を設定する
        $save_data['Post'] = $this->request->data['Post'];
        // 記事の投稿者を設定する
        $save_data['Post']['user_id'] = $this->Auth->user('id');
        // 記事をカテゴリに関連づけるために、カテゴリのidを['Category']['id']の形で格納する。
        $save_data['Category']['id'] = $this->request->data['Category']['category_id'];
        // スペース区切りのタグを取得し、それをまとめて保存できるような形式の配列を作成する。
        // $tag_array = preg_split('/[\s|\x{3000}]+/u', $this->request->data['Tag']['tag_str']);
        // $tag_data = array();
        // // 指定したタグを全て保存する
        // foreach ($tag_array as $tag) {
        //     // 既に追加されているタグは保存しない。
        //     if (!($this->Post->Tag->findByName($tag))) {
        //         $data['Tag']['name'] = $tag;
        //         $tag_data[] = $data;
        //     }
        // }
        // $this->Post->Tag->saveAll($tag_data);
        // // 保存したタグのidを取得する。
        // $tag_id = array();
        // foreach ($tag_array as $tag_name) {
        //     $tag_id[] = $this->Post->Tag->findByName($tag_name)['Tag']['id'];
        // }
        // $save_data['Tag']['Tag'] = $tag_id;
        // チェックボックスでタグを設定する。
        $save_data['Tag'] = $this->request->data['Tag'];
        // 画像を投稿する。
        // $this->log($this->request->data);
        // $this->log($this);
        if ($this->request->data['Image']['files'][0]['name']) { //空のimageが作成されるのを防ぐ
            $save_data['Image'] = array();
            foreach ($this->request->data['Image']['files'] as $file) {
                $image_data['image'] = $file;
                $save_data['Image'][] = $image_data;
            }
        }

        // if ($this->request->data['PostImage']['files'][0]['name']) { //空のimageが作成されるのを防ぐ
        //     $save_data['Image'] = array();
        //     foreach ($this->request->data['PostImage']['files'] as $file) {
        //         $image_data['attachment'] = $file;
        //         $image_data['model'] = 'Post';
        //         $save_data['Image'][] = $image_data;
        //     }
        // }

        // サムネイルを設定する。
        if ($this->request->data['Thumbnail']['thumbnail']['name']) { //空のthmbnailが作成されるのを防ぐ
            $save_data['Thumbnail']['thumbnail'] = $this->request->data['Thumbnail']['thumbnail'];
        }

        // 公開か非公開を表すフラグを設定する。1:公開 0:非公開
        $save_data['Post']['publish_flg'] = $this->request->data['publish_flg'];

        // 記事を保存する。
        if($save_data && $this->Post->saveAll($save_data, array('deep' => true))){
            $this->Flash->success(__('Successfully added an article.'));
            return $this->redirect(array('action' => 'complete'));
        }
        //タグのエラーがあったらエラーメッセージを取得する。タグは別途取得しないとviewに表示できない。
        $errors = $this->Post->validationErrors;
        if(!empty ($errors['Tag'])) {
            $this->set('tagerror', $errors['Tag']);
        }
        $this->Flash->error(__('Failed to add article.'));
      }
    }

    public function complete(){

    }

    // 記事を編集する。
    public function edit($id = null) {
      self::checkId($id);

      if ($this->request->is(array('post', 'put'))) {
          $this->Post->id = $id;
          if ($this->Post->save($this->request->data)) {
              $this->Flash->success(__('Your post has been updated.'));
              return $this->redirect(array('action' => 'index'));
          }
          $this->Flash->error(__('Unable to update your post.'));
      }

      if (!$this->request->data) {
          $this->request->data = $post;
      }
    }

    // 記事を削除する。
    public function delete($id = null) {
      if ($this->request->is('get')) {
          throw new MethodNotAllowedException();
      }

      self::checkId($id);

      if ($this->Post->delete($id)) {
          $this->Flash->success(
              __('The post with id: %s has been deleted.', h($id))
          );
      } else {
          $this->Flash->error(
              __('The post with id: %s could not be deleted.', h($id))
          );
      }

      return $this->redirect(array('action' => 'index'));
    }

    public function postDateRelatedPost($post_id = null){
        self::checkId($post_id);
        $post = $this->Post->findById($post_id);
        $post_date = explode(" ", $post['Post']['created'])[0];

        // タイトルに使用する。
        $this->set('post_date', $post_date);

        // 記事の取得条件を設定する。
        $this->paginate = array(
            'conditions' => array('Post.created LIKE' => '%'.$post_date.'%',
                                    'publish_flg' => parent::PUBLISH), // 検索する条件を設定する。
            'limit' => parent::POST_LIST_LIMIT, // 検索結果を４件ごとに表示する。
        );
        // 一覧表示をpaginate機能で表示させる。
        $this->set('posts', $this->paginate());
    }
    /******** 下書き関連 **********/
    // 下書きを一覧で表示する。
    public function draftIndex(){
        $this->paginate = array(
            'conditions' => array('publish_flg' => parent::NO_PUBLISH), // 非公開のもののみ取得する。
            'limit' => parent::POST_LIST_LIMIT, // 検索結果を４件ごとに表示する。
        );
        $this->set('draft_posts', $this->paginate());
    }
    // 下書きを公開状態にする。
    public function publishDraft($id = null){
        if ($this->request->is('get')) {
            throw new MethodNotAllowedException();
        }
        self::checkId($id);

        $draft_post = $this->Post->findById($id);
        $draft_post['Post']['publish_flg'] = parent::PUBLISH;
        if ($draft_post && $this->Post->save($draft_post)) {
            $this->Flash->success(__('An article has been published.'));
            return $this->redirect(array('action' => 'index'));
        }
        $this->Flash->error(__('The article could not be published.'));
    }

    // 下書きを編集する。
    public function editDraft($id = null){
        self::checkId($id);

        $draft_post = $this->Post->findById($id);

        $this->set('draft_post', $draft_post);

        // 編集画面でカテゴリをセレクトボックス で選択できるようにlistでデータを取得する。
        $this->set( 'categories', $this->Category->find( 'list', array(
                                                                'fields' => array( 'id', 'name')
                                                                )));

        // 編集画面でタグを一覧で表示するためにlistでデータを取得する。
        $this->set( 'tags', $this->Tag->find( 'list',
                                                array(
                                                    'fields' => array(
                                                                    'id',
                                                                    'name')
                                                    )
                                            )
                );

        if ($this->request->is(array('post', 'put'))) {
            $save_data = $this->request->data;
            // publish_flgは['Post']['publish_flg']の形で受け取れなかったので、別途格納する。
            $save_data['Post']['publish_flg'] = $this->request->data['publish_flg'];
            $this->Post->id = $id;
            if ($save_data && $this->Post->save($save_data)) {
                $this->Flash->success(__('Updated the article.'));
                return $this->redirect(array('action' => 'draftIndex'));
            }
            $this->Flash->error(__('The article could not be updated.'));
        }

        if (!$this->request->data) {
            $this->request->data = $draft_post;
        }
    }

    /********* 検索関連 ***********/

    // 検索結果を表示する。
    public function find(){
      $this->Post->recursive = 0;
      $this->Prg->commonProcess();
      $this->paginate = array(
          'conditions' => $this->Post->parseCriteria($this->passedArgs), // 検索する条件を設定する。
          'limit' => parent::POST_LIST_LIMIT, // 検索結果を４件ごとに表示する。
      );
      $this->set('posts', $this->paginate()); // paginate機能を利用して表示する。

      $this->set('keyword', $this->request->data['Post']['keyword']);
    }



    /********** 認証関連 **********/

    public function isAuthorized($user) {
      // 登録済ユーザーは投稿できる
      if ($this->action === 'add') {
          return true;
      }

      // 投稿のオーナーは編集や削除ができる
      if (in_array($this->action, array('edit', 'delete'))) {
          $postId = (int) $this->request->params['pass'][0];
          if ($this->Post->isOwnedBy($postId, $user['id'])) {
              return true;
          }
      }

      return parent::isAuthorized($user);
    }

    private function checkId($id){
        if (!$id) {
            throw new NotFoundException(__('Invalid post'));
        }

        // 数値以外なら
        if (!is_numeric($id)) {
            throw new NotFoundException(__('Invalid post'));
        }

        // idで表現できる最大値を超えていないか
        if (parent::ID_MAX < $id) {
            throw new NotFoundException(__('Invalid post'));
        }

        $post = $this->Post->findById($id);
        if (!$post) {
            throw new NotFoundException(__('Invalid post'));
        }
    }
  }
?>
