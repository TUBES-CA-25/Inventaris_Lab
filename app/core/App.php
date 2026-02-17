<?php

class App
{
    protected $controller = 'Beranda';
    protected $method = 'index';
    protected $params = [];

    public function __construct()
    {
        // --- SYSTEM-WIDE AUTOMATION (Daily Email Check) ---
        // Runs on EVERY page load (Lazy Cron), but restricted to 1x/day by file lock.
        if (file_exists('../app/models/Notification_model.php')) {
            require_once '../app/models/Notification_model.php';
            $notify = new Notification_model();
            $notify->checkAndRunDaily();
        }
        // --------------------------------------------------

        $url = $this->parseURL();

        //controller
        if (!empty($url) && file_exists('../app/controllers/' . $url[0] . '.php')) {
            $this->controller = $url[0];
            unset($url[0]);
        } elseif (!empty($url)) {
            // Controller not found - trigger 404
            $_SESSION['has_error'] = true;
            $_SESSION['error_type'] = '404';
            $_SESSION['error_message'] = 'Controller "' . $url[0] . '" tidak ditemukan';
            header("Location: " . BASEURL . "ErrorPage/notFound");
            exit;
        }

        require_once '../app/controllers/' . $this->controller . '.php';
        $this->controller = new $this->controller;

        //method
        if (isset($url[1])) {
            if (method_exists($this->controller, $url[1])) {
                $this->method = $url[1];
                unset($url[1]);
            } else {
                // Method not found - trigger 404
                $_SESSION['has_error'] = true;
                $_SESSION['error_type'] = '404';
                $_SESSION['error_message'] = 'Method "' . $url[1] . '" tidak ditemukan';
                header("Location: " . BASEURL . "ErrorPage/notFound");
                exit;
            }
        }

        //params
        if (!empty($url)) {
            $this->params = array_values($url);
        }

        //jalankan controller dan method 
        call_user_func_array([$this->controller, $this->method], $this->params);
    }

    public function parseURL()
    {
        if (isset($_GET['url'])) {
            $url = rtrim($_GET['url'], '/');
            $url = filter_var($url, FILTER_SANITIZE_URL);
            $url = explode('/', $url);

            return $url;
        }
    }
}