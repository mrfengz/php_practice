<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use CodeIgniter\Exceptions\PageNotFoundException;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Session\Session;
use Config\Services;
use Psr\Log\LoggerInterface;

/**
 * Class BaseController
 *
 * BaseController provides a convenient place for loading components
 * and performing functions that are needed by all your controllers.
 * Extend this class in any new controllers:
 *     class Home extends BaseController
 *
 * For security be sure to declare any new methods as protected or private.
 *
 * @property RequestInterface $request
 */

class BaseController extends Controller
{
	/**
	 * An array of helpers to be loaded automatically upon
	 * class instantiation. These helpers will be available
	 * to all other controllers that extend BaseController.
	 *
	 * @var array
	 */
	protected $helpers = [];

    /**
     * @var Session
     */
	protected $session;

	protected $userId = 0;

	/**
	 * Constructor.
	 *
	 * @param RequestInterface  $request
	 * @param ResponseInterface $response
	 * @param LoggerInterface   $logger
	 */
	public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
	{
		// Do Not Edit This Line
		parent::initController($request, $response, $logger);
        $this->session = Services::session();
        if (empty($_SESSION['logged_in']) || empty($_SESSION['user_id'])) {
            if (!in_array($request->getUri()->getPath(),['home/index', 'home/login', 'home/logout', 'home/reg'])) {
                return $response->redirect('/home/index');
            }
        }

        if (!empty($_SESSION['user_id']))
            $this->userId = (int)$_SESSION['user_id'];
		//--------------------------------------------------------------------
		// Preload any models, libraries, etc, here.
		//--------------------------------------------------------------------
		// E.g.: $this->session = \Config\Services::session();
	}

	public function view($page, $data = [], $onlyThisPage = false)
    {
        if ( ! file_exists(APPPATH.'/Views/'.$page.'.php'))
        {
            // Whoops, we don't have a page for that!
            throw new PageNotFoundException($page);
        }

        if (empty($data['title'])) $data['title'] = ucfirst($page); // Capitalize the first letter

        if (!$onlyThisPage) echo view('Base/Header', $data);
        // 引入js和css文件
        if ($onlyThisPage) echo view('Base/Resource');
        echo view($page, $data);
        if (!$onlyThisPage) echo view('Base/Footer', $data);
    }

    public function returnJson($message, $code = 0, $data = [])
    {
        header('Content-type:application/json;charset:utf-8');
        // return $this->response->setJSON($this->formatData($message, $code, $data), true);
        return json_encode($this->formatData($message, $code, $data), JSON_UNESCAPED_UNICODE);
    }

    public function formatData($message, $code = 0, $data = [])
    {
        return [
            'code' => $code,
            'message' => $message,
            'data' => $data
        ];
    }

    /**
     * 根据操作结果，返回json或者数组结果
     * author: august 2021/2/6
     * @param $res
     * @param string $type
     * @param bool $return
     * @return array|ResponseInterface|false|string
     */
    protected function formatCodeAndMsg($res, $type = 'delete', $return = true)
    {
        if ($res) {
            $code = 0;
            switch($type) {
                case "delete": $msg = "删除成功"; break;
                case "save": $msg = "保存成功"; break;
                case "add": $msg = "新增成功"; break;
                case "update": $msg = "编辑成功"; break;
                default: $msg=  "操作成功!";
            }
        } else {
            $code = 1;
            switch($type) {
                case "delete": $msg = "删除失败"; break;
                case "save": $msg = "保存失败"; break;
                case "add": $msg = "新增失败"; break;
                case "update": $msg = "编辑失败"; break;
                default: $msg = "操作失败";
            }

            $msg .= "，请联系技术人员";
        }

        if ($return) {
            return $this->returnJson($msg, $code);
        }
        return [$msg, $code];
    }
}
