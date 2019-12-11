<?php
    App::uses('AppController', 'Controller');
    App::uses( 'CakeEmail', 'Network/Email');
    //twitter opauth を使う
    App::import('Vendor','autoload');
    use Abraham\TwitterOAuth\TwitterOAuth;

    class UsersController extends AppController {
        const USER_LIMIT = 5;
        const HASH_USER_ID = 546745867;
        const MESSAGE_LIST_LIMIT = 5;

        // twitter認証に必要な情報
        const APIKEY = "uQz4NIazyWdbKTQzyg0hYQm7g";
        const APISECRET = "D9PiJ2KJkVb9EqkED5nqFYBLe8EtrqY6lw5NzR00hvzEyul6uG";
        const TOKEN = "991963579107524609-vaAbRM1owuJ1Yv3kEXkLU6XLSo8GOdD";
        const TOKENSECRET = "XIqftbMkSqbYUC6Qi6qEXgpDmV6ra8WDoljpqhUEMxcPH";
        const CALLBACK = 'http://blog.dv/users/callbackTwitter';

        public $uses = array('User', 'Message', 'Post');

        public function beforeFilter() {
            parent::beforeFilter();
            $this->Auth->allow( 'add',
                                'logout',
                                'sendMsg',
                                'sendMsgAjax',
                                'activate',
                                'postIndex',
                                'index',
                                'retransmission',
                                'loginTwitter',
                                'callbackTwitter',
                                'temp_complete');
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
                // ログインする条件を設定する。
                $components = array(
                    'Auth' => array(
                        'authenticate' => array(
                            'Form' => array(
                                // 認証されるには、「Userのstatusが0である必要がある」を追加する
                                'scope' => array( 'User.status' => 1)
                            )
                        )
                    ),
                );
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
                // 自作バリデーションでアクションを判定させるために必要。
                $this->request->data['User']['action'] = $this->action;
                $mode = $this->request->data['mode'];
                switch ($mode) {
                    case 'confirm':
                        // 確認画面へ行く前にバリデーションチェックをする。
                        $this->User->set($this->request->data);
                        if (!$this->User->validates()) {
                            $this->Session->error('入力内容に不備があります。');
                            $this->set('validate', $this->request->data);
                            return;
                        }
                        // セッション情報がない場合とformで画像が選択されていないときの画像情報がないときを判定しやすくするために
                        // NULLで統一したいので、
                        // formで画像が選択されていない時も、空文字ではなく、NULLが設定されている様にする。
                        if ('' === $this->request->data['User']['profile_image']['name']) {
                            $this->request->data['User']['profile_image']['name'] = NULL;
                        }

                        // 確認画面を通すと保存するときには一時ファイルが削除されているので、サーバー上に保存し直す。
                        $upload_directory = WWW_ROOT . 'files/profile/';
                        if(!file_exists($upload_directory)){
                            mkdir($upload_directory, 0777);
                        }

                        // 仕様として、修正したときに画像が選択されていないときは、前に選択された画像を設定する画像とする。
                        $user = $this->Session->read('User');
                        if (isset($this->request->data['User']['profile_image']['name'])) {
                            if (isset($user['User']['profile_image']['name'])) {
                                $file_exists = WWW_ROOT . 'files/profile/' . $user['User']['profile_image']['name'];
                                unlink($file_exists);
                            }

                            $upload_file = $upload_directory . $this->request->data['User']['profile_image']['name'];
                            move_uploaded_file($this->request->data['User']['profile_image']['tmp_name'], $upload_file);

                        }

                        // 画像が選択されていないときは、前セッションの画像情報を新たなセッションの画像情報として、設定する。
                        if (!isset($this->request->data['User']['profile_image']['name']) &&
                            (isset($user['User']['profile_image']['name']))) {
                            $this->request->data['User']['profile_image'] = $user['User']['profile_image'];
                        }

                        $this->Session->write('User', $this->request->data);
                        $this->render('confirm');
                        break;
                    case 'correct':
                        $this->render('add');
                        break;
                    case 'exec':
                        // 保存すると同時にセッション情報を削除する。
                        $user = $this->Session->read('User');
                        // 画像が選択されているときだけ名前が設定されているので、画像が保存されている場所を設定する。
                        $upload_file = '';
                        if (isset($user['User']['profile_image']['name'])) {
                            $upload_file = WWW_ROOT . 'files/profile/' . $user['User']['profile_image']['name'];
                            // 一時ファイルはすでに削除されているので、確認の処理で保存した場所をupload pluginに渡すパスとする。
                            $user['User']['profile_image']['tmp_name'] = $upload_file;
                        }
                        $this->User->create();
                        $user['User']['provider'] = 'normal';
                        if ($this->User->save($user)) {
                            // 保存に成功したので、画像が選択されている場合は削除しないといけないと思ったが
                            // uploadプラグインの仕様として、
                            // 保存されたファイルは勝手に削除される様になっている。
                            // この記述は一応残しておく。


                            // ユーザーIDをそのまま渡すとユーザーの人数を把握されてしまうので、別の数値にする。
                            $hash_user_id = $this->User->id + self::HASH_USER_ID;
                            $token = $this->User->getActivationToken();

                            // トークンを保存する。
                            $this->User->saveField('token', $token);
                            // トークンの期限を設定する。
                            $token_deadline = date('Y-m-d H:i:s');
                            $this->User->saveField('token_deadline', $token_deadline);

                            $url =
                                DS . strtolower($this->name) .          // コントローラ
                                DS . 'activate' .                       // アクション
                                DS . $hash_user_id .                  // ユーザID
                                DS . $token;  // token
                            $url = Router::url( $url, true);  // ドメイン(+サブディレクトリ)を付与

                            // メールアドレスを取得する。
                            $email_address = $user['User']['email'];

                            // メールを送信する。
                            $email = new CakeEmail( 'gmail');                        // インスタンス化
                            $email->from( array( 'kosukefunteam@gmail.com' => 'Sender'));  // 送信元
                            $email->to( $email_address);                    // 送信先
                            $email->subject( '本登録用メール');                      // メールタイトル
                            $email->send('本登録するためにURLをクリックしてください。 ' . $url);                             // メール送信
                            return $this->redirect(array('action' => 'temp_complete'));
                        }
                        $this->Flash->error(
                          __('User registration failed.')
                        );
                        break;
                } //switch end
            }
        }
        // 仮登録完了画面
        public function temp_complete(){
            // セッション情報がない場合は、完了画面を表示せず、topページを表示する。
            // reloadで完了画面を何度も表示するのを防ぐ意図もある。
            // グーグルアナリティクス的に間違った評価につながってしまう可能性があるため。
            $user = $this->Session->read('User');
            if (!isset($user)) {
                return $this->redirect(array('controller' => 'posts', 'action' => 'index'));
            }
            // ユーザーのセッション情報のみ削除する。
            $this->Session->delete('User');
        }

        // 本登録処理を実施する。
        public function activate($user_id_hash = null, $in_hash = null){
            // UserモデルにIDをセット 別の数値にしたuser_idを元に戻す。
            $user_id = $user_id_hash - self::HASH_USER_ID;
            $this->User->id = $user_id;
            // URLの期限が有効かを判定する。
            // 仮登録日時から１日を期限とする。
            $deadline_flg = true;
            // トークン期限を取得する。
            $kari_date = $this->User->find('first', array('conditions' => array('id' => $user_id),
                                            'fields' => 'User.token_deadline'))['User']['token_deadline'];
            $kari_date_ymd = explode(' ', $kari_date)[0];
            $kari_date_his = explode(' ', $kari_date)[1];

            // 現在日時を取得する。
            $date = date('Y-m-d H:i:s');
            // $date = '2019-11-13 08:28:44';
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

                // 例えば1131と1201を計算する際に、月の計算値が固定値であると正しく計算ができないので、仮登録月の最終日を月に乗算する計算値とする。
                $last_day = date("t", mktime(0, 0, 0, $kari_m, $kari_y));
                // 日数に変換して計算し直す。
                $kari_ymd_num = ($kari_y * 365) + ($kari_m * $last_day) + $kari_d;
                $ymd_num = ($y * 365) + ($m * $last_day) + $d;
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
            // トークンの期限を設定する。
            $token_deadline = date('Y-m-d H:i:s');
            $this->User->saveField('token_deadline', $token_deadline);

            $url =
              DS . strtolower($this->name) .        // コントローラ
              DS . 'activate' .                     // アクション
              DS . $hash_user_id .                  // ハッシュ化したユーザID
              DS . $token;                          // token
            $url = Router::url( $url, true);  // ドメイン(+サブディレクトリ)を付与

            // メールアドレスを取得する。
            $email_address = $this->User->field('email');

            // メールを送信する。
            $email = new CakeEmail( 'gmail');
            $email->from( array( 'kosukefunteam@gmail.com' => 'Sender'));   // 送信元
            $email->to($email_address); //送信先
            $email->subject( '本登録用メールの再送信');    // メールタイトル

            $email->send('本登録するためにURLをクリックしてください。 ' . $url);  // メール送信
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

        /***** twitter認証関連 *****/

        public function loginTwitter(){

            $twitter = new TwitterOAuth ( self::APIKEY, self::APISECRET);

            $request_token = $twitter->oauth(
                'oauth/request_token',
                [
                    'oauth_callback' => self::CALLBACK
                ]
            );

            $url = $twitter->url('oauth/authorize', ['oauth_token' => $request_token['oauth_token']]);
            $this->redirect($url);

            $this->autoRender = false;
            $this->autoLayout = false;


        }

        public function callbackTwitter(){
            // トークンが取得できなかったら(twitter認証キャンセル等)何もせず記事一覧画面に戻る。
            if (!(isset($_GET['oauth_verifier']) && isset($_GET['oauth_token']))) {
                $this->Flash->error(__('Twitter authentication failed.'));
                return $this->redirect(array('controller' => 'posts', 'action' => 'index'));
            }
            $twitter_connect = new TwitterOAuth(self::APIKEY, self::APISECRET, self::TOKEN, self::TOKENSECRET);
            $access_token = $twitter_connect->oauth('oauth/access_token', array('oauth_verifier' => $_GET['oauth_verifier'], 'oauth_token'=> $_GET['oauth_token']));
            $twitter = new TwitterOAuth(
                self::APIKEY,
                self::APISECRET,
                $access_token['oauth_token'],
                $access_token['oauth_token_secret']
            );
             // $this->log($twitter->get('account/verify_credentials'));
             // ユーザー登録に必要な情報を設定する。
             $user_info = $twitter->get('account/verify_credentials', ['include_email'=> true]);
             $this->log($user_info);
             $user = $this->User->find('first', array('conditions' => array('username' => $user_info->name,
                                                                            'provider_id' => $user_info->id_str,
                                                                            'status' => 1)));
             // データが存在しなければ、保存する。
             if (!$user) {
                 $save_data['User']['provider'] = 'twitter'; //twitterでユーザー登録されていることを表す。
                 $save_data['User']['username'] = $user_info->name;
                 $save_data['User']['provider_id'] = $user_info->id_str;
                 // メールアドレスはNULLにできないので、現在日時を利用して、ユニークなメールアドレスを作成して、設定する。
                 $save_data['User']['email'] = date("mYdiHs").date("mYdiHs").'@example.com';
                 $save_data['User']['password'] = 'HFfkhj567GHdf';
                 $save_data['User']['password_confirm'] = 'HFfkhj567GHdf';
                 $save_data['User']['role'] = 'admin';
                 $save_data['User']['address'] = $user_info->location;
                 $save_data['User']['sl_address'] = $user_info->location;
                 // そのままだと画像が小さいので大きいサイズの画像を取得する。画像名から_normalを削除すると大きいサイズが取得できる。
                 $image = str_replace('_normal', '', $user_info->profile_image_url_https);
                 $save_data['User']['profile_image'] = $image;
                 $save_data['User']['status'] = 1;
                 // twitterの情報を保存する。
                 $this->User->create();
                 if($this->User->save($save_data)){
                     $this->log('成功しました');
                 } else {
                     $this->log('失敗しました。');
                 }
             }
             // ログインするための情報を設定する。
             $this->request->data['User']['username'] = $user_info->name;
             $this->request->data['User']['password'] = 'HFfkhj567GHdf';
             // ログインする条件を設定する。
             $components = array(
                 'Auth' => array(
                     'authenticate' => array(
                         'Form' => array(
                             // 認証されるには、「Userのstatusが0である必要がある」を追加する
                             'scope' => array( 'User.status' => 1,
                                               'User.provider' => 'twitter',
                                               'User.provider_id' => $user_info->id_str)
                         )
                     )
                 ),
             );
             // ログインする。
             $this->Auth->login();
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
