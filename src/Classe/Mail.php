<?php

namespace App\Classe;

use Mailjet\Client;
use Mailjet\Resources;

class Mail
{
    private $api_key = '266c4ccbb28429981d435f856b56c06c';
    private $api_key_secret = '87366a8afd598209b355ee3e7467c077';

    public function send($to_email,$to_name,$subject,$content)
    {
        $mj = new Client($this->api_key , $this->api_key_secret ,true , ['version'=>'v3.1']);
      
$body = [
    'Messages' => [
        [
            'From' => [
                'Email' => "fullstack.reconversion@gmail.com",
                'Name' => "Art2pix"
            ],
            'To' => [
                [
                    'Email' => $to_email,
                    'Name' => $to_name
                ]
            ],
            'TemplateID'=>4241736,
            'TemplateLanguage'=> true,
            'Subject' => $subject,
            'Variables' => [
                'content' => $content,
            ],
        ]
    ]
];
$response = $mj->post(Resources::$Email, ['body' => $body]);
$response->success();
    }
    
}
