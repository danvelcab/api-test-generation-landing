<?php

namespace App\Console\Commands;

use App\Http\Requests\Users\UserStoreRequest;
use Faker\Generator;
use Illuminate\Console\Command;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Illuminate\Routing\Route;
use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Lang;
use League\Flysystem\File;

class TestExtractSpecifications extends Command
{
    const NULL_VALUE = 'null';
    const REQUEST_PATH = '\\Http\\Requests';
    const SPECIFICATIONS_PATH = '/../tests/Controller/specifications.txt';

    public $parameters = [];

    public $router;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:specifications';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(Router $router)
    {
        $this->router = $router;
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $routes = $this->router->getRoutes();
        $exist = is_dir(app_path(). '/../tests/Controller');
        if(!$exist){
            mkdir(app_path() . '/../tests/Controller');
        }
        foreach ($routes->getRoutes() as $route){
            if(strpos($route->uri, 'api') !== false){
                $slices = explode('/', $route->uri);
                $path = '';
                $count = count($slices);
                $exist = is_dir(app_path() . '/../tests/Controller/');
                if(!$exist){
                    mkdir(app_path() . '/../tests/Controller/');
                }
                foreach ($slices as $key => $slice){
                    if($slice != 'api'){
                        if(strpos($slice, '?') === false){
                            $path = $path . '/' . $slice;
                            if($key == $count - 1 || (isset($slices[$key + 1]) && strpos($slices[$key + 1], '?') !== false)){
                                $name = explode('@', $route->action['controller'])[1] . 'Test';
                                $this->extractParameters($route, $path, $name);
                            }
                        }
                    }
                }
            }
        }
        $this->saveParametersFile();
    }

