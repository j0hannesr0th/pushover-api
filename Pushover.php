<?php
declare(strict_types=1);

class Pushover
{
    protected object $config;

    public function __construct(object $config)
    {
        $this->config = $config;
    }

    public function sendNotification(array $params): string
    {
        $params = array_merge((array) $this->config, $params);

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $this->config->apiUrl);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($params));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($ch);

        curl_close($ch);

        return $response ?: '';
    }

    public function getParameters(): array
    {
        $soundOptions = $this->getSoundOptions();

        return [
            'token' => 'Your application\'s API token',
            'user' => 'The user/group key (not e-mail address) of your user (or you), viewable when logged into our dashboard',
            'message' => 'Your message',
            'attachment' => 'An image attachment to send with the message',
            'device' => 'Your user\'s device name to send the message directly to that device, rather than all of the user\'s devices',
            'title' => 'Your message\'s title, otherwise your app\'s name is used',
            'url' => 'A supplementary URL to show with your message',
            'url_title' => 'A title for your supplementary URL, otherwise just the URL is shown',
            'priority' => '-2 to send no notification/alert, -1 to always send as a quiet notification, 1 to display as high-priority and bypass the user\'s quiet hours, or 2 to also require confirmation from the user',
            'sound' => [
                'description' => 'The name of one of the sounds supported by device clients to override the user\'s default sound choice',
                'values' => $soundOptions
            ]
        ];
    }

    private function getSoundOptions(): array
    {
        $url = "https://api.pushover.net/1/sounds.json?token={$this->config->token}";

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($ch);

        curl_close($ch);

        if ($response) {
            $responseData = json_decode($response, true);
            return array_keys($responseData['sounds']);
        }

        return [];
    }
}
