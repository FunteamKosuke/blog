<?php
  App::uses('AppController', 'Controller');
  App::uses( 'CakeEmail', 'Network/Email');

  class UsersController extends AppController {
      const USER_LIMIT = 5;
      const HASH_USER_ID = 546745867;
      const MESSAGE_LIST_LIMIT = 5;

      public $uses = array('User', 'Message', 'Post');

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
          $this->Auth->allow('add', 'logout', 'sendMsg', 'activate', 'postIndex', 'index', 'retransmission');
      }

      public function index() {
          $this->User->recursive = 0;
          $this->paginate = array(
              'limit' => self::USER_LIMIT, // 検索結果を４件ごとに表示する。
          );
          $this->set('users', $this->paginate());
      }

      // ユーザーにメッセージを送信する。
      public function sendMsg($user_id = null){
          self::checkId($user_id);
          // ajaxに渡すために必要
          $this->set('user_id', $user_id);
      }

      public function sendMsgAjax($user_id = null){
          if ($this->request->is(array('ajax'))) {
              $this->autoRender = FALSE; // 設定しないと返却されるデータがhtmlになってしまう。
              $save_data = $this->request->data;
              $msg = '';
              if ($save_data && $this->User->Message->saveAll($save_data, array('deep' => true))) {
                  $msg = __('A message has been sent.');
              } else {
                  $msg = __('The message could not be sent.');
              }
              return json_encode($msg);
          }
      }

      public function myPage(){
          $user = $this->User->findById($this->Auth->user('id'));
          $this->set('user', $user);
      }

      // ユーザーが投稿した記事を一覧で表示する。
      public function postIndex($user_id = null){
          self::checkId($user_id);

          $this->paginate = array( 'Post' => array(
              'conditions' => array('user_id' => $user_id,
                                    'publish_flg' => parent::PUBLISH), // 検索する条件を設定する。
              'limit' => parent::POST_LIST_LIMIT, // 検索結果を４件ごとに表示する。
          ));
          // 一覧表示をpaginate機能で表示させる。
          $this->set('posts', $this->paginate('Post'));

          // 一覧ページのタイトルに使用する。
          $username = $this->User->find('first', array('conditions' => array('id'=> $user_id),
                                                        'fields' => array('User.username')));
          $this->set('username', $username['User']['username']);
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
                    // ユーザーIDをそのまま渡すとユーザーの人数を把握されてしまうので、別の数値にする。
                    $hash_user_id = $this->User->id + self::HASH_USER_ID;
                    $token = $this->User->getActivationToken();

                    // トークンを保存する。
                    $this->User->saveField('token', $token);

                    $url =
                        DS . strtolower($this->name) .          // コントローラ
                        DS . 'activate' .                       // アクション
                        DS . $hash_user_id .                  // ユーザID
                        DS . $token;  // token
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
      public function activate($user_id_hash = null, $in_hash = null){
        // UserモデルにIDをセット 別の数値にしたuser_idを元に戻す。
        $user_id = $user_id_hash - self::HASH_USER_ID;
        $this->User->id = $user_id;
        // URLの期限が有効かを判定する。
        // 仮登録日時から１日を期限とする。
        $deadline_flg = true;
        // cratedを取得する。
        $kari_date = $this->User->find('first', array('conditions' => array('id' => $user_id),
                                        'fields' => 'User.modified'))['User']['modified'];
        $kari_date_ymd = explode(' ', $kari_date)[0];
        $kari_date_his = explode(' ', $kari_date)[1];
        // 現在日時を取得する。
        $date = date("Y-m-d H:i:s");
        $date_ymd = explode(' ', $date)[0];
        $date_his = explode(' ', $date)[1];
        // 年月日を比較する。
        if (!($kari_date_ymd === $date_ymd)) {
            $kari_y = intval(explode('-', $kari_date_ymd)[0]);
            $kari_m = intval(explode('-', $kari_date_ymd)[1]);
            $kari_d = intval(explode('-', $kari_date_ymd)[2]);

            $y = intval(explode('-', $date_ymd)[0]);
            $m = intval(explode('-', $date_ymd)[1]);
            $d = intval(explode('-', $date_ymd)[2]);
            $kari_ymd_num = $kari_y + $kari_m + $kari_d;
            $ymd_num = $y + $m + $d;
            // 日にちが１日後だった場合は、時間を計算して、24時間経っているか計算する。
            if (($ymd_num - $kari_ymd_num) == 1) {
                $kari_h = intval(explode(':', $kari_date_his)[0]);
                $kari_i = intval(explode(':', $kari_date_his)[1]);
                $kari_s = intval(explode(':', $kari_date_his)[2]);

                $h = intval(explode(':', $date_his)[0]);
                $i = intval(explode(':', $date_his)[1]);
                $s = intval(explode(':', $date_his)[2]);

                // 経過時間を秒にしてから算出する。
                $kari_time = ($kari_h * 3600) + ($kari_i * 60) + $kari_s;
                $prog_time = (86400 - $kari_time) + ($h * 3600) + ($i * 60) + $s;

                if ($prog_time > (86400-1)) {
                    $deadline_flg = false;
                }
            } else {
                $deadline_flg = false;
            }
        }

        // トークンを取得する。
        $token = $this->User->field('token');

        $retransmission_flg = false; //本登録用のメールを再送信するか？
        if ($this->User->exists() && $in_hash == $token && $deadline_flg) {
        // 本登録に有効なURL
            // statusフィールドを1に更新
            $this->User->saveField( 'status', 1);
            $this->Flash->success(__('Your account has been activated.'));
        }else{
        // 本登録に無効なURL
            $this->Flash->error( __('Invalid activation URL'));
            $retransmission_flg = true;
            // 本登録のメールを送る際に、ハッシュ化したユーザーIDが必要。
            $this->set('user_id_hash', $user_id_hash);
        }
        $this->set('retransmission_flg', $retransmission_flg);
      }

      // 本登録用のメールを再送信する。
      public function retransmission($hash_user_id = null){
          $user_id = $hash_user_id - self::HASH_USER_ID;
          $this->User->id = $user_id;

          $token = $this->User->getActivationToken();

          // トークンを保存する。
          $this->User->saveField('token', $token);

          $url =
              DS . strtolower($this->name) .          // コントローラ
              DS . 'activate' .                       // アクション
              DS . $hash_user_id .                  // ハッシュ化したユーザID
              DS . $token;  // token
          $url = Router::url( $url, true);  // ドメイン(+サブディレクトリ)を付与

          // メールアドレスを取得する。
          $email_address = $this->User->field('username');
          $this->log($email);
          // メールを送信する。
          $email = new CakeEmail( 'gmail');                        // インスタンス化
          $email->from( array( 'kosukefunteam@gmail.com' => 'Sender'));  // 送信元
          $email->to($email_address);
          $email->subject( '本登録用メールの再送信');                      // メールタイトル

          $email->send('本登録するためにURLをクリックしてください。 ' . $url);                             // メール送信
          $this->Flash->success(__('We have resent the registration email.'));
          return $this->redirect(array('controller' => 'posts', 'action' => 'index'));
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

      // ユーザーに送信されたメッセージを一覧で表示する。
      public function messageIndex($user_id = null){
          self::checkId($user_id);

          $this->paginate = array( 'Message' => array(
              'conditions' => array('user_id' => $user_id),
              'limit' => self::MESSAGE_LIST_LIMIT,
          ));

          // 一覧表示をpaginate機能で表示させる。
          $this->set('messages', $this->paginate('Message'));
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
