<?php

namespace Core;

use RuntimeException;

/**
 * 视图渲染
 *
 * 把 app/Views 下的 PHP 模板渲染为字符串。模板中通过 e() 转义动态内容以防 XSS。
 *
 * 主题机制：解析模板时优先在「当前主题目录」下查找，找不到再回退到 Views 根目录（共享模板）。
 *   例：主题为 default 时，make('query') 先找 Views/default/query.php，再找 Views/query.php。
 * 这样切换主题只需改 config 的 app.theme，共享的布局/组件可放在 Views 根下复用。
 *
 * 模板内可用 $this->layout('...') 套用布局、$this->partial('...') 引入局部组件。
 */
class View
{
    /** @var string 当前主题 */
    private static $theme = 'default';

    /** @var string|null 模板声明使用的布局 */
    private $layout = null;

    /** @var array 传入模板的数据 */
    private $data = [];

    private function __construct()
    {
    }

    /** 设置当前主题。 */
    public static function setTheme(string $theme): void
    {
        self::$theme = $theme;
    }

    /** 渲染模板为字符串。 */
    public static function make(string $template, array $data = []): string
    {
        $view = new self();
        $view->data = $data;

        $content = $view->renderFile($template, $data);

        // 若模板调用了 layout()，把内容作为 $content 注入布局再渲染一次
        if ($view->layout !== null) {
            $content = $view->renderFile($view->layout, array_merge($data, ['content' => $content]));
        }
        return $content;
    }

    /** 在模板内调用：声明本视图使用的布局。 */
    public function layout(string $name): void
    {
        $this->layout = $name;
    }

    /** 在模板内调用：渲染并输出一个局部组件。 */
    public function partial(string $name, array $data = []): void
    {
        echo $this->renderFile($name, array_merge($this->data, $data));
    }

    /** 解析模板的真实文件路径：先主题目录，后共享目录。 */
    private function resolve(string $template): string
    {
        $rel = ltrim($template, '/');
        $candidates = [
            VIEW_PATH . '/' . self::$theme . '/' . $rel . '.php', // 主题专属
            VIEW_PATH . '/' . $rel . '.php',                      // 共享
        ];
        foreach ($candidates as $file) {
            if (is_file($file)) {
                return $file;
            }
        }
        throw new RuntimeException("视图不存在：{$template}（主题：" . self::$theme . "）");
    }

    /** 在隔离作用域内渲染模板文件并返回其输出。 */
    private function renderFile(string $template, array $data): string
    {
        $file = $this->resolve($template);
        extract($data, EXTR_SKIP);
        ob_start();
        include $file; // 模板内 $this 指向本 View，可用 partial()/layout()
        return (string) ob_get_clean();
    }
}
