<?php
    class ZipcodesController extends AppController {
        public function csv_upload(){
            if ($this->request->is('ajax')) {
                // ファイルの保存先パスを作成する
                $upload_path = WWW_ROOT . 'files/csv/zip.csv';
                //アップロードが正しく完了したかチェック
                $this->log($this->request->data);
                if(move_uploaded_file($this->request->data['Zipcode-upload']['csv_file']['tmp_name'], $upload_path)){
                    $objFile = new SplFileObject($upload_path);
                    $objFile->setFlags(SplFileObject::READ_CSV);
                    foreach ($objFile as $csv) {
                        $this->log($csv);
                    }
                    $this->log('こんなデータが取得できたよ');
                    $this->log($objFile);
                    return true;
                }else{
                    $this->log('アップロードに失敗しました。');
                    return false;
                }
            }
        }

        public function csv_import(){
            if ($this->request->is('post')) {
                // 入れ替える仕様のため、保存する前に一度全て削除する
                $this->Zipcode->truncate();
                $save_data = array();
                foreach ($this->request->data['zip'] as $zip) {
                    $data['Zipcode']['title'] = $zip;
                    $save_data[] = $data;
                }
                if ($this->Zipcode->saveAll($save_data, array('deep' => true))) {
                    $this->Flash->success(__('郵便番号の登録に成功しました。'));
                    return $this->redirect(array('controller' => 'zipcodes', 'action' => 'zip_info'));
                } else {
                    $this->Flash->error(__('郵便番号の登録に失敗しました。'));
                }
            }
        }

        public function zip_info(){
            $zips = array('' => '選択してください') + $this->Zipcode->find('list', array('fields'=>array('id','title')));
            $this->set('zipcodes',$zips);
        }
    }
?>
