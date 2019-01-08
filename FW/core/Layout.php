<?php

class Layout{


    public function getLayout( $html = null, $layout = null)
    {
        extract($html);
        if( $html && $layout != null)
        {

            if(file_exists( LAYOUTPATH. $layout .'.php' ))
            {
                ob_start();
                include_once( LAYOUTPATH. $layout .'.php');
                $data = ob_get_contents();
                ob_end_clean();
            }
            else
            {
                $data = include_once('404.php');
            }
        }
        else
        {
            $data = include_once('404.php');
        }

        return $data;
    }

}
?>