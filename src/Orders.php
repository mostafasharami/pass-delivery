<?php

namespace PassQa\Delivery;

class Orders
{
    private array $options;

    public function __construct($token)
    {
        $this->options = [
            CURLOPT_URL            => "https://api.pass.qa/business/v1/orders",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING       => "",
            CURLOPT_MAXREDIRS      => 10,
            CURLOPT_TIMEOUT        => 30,
            CURLOPT_HTTP_VERSION   => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST  => "GET",
            CURLOPT_HTTPHEADER     => [
                "Authorization: Bearer {$token}",
                'Content-Type: application/json; charset=utf-8',
                'Accept: application/json'
            ],
        ];
    }

    private function send($options)
    {
        $curl = curl_init();

        curl_setopt_array($curl, $options);

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            return [
                "status"  => "error",
                "message" => "'cURL Error #:{$err}",
                "data"    => []
            ];
        } else {
            return json_decode($response);
        }
    }

    public function Price($points_info)
    {
        $options = $this->options;

        $options[CURLOPT_URL] .= '/price/calc';
        $options[CURLOPT_CUSTOMREQUEST] = 'POST';
        $options[CURLOPT_POSTFIELDS] = json_encode($points_info);

        return $this->send($options);
    }

    public function Create($order_info)
    {
        $options = $this->options;

        $options[CURLOPT_CUSTOMREQUEST] = 'POST';
        $options[CURLOPT_POSTFIELDS] = json_encode($order_info);

        return $this->send($options);
    }

    public function Detail($order_id)
    {
        $options = $this->options;

        $options[CURLOPT_URL] .= "/{$order_id}";

        return $this->send($options);
    }

    public function Tracking($order_id)
    {
        $options = $this->options;

        $options[CURLOPT_URL] .= "/{$order_id}/eta";

        return $this->send($options);
    }

    public function List()
    {
        return $this->send($this->options);
    }

    public function Cancel($order_id)
    {
        $options = $this->options;

        $options[CURLOPT_URL] .= "/{$order_id}/cancel";

        return $this->send($options);
    }
}