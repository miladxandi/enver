<?php

namespace Milad;

class Enver
{
    public static $EnvPath;
    public function updateEnv(array $replace_array) {
        $array_going_to_modify  = $replace_array;

        if (count($array_going_to_modify) == 0) {
            return false;
        }

        $env_file = self::$EnvPath;


        $content = file_get_contents($env_file);
        preg_match_all('/^\s*([\w.-]+)\s*=\s*(.*)\s*$/m', $content, $matches, PREG_SET_ORDER);

        $env_content = [];

        foreach ($matches as $match) {
            $variableName = $match[1];
            $variableValue = $match[2];
            $env_content[$variableName] = $variableValue;
        }
        foreach ($array_going_to_modify as $modify_key => $modify_value) {
            $env_content[$modify_key] = $this->setEnvValue($modify_key, $modify_value);
        }


        $string_content = "";
        foreach ($env_content as $key => $item) {
            $line = $key . "=" . $item;
            $string_content .= $line . "\n\r";
        }

        sleep(2);

        file_put_contents($env_file, $string_content);
    }
    public function getEnv(string $key = null) {
        $env_file = self::$EnvPath;


        $content = file_get_contents($env_file);
        preg_match_all('/^\s*([\w.-]+)\s*=\s*(.*)\s*$/m', $content, $matches, PREG_SET_ORDER);

        $env_content = [];
        foreach ($matches as $match) {
            $variableName = $match[1];
            $variableValue = str_replace(['"', '\''], '', $match[2]); // Remove double and single quotes
            $env_content[$variableName] = $variableValue;
        }
        if ($key==null){
            return $env_content;
        }else{
            return $env_content[$key] ?? null;
        }
    }

    public function setEnvValue($key,$value) {
        if($key == "APP_KEY") {
            return $value;
        }
        return '"'.$value.'"';
    }

}