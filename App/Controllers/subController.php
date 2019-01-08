<?php
class subController extends coreController{

    private $_authModels;
    private $_subModels;


    public function __construct($request)
    {
        parent::__construct($request);
        $this->_subModels = new subModels();
    }

    /**
     * user subscription method
     * @param $request
     */
    public function subscribeAction($request)
    {
        $arrayData = array();
        $err = array();

       foreach($request->post() as $key => $value)
        {
            if(!empty($value))
            {
                if( $key == 'password' ) // password process
                {
                    $crypt = $value != null ? password_hash($value, PASSWORD_DEFAULT) : null; // hash method
                    if($crypt != null)
                    {
                        $arrayData[$key] = $crypt ;
                    }
                    else
                    {
                        $err['error'][] = $key . $value;
                    }
                }
                elseif($key == "birthday") // birthday process
                {
                    $current = new DateTime();
                    $current = date_create($current->format('Y'));
                    $birth = date_create($value);
                    $dateDiff = date_diff($current,$birth);

                    if($dateDiff->format('%y') <= 12) // too young
                    {
                        $err['error'][] = $key . "Vous etes trop jeune pour vous inscrire sur la plateforme <br> ";
                    }
                    else
                    {
                        $arrayData[$key] = strtolower(htmlspecialchars($value));
                    }
                }
                else
                {
                    $arrayData[$key] = strtolower(htmlspecialchars($value));
                }
            }
            else
            {
                $err['error'][] = "infos manquantes : " . $key . '<br>';
            }
        }

        if(count($err) > 0 ) // return error
        {
            echo json_encode( $err);
            return false;
        }
        else
        {
            if( $response = $this->_subModels->newUserSubs($arrayData))
            {
                if(isset($response['error']))
                {
                    echo json_encode($response);

                }
                else
                {
                    $result["success"] = "OK";
                    echo json_encode($result);
                }
            }
            else
            {
                echo json_encode($err['error'] = "erreur de requetes");
            }
        }
    }

    /**
     * connect method after succesfull registration
     * @param $request
     */
    public function connectAction($request)
    {
        $this->_authModels = new authModels();

        $ident = htmlspecialchars($request->post('email'));
        $pass = htmlspecialchars($request->post('password'));

        if( $response = $this->_authModels->connectUserAction($ident, $pass))
        {
            $this->_authModule->connect($response['id_utilisateur']);
             header('location:-index.php?controller=frontProfil&action=frontPage&userID='.$response['id_utilisateur']);
             exit();
        }
        else
        {
            echo "identifiant ou mot de passe incorrecte";
        }

        echo $this->getResponse();
    }
}