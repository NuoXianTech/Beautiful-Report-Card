<?php
header('Access-Control-Allow-Origin: *');
$header[] = "Content-type: text/json";
$header[] = "user-agent: nuoxi4n / Beautiful-Report-Card";

// 初始化Curl会话
$curl = curl_init();

// 请求的网址
curl_setopt($curl, CURLOPT_URL, "https://api.github.com/repos/NuoXianTech/Beautiful-Report-Card/releases/latest");
// 隐藏返回响应信息头
curl_setopt($curl, CURLOPT_HEADER, false);
// 请求标头
curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
// 文件流返回
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

// 检测是否报错
if ($error = curl_error($curl)) {
    // 报错输出
    echo "error: " .$error;
} else {
    // 正常输出
    // header('Content-type: application/json');

    $data = curl_exec($curl);
    $data = json_decode($data, true);

    date_default_timezone_set('PRC');
    $date = strtotime($data['published_at']);
    $date = date("Y-m-d H:i:s", $date);

    $json = array(
        'code' => 200,
        'node_id' => $data['node_id'],
        'latest_version' => $data['tag_name'],
        'publish_page' => $data['html_url'],
        'download' => $data['zipball_url'],
        'update_time' => $date,
        'update_content' => $data['body']
    );

    $data = json_encode($json, JSON_NUMERIC_CHECK | JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    $data = json_decode($data, true);
    //var_dump($data);
}

// 关闭Curl资源，并且释放系统资源
curl_close($curl);
?>
