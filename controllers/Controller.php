<?php

abstract class Controller
{
    /**
     * Render a view with optional data and layout
     *
     * @param string $file   View file path relative to /views (e.g. 'tasks/index')
     * @param array  $data   Associative array of data passed to the view
     * @param string|null $layout Optional layout file name (e.g. 'app')
     */
    public function view(string $file, array $data = [],  $layout = null): void
    {
        extract($data);

        $viewPath = __DIR__ . '/../views/' . $file . '.php';

        if (!file_exists($viewPath)) {
            http_response_code(500);
            echo "🚫 View '$file' not found at: $viewPath";
            return;
        }

        // Start output buffering for content
        ob_start();
        include $viewPath;
        $content = ob_get_clean();

        // If layout is provided, include it and pass $content
        if ($layout!=null) {
            $layoutPath = __DIR__ . '/../views/layouts/' . $layout . '.php';
            if (file_exists($layoutPath)) {
                include $layoutPath;
            } else {
                http_response_code(500);
                echo "🚫 Layout '$layout' not found at: $layoutPath";
            }
        } else {
            echo $content;
        }
    }

    /**
     * Redirect to a given path
     *
     * @param string $path Relative or absolute URL path (e.g. '/create')
     */
    protected function redirect(string $path): void
    {
        if (!str_starts_with($path, '/')) {
            $path = '/todoapp/' . $path;
        }

        header("Location: $path");
        exit;
    }
}
