<?php
  class TagsController extends AppController {
    public function add() {
        if ($this->request->is('post')) {
            if ($this->Tag->save($this->request->data)) {
                $this->Flash->success(__('Your Tag has been saved.'));
                return $this->redirect(array('controller' => 'posts', 'action' => 'index'));
            }
            $this->Flash->error(__('Unable to add your Tag.'));
        }
    }
  }
?>
