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

class TestExtractParameters extends Command
{
    const NULL_VALUE = 'null';
    const REQUEST_PATH = '\\Http\\Requests';
    const PARAMETERS_PATH = '/../tests/Controller/parameters.txt';

    public $parameters = [];

    public $router;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:extract';

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
        $exist = file_exists(app_path() . '/../tests/Controller/parameters.txt');
        if(!$exist){
            file_put_contents(app_path() . '/../tests/Controller/parameters.txt','');
        }
        $old_content = file_get_contents(app_path() . '/../tests/Controller/parameters.txt');
        $specifications_content = file_get_contents(app_path() . '/../tests/Controller/specifications.txt');
        $url_parameters = [];
        $query_parameters = [];
        $form_parameters = [];
        $middleware_parameters = [];
        $uri = $route->uri();
        while(strpos($uri, '{') !== false){
            $start = strpos($uri,'{');
            $end = strpos($uri, '}');
            $key = substr($uri, $start, $end - $start + 1);
            if(strpos($key, '?')){
                $value = self::getValue($old_content, $path, $name, $key, 'QUERY_PARAMS');
                $query_parameters[$key] = $value;
            }else{
                $value = self::getValue($old_content, $path, $name, $key, 'URL_PARAMS');
                $url_parameters[$key] = $value;
            }
            $uri = substr($uri, $end + 2);
        }

        /**
         * Getting form parameters
         */
        $pos_query_params = strpos($specifications_content, $path . "." . $name . "." . "FORM_PARAMS");
        if($pos_query_params !== false) {
            $aux_content = substr($specifications_content, $pos_query_params + strlen($path . "." . $name . "." . "FORM_PARAMS") + 2);
            $aux_content_end = strpos($aux_content, "##");
            if($aux_content_end === false){
                $aux_content_end = strlen($aux_content) -1;
            }
            $aux_content = substr($aux_content, 0, $aux_content_end);
            $parameters = $this->getParametersWithRules($aux_content);
            foreach ($parameters as $key => $rules){
                $form_parameters[$key] = self::getValue($old_content, $path, $name, $key, 'FORM_PARAMS');
                foreach ($rules as $r){
                    if($r !== 'required'){
                        $form_parameters[$key . ".!" .$r] = self::getValue($old_content, $path, $name, $key, 'FORM_PARAMS', '!' . $r, $rules);
                    }
                }
            }
        }

        /**
         * End getting form parameters
         */

        /**
         * Getting middleware jwt.auth parameters
         */

        $middlewares = $route->getAction()['middleware'];
        if((is_array($middlewares) && in_array('jwt.auth', $middlewares))
            || $middlewares === 'jwt.auth'){
            $middleware_parameters['authenticated_user_id'] = self::getValue($old_content, $path, $name, 'authenticated_user_id', 'MIDDLEWARE_PARAMS');
            $middleware_parameters['auth_type'] = 'jwt';
        }else if((is_array($middlewares) && in_array('auth.basic', $middlewares))
            || $middlewares === 'auth.basic'){
            $middleware_parameters['authenticated_user_id'] = self::getValue($old_content, $path, $name, 'authenticated_user_id', 'MIDDLEWARE_PARAMS');
            $middleware_parameters['auth_type'] = 'basic';
        }
        /**
         * End getting middleware jwt.auth parameters
         */

        $this->parameters[$path . '.' .$name . '.TITLE'] = $path . '.' .$name;
        $url_params_key = $path . '.' .$name . '.URL_PARAMS';
        $this->parameters[$url_params_key] = $url_parameters;
        $query_params_key = $path . '.' .$name . '.QUERY_PARAMS';
        $this->parameters[$query_params_key] = $query_parameters;
        $form_params_key = $path . '.' .$name . '.FORM_PARAMS';
        $this->parameters[$form_params_key] = $form_parameters;
        $middleware_params_key = $path . '.' .$name . '.MIDDLEWARE_PARAMS';
        $this->parameters[$middleware_params_key] = $middleware_parameters;

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
                        $content .= self::addLine($key2 . '=' . $p . ';');
                    }
                }
            }
        }
        file_put_contents(app_path() . self::PARAMETERS_PATH , $content);
    }
    private function getParametersWithRules($parameters_string){
        $parameters = [];
        $rules_string_exploded = explode('>', $parameters_string);
        foreach ($rules_string_exploded as $parameter){
            $rules = [];
            $parameter_explode = explode("\n", $parameter);
            $parameter = substr($parameter_explode[0], 0, strlen($parameter_explode[0]) - 1);
            foreach ($parameter_explode as $line){
                if(strpos($line, $parameter . ".") !== false){
                    $aux = explode(".", $line);
                    array_push($rules, substr($aux[1], 0, strlen($aux[1]) - 1));
                }
            }
            $parameters[$parameter] = $rules;
        }
        return $parameters;
    }
    private function addLine($text)
    {
        return $text . "\n";
    }

    private static function getValue($old_content, $path, $name, $key, $type, $rule = null, $rules = null){
        $value = 'null';
        $needle = $path . '.' . $name . '.' .  $type;
        $start = strpos($old_content, $needle);
        $old_content = substr($old_content, $start + strlen($needle));
        $end = strpos($old_content, '##');
        if($rule == null){
            $pos = strpos($old_content, $key);
        }else{
            $pos = strpos($old_content, $key . '.' . $rule);
        }
        if($pos != null && ($end == null || $pos < $end)){
            $old_content = substr($old_content, $pos);
            $equal_pos = strpos($old_content, '=') + 1;
            $end_pos = strpos($old_content, ';');
            $value = substr($old_content, $equal_pos, $end_pos - $equal_pos);
        }
        if($value === 'null' && $rule != null && $rules != null){
            $value = self::getFakerValue($value, $rule, $rules);
        }
        return $value;
    }

    private static function getFakerValue($value, $rule, $rules){
        if($rule === '!string'){
            $value = random_int(1,10);
        }else if($rule === '!numeric'){
            $value = '"' . str_random(10) . '"';
        }else if($rule === '!email'){
            $value = '"' . str_random(10) . '"';
        }else if(strpos($rule, 'max') != false){
            $rule_exploded = explode(':', $rule);
            $limit = intval($rule_exploded[1]) + 1;
            if(in_array('string', $rules)){
                $value = '"' . str_random($limit) . '"';
            }else if(in_array('numeric', $rules)){
                $value = random_int($limit, $limit + 1);
            }
        }else if(strpos($rule, 'min') != false){
            $rule_exploded = explode(':', $rule);
            $limit = intval($rule_exploded[1]) - 1;
            if(in_array('string', $rules)){
                if($limit > 0){
                    $value = '"' . str_random($limit) . '"';
                }
            }else if(in_array('numeric', $rules)){
                $value = random_int($limit - 1, $limit);
            }
        }
        return $value;
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
