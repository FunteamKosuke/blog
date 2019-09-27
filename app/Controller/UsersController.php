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

      public function view($id = null) {
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
          $this->set('user', $this->User->findById($id));
      }

      public function add() {
          if ($this->request->is('post')) {
              $this->User->create();
              if ($this->User->save($this->request->data)) {
                  $this->Flash->success(__('ユーザー登録に成功しました。'));
                  return $this->redirect(array('controller' => 'posts', 'action' => 'index'));
              }
              $this->Flash->error(
                  __('ユーザー登録に失敗しました。')
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
                  $this->Flash->success(__('The user has been saved'));
                  return $this->redirect(array('action' => 'index'));
              }
              $this->Flash->error(
                  __('The user could not be saved. Please, try again.')
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
