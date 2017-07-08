<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tools extends Model
{

    public static function searchFile($dir, $file){   // Funcion Recursiva
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
                        $r=self::searchFile($dir.'/'.$archivo,$file);
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