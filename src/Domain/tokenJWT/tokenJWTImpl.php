<?php
namespace Domain\tokenJWT;

require __DIR__ . '/../../../vendor/autoload.php';

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use DateTimeImmutable;

class tokenJWTImpl implements tokenJWT {

    static private $SECRET_KEY;
    private const SERVER_NAME = "server.article.login";
    private const EXPIRATION_TIME = "10"; //en minutes
    private const ALGORITHM = "HS512";

    function __construct() {
        tokenJWTImpl::$SECRET_KEY = "alorsvoilalesecret"; /*getenv("jwt_secret_key");*/
    }

    public function createToken($username, $password, $role, $id) {

        $issuedAt   = new DateTimeImmutable();
        $expire     = $issuedAt->modify("+".tokenJWTImpl::EXPIRATION_TIME."minutes")->getTimestamp();
        
        $data = [
            'iat'  => $issuedAt->getTimestamp(),         // Issued at:  : heure à laquelle le jeton a été généré
            'iss'  => tokenJWTImpl::SERVER_NAME,             // Émetteur (nous)
            'nbf'  => $issuedAt->getTimestamp(),         // Pas avant..
            'exp'  => $expire,                           // Expiration
            'role' => $role,                             // Role de l'utilisateur
            'id'   => $id                                // Id de l'utilisateur
        ];

        $jwt = JWT::encode(
            $data,      // Données à encoder dans le JWT
            tokenJWTImpl::$SECRET_KEY, // Clé secrète
            tokenJWTImpl::ALGORITHM     // Algorithme d'encodage
        );
        return $jwt;
    }


    public function validateToken($jwt) {
        $token = JWT::decode($jwt, new Key(tokenJWTImpl::$SECRET_KEY, tokenJWTImpl::ALGORITHM));
        $now = new DateTimeImmutable();

        if ($token->iss !== tokenJWTImpl::SERVER_NAME || $token->nbf > $now->getTimestamp() || $token->exp < $now->getTimestamp()){
            return false;
        }
        return true;
    }

    public function getAuthHeader(){
        $headers = null;
        if (isset($_SERVER['Authorization'])) {
            $headers = trim($_SERVER["Authorization"]);
        }
        else if (isset($_SERVER['HTTP_AUTHORIZATION'])) { //Nginx or fast CGI
            $headers = trim($_SERVER["HTTP_AUTHORIZATION"]);
        } elseif (function_exists('apache_request_headers')) {
            $requestHeaders = apache_request_headers();
            // Server-side fix for bug in old Android versions (a nice side-effect of this fix means we don't care about capitalization for Authorization)
            $requestHeaders = array_combine(array_map('ucwords', array_keys($requestHeaders)), array_values($requestHeaders));
            //print_r($requestHeaders);
            if (isset($requestHeaders['Authorization'])) {
                $headers = trim($requestHeaders['Authorization']);
            }
        }
        return $headers;
    }
    
    /**
     * get access token from header
     * */
    public function getBearerToken() {
        $headers = $this->getAuthHeader();
        // HEADER: Get the access token from the header
        if (!empty($headers)) {
            if (preg_match('/Bearer\s(\S+)/', $headers, $matches)) {
                return $matches[1];
            }
        }
        return null;
    }

    public function getPayload($jwt) {
        $token = JWT::decode($jwt, new Key(tokenJWTImpl::$SECRET_KEY, tokenJWTImpl::ALGORITHM));
        return $token;
    }

}
?>