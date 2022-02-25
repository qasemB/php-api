<?php
require $_SERVER['DOCUMENT_ROOT'] . '/php_projects/test_api/vendor/autoload.php';

use Firebase\JWT\JWT;
// use Firebase\JWT\Key;

class Post
{
    private $conn;

    public $id;
    public $category_id;
    public $category_name;
    public $title;
    public $body;
    public $author;
    public $create_at;

    public function __construct($db)
    {
        $this->conn = $db;
    }




    public function read()
    {
        $headers = apache_request_headers();
        if (isset($headers['Authorization'])) {
            $user_token = str_replace("Bearer ", '', $headers['Authorization']);

            $q = 'SELECT * FROM tokens WHERE token=? LIMIT 1';
            $stmt = $this->conn->prepare($q);
            $stmt->bindValue(1, $user_token);
            $stmt->execute();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            if (isset($row['token']) && $row['token'] == $user_token) {
                try {
                    // $decoded = JWT::decode($jwt, new Key($this->key, 'HS512'));
                    $query = "SELECT c.name as category_name, p.id, p.category_id, p.title, p.body, p.author, p.create_at 
                    FROM 
                    posts p 
                    LEFT JOIN 
                    categories c 
                    ON p.category_id = c.id
                    ORDER BY p.create_at DESC";

                    $stmt = $this->conn->prepare($query);
                    $stmt->execute();
                    return $stmt;
                } catch (\Exception $e) {
                    print_r($e);
                }
            } else {
                http_response_code(401);
                print_r(json_encode([
                    'message' => 'not authorized'
                ]));
                die();
            }
        } else {
            http_response_code(401);
            print_r(json_encode([
                'message' => 'not authorized'
            ]));
            die();
        }
    }





    public function read_single()
    {
        $query = "SELECT c.name as category_name, p.id, p.category_id, p.title, p.body, p.author, p.create_at 
            FROM 
            posts p 
            LEFT JOIN 
            categories c 
            ON p.category_id = c.id
            WHERE p.id = ? LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->id);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        $this->title = $row['title'];
        $this->body = $row['body'];
        $this->author = $row['author'];
        $this->category_id = $row['category_id'];
        $this->category_name = $row['category_name'];
    }




    public function create()
    {
        $query = "INSERT INTO posts SET 
            title           = :title, 
            body            = :body, 
            author          = :author, 
            category_id     = :category_id";

        $stmt = $this->conn->prepare($query);

        $this->title    = htmlspecialchars(strip_tags($this->title));
        $this->body    = htmlspecialchars(strip_tags($this->body));
        $this->author    = htmlspecialchars(strip_tags($this->author));
        $this->category_id    = htmlspecialchars(strip_tags($this->category_id));


        $stmt->bindparam(':title', $this->title);
        $stmt->bindparam(':body', $this->body);
        $stmt->bindparam(':author', $this->author);
        $stmt->bindparam(':category_id', $this->category_id);

        if ($stmt->execute()) {
            return true;
        }

        printf("Error %s. \n", $stmt->error);
        return false;
    }





    public function update()
    {
        $query = "UPDATE posts SET 
            title           = :title, 
            body            = :body, 
            author          = :author, 
            category_id     = :category_id
            WHERE id = :id";

        $stmt = $this->conn->prepare($query);

        $this->title    = htmlspecialchars(strip_tags($this->title));
        $this->body    = htmlspecialchars(strip_tags($this->body));
        $this->author    = htmlspecialchars(strip_tags($this->author));
        $this->category_id    = htmlspecialchars(strip_tags($this->category_id));
        $this->id    = htmlspecialchars(strip_tags($this->id));

        $stmt->bindparam(':title', $this->title);
        $stmt->bindparam(':body', $this->body);
        $stmt->bindparam(':author', $this->author);
        $stmt->bindparam(':category_id', $this->category_id);
        $stmt->bindparam(':id', $this->id);

        if ($stmt->execute()) {
            return true;
        }

        printf("Error %s. \n", $stmt->error);
        return false;
    }





    public function delete()
    {
        $query = "DELETE FROM posts WHERE id = :id";
        $stmt = $this->conn->prepare($query);

        $this->id    = htmlspecialchars(strip_tags($this->id));

        $stmt->bindParam(':id', $this->id);

        if ($stmt->execute()) {
            return true;
        }
        printf("Error %s. \n", $stmt->error);
        return false;
    }
}
