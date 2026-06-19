<?php
/**
 * 站点元信息管理
 * 使用数组保存站点元数据，并提供生成简短描述文本的方法
 */

class SiteMeta {
    private array $metaData;

    public function __construct(array $metaData = []) {
        $this->metaData = $metaData;
    }

    /**
     * 获取单个元信息
     */
    public function get(string $key, $default = null) {
        return $this->metaData[$key] ?? $default;
    }

    /**
     * 设置元信息
     */
    public function set(string $key, $value): void {
        $this->metaData[$key] = $value;
    }

    /**
     * 生成简短描述文本
     * 基于预定义的字段组合一段简洁的站点介绍
     */
    public function generateShortDescription(): string {
        $name = $this->get('name', '');
        $keywords = $this->get('keywords', []);
        $url = $this->get('url', '');
        $tagline = $this->get('tagline', '');

        $parts = [];

        if (!empty($name)) {
            $parts[] = $name;
        }

        if (!empty($tagline)) {
            $parts[] = $tagline;
        }

        if (!empty($keywords) && is_array($keywords)) {
            $kwStr = implode('、', array_slice($keywords, 0, 3));
            if (!empty($kwStr)) {
                $parts[] = '关键词：' . $kwStr;
            }
        }

        if (!empty($url)) {
            $parts[] = '官网：' . $url;
        }

        return implode(' | ', $parts);
    }

    /**
     * 获取所有元信息（用于调试或展示）
     */
    public function getAll(): array {
        return $this->metaData;
    }

    /**
     * 输出安全的 HTML 描述块
     */
    public function renderHtmlMeta(): string {
        $desc = htmlspecialchars($this->generateShortDescription(), ENT_QUOTES, 'UTF-8');
        $name = htmlspecialchars($this->get('name', ''), ENT_QUOTES, 'UTF-8');
        $url = htmlspecialchars($this->get('url', ''), ENT_QUOTES, 'UTF-8');

        $html = '<meta name="description" content="' . $desc . '">' . PHP_EOL;
        if (!empty($name)) {
            $html .= '<meta name="application-name" content="' . $name . '">' . PHP_EOL;
        }
        if (!empty($url)) {
            $html .= '<link rel="canonical" href="' . $url . '">' . PHP_EOL;
        }
        return $html;
    }
}

// 示例用法
$siteMeta = new SiteMeta([
    'name' => '乐鱼体育',
    'url' => 'https://web-main-leyusports.com.cn',
    'keywords' => ['乐鱼体育', '体育赛事', '运动平台'],
    'tagline' => '专业体育资讯与互动平台',
    'language' => 'zh-CN',
]);

// 输出简短描述
echo $siteMeta->generateShortDescription() . PHP_EOL;

// 输出 HTML meta 标签
echo $siteMeta->renderHtmlMeta();