<?php
    include('database.php');
    $req = $_REQUEST;
    $allowed_methods = array('subscribe','unsubscribe', 'verifyemail', 'unsubscribe');
    $result = [];
    if($req['action']=='verifyemail'){
         $response = updateEmailToVerify();
         
    }else{
        if(isset($_POST['action']) && in_array($_POST['action'], $allowed_methods)) {
            if($_POST['action'] === 'subscribe') {
                $response = subscribe();
                echo json_encode($response);
                die;
            }
        }else{
            $result['status'] = false;
            $result['msg'] = 'method not allowed';
            echo json_encode($result);
            die;
        }
    }

    function subscribe () {
        $error = false;
        $msg = '';
        $response = ['status' => true];
        if(isset($_POST['user_name']) && !empty($_POST['user_name'])) {
            $name = $_POST['user_name'];
        } else {
            $error = true;
            $msg .= '<div class="error_response"> Name cannot be blank </div>';
        }
        if(isset($_POST['user_email']) && !empty($_POST['user_email'])) {
            $email = $_POST['user_email'];
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $error = true;
                $msg .= '<div class="error_response"> Email id is not valid </div>';
            }
        } else {
            $error = true;
            $msg .= '<div class="error_response"> Email cannot be blank </div>';
        }
        
        if($error) {
            $response['status'] = !$error;
            $response['msg'] = $msg;
        } else {
            global $conn;
            $email = mysqli_escape_string($conn, $email);
            $name = mysqli_escape_string($conn, $name);
            $sql = "INSERT INTO users (user_name, email) VALUES ('".$name."', '".$email."')";
            try{
                if ($conn->query($sql) === TRUE) {
                    $response['msg'] = "Record Successfully inserted";
                    sendmail($name, $email);
                } else {
                    throw new Exception($conn->error);
                }
            } catch(Exception $e) {
                $response['status'] = !$response['status'];
                $response['msg'] = $e->getMessage();
            }
        }
        return $response;
    }
    
    function sendMail($name, $email){
        $subject = "Email-verification";
        $md5Email = md5($email);
        $html = '<html>
            <body>
                <div style="background:#f9f9f9">
                    <div style="background-color:#f9f9f9">
                        <div style="margin:0px auto;max-width:640px;background:transparent">
                            <table role="presentation" cellpadding="0" cellspacing="0" style="font-size:0px;width:100%;background:transparent" align="center" border="0">
                                <tbody>
                                    <tr>
                                        <td style="text-align:center;vertical-align:top;direction:ltr;font-size:0px;padding:40px 0px">
                                            <div aria-labelledby="mj-column-per-100" class="m_824957694527970661mj-column-per-100" style="vertical-align:top;display:inline-block;direction:ltr;font-size:13px;text-align:left;width:100%">
                                                <table role="presentation" cellpadding="0" cellspacing="0" width="100%" border="0">
                                                    <tbody>
                                                        <tr>
                                                            <td style="word-break:break-word;font-size:0px;padding:0px" align="center">
                                                                <table role="presentation" cellpadding="0" cellspacing="0" style="border-collapse:collapse;border-spacing:0px" align="center" border="0">
                                                                    <tbody>
                                                                        <tr>
                                                                            <td style="width:138px">
                                                                                <a>
                                                                                    <img height="130px" src="https://www.holidaytravelzone.com/htz_timeline/rtCamplogo.jpg" width="150" class="CToWUd" data-bit="iit">
                                                                                </a>
                                                                            </td>
                                                                        </tr>
                                                                    </tbody>
                                                                </table>
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div style="max-width:640px;margin:0 auto;border-radius:4px;overflow:hidden">
                            <div style="margin:0px auto;max-width:640px;background:#ffffff">
                                <table role="presentation" cellpadding="0" cellspacing="0" style="font-size:0px;width:100%;background:#ffffff" align="center" border="0">
                                    <tbody>
                                        <tr>
                                            <td style="text-align:center;vertical-align:top;direction:ltr;font-size:0px;padding:40px 50px">
                                                <div aria-labelledby="mj-column-per-100" class="m_824957694527970661mj-column-per-100" style="vertical-align:top;display:inline-block;direction:ltr;font-size:13px;text-align:left;width:100%">
                                                    <table role="presentation" cellpadding="0" cellspacing="0" width="100%" border="0">
                                                        <tbody>
                                                            <tr>
                                                                <td style="word-break:break-word;font-size:0px;padding:0px" align="left">
                                                                    <div style="color:#737f8d;font-family:Whitney,Helvetica Neue,Helvetica,Arial,Lucida Grande,sans-serif;font-size:16px;line-height:24px;text-align:left">
                                                                        <h2 style="font-family:Whitney,Helvetica Neue,Helvetica,Arial,Lucida Grande,sans-serif;font-weight:500;font-size:20px;color:#4f545c;letter-spacing:0.27px">Hey {{USER_NAME}},</h2>
                                                                        <p>We are so happy that out of all the emails you could have signed up for, you chose ours. We pledge to do our very best to only send you content that is valuable to you. Before we get started, we just need to confirm that this is you. Click below to verify your email address:</p>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td style="word-break:break-word;font-size:0px;padding:10px 25px;padding-top:20px" align="center">
                                                                    <table role="presentation" cellpadding="0" cellspacing="0" style="border-collapse:separate" align="center" border="0">
                                                                        <tbody>
                                                                            <tr>
                                                                                <td style="border:none;border-radius:3px;color:white;padding:15px 19px" align="center" valign="middle" bgcolor="#5865f2">
                                                                                    <a style="text-decoration:none;line-height:100%;background:#5865f2;color:white;font-family:Ubuntu,Helvetica,Arial,sans-serif;font-size:15px;font-weight:normal;text-transform:none;margin:0px" href="https://www.holidaytravelzone.com/htz_timeline/user.php?action=verifyemail&email='.$md5Email.'"> Verify Email </a>
                                                                                </td>
                                                                            </tr>
                                                                        </tbody>
                                                                    </table>
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </body>
        </html>';

        $message = str_replace("{{USER_NAME}}",$name, $html);
        
        $headers = "From: " . strip_tags('thanku@holidaytravelzone.com') . "\r\n";
        $headers .= "Reply-To: ". strip_tags('thanku@holidaytravelzone.com') . "\r\n";
        $headers .= "MIME-Version: 1.0\r\n";
        $headers .= 'Content-type: text/html; charset=UTF-8' . "\r\n";
        mail($email, $subject, $message, $headers);
    }
    
    function updateEmailToVerify(){
        global $conn;
        $email = mysqli_escape_string($conn, $_REQUEST['email']);
        
        if(empty($email)){
            $msg = "Email-id is empty";
        }else{
            $selectSql = "SELECT * from users where md5(email)='".$email."'";
            
            $result = $conn->query($selectSql);
            if($result->num_rows==1){
                while($row = mysqli_fetch_array($result)){
                    $is_verified = $row['is_verified'];
                }
                if($is_verified){
                    $msg = "This Email-id Has been Already Verified!";
                }else{
                    $sql = "UPDATE users set is_verified='1', is_subscribed='1' where md5(email)='".$email."'";
                    if($conn->query($sql)){
                        $msg = "Thank you ! <br>Your Email-id Has been Verified";
                    }
                }
            }else{
                $msg = "This Email-id is not associated with any account!";
            }
        }
        
        echo '<html lang="en">
            <head>
                <meta charset="UTF-8">
                <meta http-equiv="X-UA-Compatible" content="IE=edge">
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <title>rtcamp assignment</title>
                <link rel="stylesheet" href="index.css" type="text/css">
            </head>
            <body>
            	<h2>'.$msg.'</h2>
            </body>
            </html>';    
        die;
        
    }
?>