    public function extractParameters(Route $route, $path, $name){
        $exist = file_exists(app_path() . '/../tests/Controller/specifications.txt');
        if(!$exist){
            file_put_contents(app_path() . '/../tests/Controller/specifications.txt','');
        }
        $old_content = file_get_contents(app_path() . '/../tests/Controller/specifications.txt');
        $specifications_content = file_get_contents(app_path() . '/../tests/Controller/specifications.txt');
        $form_parameters = [];

        /**
         * Getting form parameters
         */
        $controller_path = explode('@', $route->getAction()['controller'])[0];
        $pos = strpos($controller_path, 'Controller');
        $controller_name = substr($controller_path, $pos + 12);
        $controller_function = $route->getActionMethod();
        $controller_content = file_get_contents(app_path() . '/Http/Controllers/' . $controller_name . '.php');
        $start_function = strpos($controller_content, $controller_function . '(');
        $controller_content = substr($controller_content, $start_function);
        $end_function = strpos($controller_content, ')');
        $controller_content = substr($controller_content, 0, $end_function);
        $pos_request = strpos($controller_content, 'Request');
        if($pos_request !== false) {
            $start_request = strpos($controller_content, '(') + 1;
            $end_request = $pos_request + 8;
            $form_request_name = substr($controller_content, $start_request, $end_request - $start_request - 1);
            $request_file = $this->searchFile(app_path() . self::REQUEST_PATH, $form_request_name . '.php');
            $request_content = file_get_contents($request_file);
            $start_namespace = strpos($request_content, 'namespace');
            $request_content = substr($request_content, $start_namespace + 10);
            $end_namespace = strpos($request_content, ';');
            $namespace = substr($request_content, 0, $end_namespace);

            $form_request_name = $namespace . '\\' .$form_request_name;
            $request = new $form_request_name;

            $aux_content = null;
            $pos_query_params = strpos($specifications_content, $path . "." . $name . "." . "FORM_PARAMS");
            if($pos_query_params !== false) {
                $aux_content = substr($specifications_content, $pos_query_params + strlen($path . "." . $name . "." . "FORM_PARAMS") + 2);
                $aux_content_end = strpos($aux_content, "##");
                if($aux_content_end === false){
                    $aux_content_end = strlen($aux_content) -1;
                }
                $aux_content = substr($aux_content, 0, $aux_content_end);
            }

            foreach ($request->rules() as $key => $rule){
                $rules = explode('|', $rule);
                array_push($form_parameters, ">" . $key);
                foreach ($rules as $r){
                    array_push($form_parameters, $key . "." . $r);
                }
                if($aux_content != null);
                $form_parameters = $this->getSpecificationsOfParams($aux_content, $form_parameters, $key);
            }
            $form_parameters = $this->addSpecificationsParams($form_parameters, $aux_content);
        }

        /**
         * End getting form parameters
         */


        $form_params_key = $path . '.' .$name . '.FORM_PARAMS';
        $this->parameters[$form_params_key] = $form_parameters;

    }
    public function saveParametersFile(){
        $content = '';
        foreach ($this->parameters as $key => $PARAMETER){
            if(!is_array($PARAMETER)){
                if(count($this->parameters[$PARAMETER . '.URL_PARAMS']) > 0
                    || count($this->parameters[$PARAMETER . '.QUERY_PARAMS']) > 0
                    || count($this->parameters[$PARAMETER . '.FORM_PARAMS']) > 0
                    || count($this->parameters[$PARAMETER . '.MIDDLEWARE_PARAMS']) > 0)
                    $content .= self::addLine("\n## " . $PARAMETER);
            }else{
                if(count($PARAMETER) > 0){
                    $content .= self::addLine('## ' . $key);
                    foreach ($PARAMETER as $key2 => $p){
                        $content .= self::addLine($p . ';');
                    }
                    $content .= self::addLine("\n");
                }
            }
        }
        file_put_contents(app_path() . self::SPECIFICATIONS_PATH , $content);
    }
    private function getSpecificationsOfParams($specification_content, $form_parameters, $param){
        $rules_string_exploded = explode('>', $specification_content);
        foreach ($rules_string_exploded as $parameter){
            $parameter_explode = explode("\n", $parameter);
            $parameter = substr($parameter_explode[0], 0, strlen($parameter_explode[0]) - 1);
            if($parameter === $param){
                foreach ($parameter_explode as $line){
                    if(strpos($line, $parameter . ".") !== false){
                        $clean_param = substr($line, 0, strlen($line) - 1);
                        if(!in_array($clean_param, $form_parameters)){
                            array_push($form_parameters, $clean_param);
                        }
                    }
                }
            }
        }
        return $form_parameters;
    }
    private function addSpecificationsParams($form_parameters, $specification_content){
        $rules_string_exploded = explode('>', $specification_content);
        foreach ($rules_string_exploded as $parameter){
            $parameter_explode = explode("\n", $parameter);
            foreach ($parameter_explode as $p){
                $clean_param = substr($p, 0, strlen($p) - 1);
                if(!empty($clean_param) && strpos($clean_param, '.') === false){
                    $clean_param = ">" . $clean_param;
                }
                if($clean_param !== false
                    && !empty($clean_param)
                    && !in_array($clean_param, $form_parameters)){
                    array_push($form_parameters, $clean_param);
                }
            }
        }
        return $form_parameters;
    }
    private function addLine($text)
    {
        return $text . "\n";
    }

    private function searchFile($dir, $file){   // Funcion Recursiva
        // Autor DeeRme
        // http://deerme.org
        if ( is_dir($dir) )
        {
            // Recorremos Directorio
            $d=opendir($dir);
            while( $archivo = readdir($d) )
            {
                if ( $archivo!="." AND $archivo!=".."  )
                {

                    if ( is_file($dir.'/'.$archivo) )
                    {
                        // Es Archivo
                        if ( $archivo == $file  )
                        {
                            return ($dir.'/'.$archivo);
                        }

                    }

                    if ( is_dir($dir.'/'.$archivo) )
                    {
                        // Es Directorio
                        // Volvemos a llamar
                        $r=$this->searchFile($dir.'/'.$archivo,$file);
                        if ( basename($r) == $file )
                        {
                            return $r;
                        }


                    }





                }

            }

        }
        return FALSE;
    }

}
