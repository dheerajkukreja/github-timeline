#!/usr/bin/php
<?php
    date_default_timezone_set("Asia/Kolkata");
    $url = 'https://github.com/timeline';
    $data = simplexml_load_file($url);
    $githubData = json_decode(json_encode($data), 1);
    $html = '';
    
    foreach ($githubData['entry'] as $key => $value) {
        //print_r($value);
        $link = $value['link']['@attributes']['href'];
        $html .= '<div>
            <div style="overflow:hidden;margin-bottom:0px;margin-top:20px;">
                <div style="display:inline-block;padding-bottom:0px;width:100%;">
                    <div style="display:inline-block;vertical-align:top;padding-bottom:12px;min-height:100px;">
                        <div style="margin-bottom:4px;">
                            <div style="font-weight:400;color:rgba(41,41,41,1);font-size:17px;line-height:28px;margin-bottom:28px;">
                                <b>
                                    <a href="'.$link.'">'.$value['title'].'</a>
                                </b>
                            </div>
                        </div>
                        <div style="font-weight:400;color:rgba(41,41,41,1);line-height:28px;font-size:20px;">
                            <relative-time datetime="2022-10-04T16:42:06Z" class="no-wrap">'.date("M d, Y H:i", strtotime($value['published'])).'</relative-time>
                        </div>
                    </div>
                </div>
            </div>
            <div style="width:100%;border-bottom:1px solid rgba(230,230,230,1);height:0px;"></div>
        </div>';
    }
    include('database.php');
    $message = '<html>
        <body>
            <div>
                <div style="margin:0;padding:0">
                    <div style="background-color:rgba(255,255,255,1)"> 
                        <table role="presentation" cellpadding="0" cellspacing="0" style="margin-left:auto;margin-right:auto;width:100%;max-width:680px" width="100%" >
                            <tbody>
                                <tr>
                                    <td style="width:100%;min-width:100%" width="100%">
                                        <div style="font-family:sohne,Helvetica Neue,Helvetica,Arial,sans-serif;color:rgba(25,25,25,1)">
                                            <div style="background-color:rgba(255,255,255,1)"> 
                                                <div style="padding:12px 27px 0 27px"> 
                                                    <div style="margin-bottom:8px;margin-top:22px;text-align:center">
                                                        <img alt="Github timeline update" src="https://www.holidaytravelzone.com/htz_timeline/github_PNG17.png" width="500" style="width:70%;min-width:400px">
                                                    </div>
                                                </div>
                                                <div style="padding:0 27px"></div>
                                                <div style="width:100%;border-bottom:1px solid rgba(230,230,230,1);height:0px"></div>
                                                <div style="background-color:#fafafa;padding:20px 27px 40px"> 
                                                    <div style="margin-bottom:11px;margin-top:11px">
                                                        <p style="margin:0;color:rgba(41,41,41,1);font-weight:700;font-size:18px;line-height:24px;text-transform:uppercase">Highlights</p>
                                                    </div>
                                                    <div style="width:100%;height:0px;border-bottom:1px solid rgba(117,117,117,1)"></div>
                                                  '.$html.'  
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </body>
    </html>';
    global $conn;
    $mails = [];
    $sql = "SELECT email from users where is_verified='1' and is_subscribed='1'";
    $result = $conn->query($sql);
    
    if($result->num_rows>=1){
        while($row = mysqli_fetch_array($result)){
            $mails[] = $row['email'];
        }
    }
    
    $headers = "From: " . strip_tags('thanku@holidaytravelzone.com') . "\r\n";
    $headers .= "Reply-To: ". strip_tags('thanku@holidaytravelzone.com') . "\r\n";
    $headers .= "MIME-Version: 1.0\r\n";
    $headers .= 'Content-type: text/html; charset=UTF-8' . "\r\n";
    $subject = "Gihtub Timeline updates";
    if(!empty($mails)){
        if(mail(implode(",", $mails) , $subject, $message, $headers)){
            echo "sent";
        }
    }

?>