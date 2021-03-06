<?php defined('SYSPATH') OR die('No direct access allowed.');

/**
 * Retrieves the PNP config files 
 */
class Config_Model extends System_Model
{
    public $conf = array();
    public $views = array();

    public function read_config(){
        if(getenv('PNP_CONFIG_FILE') != ""){
            $config = getenv('PNP_CONFIG_FILE');
        }elseif(OMD){
            $config = OMD_SITE_ROOT.'/etc/pnp4nagios/config';
        }else{
            $config = Kohana::config('core.pnp_etc_path')."/config";
        }

        # Default Values
        $conf['doc_language']   = Kohana::config('core.doc_language');
        $conf['graph_width']    = Kohana::config('core.graph_width');
        $conf['graph_height']   = Kohana::config('core.graph_height');
        $conf['zgraph_width']   = Kohana::config('core.zgraph_width');
        $conf['zgraph_height']  = Kohana::config('core.zgraph_height');
        $conf['pdf_width']      = Kohana::config('core.pdf_width');
        $conf['pdf_height']     = Kohana::config('core.pdf_height');
        $views = Kohana::config('core.views');
        
        if (is_readable($config . ".php")) {
            include ($config . ".php");
        }else {
            throw new Kohana_Exception('error.config-not-found', $config.'.php');
        }

        if (is_readable($config . "_local.php")) {
            $array_a = $views;
            $views = array();
            include ($config . "_local.php");
            $array_b = $views;
            if(sizeof($views) == 0 ){
                $views = $array_a;
            }
        }
        $this->conf = $conf;
        $this->views = $views;
    }
}
