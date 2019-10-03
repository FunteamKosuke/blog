<?php

  class PostsController extends AppController {
    // Postモデル以外のモデルを使用できるようにする。
    public $uses = array('Post', 'Category', 'Tag', 'Image', 'Thumbnail');
    public $helpers = array('Html', 'Form');
    public $components = array('Search.Prg');
    public $presetVars = true;
    // index.ctpの記事一覧表示の際のpaginateの表示設定 find.ctp（検索結果表示）には適用されないので気をつける。
    public $paginate = array(
        'limit' => 4
    );

    public function index() {
        // 検索フォームのカテゴリのプルダウン用のデータを取得する。
        $categories = $this->Post->Category->find('list');
        $this->set(compact('categories'));
        // 検索フォームのタグのプルダウン用のデータを取得する。
        $tags = $this->Post->Tag->find('list');
        $this->set(compact('tags'));
        // 一覧表示をpaginate機能で表示させる。
        $this->set('posts', $this->paginate());
    }

    // 検索結果を表示する。
    public function find(){
      $this->Post->recursive = 0;
      $this->Prg->commonProcess();
      $this->paginate = array(
          'conditions' => $this->Post->parseCriteria($this->passedArgs), // 検索する条件を設定する。
          'limit' => 4, // 検索結果を４件ごとに表示する。
      );
      $this->set('posts', $this->paginate()); // paginate機能を利用して表示する。
    }

    public function view($id = null) {
      if (!$id) {
          throw new NotFoundException(__('Invalid post'));
      }

      $post = $this->Post->findById($id);
      if (!$post) {
          throw new NotFoundException(__('Invalid post'));
      }
      $this->set('post', $post);
    }

    public function add(){
      // Viewでカテゴリをプルダウンメニューで表示するためにタグのデータを全て取得する。
      $this->set('categories',$this->Category->find('list', array('fields'=>array('id','name'))));
      // ViewでタグをSELECTボックスで表示するためにタグのデータを全て取得する。
      $this->set( 'tags', $this->Tag->find( 'list', array(
                                              'fields' => array( 'id', 'name'))));

      $post_body = array('yuou' => 'djghdjgfhkfgk', 'dhgjfg' => 'dghjghjkgfh', 'ghjf' => 'dtyjdykdg');
      $this->set('bodys', $post_body);
      // 記事の追加処理
      if ($this->request->is('post')) {
          $this->Tag->set($this->request->data['Tag']['Tag']);
          $this->Tag->validates();
        // アソシエーションの形式で保存するための配列を作成する。
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
        $this->log($this->request->data['Tag']['Tag']);
        $save_data['Tag']['Tag'] = $this->request->data['Tag']['Tag'];
        // 画像を投稿する。
        if ($this->request->data['Image']['files'][0]['name']) { //空のthumbnailが作成されるのを防ぐ
            $save_data['Image'] = array();
            foreach ($this->request->data['Image']['files'] as $file) {
                $image_data['image'] = $file;
                $save_data['Image'][] = $image_data;
            }
        }

        // サムネイルを設定する。
        if ($this->request->data['Thumbnail']['thumbnail']['name']) { //空のimageが作成されるのを防ぐ
            $save_data['Thumbnail'] = $this->request->data['Thumbnail'];
        }

        $this->log($save_data);
        // 記事をカテゴリとタグに関連づけて保存する。
        if($save_data = $this->Post->saveAll($save_data, array('deep' => true))){
            $this->Flash->success(__('記事を追加することに成功しました。'));
            return $this->redirect(array('action' => 'index'));
        }
      }
    }

    // 記事を編集する。
    public function edit($id = null) {
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
    public function delete($id) {
      if ($this->request->is('get')) {
          throw new MethodNotAllowedException();
      }
      // 空ではないか
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

      // 存在するか
      $post = $this->Post->findById($id);
      if (!$post) {
          throw new NotFoundException(__('Invalid post'));
      }

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
  }
?>
