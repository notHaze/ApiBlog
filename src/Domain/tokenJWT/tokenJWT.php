<?php
namespace Domain\tokenJWT;

interface tokenJWT {

    function __construct(); 

    public function createToken($username, $password, $role, $id);

    public function validateToken($jwt);

    public function getAuthHeader();
    
    public function getBearerToken();

    public function getPayload($jwt);

}
?>