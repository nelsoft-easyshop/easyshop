<?php

class xmlmap {

    public function getFilenameID($file, $id, $node='select') {
        $query = simplexml_load_file(APPPATH . "resources/" . $file . ".xml");   
		$query = $query->xpath(' /map/'.$node.'[@id="' . $id . '"] ');
        foreach ($query as $string) {
            return $string;
        }
    }
    
    public function getFilenameNode($file, $node) {
        $query = simplexml_load_file(APPPATH . "resources/" . $file . ".xml");   
		$query = $query->xpath(' /map/'.$node);
        foreach ($query as $string) {
            return $string;
        }
    }
    
    public function getFilename($file) {
        $xml = simplexml_load_file(APPPATH . "resources/" . $file . ".xml");
        $simple = json_decode(json_encode($xml), 1);
        
        return $simple;
    }
    


}

/* End of file xmlmap.php */
/* Location: ./application/libraries/xmlmap.php */