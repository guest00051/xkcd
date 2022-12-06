<?php
require_once __DIR__ . '/Model/connectdb.php';

class cron_job
{
    private $content;
    private $body;


    function __construct()
    {
        $context = stream_context_create([
            'ssl' => [
                'verify_peer' => false,
                'verify_peer_name' => false,
            ],
        ]);
        $randomComicUrl = get_headers('https://c.xkcd.com/random/comic', 0, $context)[15]; //get random comic url
        $splitLink = ltrim($randomComicUrl, 'Location :');
        $url = $splitLink . 'info.0.json';

        if (filter_var($url, FILTER_VALIDATE_URL)) {
            $url_data = file_get_contents($url);
            $this->content = json_decode($url_data);

            $this->runCronJob();


            // $this->content->day.'/'.$this->content->month;
            // $this->content->safe_title; //       
            // $this->content->alt;           
            // $this->content->num;          // 
            // $this->content->img;            
            // str_replace( array( '\'', '[',']' , '{', '}'), ' ', $this->content->transcript);
            // $email = "cpanchal";
            // $unSubLink = "https://xkcdchallenge2022.000webhostapp.com/View/unsubscribe.php?email=".$email; 
            // $this->body = '<!DOCTYPE html><html><head><meta name="viewport" content="width=device-width,initial-scale=1"><style>body{background-color:#f2e7d5}.outer{padding:3px 5px;text-align:center}.title{background-color:#393e46;border-radius:10px;padding:6px;color:#f7f7f7;font-weight:700}.alttext{padding:4px 5px;margin:12px 5px;border-bottom:2px solid #6d9886;font-weight:100}img{width:100%;height:auto;padding-top:5px}</style></head><body><div class="outer"><p class="title"> '.$this->content->safe_title."[".$this->content->num."]".'  </p><p class="alttext">That one is a variable star which pulses every 30 seconds. Its name comes from a Greek word meaning "smoke alarm."</p><img src="'.$this->content->img.'" alt="xkcd-comic-'.$this->content->num.'"><p class="desc">'.str_replace( array( '\'', '[',']' , '{', '}'), ' ', $this->content->transcript).'</p><hr><p>if you do not want to recieve these emails, you can unsubscribe here<a href="'.$unSubLink.'">unsubscribe</a></p></div></body></html>';

        } else
            echo ("failed to load url");
    }

    function runCronJob()
    {

        $dbObj = new connectDB();
        $dbCon = $dbObj->databaseConn();
        $queryStr = 'SELECT * FROM USERS WHERE isactivate=? and isverify=?';
        $query = $dbCon->prepare($queryStr);
        $bool = 1;
        $query->bind_param('ii', $bool, $bool);
        $query->execute();
        $result = $query->get_result();
        
        if ($result->num_rows > 0) {
            while ($array = $result->fetch_assoc()) {
                $email = $array['email'];
                $unSubLink = "https://xkcdchallenge2022.000webhostapp.com/View/unsubscribe.php?email=" . $email;
                $this->body = '<!DOCTYPE html><html><head><meta name="viewport" content="width=device-width,initial-scale=1"><style>body{background-color:#f2e7d5}.outer{padding:3px 5px;text-align:center}.title{background-color:#393e46;border-radius:10px;padding:6px;color:#f7f7f7;font-weight:700}.alttext{padding:4px 5px;margin:12px 5px;border-bottom:2px solid #6d9886;font-weight:100}img{width:100%;height:auto;padding-top:5px}</style></head><body><div class="outer"><p class="title"> ' . $this->content->safe_title . " # " . $this->content->num . '  </p><p class="alttext">That one is a variable star which pulses every 30 seconds. Its name comes from a Greek word meaning "smoke alarm."</p><img src="' . $this->content->img . '" alt="xkcd-comic-' . $this->content->num . '"><p class="desc">' . str_replace(array('\'', '[', ']', '{', '}'), ' ', $this->content->transcript) . '</p><hr><p>if you do not want to recieve these emails, you can unsubscribe here<a href="' . $unSubLink . '"> unsubscribe </a></p></div></body></html>';

                $file = $this->content->img;
                $content = file_get_contents($file);
                $content = chunk_split(base64_encode($content));
                $uid = md5(uniqid(time()));
                $filename = basename($file);

                $header = "From:chirag@xkcd2022challenge.com \r\n";
                $header .= "Cc:cpanchal2022@gmail.com \r\n";
                $header .= "MIME-Version: 1.0\r\n";
                $header .= "Content-Type: multipart/mixed; boundary=\"" . $uid . "\"\r\n\r\n";

                $body = "--" . $uid . "\r\n";
                $body .= "Content-type:text/html; charset=iso-8859-1\r\n";
                $body .= "Content-Transfer-Encoding: 7bit\r\n\r\n";
                $body .= $this->body . "\r\n\r\n";
                $body .= "--" . $uid . "\r\n";
                $body .= "Content-Type: application/octet-stream; name=\"" . $filename . "\"\r\n";
                $body .= "Content-Transfer-Encoding: base64\r\n";
                $body .= "Content-Disposition: attachment; filename=\"" . $filename . "\"\r\n\r\n";
                $body .= $content . "\r\n\r\n";
                $body .= "--" . $uid . "--";
                $subject = "XKCD Comic - " . $this->content->safe_title . " # " . $this->content->num;
                $retval = mail($email, $subject, $body, $header);

                if (!$retval) {
                    echo "Mailer Error: ";
                } else {
                    $updateQuery = 'UPDATE USERS SET cronjob = cronjob + 1 WHERE email = ?';
                    $updateQuery = $dbCon->prepare($updateQuery);
                    $updateQuery->bind_param('s', $email);
                    $updateQuery->execute();
                    if ($updateQuery->affected_rows > 0)
                        echo "Cron run successfully ==> " . $email . "</br>";
                    else
                        echo "failed to update cronjob";
                }

            }
        } else {
            echo "No users subscribed the comics";
        }
    }
}
new cron_job();

?>







