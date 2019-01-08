<?php

class view{

    private $_layoutObject;
    private $_view;

    public function __construct($view)
    {
        $this->_layoutObject = new Layout();
        $this->_view = $view;
    }

    /**
     * render method for view
     * @param null $data
     * @return mixed|string
     */
    public function renderPartial( $data = null)
    {
       if(is_array($data) && $data!= null)
       {
           extract($data);
       }
            // view path
        if(file_exists(VIEWSPATH.$this->_view.'.php'))
        {
            ob_start();
            include_once( VIEWSPATH.$this->_view.'.php');

            $partial = ob_get_contents();
            ob_end_clean();

        } // Admin view path
        elseif(file_exists(ADMINPATH.$this->_view.'.php'))
        {
            ob_start();
            include_once( ADMINPATH.$this->_view.'.php');

            $partial = ob_get_contents();
            ob_end_clean();
        }
        else
        {
            // get layout
            if($partial = $this->_layoutObject->getLayout($data,$this->_view))
            {
                return $partial;
            }
            else
            {
                $partial = include_once('404.php');
            }
        }
        return $partial;
    }
}

?>