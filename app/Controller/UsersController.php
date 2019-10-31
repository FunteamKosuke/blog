<?php
  App::uses('AppController', 'Controller');
  App::uses( 'CakeEmail', 'Network/Email');

  class UsersController extends AppController {
      const USER_LIMIT = 5;
      public $uses = array('User', 'Message');

        var $components = array(
            'Auth' => array(
                'authenticate' => array(
                    'Form' => array(
                        // 認証されるには、「Userのstatusが0である必要がある」を追加する
                        'scope' => array( 'User.status' => 1)
                    )
                )
            ),
        );

      public function beforeFilter() {
          parent::beforeFilter();
          $this->Auth->allow('add', 'logout', 'sendMsg', 'activate');
      }

      public function index() {
          $this->User->recursive = 0;
          $this->paginate = array(
              'limit' => self::USER_LIMIT, // 検索結果を４件ごとに表示する。
          );
          $this->set('users', $this->paginate());
      }

      // ユーザーにメッセージを送信する。
      public function sendMsg($id = null){
          self::checkId($id);

          if ($this->request->is(array('post'))) {
              $save_data = $this->request->data;
              $user_id = $id;
              $save_data['Message']['user_id'] = $user_id;
              if ($save_data && $this->User->Message->saveAll($save_data, array('deep' => true))) {
                  $this->Flash->success(
                      __('A message has been sent.')
                  );
                  return $this->redirect(array('action' => 'index'));
              }
              $this->Flash->error(
                  __('The message could not be sent.')
              );
          }
      }

      public function myPage(){
          $user = $this->User->findById($this->Auth->user('id'));
          $this->set('user', $user);
      }

      // ユーザーが投稿した記事を一覧で表示する。
      public function postIndex($id = null){
          self::checkId($id);

          $user_id = $id;
          $this->paginate = array( 'Post' => array(
              'conditions' => array('user_id' => $user_id,
                                    'publish_flg' => parent::PUBLISH), // 検索する条件を設定する。
              'limit' => parent::POST_LIST_LIMIT, // 検索結果を４件ごとに表示する。
          ));
          // 一覧表示をpaginate機能で表示させる。
          $this->set('posts', $this->paginate('Post'));

          // 一覧ページのタイトルに使用する。
          $this->set('username', $this->Auth->user('username'));
      }

      public function login() {
        if ($this->request->is('post')) {
            if ($this->Auth->login()) {
                $this->redirect($this->Auth->redirect());
            } else {
                $this->Flash->error(__('Invalid username or password, try again'));
            }
        }
      }

      public function logout() {
        $this->redirect($this->Auth->logout());
      }

      // 仮登録処理を実施する。
      public function add() {
          if ($this->request->is('post')) {
              $this->User->create();
              if ($this->User->save($this->request->data)) {
                  $url =
                        DS . strtolower($this->name) .          // コントローラ
                        DS . 'activate' .                       // アクション
                        DS . $this->User->id .                  // ユーザID
                        DS . $this->User->getActivationHash();  // ハッシュ値
                    $url = Router::url( $url, true);  // ドメイン(+サブディレクトリ)を付与

                    // メールを送信する。
                    $email = new CakeEmail( 'gmail');                        // インスタンス化
                    $email->from( array( 'kosukefunteam@gmail.com' => 'Sender'));  // 送信元
                    $email->to( $this->request->data['User']['username']);                    // 送信先
                    $email->subject( '本登録用メール');                      // メールタイトル

                    $email->send('本登録するためにURLをクリックしてください。 ' . $url);                             // メール送信
                  $this->Flash->success(__('Temporary registration success. Email sent.'));
                  return $this->redirect(array('controller' => 'posts', 'action' => 'index'));
              }
              $this->Flash->error(
                  __('User registration failed.')
              );
          }
      }

      // 本登録処理を実施する。
      public function activate($user_id = null, $in_hash = null){
          // UserモデルにIDをセット
        $this->User->id = $user_id;
        if ($this->User->exists() && $in_hash == $this->User->getActivationHash()) {
        // 本登録に有効なURL
            // statusフィールドを1に更新
            $this->User->saveField( 'status', 1);
            $this->Flash->success( 'Your account has been activated.');
        }else{
        // 本登録に無効なURL
            $this->Flash->error( 'Invalid activation URL');
        }
      }

      public function edit($id = null) {
          self::checkId($id);

          if ($this->request->is('post') || $this->request->is('put')) {
              if ($this->User->save($this->request->data)) {
                  $url =
                        DS . strtolower($this->name) .          // コントローラ
                        DS . 'activate' .                       // アクション
                        DS . $this->User->id .                  // ユーザID
                        DS . $this->User->getActivationHash();  // ハッシュ値
                    $url = Router::url( $url, true);  // ドメイン(+サブディレクトリ)を付与
                    $email = new CakeEmail( 'gmail');                        // インスタンス化
                    $email->from( array( 'sender@domain.com' => 'Sender'));  // 送信元
                    $email->to( 'reciever@domain.com');                      // 送信先
                    $email->subject( 'メールタイトル');                      // メールタイトル

                    $email->send( 'メール本文');                             // メール送信
                  $this->Flash->success(__('Temporary registration success. Email sent.'));
                  return $this->redirect(array('action' => 'index'));
              }
              $this->Flash->error(
                  __('Editing user information failed.')
              );
          } else {
              $this->request->data = $this->User->findById($id);
              unset($this->request->data['User']['password']);
          }
      }

      public function delete($id = null) {
          self::checkId($id);

          $this->request->allowMethod('post');

          if ($this->User->delete()) {
              $this->Flash->success(__('User deleted'));
              return $this->redirect(array('action' => 'index'));
          }
          $this->Flash->error(__('User was not deleted'));
          return $this->redirect(array('action' => 'index'));
      }

      private function checkId($id){
          if (!$id) {
              throw new NotFoundException(__('Invalid user'));
          }

          // 数値以外なら
          if (!is_numeric($id)) {
              throw new NotFoundException(__('Invalid user'));
          }

          // idで表現できる最大値を超えていないか
          if (parent::ID_MAX < $id) {
              throw new NotFoundException(__('Invalid user'));
          }

          $user = $this->User->findById($id);
          if (!$user) {
              throw new NotFoundException(__('Invalid user'));
          }

          $this->User->id = $id;
          if (!$this->User->exists()) {
              throw new NotFoundException(__('Invalid user'));
          }
      }

  }
?>
