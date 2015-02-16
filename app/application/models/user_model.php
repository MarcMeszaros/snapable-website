<?php
Class User_model extends CI_Model
{

    /**
     * This function allows to query the API and returns the response
     */
    function query($params=array())
    {
        // get type text
        $verb = 'GET';
        $path = '/user/';
        $resp = SnapApi::send($verb, $path, $params);

        return array(
            'response' => json_decode($resp['response'], true),
            'code' => $resp['code'],
        );
    }
    

}