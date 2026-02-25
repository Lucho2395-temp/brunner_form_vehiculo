<?php
/**
 * Created by PhpStorm.
 * User: CesarJose39
 * Date: 03/10/2018
 * Time: 12:21
 */

class Database{
    private static $db;

    public static function getConnection(){
        //Crea la conexion a la base de datos
        try{
            if(empty(self::$db)){
                $pdo = new PDO('mysql:host='._SERVER_DB_.';dbname='._DB_.';charset=utf8',_USER_DB_,_PASSWORD_DB_);
                //En caso de trabajar localmente, descomentar la siguiente linea y comentar la anterior

                //Sirve para indicar al PDO que todo lo que retorne sean objetos
                $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);
                //Sirve para indicar que si encuentra error, los muestre
                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                self::$db = $pdo;
            }
            return self::$db;
            //Si existe algún error en la conexión entra al catch y detiene totalmente todo el sistema y corremos en circulos porque no sabemos que hacer :(
        } catch (Throwable $e){
            $error = array("result" => 'ERROR CRITICO');
            echo json_encode($error);
            exit;
            //$this->log->insert($e->getMessage(), get_class($this).'|'.__FUNCTION__);;
            //echo "<script language=\"javascript\">window.location.href=\"error/error\";</script>";
        }
    }
}