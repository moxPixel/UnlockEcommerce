<?php


namespace App\Service;





use Mailjet\Client;
use App\Entity\User;
use Twig\Environment;
use Mailjet\Resources;


class Mailjet
{
    private $twig;
    private $mailJetKey;
    private $mailJetKeySecret;

    public function __construct(Environment $twig,$mailJet_api_key, $mailJet_api_key_secret)
    {
        $this->twig = $twig;
        $this->mailJetKey = $mailJet_api_key;
        $this->mailJetKeySecret = $mailJet_api_key_secret;
       
   
    }

    public function sendEmail(User $user, string $myMessage)
    {
        $message = $this->twig->render('models/message.html.twig', [
            'user' => $user,
            'message' => $myMessage
        ]);

        $this->send($this->generateSingleBody($user, "Unlock technologies", $message));
    }

    private function generateSingleBody(User $user, string $subject, string $message): array
    {
        return [
            'Messages' => [
                [
                    'From' => [
                        'Email' => "contact@unlock-technologies.fr",
                        'Name' => "Unlock technologies"
                    ],
                    'To' => [
                        [
                            'Email' => $user->getEmail(),
                            'Name' => $user->getFirstname()
                        ]
                    ],
                    'TemplateID' => 3076252,
                    'TemplateLanguage' => true,
                    'Subject' => $subject,
                    'Variables' => [
                        'body' => $message,
                    ]

                ]
            ]
        ];
    }

    /**
     * Envoi de l'Email avec Mailjet
     * @param array $body
     */
    private function send(array $body): void
    {
       
        $mj = new Client($this->mailJetKey, $this->mailJetKeySecret, true, ['version' => 'v3.1']);
      
        $response = $mj->post(Resources::$Email, ['body' => $body]);
        $response->success();
    }

}