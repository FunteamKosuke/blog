<?php
  class CategoriesController extends AppController {
    public function add() {
        if ($this->request->is('post')) {
            $this->Category->create();
            if ($this->Category->save($this->request->data)) {
                $this->Flash->success(__('Your Category has been saved.'));
                return $this->redirect(array('controller' => 'posts', 'action' => 'index'));
            }
            $this->Flash->error(__('Unable to add your Category.'));
        }
    }
  }
?>
