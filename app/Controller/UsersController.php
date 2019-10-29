<?php
  App::uses('AppController', 'Controller');

  class UsersController extends AppController {
      const USER_LIMIT = 5;
      public $uses = array('User', 'Message');

      public function beforeFilter() {
          parent::beforeFilter();
          $this->Auth->allow('add', 'logout', 'sendMsg');
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

      public function add() {
          if ($this->request->is('post')) {
              $this->User->create();
              if ($this->User->save($this->request->data)) {
                  $this->Flash->success(__('User registration was successful.'));
                  return $this->redirect(array('controller' => 'posts', 'action' => 'index'));
              }
              $this->Flash->error(
                  __('User registration failed.')
              );
          }
      }

      public function edit($id = null) {
          self::checkId($id);

          if ($this->request->is('post') || $this->request->is('put')) {
              if ($this->User->save($this->request->data)) {
                  $this->Flash->success(__('User information has been edited successfully.'));
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
