<?


class Package {

    function __construct() {
        $this->install = simplexml_load_file("install.xml");
    }

    function getName() {
        return  strip_tags((string) $this->install->name );
    }

    function fixFiles() {

        unset($this->install->administration->files);
        unset($this->install->files);

        $this->install->administration->addChild("files","\n");
        $this->install->addChild("files","\n");

        //$admin = new  SimpleXMLElement("<files/>");
        //$site = new  SimpleXMLElement("<files/>");

        $site = $this->install->files ;
        $admin = $this->install->administration->files;

        $site->addAttribute("folder","site");
        $admin->addAttribute("folder","admin");

        $sFiles = $this->read("./site");
        $aFiles = $this->read("./admin");

        $this->addFiles($site,$sFiles);
        $this->addFiles($admin,$aFiles);

        $xml = $this->install->asXml();

        file_put_contents("install.xml",$xml);
    }

    function read($dir) {
        $pointer = opendir($dir);
        $files = array() ;
        while($file = readdir($pointer)) {
            if($file == "." or $file == "..") continue ;
            $path = $dir."/".$file ;
            if(is_dir($path)) {
                $files = array_merge($files,$this->read($path)); 
            } else {
                $files[] = $path ;
            }
        }
        return $files ;
    }

    function addFiles($xml,$files){
        if(count($files) < 1) return ;
        foreach($files as $file) {
            $file = substr($file,2);
            $p = strpos($file,"/");
            $file = substr($file,$p + 1);
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
$p->fixFiles();
echo $p->makezip("com_".$p->getName());

?>
