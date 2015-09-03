<?php
/**
 * Set the ajax action and call for wordpress
 */
class SQ_Action extends SQ_FrontController{
    /** @var array with all form and ajax actions  */
    var $actions = array();
    /** @var array from core config */
    private static $config;

    /**
    * The hookAjax is loaded as custom hook in hookController class
    * 
    * @return void
    */
    function hookInit(){
        /* Only if ajax */
        if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
            $this->actions = array();
            $this->getActions(((isset($_GET['action']) ? $_GET['action'] : (isset($_POST['action']) ? $_POST['action'] : '')))); 
        }
    }
    
    /**
    * The hookSubmit is loaded when action si posted
    * 
    * @return void
    */
    function hookMenu(){
        /* Only if post */
        if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') return;
        
        $this->actions = array();
        $this->getActions(((isset($_GET['action']) ? $_GET['action'] : (isset($_POST['action']) ? $_POST['action'] : '')))); 
    }
    
    /**
    * The hookHead is loaded as admin hook in hookController class for script load
     * Is needed for security check as nonce
    * 
    * @return void
    */
    function hookHead(){
     
        echo '<script type="text/javascript" src="https://www.google.com/jsapi"></script>
              <script type="text/javascript">
                  var sqQuery = {    
                    "ajaxurl": "'.admin_url( 'admin-ajax.php' ).'",
                    "nonce": "'.wp_create_nonce( _SQ_NONCE_ID_ ).'"
                  }
                  
                  if(parseInt(jQuery.fn.jquery.replace(/\./g,"")) < 162)
                    google.load("jquery", "1.6.2");
              </script>';
    }
    
    
   /**
    * Get all actions from config.xml in core directory and add them in the WP
    * 
    * @return void
    */
    public function getActions($cur_action){
        
           /* if config allready in cache */
           if (!isset(self::$config)){
                   $config_file= _SQ_CORE_DIR_ . 'config.xml';
                   if (!file_exists($config_file)) return;

                   /* load configuration blocks data from core config files */
                   $data = file_get_contents($config_file);
                   self::$config = json_decode(json_encode((array) simplexml_load_string($data)),1);;

           }
           
           if (is_array(self::$config))
                   foreach (self::$config['block'] as $block){
                   if ($block['active'] == 1)	{
                       /* if there is a single action */
                       if (isset($block['actions']['action']))
                        
                            /* if there are more actions for the current block */
                        if (!is_array($block['actions']['action'])){
                            /* add the action in the actions array */
                            if ( $block['actions']['action'] == $cur_action)
                                $this->actions[] = array( 'class' => $block['name'], 'path' => $block['path']);
                        }else{
                            /* if there are more actions for the current block */
                            foreach ($block['actions']['action'] as $action){
                                /* add the actions in the actions array */
                                if ( $action == $cur_action)
                                    $this->actions[] = array( 'class' => $block['name'], 'path' => $block['path']);
                            }
                        }
                        
                   }
                 

           }
         
           /* add the actions in WP */
           foreach ($this->actions as $actions){
               if ($actions['path'] == 'core')
                   SQ_ObjController::getBlock($actions['class'])->action();
               elseif ($actions['path'] == 'controllers')
                   SQ_ObjController::getController($actions['class'])->action();
                
           }
     }
     
    public static function apiCall($module, $args = array()){
       $parameters = "";
       if (SQ_Tools::$options['sq_api'] == '' && $module <> 'sq/login' && $module <> 'sq/register') return false;
       
       $extra = array('user_url' => ((get_bloginfo('wpurl') <> '') ? get_bloginfo('wpurl') :  'http://'.basename($_SERVER['HTTP_HOST'])),
                      'versq' => SQ_VERSION_ID,
                      'verwp' => WP_VERSION_ID,
                      'verphp' => PHP_VERSION_ID,
                      'token' => SQ_Tools::$options['sq_api']);
       
       if ($module <> "") $module .= "/";
       
       if (is_array($args)){
           $args = array_merge($args,$extra);
       }else{
           $args = $extra;
       }
       
       foreach ($args as $key => $value)
           if ($value <> '')
           $parameters .= ($parameters == "" ? "" : "&") . $key."=".$value;
       
       /* If the call is for login on register then use base64 is exists */
       if($module == 'sq/login' || $module == 'sq/register')
        if(function_exists('base64_encode'))
           $parameters = 'q='.base64_encode($parameters);
       
       $url = self::cleanUrl(_SQ_API_URL_.$module."?".$parameters);
       
       // echo $url;
       return SQ_Tools::sq_remote_get($url);
     
   }
   
   private static function cleanUrl($url){
       return str_replace(array(' '), array('+'), $url);
   }
   
}