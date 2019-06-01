<?php
/**
 * Created by PhpStorm.
 * User: shockvvave
 * Date: 01.06.19
 * Time: 11:49
 */

/**
 * @param $args
 *
 */


const DEBUG_FLAG = true;

//Debug block
if (DEBUG_FLAG) {

    ini_set('display_errors', 'On');
    error_reporting(E_ALL);
    putenv('ENV_MODE=debug');
} else {
    putenv('ENV_MODE=production');
}


/**
 *Validate  wall structure data
 *
 * @param $string
 * @param $size
 * @return array
 */
function breakString($string, $size)
{
    $string = str_replace(array("\r\n", "\r", "\n"), '', $string);

    //Resulting Function Array
    $brokenString = array();

    //Validation of wall structure data
    if ($size != (strlen($string))) {
        echo "\"" . $string . "\"" . 'Не соответствие данных';
        die();
    }

    for ($i = 0; $i < $size; $i++) {
        $brokenString[$i] = $string[$i];
    }

    return $brokenString;

}


/**
 * Parse and validate the source file
 *
 * @param  $dataFile
 * @return array|void
 */
function dataParse( $dataFile)
{
    rewind($dataFile);

    //Resulting Function Array
    $dataMass = array();

    //Parsing the height and width of the wall
    $strMass = explode(' ', fgets($dataFile));

    //Validation of data
    if (sizeof($strMass) != 2) {
        echo 'Не правильный файл(размер стены)';
        die;
    }

    $dataMass['wallSize'] = $strMass;

    //Parsing and validation wall structure data
    for ($j = 0; $j < (int)$dataMass['wallSize'][1]; $j++) {
        $strMass = breakString(fgets($dataFile), (int)$dataMass['wallSize'][0]);
        $dataMass["row$j"] = $strMass;

    }

    //Parsing figure number data
    $numberOfShapeTypes = (int)fgets($dataFile);
    //Validation of the number of types of figures
    if (strlen($numberOfShapeTypes) != 1) {
        echo 'Не правильный файл(кол-во типов фигур)';
        die;
    }


    $dataMass['numberOfShapeTypes'] = $numberOfShapeTypes;

    //Parsing figure parameter data
    for ($j = 0; $j < (int)$dataMass['numberOfShapeTypes']; $j++) {

        $strMass = explode(' ', fgets($dataFile));

        //Validation figure parameter date
        if (sizeof($strMass) != 3) {
            echo 'Не правильный файл(параметры фигуры)';
            die;
        }
        $dataMass["type$j"] = $strMass;

    }


    return $dataMass;
}

/**
 * Initialization of the program
 *
 * @param $args
 */
function init($args)
{
    //Checks the existence of a file
    if (!file_exists($args[1])) {
        echo "Файл не найден";
        die;
    };

    //Open file
    $dataFile = fopen($args[1], "r");

    var_dump(dataParse($dataFile));

}

//Initiate
init($argv);

