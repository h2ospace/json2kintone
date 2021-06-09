<?php
class Json2kintone {
    private $config;

    public function __construct() {
        // load a config file
        $config_file = file_get_contents(dirname(__FILE__) . '/config.json');
        $this->config = json_decode($config_file);
    }

    /**
     * Save to kintone all data
     * @param $app
     * @param $data
     * @param string $kintone
     */
    public function save_bulk($app, $data_kintone, $kintone = 'default') {
        if (empty($this->config->$kintone)) die('Unknown kintone code');

        if ($_SERVER['SERVER_NAME'] != 'wizmu.jp') {
            $kintone = 'develop';
        }

        // get a app data
        $app_data = '';
        foreach ($this->config->$kintone->apps as $apps) {
            if ($apps->key === $app) {
                $app_data = $apps;
            }
        }

        if (empty($app_data)) die('Unknown app code');

        $body = [
            'app' => $app_data->app_id,
            'records' => $data_kintone,
        ];

        $context = stream_context_create([
            'http' => array(
                'method' => 'POST',
                'header' => 'X-Cybozu-API-Token:' . $app_data->api_token . "\r\n" .
                    'Content-Type: application/json' . "\r\n",
                'content' => json_encode($body),
                'ignore_errors' => true,
            )
        ]);
        $header = ['X-Cybozu-API-Token:' . $app_data->api_token,
            'Content-Type: application/json'
        ];

        $curl = curl_init();

        curl_setopt($curl, CURLOPT_URL, 'https://' . $this->config->$kintone->domain . '/k/guest/20/v1/records.json');
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($body));
        curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HEADER, true);

        $response = curl_exec($curl);

        $header_size = curl_getinfo($curl, CURLINFO_HEADER_SIZE);
        $header = substr($response, 0, $header_size);
        $body = substr($response, $header_size);
        $result = json_decode($body, true);

        curl_close($curl);

        return $result;
    }

    /**
     * @param $app
     * @param $data
     * @param string $kintone
     * @return mixed
     */
    public function save($app, $data, $kintone = 'default') {
        if (empty($this->config->$kintone)) die('Unknown kintone code');

        if ($_SERVER['SERVER_NAME'] != 'wizmu.jp') {
            $kintone = 'develop';
        }

        // get a app data
        $app_data = '';
        foreach ($this->config->$kintone->apps as $apps) {
            if ($apps->key === $app) {
                $app_data = $apps;
            }
        }

        if (empty($app_data)) die('Unknown app code');

        $data_kintone = [];
        foreach ($data as $key => $val) {
            $data_kintone[$key] = [
                'value' => $val,
            ];
        }

        $body = [
            'app' => $app_data->app_id,
            'record' => $data_kintone,
        ];


        $context = stream_context_create([
            'http' => array(
                'method' => 'POST',
                'header' => 'X-Cybozu-API-Token:' . $app_data->api_token . "\r\n" .
                    'Content-Type: application/json' . "\r\n",
                'content' => json_encode($body),
                'ignore_errors' => true,
            )
        ]);
        $header = ['X-Cybozu-API-Token:' . $app_data->api_token,
            'Content-Type: application/json'
        ];

//        $contents = file_get_contents('https://' . $this->config->$kintone->domain . '/k/v1/record.json', false, $context);
        $curl = curl_init();

        curl_setopt($curl, CURLOPT_URL, 'https://' . $this->config->$kintone->domain . '/k/guest/20/v1/record.json');
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'POST'); // post
        curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($body)); // jsonデータを送信
        curl_setopt($curl, CURLOPT_HTTPHEADER, $header); // リクエストにヘッダーを含める
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HEADER, true);

        $response = curl_exec($curl);

        $header_size = curl_getinfo($curl, CURLINFO_HEADER_SIZE);
        $header = substr($response, 0, $header_size);
        $body = substr($response, $header_size);
        $result = json_decode($body, true);

        curl_close($curl);

        return $result;
    }

    public function update($app, $data, $id, $kintone = 'default') {
        if (empty($this->config->$kintone)) die('Unknown kintone code');

        if ($_SERVER['SERVER_NAME'] != 'wizmu.jp') {
            $kintone = 'develop';
        }

        // get a app data
        $app_data = '';
        foreach ($this->config->$kintone->apps as $apps) {
            if ($apps->key === $app) {
                $app_data = $apps;
            }
        }

        if (empty($app_data)) die('Unknown app code');

        $data_kintone = [];
        foreach ($data as $key => $val) {
            $data_kintone[$key] = [
                'value' => $val,
            ];
        }

        $body = [
            'app' => $app_data->app_id,
            "id" => $id,
            'record' => $data_kintone,
        ];

//        var_dump(json_encode($data_kintone, JSON_UNESCAPED_UNICODE));
//        exit();

        $context = stream_context_create([
            'http' => array(
                'method' => 'PUT',
                'header' => 'X-Cybozu-API-Token:' . $app_data->api_token . "\r\n" .
                    'Content-Type: application/json' . "\r\n",
                'content' => json_encode($body),
                'ignore_errors' => true,
            )
        ]);
        $header = ['X-Cybozu-API-Token:' . $app_data->api_token,
            'Content-Type: application/json'
        ];

//        $contents = file_get_contents('https://' . $this->config->$kintone->domain . '/k/v1/record.json', false, $context);
        $curl = curl_init();

        curl_setopt($curl, CURLOPT_URL, 'https://' . $this->config->$kintone->domain . '/k/guest/20/v1/record.json');
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'PUT'); // post
        curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($body)); // jsonデータを送信
        curl_setopt($curl, CURLOPT_HTTPHEADER, $header); // リクエストにヘッダーを含める
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HEADER, true);

        $response = curl_exec($curl);

        $header_size = curl_getinfo($curl, CURLINFO_HEADER_SIZE);
        $header = substr($response, 0, $header_size);
        $body = substr($response, $header_size);
        $result = json_decode($body, true);

        curl_close($curl);

        return $result;
    }

    // データの取得
    function getRows($app, $query, $kintone = 'default') {
        if (empty($this->config->$kintone)) die('Unknown kintone code');

        if ($_SERVER['SERVER_NAME'] != 'wizmu.jp') {
            $kintone = 'develop';
        }

        // get a app data
        $app_data = '';
        foreach ($this->config->$kintone->apps as $apps) {
            if ($apps->key === $app) {
                $app_data = $apps;
            }
        }

        if (empty($app_data)) die('Unknown app code');

        $context = stream_context_create(array(
            'http' => array(
                'method' => 'GET',
                'header' => 'X-Cybozu-API-Token:' . $app_data->api_token . "\r\n"
            )
        ));

        $param = 'app=' . $app_data->app_id . '&query=' . urlencode($query);

        $contents = file_get_contents('https://' . $this->config->$kintone->domain . '/k/guest/20/v1/records.json?' . $param, false, $context);
        if ($contents) {
            $json = json_decode($contents);
            return $json->records;
        }
    }
}

/*
$records = array();
$st = $_SESSION['structures'];
foreach ($fragment as $key => $val) {
    $records[$key] = array(
        'value' => $val,
    );
}
$form = array(
    'app' => $kintone_app['members']['id'],
    'record' => $records
);

$context = stream_context_create(array(
    'http' => array(
        'method' => 'POST',
        'header' => 'X-Cybozu-API-Token:' . $kintone_app['members']['token'] . "\r\n" .
            'Content-Type: application/json' . "\r\n",
        'content' => json_encode($form),
    )
));

$contents = file_get_contents('https://f2f.cybozu.com/k/guest/' . KINTONE_GUEST_SPACE_ID . '/v1/record.json', false, $context);
*/
