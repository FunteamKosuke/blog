<?php
App::uses('Component', 'Controller');
class MySecurityComponent extends Component {
    // 実装中のコンポーネントが使っている他のコンポーネント
    public $components = array('Security');

    public function startup(Controller $controller) {
        // 送信元がcore.phpで設定したドメインだった場合はセキュリティチェックはしない。
		if (isset($_SERVER['HTTP_REFERER']) && strpos($_SERVER['HTTP_REFERER'], Configure::read('domainName'))) {
            return true;
        }
        $this->Security->startup($controller);
    }
}
?>
