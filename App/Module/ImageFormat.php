<?php

trait imageFormat
{
    /**
     * format and upload images
     * @param $userID
     * @param $source
     * @return string
     */
    public function addImageAction($userID, $source)
    {
        $uniq = md5(uniqid(rand(), true)); // make uniqID
        $mime = mime_content_type($_FILES['file_img']['tmp_name']); // getMime fontent for extension
        $extension = explode("/", $mime);

        $maxSize = 1048576;
        $extensions_valides = array( 'jpg' , 'jpeg' , 'gif' , 'png' );

        if(isset($_FILES) && $_FILES['file_img']['error'] > 0) // if error while upload
        {
           $result['error'] = "le fichier ne s'est pas correctement uploader en temporaire";
           return $result;
        }

        if( isset($_FILES) && $_FILES['file_img']['size'] > $maxSize) // if file is too big
        {
            $result['error'] =  "le fichier est trop volumineux";
            return $result;
        }

        if(isset($extension) && !in_array(strtolower(end($extension)), $extensions_valides)) // if extension is not valid
        {
            $result['error'] =  "l'extension du fichier n'est pas valide";
            return $result;
        }

        // upload switch directory
        switch ($source)
        {
            case "profil":
                if(!file_exists("avatar/user_".$userID."/"))
                {
                    mkdir("avatar/user_".$userID."/",0777, true); // make directory
                }
                if(!file_exists("avatar/user_miniature_".$userID."/"))
                {
                    mkdir("avatar/user_miniature_".$userID."/",0777, true); // make thumb
                }
                $fileName = "avatar/user_".$userID."/".$userID.".avatar.".end($extension);
                break;
            case "pwit":
                if(!file_exists("pwit_image/user_".$userID."/"))
                {
                    mkdir("pwit_image/user_".$userID."/",0777, true);
                }
                if(!file_exists("pwit_image/user_miniature_".$userID."/"))
                {
                    mkdir("pwit_image/user_miniature_".$userID."/",0777, true);
                }
                $fileName = "pwit_image/user_".$userID."/".$userID.".pwit.".$uniq.".".end($extension);
                break;
            case "comm":
                if(!file_exists("comm_image/user_".$userID."/"))
                {
                    mkdir("comm_image/user_".$userID."/",0777, true);
                }
                if(!file_exists("comm_image/user_miniature_".$userID."/"))
                {
                    mkdir("comm_image/user_miniature_".$userID."/",0777, true);
                }
                $fileName = "comm_image/user_".$userID."/".$userID.".comm.".$uniq.".".end($extension);
                break;
        }

        if($response = move_uploaded_file($_FILES['file_img']['tmp_name'], $fileName)) // if ok move upload to server
        {
            return $fileName;
        }
        else
        {
            $result['error'] =  "le fichier n'a pas pu etre sauvegarder sur le server";
            return $result;
        }
    }

    /**
     * make thumb src for img display on site
     * @param $user
     * @param $from
     * @param $ext
     * @param null $idData
     * @return string
     */
    public function makeThumbSrc($user,$from,$ext,$idData = null)
    {
        $ext = explode('.', $ext);

        switch($from)
        {
            case "pwit":
                return "pwit_image/user_miniature_".$user."/".$idData."pwit.". end($ext);
                break;
            case "comm":
                return "comm_image/user_miniature_".$user."/".$idData."comm.".end($ext);
                break;
            case "profil":
                return "avatar/user_miniature_".$user."/".$user."_avatar_mini.".end($ext);
                break;
        }
    }


    /**
     * make thumb process while upload
     * @param $src
     * @param $dest
     * @param $desired_width
     * @return bool
     */
    public function make_thumb($src, $dest, $desired_width)
    {

        /* read the source image */
        $extension = explode(".", $src);
        switch (end($extension))
        {
            case "jpeg":
            case "jpg":
                $source_image = imagecreatefromjpeg($src);
            break;
            case "png":
                $source_image = imagecreatefrompng($src);
                break;
            case "gif":
                $source_image = imagecreatefromgif($src);
        }

        $width = imagesx($source_image);
        $height = imagesy($source_image);

        /* find the "desired height" of this thumbnail, relative to the desired width  */
        $desired_height = floor($height * ($desired_width / $width));

        /* create a new, "virtual" image */
        $virtual_image = imagecreatetruecolor($desired_width, $desired_height);

        /* copy source image at a resized size */
        imagecopyresampled($virtual_image, $source_image, 0, 0, 0, 0, $desired_width, $desired_height, $width, $height);

        /* create the physical thumbnail image to its destination */
        if(imagejpeg($virtual_image, $dest))
        {
            return true;
        }
    }
}

?>