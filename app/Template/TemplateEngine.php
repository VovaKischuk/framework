<?php

namespace Framework\Template;

class TemplateEngine
{
    private string $templateDir;

    public function __construct(string $templateDir)
    {
        $this->templateDir = rtrim($templateDir, '/');
    }

    public function render(string $template, array $variables = []): string
    {
        $templatePath = $this->templateDir . '/' . $template . '.php';

        if (!file_exists($templatePath)) {
            throw new \RuntimeException("Template not found: $templatePath");
        }

        extract($variables, EXTR_SKIP);
        ob_start();
        include $templatePath;
        return ob_get_clean();
    }
}