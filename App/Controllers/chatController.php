<?php

class chatController extends coreController
{
    private $_aboID;
    private $_chatModel;

    public function __construct($request)
    {
        parent::__construct($request);
        $this->_chatModel = new chatModels();
        $this->_aboID = ctype_digit( $request->get("aboID")) ? (int) $request->get("aboID") : 0;
    }

    /**
     * get all tchat message between user and another
     * @return view
     */
    public function getChatMessageAction()
    {
        if($this->_authModule->isConnected())
        {
            if($this->_authModule->getLogin() && $this->_aboID != 0)
            {
                if( ( $convTemp['conv'] = $this->_chatModel->getMessage($this->_authModule->getLogin(), $this->_aboID) ) == false )
                {
                    $convTemp = $this->_aboID; // if no message find keep abo id (for render purpose
                }
                $path = "div#chatBox".$this->_authModule->getLogin().$this->_aboID."";
                // adding ("") around the string
                $path = "\"$path\"";
                // unset session variable conver if exist
                if(isset($_SESSION['currentUser']['convActiv'][$this->_aboID]))
                {
                    unset($_SESSION['currentUser']['convActiv'][$this->_aboID]);
                }
                // make session variable whit id abo
                $_SESSION['currentUser']['convActiv'][$this->_aboID] = array('user' => $this->_authModule->getLogin(), 'abo' =>$this->_aboID, 'path' => $path );

                $abo = $this->_aboID;

                $chat = $this->renderP( 'chatView', compact('convTemp', 'abo'));

                $this->renderFinale("layout2", compact('chat'));

                echo $this->getResponse();
            }
            else
            {
                $error['error'] = "Une erreur est survenue, veuillez nous en excuser";
                echo json_encode($error);
            }
        }
        else
        {
            $error['error'] = "Vous devez être connecter pour effectuer cette action";
            echo json_encode($error);
        }
    }

    /**
     * get the last usert insert tchat message method
     * @param $request
     */
    public function getLastChatMessageAction($request)
    {
        $last = ctype_digit($request->get('lastId')) ? (int) $request->get('lastId') : 0;

        if( $last !=0 && $response =  $this->_chatModel->getLastMess($this->_authModule->getLogin(), $this->_aboID, $last) )
        {
            echo json_encode($response);
        }
        else
        {
            $error['error'] = "Une erreur est survenue, veuillez nous en excuser";
            echo json_encode($error);
        }
    }

    /**
     * send new user tchat message method
     * @param $request
     */
    public function sendMessageAction($request)
    {
        $content = $request->get("data") != null ? htmlspecialchars($request->get("data")) : null;

        if($content != null )
        {
            if( $response = $this->_chatModel->insertMessage($this->_authModule->getLogin(),$this->_aboID,$content))
            {
                return true;
            }
            else
            {
                $error['error'] = "Désolé, votre message n'a pas été envoyer";
                echo json_encode($error);
            }
        }
    }

    /**
     * close active tchat box method
     * @return bool
     */
    public function closeConversAction()
    {
        if($this->_authModule->getLogin() && $this->_aboID != 0)
        {
            unset($_SESSION['currentUser']['convActiv'][$this->_aboID]);
            return true;
        }
        return false;
    }
}