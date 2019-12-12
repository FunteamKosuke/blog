<?php
App::uses('Component', 'Controller');
class MySecurityComponent extends Component {
    // 実装中のコンポーネントが使っている他のコンポーネント
    public $components = array('Security');

    public function startup(Controller $controller) {
        // 送信元がblog.dvだった場合はセキュリティチェックはしない。
		if (strpos($_SERVER['HTTP_REFERER'], "://blog.dv")) {
            return true;
        }
        $this->Security->startup($controller);
    }
}
?>
