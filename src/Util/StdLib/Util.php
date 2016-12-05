<?php

/**
 * UTIL - Classes úteis ao UACL
 * @link      https://bitbucket.org/jotjunior/util para o repositório da aplicação
 * @copyright Copyright (c) 2014 Jot! (http://jot.com.br)
 * @author    João G. Zanon Jr. <jot@jot.com.br>
 */

namespace Util\StdLib;

class Util
{

    /**
     * Recebe um objeto e o converte em um array multidimensional
     * @param stdClass $object
     * @return array
     */
    static public function objectToArray($object)
    {
        if (is_object($object)) {
            $object = get_object_vars($object);
        }
        if (is_array($object)) {
            return array_map(__METHOD__, $object);
        } else {
            return $object;
        }
    }

    /**
     * Converte um array de multi nível em uma única string
     * @param array $array
     * @param string $string
     * @param string $separator
     * @return type
     */
    static public function arrayToString($array, $string = null, $separator = '<br />')
    {
        if (!is_array($array)) {
            exit($array);
        }
        foreach ($array as $key => $item) {
            if (is_array($item)) {
                $string .= $key . " => " . self::arrayToString($item, $string, $separator);
            } else {
                $string .= $key . " => " . $item . $separator;
            }
        }
        return $string;
    }

    /**
     * Transforma uma rray simples em uma coleção de objetos
     * @param array $array
     * @param object $object
     * @return array
     * @throws \InvalidArgumentException
     */
    static public function createCollection(array $array, $object)
    {

        if (!is_array($array)) {
            throw new \InvalidArgumentException("\$array deve ser no formato array");
        }

        if (!class_exists($object, true)) {
            throw new \InvalidArgumentException("\$object deve ser uma classe válida");
        }

        $collection = array();
        foreach ($array as $item) {
            $collection[] = new $object($item);
        }

        return $collection;
    }

    /**
     * Identifica se o valor de uma variável foi serializado
     * @param string $data
     * @return boolean
     */
    static public function isSerialized($data)
    {
        if (!is_string($data)) {
            return false;
        }
        $data = trim($data);
        if ('N;' == $data) {
            return true;
        }
        $badions = null;
        if (!preg_match('/^([adObis]):/', $data, $badions)) {
            return false;
        }
        switch ($badions[1]) {
            case 'a' :
            case 'O' :
            case 's' :
                if (preg_match("/^{$badions[1]}:[0-9]+:.*[;}]\$/s", $data)) {
                    return true;
                }
                break;
            case 'b' :
            case 'i' :
            case 'd' :
                if (preg_match("/^{$badions[1]}:[0-9.E-]+;\$/", $data)) {
                    return true;
                }
                break;
        }
        return false;
    }

    /**
     * Retorna um array a partir de um arquivo CSV
     * @param string $file
     * @return array
     * @throws \InvalidArgumentException
     */
    static public function parseCsv($file)
    {
        if (!is_file($file)) {
            throw new \InvalidArgumentException("$file não é um arquivo válido");
        }

        $result = array();

        $handle = fopen($file, 'r');

        while (false !== ($data = fgetcsv($handle, 2048, ','))) {
            array_push($result, $data);
        }

        return $result;
    }

    /**
     *
     * @param array $collection
     * @param string $key
     * @param string $value
     * @return array
     * @throws \InvalidArgumentException
     */
    static public function fetchPairs($collection, $key, $value)
    {

        /**
         * Testa se a coleção enviada é um array
         */
        if (!is_array($collection)) {
            throw new \InvalidArgumentException('$collection deve ser um array');
        }

        /**
         * Testa se o primeiro índice da coleção é um objeto
         */
        if (!is_object($collection[0])) {
            throw new \InvalidArgumentException('$collection deve ser uma coleção de objetos');
        }

        /**
         * Cria os métodos básicos para key e value e os testa
         */
        $keyMethod = 'get' . ucfirst($key);
        $valueMethod = 'get' . ucfirst($value);

        if (!method_exists($collection[0], $keyMethod) || !method_exists($collection[0], $keyMethod)) {
            throw new \InvalidArgumentException('$key e $value devem ser métodos válidos do objeto');
        }

        /**
         * Retorna um array associativo com os resultados
         */
        $result = array();
        foreach ($collection as $item) {
            $result[$item->$keyMethod()] = $item->$valueMethod();
        }

        return $result;
    }

