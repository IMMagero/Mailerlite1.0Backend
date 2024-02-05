<?php
class UserController extends BaseController
{
    public $strErrorDesc;
    public $requestMethod;
    
/** 
* "/user/list" Endpoint - Get list of users 
*/
    public function listAction()
    {
        $this->strErrorDesc = '';
        $this->requestMethod = $_SERVER["REQUEST_METHOD"];
        $arrQueryStringParams = $this->getQueryStringParams();
        if (strtoupper($this->requestMethod) == 'GET') {
            try {
                $userModel = new UserModel();
                $intLimit = 20;
                if (isset($arrQueryStringParams['limit']) && $arrQueryStringParams['limit']) {
                    $intLimit = $arrQueryStringParams['limit'];
                }
                $arrUsers = $userModel->getSubscriber($intLimit);
                $responseData = json_encode($arrUsers);
            } catch (Error $e) {
                $this->strErrorDesc = $e->getMessage().'Something went wrong! Please contact support.';
                $strErrorHeader = 'HTTP/1.1 500 Internal Server Error';
            }
        } else {
            $this->strErrorDesc = 'Method not supported';
            $strErrorHeader = 'HTTP/1.1 422 Unprocessable Entity';
        }
        // send output 
        if (!$this->strErrorDesc) {
            $this->sendOutput(
                $responseData,
                array('Content-Type: application/json', 'HTTP/1.1 200 OK')
            );
        } else {
            $this->sendOutput(json_encode(array('error' => $this->strErrorDesc)), 
                array('Content-Type: application/json', $strErrorHeader)
            );
        }
    }

/** 
* "/user/create" Endpoint - Create users 
*/
    public function createAction()
    {
        $this->strErrorDesc = '';
        $this->requestMethod = $_SERVER["REQUEST_METHOD"];
        if (strtoupper($this->requestMethod) == 'POST') {

            //Get posted data either from a frontend app or API platform e.g Postman
            $data = json_decode(file_get_contents("php://input"),true);
            
            if(!empty($data)){
                $name = $data['name'];
                $lastname = $data['lastname'];
                $email = $data['email'];
            }
            else{
                $name = $_POST['name'];
                $lastname = $_POST['lastname'];
                $email = $_POST['email'];
            }
            
            if(!empty($name) && !empty($lastname) && !empty($email)) { 
                try {
                    //Email Validation
                    //$email = $_POST['email'];
                    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                        $this->strErrorDesc = "Email address '$email' is considered invalid.\n";
                        $strErrorHeader = 'HTTP/1.1 422 Unprocessable Entity';
                        $this->sendOutput(json_encode(array('error' => $this->strErrorDesc)), 
                        array('Content-Type: application/json', $strErrorHeader) );
                        
                    } 
                    $UserCreate = [
                        "name" => $name,
                        "last_name" => $lastname,
                        "email_address" => $email,
                        "status" => 1, // 1 - Active 0 - Inactive
                        "user_type" => 'Subscriber', 
                    ];
                    $userModel = new UserModel();
                    $createdUser = $userModel->createSubscriber($UserCreate);
                    $responseData = json_encode($createdUser); 

                }  catch (Error $e) {
                    $this->strErrorDesc = $e->getMessage().'Something went wrong! Please contact support.';
                    $strErrorHeader = 'HTTP/1.1 500 Internal Server Error';
                }

 

            } else {
                $strErrorHeader = 'Missing required data';
                $this->strErrorDesc = 'Required param missing';
                $this->sendOutput(json_encode(array('error' => $this->strErrorDesc)), 
                array('Content-Type: application/json', $strErrorHeader)
            );

            }
            
            

        } else {
            $this->strErrorDesc = 'Method not supported'.strtoupper($this->requestMethod);
            $strErrorHeader = 'HTTP/1.1 422 Unprocessable Entity';
        }

        // send output 
        if (!$this->strErrorDesc) {
            $this->sendOutput(
                $responseData,
                array('Content-Type: application/json', 'HTTP/1.1 200 OK')
            );
        } else {
            $this->sendOutput(json_encode(array('error' => $this->strErrorDesc)), 
                array('Content-Type: application/json', $strErrorHeader)
            );
        }


    }
/** 
* "/user/search?email=test@gmail.com" Endpoint - Get user by email address 
*/
    public function searchAction()
    {
        $this->requestMethod = $_SERVER["REQUEST_METHOD"];   
        
        if (strtoupper($this->requestMethod) == 'GET') {
            try {

                if(isset($_SERVER['QUERY_STRING']) && isset($_GET['email']) ) {
                    $email = $_GET["email"];
                    $userModel = new UserModel();
                    $user = $userModel->findSubscriber($email);
                    $responseData = json_encode($user); 
           } else
                {
                    $this->strErrorDesc = 'Required URL parameter missing';
                    $strErrorHeader = 'HTTP/1.1 400 Unprocessable Entity';

                }
            } catch (Error $e) {
                $this->strErrorDesc = $e->getMessage().'Something went wrong! Please contact support.';
                $strErrorHeader = 'HTTP/1.1 500 Internal Server Error';
            }
    } else {
        $this->strErrorDesc = 'Method not supported';
        $strErrorHeader = 'HTTP/1.1 422 Unprocessable Entity';
    }
    // send output 
    if (!$this->strErrorDesc) {
        $this->sendOutput(
            $responseData,
            array('Content-Type: application/json', 'HTTP/1.1 200 OK')
        );
    } else {
        $this->sendOutput(json_encode(array('error' => $this->strErrorDesc)), 
            array('Content-Type: application/json', $strErrorHeader)
        );
    }


}
}