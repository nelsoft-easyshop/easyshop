<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class sqlmap {

    function getFilenameID($file, $id) {
        $query = simplexml_load_file(APPPATH . "resources/sql/" . $file . ".xml");
		$query = $query->xpath(' /map/select[@id="' . $id . '"] ');

        foreach ($query as $string) {
            return $string;
        }
    }

}

/* End of file sqlmap.php */
/* Location: ./application/libraries/sqlmap.php */