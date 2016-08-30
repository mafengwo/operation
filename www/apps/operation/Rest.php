<?php
/**
 * Operation System by mafengwo.cn
 * Vision: To make a visual and automatic operation syetem, and free operation engineers
 * Official Github: <https://github.com/mafengwo/operation>
 * User: Jichen Zhou
 */
namespace apps\operation;
class MRest extends \Ko_Mode_Rest{
	const ERROR_PERMISSION_FORBIDDEN = -10000101;
	protected function _sGetClassname($sModule, $sResource)
	{
		$item = explode('/', $sModule);
		$classname = KO_APPS_NS.'\\';
		foreach ($item as $v)
		{
			$classname .= $v.'\\';
		}
		$classname .= 'MRest_'.$sResource;
		return $classname;
	}

	protected function _aLoadConf($sModule, $sResource)
	{
		$sModule!='user' && $this->_bAccess();//user以外的模块进行权限检查
		$classname = $this->_sGetClassname($sModule, $sResource);
		if (!class_exists($classname) || !isset($classname::$s_aConf))
		{
			throw new \Exception('资源不存在', self::ERROR_RESOURCE_INVALID);
		}
		return $classname::$s_aConf;
	}

	private function _bAccess()
	{
		$refer = \Ko_Web_Request::SHttpReferer();
		$uri_info = parse_url($refer);
		$loginApi = new \apps\user\MloginApi();
		$uid = $loginApi->iGetLoginUid();
		$app = new \apps\system\MApp();
		if(!$app->bAccess($uid,$uri_info['path'])){
			throw new \Exception('没有操作权限', self::ERROR_PERMISSION_FORBIDDEN);
		}
	}

	public function run()
	{
		$uri = \Ko_Web_Request::SGet('uri');
		$req_method = \Ko_Web_Request::SRequestMethod(true);
		if ('POST' === $req_method)
		{
			$method = \Ko_Web_Request::SPost('method');
			if ('PUT' === $method || 'DELETE' === $method)
			{
				$req_method = $method;
			}
		}
		$input = ('GET' === $req_method) ? $_GET : $_POST;
		unset($input['uri']);
		unset($input['method']);
		if (isset($input['jsondata']))
		{
			$input = json_decode($input['jsondata'], true);
		}

		$rest = new MRest;
		$data = $rest->aCall($req_method, $uri, $input);
		$render = new \apps\render\Mjson();
		$render->oSetData($data)->oSend();
	}
}
