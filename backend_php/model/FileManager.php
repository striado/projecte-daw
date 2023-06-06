<?php

class FileManager {

    public static function JsonConvert($fitxer) {
        
        $dades = file_get_contents($fitxer);
        $rows = preg_split("/\r\n|\n|\r/", $dades);
        $dades_alumnes = array();
        foreach ($rows as $fila) {
            $camps = preg_split("/\t+/", $fila);
            if ($camps[0] === 'Usuari') {
                continue;
            } else {
                array_push($dades_alumnes, array(
                    'usuari' => $camps[0],
                    'nomCognoms' => $camps[1],
                    'etapa' => $camps[2],
                    'curs' => $camps[3],
                    'grup' => $camps[4] ?? '',
                ));
            }            
        }
        $dadesJson = json_encode($dades_alumnes);
        file_put_contents('../../config/alumnes/dades_alumnes.json', $dadesJson);
    }

}

?>