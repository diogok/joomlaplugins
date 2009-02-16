<?


class Package {

    function __construct() {
        $f = __FILE__;
        $fs = explode("/",$f);
        $c = count($fs);
        $c = $c -3;
        $name = $fs[$c];
        $this->install = simplexml_load_file($name.".xml");
    }

    function fixName(){
        $name = $this->getName();
        $files = $this->read(".",true);
        foreach($files as $file) {
            if(substr($file,0,9)=="./scripts") {
                continue ;
            }
            if(!is_dir($file)) {
                $oContent = file_get_contents($file);
                $nContent = str_replace("mod_blank",$name,$oContent);
                file_put_contents($file,$nContent);
            }
            $old = $file ;
            $new = str_replace("mod_blank",$name,$file);
            rename($old,$new);
        }
    }

    function getName() {
        return  strip_tags((string) $this->install->name );
    }

    function fixFiles() {

        unset($this->install->files);

        $this->install->addChild("files","\n");

        $site = $this->install->files ;

        $sFiles = $this->read(".");

        $this->addFiles($site,$sFiles);

        $xml = $this->install->asXml();

        file_put_contents($this->getName().".xml",$xml);
    }

    function read($dir,$all=false) {
        $pointer = opendir($dir);
        $files = array() ;
        while($file = readdir($pointer)) {
            if($file == "." or $file == "..") continue ;
            $path = $dir."/".$file ;
            if(is_dir($path)) {
                if($all) $files[] = $path ;
                $files = array_merge($files,$this->read($path)); 
            } else {
                $files[] = $path ;
            }
        }
        return $files ;
    }

    function addFiles($xml,$files){
        if(count($files) < 1) return ;
        $xml->addChild("filename",$this->getName().".php") ;
        $xml->filename[0]->addAttribute("module",$this->getName());
        foreach($files as $file) {
            $file = substr($file,2);
            $p = strpos($file,"/");
            //$file = substr($file,$p + 1);
            if($file == $this->getName().".php") continue ;
            $xml->addChild("filename",$file) ;
        }
        return $xml ;
    }

    function makezip($name) {
        $cmd = "zip -r ../".$name.".zip . ../".$name ;
        return  shell_exec($cmd);
    }
}


$p = new package ;
if($argv[1] == "name") {
    $p->fixName();
} else if($argv[1] == "") {
    $p->fixFiles();
    echo $p->makezip($p->getName());
}

?>