    /**
     * Ordena um array associativo pela chave
     * @param array $arr
     * @param string $col
     * @param int $dir
     */
    public static function arraySortByColumn(array &$arr, $col, $dir = SORT_ASC)
    {
        $sort_col = array();
        foreach ($arr as $key => $row) {
            $sort_col[$key] = $row[$col];
        }

        array_multisort($sort_col, $dir, $arr);
    }

    public static function isJson($string)
    {
        \json_decode($string);
        return (json_last_error() == JSON_ERROR_NONE);
    }


    /**
     * @param array $result
     * @param string $format
     * @return string
     * @throws \PHPExcel_Exception
     * @throws \PHPExcel_Reader_Exception
     */
    public static function exportExcel($result, $format = 'xlsx', $type = 1)
    {

        // abrindo uma instancia do excel
        $objPHPExcel = new \PHPExcel();

        $objPHPExcel->getProperties()->setCreator("Inc.realty")
            ->setLastModifiedBy("Inc.realty")
            ->setTitle("Exportação de dados")
            ->setSubject("Lista de dados exportados")
            ->setDescription("Gerado eletronicamente pela API da Inc.realty | dev by Jot! (jot.com.br)")
            ->setKeywords("items, export, excel")
            ->setCategory("excel");

        // gerando o cabeçalho
        $headers = \Crm\Entity\Lead::excelHeader($type);

        foreach ($headers as $column => $label) {
            $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue($column, $label);
        }

        // injetando os dados
        $line = 2;
        foreach ($result as $item) {
            foreach ($item->getExcelData($line, $type) as $column => $data) {
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue($column, $data);
            }
            $line++;
        }

        // definindo o tipo de saída
        switch ($format) {
            case 'xlsx' :
            default :
                $version = 'Excel2007';
                break;
            case 'xls' :
                $version = 'Excel5';
                break;
            case 'csv' :
                $version = 'csv';
                break;
        }

        // definindo o nome do arquivo
        $fileName = 'public/uploads/export/' . uniqid('inc.') . '.' . $format;

        // escrevendo no arquivo
        $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, $version);
        $objWriter->save($fileName);

        return \str_replace('public', '', $fileName);
    }

    static public function slugify($text)
    {
        $text = self::normalize($text);

        // replace non letter or digits by -
        $text = preg_replace('~[^\\pL\d]+~u', '-', $text);

        // trim
        $text = trim($text, '-');

        // transliterate
        $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);

        // lowercase
        $text = strtolower($text);

        // remove unwanted characters
        $text = preg_replace('~[^-\w]+~', '', $text);

        if (empty($text)) {
            return 'n-a';
        }

        return $text;
    }

    static public function normalize($string)
    {
        $table = array(
            'Š' => 'S', 'š' => 's', 'Đ' => 'Dj', 'đ' => 'dj', 'Ž' => 'Z', 'ž' => 'z', 'Č' => 'C', 'č' => 'c', 'Ć' => 'C', 'ć' => 'c',
            'À' => 'A', 'Á' => 'A', 'Â' => 'A', 'Ã' => 'A', 'Ä' => 'A', 'Å' => 'A', 'Æ' => 'A', 'Ç' => 'C', 'È' => 'E', 'É' => 'E',
            'Ê' => 'E', 'Ë' => 'E', 'Ì' => 'I', 'Í' => 'I', 'Î' => 'I', 'Ï' => 'I', 'Ñ' => 'N', 'Ò' => 'O', 'Ó' => 'O', 'Ô' => 'O',
            'Õ' => 'O', 'Ö' => 'O', 'Ø' => 'O', 'Ù' => 'U', 'Ú' => 'U', 'Û' => 'U', 'Ü' => 'U', 'Ý' => 'Y', 'Þ' => 'B', 'ß' => 'Ss',
            'à' => 'a', 'á' => 'a', 'â' => 'a', 'ã' => 'a', 'ä' => 'a', 'å' => 'a', 'æ' => 'a', 'ç' => 'c', 'è' => 'e', 'é' => 'e',
            'ê' => 'e', 'ë' => 'e', 'ì' => 'i', 'í' => 'i', 'î' => 'i', 'ï' => 'i', 'ð' => 'o', 'ñ' => 'n', 'ò' => 'o', 'ó' => 'o',
            'ô' => 'o', 'õ' => 'o', 'ö' => 'o', 'ø' => 'o', 'ù' => 'u', 'ú' => 'u', 'û' => 'u', 'ý' => 'y', 'ý' => 'y', 'þ' => 'b',
            'ÿ' => 'y', 'Ŕ' => 'R', 'ŕ' => 'r',
        );

        return strtr($string, $table);
    }

}
