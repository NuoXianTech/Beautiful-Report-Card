<?php

namespace App\Services;

use Core\App;

/**
 * 检查更新服务
 *
 * 调用 GitHub Releases API 获取最新版本，结果缓存到 storage/cache（默认 1 小时），
 * 避免像旧版那样每次打开后台都请求一次 API（未认证有 60 次/小时 的限速）。
 */
class UpdateService
{
    private const API = 'https://api.github.com/repos/NuoXianTech/Beautiful-Report-Card/releases/latest';
    private const TTL = 3600; // 缓存秒数

    /** 返回最新版本信息数组，获取失败返回 null。 */
    public function latest(): ?array
    {
        $cached = $this->readCache();
        if ($cached !== null) {
            return $cached;
        }

        $raw = $this->fetch();
        if ($raw === null) {
            return null;
        }

        $data = json_decode($raw, true);
        if (!is_array($data) || empty($data['tag_name'])) {
            return null;
        }

        $info = [
            'current_version' => App::VERSION,
            'latest_version'  => $data['tag_name'],
            'publish_page'    => $data['html_url'] ?? '',
            'update_time'     => !empty($data['published_at'])
                ? date('Y-m-d H:i:s', strtotime($data['published_at']))
                : '',
            'update_content'  => $data['body'] ?? '',
        ];
        $this->writeCache($info);
        return $info;
    }

    private function cacheFile(): string
    {
        return STORAGE_PATH . '/cache/latest_release.json';
    }

    private function readCache(): ?array
    {
        $file = $this->cacheFile();
        if (!is_file($file) || (time() - filemtime($file)) > self::TTL) {
            return null;
        }
        $data = json_decode((string) file_get_contents($file), true);
        return is_array($data) ? $data : null;
    }

    private function writeCache(array $info): void
    {
        @file_put_contents($this->cacheFile(), json_encode($info, JSON_UNESCAPED_UNICODE));
    }

    /** 实际请求 API，返回响应体或 null。优先 curl，回退 file_get_contents。 */
    private function fetch(): ?string
    {
        if (function_exists('curl_init')) {
            $ch = curl_init(self::API);
            curl_setopt_array($ch, [
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_HTTPHEADER     => ['User-Agent: Beautiful-Report-Card'],
                CURLOPT_TIMEOUT        => 5,
            ]);
            $res  = curl_exec($ch);
            $code = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);
            return ($res !== false && $code === 200) ? (string) $res : null;
        }

        $ctx = stream_context_create([
            'http' => ['header' => "User-Agent: Beautiful-Report-Card\r\n", 'timeout' => 5],
        ]);
        $res = @file_get_contents(self::API, false, $ctx);
        return $res === false ? null : $res;
    }
}
