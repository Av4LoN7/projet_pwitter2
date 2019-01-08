<?php

class search
{
    /**
     * search method for person and tag
     * @param $request|object Model
     */
    public function searchTermAction($term, $from, $model)
    {
        if($term && $from != null)
        {
            switch($from)
            {
                case "person":
                    $searchResult = $model->research($term);
                    break;
                default :
                    $searchResult = $model->researchCat($term);
                    break;
            }
            if(!$searchResult)
            {
                 return false;
            }
            else
            {
                return $searchResult;
            }
        }
        else
        {
            return false;
        }
    }
}