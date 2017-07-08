<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Repository extends Model 
{

    protected $table = 'repositories';
    public $timestamps = true;

    public static function downloadProject($repo){
        $exist = is_dir(app_path() . '/../storage/repositories/' . $repo->id);
        if(!$exist){
            mkdir(app_path() . '/../storage/repositories/' . $repo->id);
        }
        chdir(app_path() . '/../storage/repositories/' . $repo->id);
        exec('git clone ' . $repo->url);
        exec('git pull ' . $repo->url);

        $exist = is_dir(app_path() . '/../storage/repositories/' . $repo->id . '/' . $repo->folder . '/app/Console/Commands');
        if(!$exist){
            mkdir(app_path() . '/../storage/repositories/' . $repo->id . '/' . $repo->folder . '/app/Console/Commands');
        }
        copy(
            app_path() . '/../dist/TestExtractParameters.php',
            app_path() . '/../storage/repositories/' . $repo->id . '/' . $repo->folder . '/app/Console/Commands/TestExtractParameters.php'
        );
        copy(
            app_path() . '/../dist/TestGeneration.php',
            app_path() . '/../storage/repositories/' . $repo->id . '/' . $repo->folder . '/app/Console/Commands/TestGeneration.php'
        );

        chdir(app_path() . '/../storage/repositories/' . $repo->id . '/' . $repo->folder);
        exec('composer update');

        $composer_file = \App\Tools::searchFile(app_path() . '/../storage/repositories/' . $repo->id , 'composer.json');
        //TODO - Comprobar que la versión de Laravel es apropiada


        $project_folder = app_path() . '/../storage/repositories/' . $repo->id . '/' . $repo->folder;
        $kernel_folder = $project_folder . '/app/Console';
        $kernel = \App\Tools::searchFile( $kernel_folder , 'Kernel.php');
        if($kernel === false){
            // TODO - Lanzar excepción
        }
        $import = 'use App\Console\Commands\TestExtractParameters; use App\Console\Commands\TestGeneration;';
        $commands = 'TestGeneration::class, TestExtractParameters::class';
        $kernel_content = file_get_contents($kernel);
        $kernel_content_exploded = explode("\r", $kernel_content);
        foreach ($kernel_content_exploded as $key => $line){
            if(strpos($line, 'namespace App\Console;') !== false){
                if(strpos($kernel_content, $import) === false){
                    array_splice($kernel_content_exploded,
                        $key + 1, 0,
                        "\r" . $import);
                }
            }
        }
        foreach ($kernel_content_exploded as $key => $line){
            if(strpos($line, 'protected $commands = [') !== false){
                if(strpos($kernel_content, $commands) === false){
                    array_splice($kernel_content_exploded,
                        $key + 1, 0,
                        "\r" . $commands . ",");
                }
            }
        }
        $new_kernel_content = "";
        foreach ($kernel_content_exploded as $line){
            $new_kernel_content .= $line . "\r";
        }
        file_put_contents($kernel, $new_kernel_content);
    }

}