<?php

class CHttp
{
    protected bool $XSS = true;

    public function onXSS(): void
    {
        $this->XSS = true;
    }

    public function offXSS(): void
    {
        $this->XSS = false;
    }

    /**
     * Get request type (GET, POST, HEAD, etc.)
     */
    public function getType(): string
    {
        return strtoupper($_SERVER['REQUEST_METHOD'] ?? 'GET');
    }

    /**
     * Check if request is POST
     */
    public function isPost(): bool
    {
        return isset($_SERVER['REQUEST_METHOD']) && strcasecmp($_SERVER['REQUEST_METHOD'], 'POST') === 0;
    }

    /**
     * Check if request is AJAX
     */
    public function isAjax(): bool
    {
        return ($_SERVER['HTTP_X_REQUESTED_WITH'] ?? '') === 'XMLHttpRequest';
    }

    /**
     * Get request param (GET has higher priority over POST)
     */
    public function getParam(string $name, mixed $default = null): mixed
    {
        $param = $_GET[$name] ?? $_POST[$name] ?? $default;

        if ($this->XSS && is_string($param)) {
            $param = strip_tags($param);
        }
        return $param;
    }

    /**
     * Get GET param
     */
    public function getQuery(string $name, mixed $default = null): mixed
    {
        return $_GET[$name] ?? $default;
    }

    /**
     * Get POST param
     */
    public function getPost(string $name, mixed $default = null): mixed
    {
        return $_POST[$name] ?? $default;
    }

    /**
     * Get raw query string
     */
    public function getQueryString(): string
    {
        return $_SERVER['QUERY_STRING'] ?? '';
    }

    /**
     * Redirect to given URL
     */
    public function redirect(?string $url, bool $terminate = true, int $statusCode = 302): void
    {
        $url = trim($url ?? '');

        if ($url === '') {
            // Agar URL missing ho → fallback home
            $url = $this->getHost() . '/';
        }

        // Handle relative URLs like "/dashboard"
        if (str_starts_with($url, '/') && !str_starts_with($url, '//')) {
            $url = $this->getHost() . $url;
        }

        header('Location: ' . $url, true, $statusCode);

        if ($terminate) {
            exit();
        }
    }

    /**
     * Get server software
     */
    public function serverSoftware(): string
    {
        return $_SERVER['SERVER_SOFTWARE'] ?? 'unknown';
    }

    /**
     * Get host URL (scheme + domain + port)
     */
    private function getHost(): string
    {
        $scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? "https" : "http";
        $host   = $_SERVER['HTTP_HOST'] ?? ($_SERVER['SERVER_NAME'] ?? 'localhost');
        return $scheme . "://" . $host;
    }
}
