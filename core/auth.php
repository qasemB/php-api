<?php
require $_SERVER['DOCUMENT_ROOT'] . '/php_projects/test_api/vendor/autoload.php';

use Firebase\JWT\JWT;
// use Firebase\JWT\Key;

class Auth
{
    private $conn;
    private $key = 'privatekey';

    public function __construct($db)
    {
        $this->conn = $db;
    }


    public function auth()
    {
        try {
            $iat = time();
            $exp = $iat + 3600;
            $payload = [
                'iat' => $iat,
                'exp' => $exp
            ];
            $jwt = JWT::encode($payload, $this->key, 'HS512');
            // $decoded = JWT::decode($jwt, new Key($this->key, 'HS512'));
            $query = "INSERT IGNORE INTO tokens SET 
                    token    = :token, 
                    exp_time = :exp_time";

            $stmt = $this->conn->prepare($query);
            $stmt->bindparam(':token', $jwt);
            $stmt->bindparam(':exp_time', $exp);
            $stmt->execute();

            return [
                'success' => true,
                'token' => $jwt,
                'expires' => $exp,
                'type' => 'Bearer'
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => $e
            ];
        }
    }
}
