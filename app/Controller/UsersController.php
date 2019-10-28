<?php
  App::uses('AppController', 'Controller');

  class UsersController extends AppController {

      public function beforeFilter() {
          parent::beforeFilter();
          $this->Auth->allow('add', 'logout');
      }

      public function index() {
          $this->User->recursive = 0;
          $this->set('users', $this->paginate());
      }

      public function msgSend($id = null){
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

      public function myPage(){
          $user = $this->User->findById($this->Auth->user('id'));
          $this->set('user', $user);
      }

      // ユーザーが投稿した記事を一覧で表示する。
      public function postIndex($id = null){
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
          if (!$id) {
              throw new NotFoundException(__('Invalid user'));
          }

          // 数値以外なら
          if (!is_numeric($id)) {
              throw new NotFoundException(__('Invalid user'));
          }

          // idで表現できる最大値を超えていないか
          if (ID_MAX < $id) {
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
          // Prior to 2.5 use
          // $this->request->onlyAllow('post');

          if (!$id) {
              throw new NotFoundException(__('Invalid user'));
          }

          // 数値以外なら
          if (!is_numeric($id)) {
              throw new NotFoundException(__('Invalid user'));
          }

          // idで表現できる最大値を超えていないか
          if (ID_MAX < $id) {
              throw new NotFoundException(__('Invalid user'));
          }

          $user = $this->User->findById($id);
          if (!$user) {
              throw new NotFoundException(__('Invalid user'));
          }

          $this->request->allowMethod('post');

          $this->User->id = $id;
          if (!$this->User->exists()) {
              throw new NotFoundException(__('Invalid user'));
          }
          if ($this->User->delete()) {
              $this->Flash->success(__('User deleted'));
              return $this->redirect(array('action' => 'index'));
          }
          $this->Flash->error(__('User was not deleted'));
          return $this->redirect(array('action' => 'index'));
      }

  }
?>
