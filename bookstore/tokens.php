<?php

    function get_token_list()
    {
        $pdo = new PDO("mysql:host=127.0.0.1;dbname=bookPortal", 'root', 'p03S3rs_44');
        
        $stmt = $pdo->query("SELECT * from tokens;");
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        $token_list = array();
        
        foreach($rows as $row)
        {
            array_push($token_list, $row['token']);
        }
        
        $pdo->null;
        
        return $token_list;
    }

    function generate_random_string($length)
    {
        $chars = "0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
        $rs = '';
        
        for ($i = 0; $i < $length; $i++)
        {
            $pos = rand(0, strlen($chars));
            $rs = $rs . $chars[$pos];
        }
        
        return $rs;
    }
    
    function token_create($user_id, $user_type)
    {
        $current_tokens = get_token_list();
        
        $token = generate_random_string(16);
        while (in_array($token, $current_tokens))
        {
            $token = generate_random_string(16);
        }
        
        $pdo = new PDO("mysql:host=127.0.0.1;dbname=bookPortal", 'root', 'p03S3rs_44');
        
        $stmt = $pdo->prepare("INSERT INTO tokens(token, user_id, user_type) VALUES (:token, :user_id, :user_type);");
        $stmt->bindParam(':token', $token, PDO::PARAM_STR);
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->bindParam(':user_type', $user_type, PDO::PARAM_STR);

        $stmt->execute();

        $pdo->null;
        
        return array("token" => $token);
    }
    
    function token_check($token)
    {
        $pdo = new PDO("mysql:host=127.0.0.1;dbname=bookPortal", 'root', 'p03S3rs_44');
        
        $stmt = $pdo->query("SELECT * FROM tokens WHERE token='$token';");
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        
        $pdo->null;
        
        return $row["user_id"];
    }

 ?>