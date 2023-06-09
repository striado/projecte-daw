<?php

class LlistaUsuaris {

    public static function crearUsuarisDelFitxer($dades) {
        foreach ($dades as $usuari) {
            $persona = new Usuari($usuari->nomCognoms, $usuari->usuari, $usuari->etapa, $usuari->curs, $usuari->grup);
            if (Usuari::exists($persona->getUsuari())) {
                $persona->update();
            } else {
                $persona->save();
            }
        }
    }

    public static function getAll(){
        $llistaUsuaris = array();

        $query = "SELECT * FROM persones;";

        Connection::connect();
        $stmt = Connection::execute($query);
        Connection::close();

        $num = $stmt->rowCount();

        if ($num > 0) {
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
                extract($row);

                $usuari = new Usuari($row['nom_cognoms'], $row['usuari'], $row['etapa'], $row['curs'], $row['grup'], $row['id']);
                array_push($llistaUsuaris, $usuari);
            }
        }

        return $llistaUsuaris;
    }

    public static function buscarPersones($criteri, $pagina, $qtPersones){
        $primer = ($pagina > 1) ? ($pagina - 1) * $qtPersones : 0;
        $llistaUsuaris = array();

        $query = "SELECT * FROM persones WHERE nom_cognoms LIKE :criteri OR usuari LIKE :criteri OR categoria LIKE :criteri LIMIT :primer, :num;";

        
        Connection::connect();
        $stmt = Connection::prepare($query);

        $params = array(
            ":criteri" => "%$criteri%",
        );
        Connection::bind_str_params($stmt, $params);

        $params = array(
            ':primer' => $primer,
            ':num' => $qtPersones
        );
        Connection::bind_int_params($stmt, $params);
        $stmt = Connection::rawExecute($stmt);
        Connection::close();

        $num = $stmt->rowCount();
        if ($num > 0) {
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
                extract($row);

                $usuari = new Usuari($row['nom_cognoms'], $row['usuari'], $row['etapa'], $row['curs'], $row['grup'], $row['id']);
                array_push($llistaUsuaris, $usuari);
            }
        }

        return $llistaUsuaris;
    }

    public static function numPagines($numPerPagina, $criteri){
        
        if ($criteri != '' && $criteri != null) {
            $query = "SELECT COUNT(*) FROM persones WHERE nom_cognoms LIKE :criteri OR usuari LIKE :criteri OR categoria LIKE :criteri;";
            $params = array(
                ":criteri" => "%$criteri%",
            );

            Connection::connect();
            $stmt = Connection::execute($query, $params);
            
        } else {
            $params = array();
            $query = "SELECT COUNT(*) FROM persones;";
            
            Connection::connect();
            $stmt = Connection::execute($query, $params);
            
        }
        
        Connection::close();
        
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($row) {
            $count = intval($row['COUNT(*)']);
            $numPagines = ($count % $numPerPagina > 0) ? floor($count / $numPerPagina + 1) : floor($count / $numPerPagina);
            return $numPagines;
        } else {
            return false;
        }
    }

    public static function getPersones($persones, $pagina){
        $primer = ($pagina > 1) ? ($pagina - 1) * $persones : 0;
        $llistaUsuaris = array();

        $query = "SELECT * FROM persones LIMIT :primer, :num;";

        $params = array(
            ':primer' => $primer,
            ':num' => $persones
        );

        Connection::connect();
        $stmt = Connection::execute_int_params($query, $params);
        Connection::close();

        $num = $stmt->rowCount();

        if ($num > 0) {
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
                extract($row);

                $usuari = new Usuari($row['nom_cognoms'], $row['usuari'], $row['etapa'], $row['curs'], $row['grup'], $row['id']);
                array_push($llistaUsuaris, $usuari);
            }
        }

        return $llistaUsuaris;
    }

    public static function createPersona($persona){
        $persona = new Usuari($persona->nom_cognoms, $persona->usuari, $persona->etapa, $persona->curs, $persona->grup);

        return $persona->save();
    }

    public static function getSingle($id){
        $query = "SELECT * FROM persones WHERE id = :id;";
        $params = array(
            ":id" => $id,
        );

        Connection::connect();
        $stmt = Connection::execute($query, $params);
        Connection::close();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($row) {
            extract($row);
            $usuari = new Usuari($row['nom_cognoms'], $row['usuari'], $row['etapa'], $row['curs'], $row['grup'], $row['id']);
            return $usuari;
        } else {
            return false;
        }
    }

    public static function updatePersona($persona){
        $personaOG = self::getSingle($persona->id);

        $nom_cognoms = $persona->nom_cognoms ?? $personaOG->getNomCognoms();
        $usuari = $persona->usuari ?? $personaOG->getUsuari();
        $etapa = $persona->etapa ?? $personaOG->getEtapa();
        $curs = $persona->curs ?? $personaOG->getCurs();
        $grup = $persona->grup ?? $personaOG->getGrup();

        $newPersona = new Usuari($nom_cognoms, $usuari, $etapa, $curs, $grup, $persona->id);

        return $newPersona->update();
    }

    public static function deletePersona($persona){
        $personaDel = new Usuari($persona->nom_cognoms, $persona->usuari,$persona->etapa, $persona->curs, $persona->grup, $persona->id);
        if (Usuari::exists($personaDel->getUsuari())) {
            return $personaDel->delete();
        }
    }

    public static function addEspaiPersona($id_espai, $id_persona){
        $query = "INSERT INTO espai_persona (id, id_espai, id_persona) 
                    VALUES (null, :id_espai, :id_persona);";

        $params = array(
            ':id_espai' => $id_espai,
            ':id_persona' => $id_persona
        );

        Connection::connect();
        $stmt = Connection::execute($query, $params);
        Connection::close();

        if ($stmt) {
            return true;
        } else {
            return false;
        }
    }

    public static function addPersonaDispositiu($id_persona, $id_dispositiu){
        
        $query = "INSERT INTO persona_dispositiu (id, id_persona, id_dispositiu) 
                    VALUES (null, :id_persona, :id_dispositiu);";

        $params = array(
            ':id_persona' => $id_persona,
            ':id_dispositiu' => $id_dispositiu
        );

        Connection::connect();
        $stmt = Connection::execute($query, $params);
        Connection::close();

        if ($stmt) {
            return true;
        } else {
            return false;
        }
    }

    public static function getEspaisPersona($persona_id){
        $llistaEspais = array();
        $query = "SELECT * FROM espais e 
                    INNER JOIN espai_persona ep ON e.id = ep.id_espai 
                    INNER JOIN persones p ON p.id = ep.id_persona 
                    WHERE p.id = :id_persona;";

        $params = array(
            ':id_persona' => $persona_id
        );

        Connection::connect();
        $stmt = Connection::execute($query, $params);
        Connection::close();

        $num = $stmt->rowCount();

        if ($num > 0) {
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
                extract($row);

                $espai = new Espai($row['nom'], $row['id_espai']);
                array_push($llistaEspais, $espai);
            }
        }
        return $llistaEspais;
    }

    public static function getDispositiusPersona($persona_id){
        $llistaEspais = array();
        $query = "SELECT * FROM dispositius d 
                    INNER JOIN persona_dispositiu pd ON d.id = pd.id_dispositiu 
                    INNER JOIN persones p ON p.id = pd.id_persona 
                    WHERE p.id = :id_persona;";

        $params = array(
            ':id_persona' => $persona_id
        );

        Connection::connect();
        $stmt = Connection::execute($query, $params);
        Connection::close();

        $num = $stmt->rowCount();

        if ($num > 0) {
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
                extract($row);

                $espai = new Dispositiu($row['nom'], $row['tipus'], $row['estat'], $row['id_dispositiu']);
                array_push($llistaEspais, $espai);
            }
        }
        return $llistaEspais;
    }

    
    public static function deleteEspaiPersona($persona_id, $espai_id){
        
        $query = "DELETE FROM espai_persona WHERE id_espai = :id_espai AND id_persona = :id_persona;";
        $params = array(
            ':id_espai' => $espai_id,
            ':id_persona' => $persona_id
        );

        Connection::connect();
        $stmt = Connection::execute($query, $params);
        Connection::close();
        
        if ($stmt) {
            return true;
        } else {
            return false;
        }
    }

    public static function deleteDispositiuPersona($persona_id, $dispositiu_id){

        $query = "DELETE FROM persona_dispositiu WHERE id_dispositiu = :id_dispositiu AND id_persona = :id_persona;";
        $params = array(
            ':id_dispositiu' => $dispositiu_id,
            ':id_persona' => $persona_id
        );

        Connection::connect();
        $stmt = Connection::execute($query, $params);
        Connection::close();
        
        if ($stmt) {
            return true;
        } else {
            return false;
        }
    }
}

?>