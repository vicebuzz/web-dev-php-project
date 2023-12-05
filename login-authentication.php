<?php
require './resources/crud/userCrud.php';

Class loginValidate{

    public $userCrud;
    function __construct(){
    $this->userCrud = new UserCRUD();
    }


    function login(){
//TODO: make html form retrieval

        $usernameInput = "vikstar"; #form submission
        $passwordInput = "vikstarcool12345"; # form submission

        $dataArr = array(
        'username' => $usernameInput,
        'user_password' => $passwordInput
    );

    $jsonPar = json_encode($dataArr);

    $result = ($this -> userCrud -> getUser($jsonPar));

    if ($result){
        $isAdmin = $this->userCrud->isUserAnAdmin($jsonPar);
        if($isAdmin){
            $userLoginType = "admin";
        }
        else $userLoginType = "basic_user";
    }
    else {
        $this -> handleFailedLogin();
    };


    return $userLoginType;
    }

    

    function handleFailedLogin(){

    }

   
   
    function loadPage($pageType){

        if ($pageType == "admin"){
            //load admin page
        }
        elseif ($pageType == "basic_user"){
            //load basic page
        }
        return $pageType;
    }

}

$loginRun = new loginValidate();
echo $loginRun ->loadPage($loginRun->login());

?>