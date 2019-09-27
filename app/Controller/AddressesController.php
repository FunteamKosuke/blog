<?php
    class AddressesController extends AppController {
        const ADDRESS_COLUMN = 15;

        public function beforeFilter(){
    		parent::beforeFilter();
    		$this->Auth->allow('search');
    	}

        // csvインポート用
        public function csv_upload(){
            if ($this->request->is('post')) {
                $time_start = microtime(true);
                $this->log($this->request->data['Address']['csv_file']);
                // ファイルの保存先パスを作成する
                $upload_path = WWW_ROOT . 'files/csv/zip.csv';
                if(move_uploaded_file($this->request->data['Address']['csv_file']['tmp_name'], $upload_path)){
                    $save_data = array();
                    if (($handle = fopen($upload_path, "r")) !== false) {
                        while (($csv = fgetcsv($handle, 1000, ",")) !== false) {
                                // 終端の空行を除く && csvのカラム数がaddressesのカラム数と同じ場合
                                if((!is_null($csv[0])) && (count($csv) == self::ADDRESS_COLUMN)){
                                    $data = array();
                                    // 地方コードの設定
                                    $data['Address']['region_code'] = $csv[0];
                                    // 旧郵便番号の設定
                                    $data['Address']['old_zipcode'] = $csv[1];
                                    // 現郵便番号の設定
                                    $data['Address']['zipcode'] = $csv[2];
                                    // 都道府県名(カタカナ)の設定
                                    $data['Address']['prefectures_kana'] = $csv[3];
                                    // 市区町村(カタカナ)の設定
                                    $data['Address']['city_kana'] = $csv[4];
                                    // 町域(カタカナ)の設定
                                    $data['Address']['town_area_kana'] = $csv[5];
                                    // 都道府県(漢字)の設定
                                    $data['Address']['prefectures_kannzi'] = $csv[6];
                                    // 市区町村(漢字)の設定
                                    $data['Address']['city_kannzi'] = $csv[7];
                                    // 町域(漢字)の設定
                                    $data['Address']['town_area_kannzi'] = $csv[8];
                                    // 一町域が二以上の郵便番号で表される場合の表示
                                    $data['Address']['town_two'] = $csv[9];
                                    // 小字毎に番地が起番されている町域の表示
                                    $data['Address']['town_address'] = $csv[10];
                                    // 丁目を有する町域の場合の表示
                                    $data['Address']['chome_town'] = $csv[11];
                                    // 一つの郵便番号で二以上の町域を表す場合の表示
                                    $data['Address']['zip_two'] = $csv[12];
                                    // 更新の表示
                                    $data['Address']['update_display'] = $csv[13];
                                    // 変更理由
                                    $data['Address']['reason_change'] = $csv[14];
                                    $save_data[] = $data;
                                }
                        }
                        fclose($handle);
                    }
                    // 記事では以下のやり方の方が早いと記述がありましたが、実際測ってみるとfgetcsvの方が早かった。
                    // 環境の違い？
                    // 一応記述は残しておく。
                    // $objFile = new SplFileObject($upload_path);
                    // $this->log($objFile);
                    // $objFile->setFlags(SplFileObject::READ_CSV);
                    // $save_data = array();
                    // foreach ($objFile as $csv) {
                    //     //終端の空行を除く処理　空行の場合に取れる値は後述
                    //     if(!is_null($csv[0])){
                    //         $data = array();
                    //         // 地方コードの設定
                    //         $data['Address']['region_code'] = $csv[0];
                    //         // 旧郵便番号の設定
                    //         $data['Address']['old_zipcode'] = $csv[1];
                    //         // 現郵便番号の設定
                    //         $data['Address']['zipcode'] = $csv[2];
                    //         // 都道府県名(カタカナ)の設定
                    //         $data['Address']['prefectures_kana'] = $csv[3];
                    //         // 市区町村(カタカナ)の設定
                    //         $data['Address']['city_kana'] = $csv[4];
                    //         // 町域(カタカナ)の設定
                    //         $data['Address']['town_area_kana'] = $csv[5];
                    //         // 都道府県(漢字)の設定
                    //         $data['Address']['prefectures_kannzi'] = $csv[6];
                    //         // 市区町村(漢字)の設定
                    //         $data['Address']['city_kannzi'] = $csv[7];
                    //         // 町域(漢字)の設定
                    //         $data['Address']['town_area_kannzi'] = $csv[8];
                    //         // 一町域が二以上の郵便番号で表される場合の表示
                    //         $data['Address']['town_two'] = $csv[9];
                    //         // 小字毎に番地が起番されている町域の表示
                    //         $data['Address']['town_address'] = $csv[10];
                    //         // 丁目を有する町域の場合の表示
                    //         $data['Address']['chome_town'] = $csv[11];
                    //         // 一つの郵便番号で二以上の町域を表す場合の表示
                    //         $data['Address']['zip_two'] = $csv[12];
                    //         // 更新の表示
                    //         $data['Address']['update_display'] = $csv[13];
                    //         // 変更理由
                    //         $data['Address']['reason_change'] = $csv[14];
                    //         $save_data[] = $data;
                    //     }
                    // }
                    $time = microtime(true) - $time_start;
                    $this->log("{$time} 秒");
                    if ($save_data) {
                        $this->Address->truncate(); // 新しくインポートする際は入れ替えたいので一度削除する。
                        if ($this->Address->saveAll($save_data)) {
                            $this->Flash->success(__('csvインポートに成功しました。'));
                            // 画面遷移はしないようにする。一応残す
                            // return $this->redirect(array('controller' => 'posts', 'action' => 'index'));
                        }
                    }
                    // インポートが完了したらアップロードしたファイルは削除する。
                    unlink($upload_path);
                }
                $this->Flash->error(__('csvインポートに失敗しました。'));
            }
        }
        // csv更新用
        public function csv_update(){
            if ($this->request->is('post')) {
                $time_start = microtime(true);
                $upload_path = WWW_ROOT . 'files/csv/zip.csv';
                if(move_uploaded_file($this->request->data['Address']['csv_file']['tmp_name'], $upload_path)){
                    // 更新用のcsvデータを読み込む
                    $update_data = array();
                    if (($handle = fopen($upload_path, "r")) !== false) {
                        while (($csv = fgetcsv($handle, 1000, ",")) !== false) {
                            // 終端の空行を除く && csvのカラム数がaddressesのカラム数と同じ場合
                            if((!is_null($csv[0])) && (count($csv) == self::ADDRESS_COLUMN)){
                                // 郵便番号と町域が一致したデータを更新したいデータとする。

                                $update = $this->Address->find('first', array(
                                            			    'conditions'=>array(
                                            			         'zipcode' => $csv[2],
                                                                 'city_kannzi' => $csv[7],
                                                                 'town_area_kannzi' => $csv[8]
                                            				),
                                            			));
                                // $this->log($update);
                                // 地方コードの設定
                                $update['Address']['region_code'] = $csv[0];
                                // 旧郵便番号の設定
                                $update['Address']['old_zipcode'] = $csv[1];
                                // 現郵便番号の設定
                                $update['Address']['zipcode'] = $csv[2];
                                // 都道府県名(カタカナ)の設定
                                $update['Address']['prefectures_kana'] = $csv[3];
                                // 市区町村(カタカナ)の設定
                                $update['Address']['city_kana'] = $csv[4];
                                // 町域(カタカナ)の設定
                                $update['Address']['town_area_kana'] = $csv[5];
                                // 都道府県(漢字)の設定
                                $update['Address']['prefectures_kannzi'] = $csv[6];
                                // 市区町村(漢字)の設定
                                $update['Address']['city_kannzi'] = $csv[7];
                                // 町域(漢字)の設定
                                $update['Address']['town_area_kannzi'] = $csv[8];
                                // 一町域が二以上の郵便番号で表される場合の表示
                                $update['Address']['town_two'] = $csv[9];
                                // 小字毎に番地が起番されている町域の表示
                                $update['Address']['town_address'] = $csv[10];
                                // 丁目を有する町域の場合の表示
                                $update['Address']['chome_town'] = $csv[11];
                                // 一つの郵便番号で二以上の町域を表す場合の表示
                                $update['Address']['zip_two'] = $csv[12];
                                // 更新の表示
                                $update['Address']['update_display'] = $csv[13];
                                // 変更理由
                                $update['Address']['reason_change'] = $csv[14];
                                $save_data[] = $update;
                                // $update_array = $this->Address->find('all');
                                // if (count($update_array) > 1) {
                                //     $this->log('csvupdate');
                                //     $this->log($update_array);
                                // }
                                //郵便局が作成しているcsvファイルなのに全く同じデータが入ってるので、その対応。
                                // foreach ($update_array as $update) {
                                //     // 地方コードの設定
                                //     $update['Address']['region_code'] = $csv[0];
                                //     // 旧郵便番号の設定
                                //     $update['Address']['old_zipcode'] = $csv[1];
                                //     // 現郵便番号の設定
                                //     $update['Address']['zipcode'] = $csv[2];
                                //     // 都道府県名(カタカナ)の設定
                                //     $update['Address']['prefectures_kana'] = $csv[3];
                                //     // 市区町村(カタカナ)の設定
                                //     $update['Address']['city_kana'] = $csv[4];
                                //     // 町域(カタカナ)の設定
                                //     $update['Address']['town_area_kana'] = $csv[5];
                                //     // 都道府県(漢字)の設定
                                //     $update['Address']['prefectures_kannzi'] = $csv[6];
                                //     // 市区町村(漢字)の設定
                                //     $update['Address']['city_kannzi'] = $csv[7];
                                //     // 町域(漢字)の設定
                                //     $update['Address']['town_area_kannzi'] = $csv[8];
                                //     // 一町域が二以上の郵便番号で表される場合の表示
                                //     $update['Address']['town_two'] = $csv[9];
                                //     // 小字毎に番地が起番されている町域の表示
                                //     $update['Address']['town_address'] = $csv[10];
                                //     // 丁目を有する町域の場合の表示
                                //     $update['Address']['chome_town'] = $csv[11];
                                //     // 一つの郵便番号で二以上の町域を表す場合の表示
                                //     $update['Address']['zip_two'] = $csv[12];
                                //     // 更新の表示
                                //     $update['Address']['update_display'] = $csv[13];
                                //     // 変更理由
                                //     $update['Address']['reason_change'] = $csv[14];
                                //     $save_data[] = $update;
                                // }
                            }
                        }
                        //一括更新する
                        // $this->log($save_data);
                        $this->Address->saveAll($save_data);
                        $time = microtime(true) - $time_start;
                        $this->log("{$time} 秒");
                    }
                    unlink($upload_path);
                }

            }
        }

        public function search(){
            $this->autoRender = FALSE; // 自動でviewが読み込まれるのを防ぐ
            if($this->request->is('ajax')) {
                $search_result = $this->Address->find('all', array(
                            			    'conditions'=>array(
                            			         'zipcode'=>$this->request->data['zipcode'],
                            				),
                            			));
                // $this->log($search_result);
                //別言語に渡す時はjson形式で渡さないとエラーになる。
                $this->log($search_result);
                return json_encode($search_result);
                // return $search_result;
            //     $this->log($this->request->data);
            //     if ($this->Address->findByZipcode($this->request->data['zipcode'])) {
            //         $this->log($this->Address->findByZipcode($this->request->data['zipcode']));
            // //         $this->log();
            //         return true;
            //     } else {
            //         $this->log('存在してなかったよ');
            //         return false;
            //     }
            }
        }

        // public function csv_import(){
        //     if ($this->request->is('post')) {
        //         // 入れ替える仕様のため、保存する前に一度全て削除する
        //         $this->Zipcode->truncate();
        //         $save_data = array();
        //         foreach ($this->request->data['zip'] as $zip) {
        //             $data['Zipcode']['title'] = $zip;
        //             $save_data[] = $data;
        //         }
        //         if ($this->Zipcode->saveAll($save_data, array('deep' => true))) {
        //             $this->Flash->success(__('郵便番号の登録に成功しました。'));
        //             return $this->redirect(array('controller' => 'zipcodes', 'action' => 'zip_info'));
        //         } else {
        //             $this->Flash->error(__('郵便番号の登録に失敗しました。'));
        //         }
        //     }
        // }
        //
        // public function zip_info(){
        //     $zips = array('' => '選択してください') + $this->Zipcode->find('list', array('fields'=>array('id','title')));
        //     $this->set('zipcodes',$zips);
        // }
    }
?>
