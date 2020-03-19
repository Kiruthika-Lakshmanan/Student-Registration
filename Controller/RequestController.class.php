<?php
namespace Student\Controller;

class RequestControllerException extends \Exception {}

class RequestController {

    protected $module;
    protected $action;
    protected $postParams = [];
    protected $template;
//  protected $response = [];

    /**
     * Constructor to validate the request
     */
    public function __construct()
    {
        $this->template = new \HTML_Template_Sigma(ROOT_DIR . '/View/Template/');
        $this->template->setErrorHandling(PEAR_ERROR_DIE);

        try {
            $this->validateRequest();
        } catch (RequestControllerException $e) {
            return $e->getMessage();
        }
    }

    /**
     * Validate request parameters
     *
     * @throws ApiRequestHandlerException
     */
    private function validateRequest()
    {
        $this->module = isset($_GET['module']) && !empty($_GET['module']) ? $_GET['module'] : 'login';
        $this->action = isset($_GET['act']) && !empty($_GET['act']) ? $_GET['act'] : '';
        $this->setPostParams();
    }

    /**
     * Fetch and set the post input parameter values
     */
    protected function setPostParams()
    {
        $this->postParams = !empty($_POST) ? $_POST : [];
    }

    /**
     * Get the post parameters
     *
     * @return array Post parameters
     */
    public function getPostParams()
    {
        return $this->postParams;
    }

    public function processRequest()
    {
        if ($this->module == 'login') {
           $loginController = new \Student\Controller\LoginController($this);
           switch ($this->action) {
               case 'register':
                    $response = $loginController->registration();
                   break;
               case 'forget':
                    $response = $loginController->forgetPassword();
                   break;
                case 'reset':
                    $response = $loginController->resetPassword();
                   break;
                   default:
                    $response = $loginController->login();
                   break;
           }
        } 
       
        if ($response) {
          $this->finalResponse($response);
        }
   
    }
     
    public function submitRequest()
    {
       
        $loginController = new \Student\Controller\LoginController($this);    
        $response = $loginController->sucess();
        
        if ($response) {
          $this->finalResponse($response);
        }
       
    }        

    public function finalResponse($output)
    {
        $this->template->loadTemplateFile('layout.html');
        $this->template->setVariable([
            'CONTENT' => $output,
            'BACKGROUND_CLASS' =>  $this->module 
        ]);
        echo $this->template->get();
        exit;
    }
}