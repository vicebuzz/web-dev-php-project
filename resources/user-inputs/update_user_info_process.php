<?php
require_once "../crud/userCrud.php";
function updateUserInfo($originalEmail,$originalPassword,$newEmail,$newPassword,$confirmNewPassword){

    $userCRUD = new UserCRUD();

    if ($newPassword == $confirmNewPassword) {

        $data = array(
            "userPassword" => $originalPassword,
            "email" => $newEmail
        );

        $jsonString = json_encode($data);


        $result = $userCRUD->getUser($jsonString);

        print_r($result);

        if ($result[0]["userPassword"] == hash('sha1', $originalPassword)) {
            $emailUpdateJson = json_encode(array(
                'selectParameters' => array(
                    'email' => $originalEmail,
                    'userPassword' => $originalPassword
                ),
                'updateParameters' => array(
                    'email' => $newEmail,
                    'userPassword' => $newPassword
                )
            ));
            echo $emailUpdateJson;
            $userCRUD->updateUser($emailUpdateJson);

        } else {

        }
    } else{

    }
    return "it worked";
}

$originalEmail =$_POST["original-email"];
$originalPassword=$_POST["original-password"];
$newEmail = $_POST["new-email"];
$newPassword = $_POST["new-password"];
$confirmNewPassword = $_POST["confirm-password"];


echo updateUserInfo($originalEmail,$originalPassword,$newEmail,$newPassword,$confirmNewPassword);


?>