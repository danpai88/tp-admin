<?php
namespace app\admin\controller;

use think\captcha\Captcha;
use think\Controller;
use think\facade\Request;
use think\facade\Session;
use app\common\validate\UserLoginValidate;

class Login extends Controller
{
	public function index()
	{
		if(Request::isPost()){
			$data = Request::post();

			$validate = new UserLoginValidate();
			if(!$validate->check($data)){
				$this->error($validate->getError());
			}

			$user = model('AdminUser')->where('username', $data['username'])->find();
			if(!$user){
				$this->error('用户不存在');
			}

			if(password_verify($data['password'], $user->password)){
				Session::set('username', $user->username);
				Session::set('uid', $user->id);
				Session::set('nickname', $user->nickname);

				$this->success('登录成功', url('menu/index'));
			}
			$this->error('用户或者密码错误', url('menu/index'));
		}
		return $this->fetch('common@login/index');
	}

	public function showCaptcha()
	{
		$captcha = new Captcha();
		return $captcha->entry('user_login');
	}

	public function logout()
	{
		Session::clear();
		$this->success('登出成功', url('login/index'));
	}

	public function build()
	{
		if(Request::isPost()){
			$content = Request::post('content');
			$arr = explode("\n", $content);
			
			$fields = [];
			foreach ($arr as $key => $item) {
				if($key === 0){
					continue;
				}

				if(!stristr($item, '`')){
					continue;
				}

				$item = trim(strtolower($item));
				$tmp = explode(" ", $item);

				foreach ($tmp as $index => $sitem) {
					if($index === 0){
						list($type,) = explode('(', rtrim(trim($tmp[$index+1], '\''),',\''));
						$fields[$key] = [
							'name' => trim($sitem, '`'),
							'type' => $type
						];
					}
					if($sitem == 'comment'){
						$fields[$key]['comment'] = rtrim(trim($tmp[$index+1], '\''),',\'');
					}
				}
			}
			$this->assign('content', $content);
			$this->assign('fields', $fields);
		}
		return $this->fetch();
	}
}