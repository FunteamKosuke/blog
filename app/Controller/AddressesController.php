<?php
    class AddressesController extends AppController {
        const ADDRESS_COLUMN = 15;

        public function beforeFilter(){
    		parent::beforeFilter();
    		$this->Auth->allow('search','getSelectElem');
    	}

        // csvインポート用
        public function csv_import(){
            if ($this->request->is('ajax')) {
                $this->autoRender = FALSE; // 設定しないと返却されるデータがhtmlになってしまう。
                $time_start = microtime(true);
                // cancel処理用にデータをaddressesのデータを全てjson形式で書き出しておく。
                // バックアップ用
                // $backup_path = WWW_ROOT
                // ファイルの保存先パスを作成する
                $upload_path = WWW_ROOT . 'files/csv/zip.csv';
                if(move_uploaded_file($this->request->data['Address']['csv_file']['tmp_name'], $upload_path)){
                    $fp = self::readCsvFile($upload_path);
                    if ($fp == null) {
                        return json_encode(__('The file does not support character codes. Please convert to SJIS or UTF-8.'));
                    }


                    $save_data = array();
                    //csvデータの読み込みとデータの作成
                    if ($fp !== false) {
                        while (($csv = fgetcsv($fp, 1000, ",")) !== false) {
                                // 終端の空行を除く && csvのカラム数がaddressesのカラム数と同じ場合
                                if((!is_null($csv[0])) && (count($csv) == self::ADDRESS_COLUMN)){
                                    $save_data[] = self::setCsvData($csv);
                                }
                        }
                        fclose($fp);
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
                    // 時間計測
                    // $time = microtime(true) - $time_start;
                    // $this->log("{$time} 秒");

                    // 保存用のデータが作成できたらアップロードしたファイルは削除する。
                    unlink($upload_path);
                    if ($save_data) {
                        // $this->Address->truncate(); // 新しくインポートする際は入れ替えたいので一度削除する。
                        if ($this->Address->saveAll($save_data)) {
                            // 画面遷移はしないようにする。
                            return json_encode(__('The import was successful.'));
                        }
                    }
                    $this->log(__('Failed to read csv file.'));
                    return json_encode(__('Import failed.'));

                }
                $this->log(__('File upload failed.'));
                return json_encode(__('Import failed.'));
            }
        }
        // csv更新用
        // 更新方法としては、更新したいデータを取得し、csvファイルに記述されている内容に入れ替えてからsaveAllをする。
        public function csv_update(){
            if ($this->request->is('ajax')) {
                $this->autoRender = FALSE; // 設定しないと返却されるデータがhtmlになってしまう。
                $time_start = microtime(true);
                $upload_path = WWW_ROOT . 'files/csv/zip.csv';
                // $address_column = $this->Address->getColumnTypes();
                // $this->log(count($address_column));
                if(move_uploaded_file($this->request->data['Address']['csv_file']['tmp_name'], $upload_path)){
                    $fp = self::readCsvFile($upload_path);
                    if ($fp == null) {
                        return json_encode(__('The file does not support character codes. Please convert to SJIS or UTF-8.'));
                    }
                    // 更新用のcsvデータを読み込む
                    $save_data = array();
                    if ($fp !== false) {
                        while (($csv = fgetcsv($fp, 1000, ",")) !== false) {
                            // 終端の空行を除く && csvのカラム数がaddressesのカラム数と同じ場合
                            // idとcreatedとmodifyの数を引いた数をカラム数とする。
                            if((!is_null($csv[0])) && (count($csv) == self::ADDRESS_COLUMN )){
                                // 郵便番号と町域と市区町村が一致したデータを更新したいデータとする。
                                // find firstでupdate
                                $address_data = null;
                                $address_data = $this->Address->find('first', array(
                                            			    'conditions'=>array(
                                            			         'zipcode' => $csv[2],
                                                                 'city_kannzi' => $csv[7],
                                                                 'town_area_kannzi' => $csv[8]
                                            				),
                                            			));
                                // テーブルからカラム情報を取得してforeachで回そうとしたが、idやcreatedが含まれており、
                                // 余計複雑になるため却下します。
                                // foreach ($address_column as $index) {
                                //     $this->log($index);
                                //     // $this->log($column);
                                //     // $update['Address'][$column] = $csv[$index];
                                // }
                                // $this->log($update);

                                // 見つかったデータのみ更新する。見つからなかったデータに関しては、新規で追加したりしない。
                                if ($address_data) {
                                    $save_data[] = self::setCsvData($csv, $address_data);
                                }
                                // find allでupdate
                                // $update_array = $this->Address->find('all', array(
                                //             			    'conditions'=>array(
                                //             			         'zipcode' => $csv[2],
                                //                                  'city_kannzi' => $csv[7],
                                //                                  'town_area_kannzi' => $csv[8]
                                //             				),
                                //             			));
                                // // if (count($update_array) > 1) {
                                // //     $this->log('csvupdate');
                                // //     $this->log($update_array);
                                // // }
                                // // 郵便局が作成しているcsvファイルなのに全く同じデータが入ってるので、その対応。
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
                                // queryでデータを取得する。
                                // $query = "SELECT * FROM addresses as Address where zipcode=${csv[2]} AND city_kannzi='";
                                // $query .= $csv[7];
                                // $query .= "'AND town_area_kannzi='";
                                // $query .= $csv[8];
                                // $query .= "'";
                                // $update_array = $this->Address->query($query);
                                //
                                // // 郵便局が作成しているcsvファイルなのに全く同じデータが入ってるので、その対応。
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
                                // queryでデータを取得する。
                                // $query = "SELECT * FROM addresses as Address where zipcode=${csv[2]} AND city_kannzi='";
                                // $query .= $csv[7];
                                // $query .= "'AND town_area_kannzi='";
                                // $query .= $csv[8];
                                // $query .= "' LIMIT 1";
                                // $update_array = $this->Address->query($query);
                                //
                                // // 郵便局が作成しているcsvファイルなのに全く同じデータが入ってるので、その対応。
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
                            } // end if
                        }// end while
                        fclose($fp);
                        unlink($upload_path);
                        //一括更新する
                        set_time_limit(600); //saveallに標準の上限に30秒以上かかってしまうので、変更する。
                        if ($save_data && $this->Address->saveAll($save_data)) {
                            // 時間計測
                            $time = microtime(true) - $time_start;
                            $this->log("{$time} 秒");
                            return json_encode(__('The update was successful.'));
                        }
                        $this->log(__('Failed to read csv file.'));
                        return json_encode(__('Update failed.'));

                    }
                }
                $this->log(__('File upload failed.'));
                return json_encode(__('Update failed.'));
            }
        }

        public function search(){
            if($this->request->is('ajax')) {
                $this->autoRender = FALSE; // 自動でviewが読み込まれるのを防ぐ
                $this->log($this->request->data);
                $search_result = $this->Address->find('all', array(
                            			    'conditions'=>array(
                            			         'zipcode'=>$this->request->data['zipcode'],
                            				),
                            			));
                //別言語に渡す時はjson形式で渡さないとエラーになる。
                return json_encode($search_result);
                // return json_encode('hjgfhk');
            }
        }

        // 市区町村と町域のセレクトボックス の要素を取得するための
        public function getSelectElem(){
            $this->autoRender = FALSE; // 自動でviewが読み込まれるのを防ぐ
            if($this->request->is('ajax')) {
                $get_data = $this->Address->find('all', array('fields' => array('DISTINCT Address.' . $this->request->data['distinct_column']),
                                                                'conditions' => array($this->request->data['get_column'] => $this->request->data['get_data'])));

                // 連想配列で取得したので、通常の配列として渡す。
                $select_elem = array();
                foreach ($get_data as $data) {
                    // 町域が空欄の場合があるので、データがある場合のみselect boxのデータとする。
                    if ($data['Address'][$this->request->data['distinct_column']]) {
                        $select_elem[] = $data['Address'][$this->request->data['distinct_column']];
                    }
                }
                return json_encode($select_elem);
            }
        }

        // dbから取得したアドレスのデータを渡すとidが含まれているので、更新扱いとなる。
        // csvファイルのデータだけ渡した場合は、idが無いので、新規扱いとなる。
        private function setCsvData($csv, $address_data = null){
            // 地方コードの設定
            $address_data['Address']['region_code'] = $csv[0];
            // 旧郵便番号の設定
            $address_data['Address']['old_zipcode'] = $csv[1];
            // 現郵便番号の設定
            $address_data['Address']['zipcode'] = $csv[2];
            // 都道府県名(カタカナ)の設定
            $address_data['Address']['prefectures_kana'] = $csv[3];
            // 市区町村(カタカナ)の設定
            $address_data['Address']['city_kana'] = $csv[4];
            // 町域(カタカナ)の設定
            $address_data['Address']['town_area_kana'] = $csv[5];
            // 都道府県(漢字)の設定
            $address_data['Address']['prefectures_kannzi'] = $csv[6];
            // 市区町村(漢字)の設定
            $address_data['Address']['city_kannzi'] = $csv[7];
            // 町域(漢字)の設定
            $address_data['Address']['town_area_kannzi'] = $csv[8];
            // 一町域が二以上の郵便番号で表される場合の表示
            $address_data['Address']['town_two'] = $csv[9];
            // 小字毎に番地が起番されている町域の表示
            $address_data['Address']['town_address'] = $csv[10];
            // 丁目を有する町域の場合の表示
            $address_data['Address']['chome_town'] = $csv[11];
            // 一つの郵便番号で二以上の町域を表す場合の表示
            $address_data['Address']['zip_two'] = $csv[12];
            // 更新の表示
            $address_data['Address']['update_display'] = $csv[13];
            // 変更理由
            $address_data['Address']['reason_change'] = $csv[14];
            return $address_data;
        }

        // 文字コードによってcsvファイルの読み込み方が異なるので、その対応した関数。
        // 現在はutf-8とSJISのみ対応
        private function readCsvFile($filepath){
            // 検出する文字コードの設定
            mb_detect_order("UTF-8,UTF-7,ASCII,EUC-JP,SJIS,eucJP-win,sjis-win,JIS,ISO-2022-JP,Unicode");
            // ファイルを開く
            $data = file_get_contents($filepath);
            $this->log(mb_detect_encoding($data . 'ファイルの文字コード'));
            // ファイルの文字コードによって読み込み方を変更する。
            switch (mb_detect_encoding($data)) {
                case 'SJIS': //SJIS対応
                    // ユニークな一時ファイル作成する
                    $csv_file = tmpfile();
                    // 文字コード変換して一時ファイルに書き込む
                    fwrite($csv_file, mb_convert_encoding($data, 'UTF-8', 'SJIS'));
                    // $this->log(mb_detect_encoding($fp));
                    // ポインタを先頭に
                    fseek($csv_file, 0);
                    break;
                case 'UTF-8': //UTF-8対応
                    $csv_file = fopen($filepath, "r");
                    break;
                default:
                    $csv_file = null;
                    break;
            }
            return $csv_file;
        }
    }
?>
