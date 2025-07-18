<?php
namespace App\Core\Abstract;

abstract class AbstractController {
    protected $communLayout = 'baseLayout';
  

    public function renderIndex($view, $data = []) {
        // var_dump($view); die;
        extract($data); // <-- décommente cette ligne

        ob_start();

        require_once "../templates/" . $view . '.html.php';

        $content = ob_get_clean();

        require_once "../templates/layout/" . $this->communLayout . ".html.php";
    }

    abstract public function index();
    abstract public function show();
    abstract public function create();
    abstract public function store();
    abstract public function edit();
    abstract public function update();
    abstract public function delete();

}