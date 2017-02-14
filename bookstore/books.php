<?php

    function books_index()
    {
        $pdo = new PDO("mysql:host=127.0.0.1;dbname=bookPortal", 'root', 'p03S3rs_44');
        
        $stmt = $pdo->query("SELECT * from books;");
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        $books = array();
        
        foreach($rows as $row)
        {
            $book = new Book();
    
            $book->id = $row['id'];
            $book->title = $row['title'];
            $book->author = $row['author'];
            $book->copies = $row['copies'];
            $book->fiction = $row['fiction'];
            $book->original_lang = $row['original_lang'];
            $book->cost_buy = $row['cost_buy'];
            $book->cost_borrow = $row['cost_borrow'];
//            $book->description = $row['description'];
            
            array_push($books, $book);
        }
        
        $pdo->null;
        
        return $books;
    }
    
    function books_show($id)
    {
        $pdo = new PDO("mysql:host=127.0.0.1;dbname=bookPortal", 'root', 'p03S3rs_44');
        
        $stmt = $pdo->query("SELECT * from books WHERE id=" . $id . ";");
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        $book = new Book();

        $book->id = $row['id'];
        $book->title = $row['title'];
        $book->author = $row['author'];
        $book->copies = $row['copies'];
        $book->fiction = $row['fiction'];
        $book->original_lang = $row['original_lang'];
        $book->cost_buy = $row['cost_buy'];
        $book->cost_borrow = $row['cost_borrow'];
//        $book->description = $row['description'];
        
        $pdo->null;
        
        return $book;
    }
    
    function books_add($book)
    {
        $pdo = new PDO("mysql:host=127.0.0.1;dbname=bookPortal", 'root', 'p03S3rs_44');

        $stmt = $pdo->prepare("INSERT INTO books(title, author, copies, fiction, original_lang, cost_buy, cost_borrow, description) VALUES (:title,:author,:copies,:fiction,:original_lang,:cost_buy,:cost_borrow,:description);");
        $stmt->bindParam(':title', $book->title, PDO::PARAM_STR);
        $stmt->bindParam(':author', $book->author, PDO::PARAM_STR);
        $stmt->bindParam(':copies', $book->copies, PDO::PARAM_INT);
        $stmt->bindParam(':fiction', $book->fiction, PDO::PARAM_INT);
        $stmt->bindParam(':original_lang', $book->original_lang, PDO::PARAM_STR);
        $stmt->bindParam(':cost_buy', $book->cost_buy, PDO::PARAM_INT);
        $stmt->bindParam(':cost_borrow', $book->cost_borrow, PDO::PARAM_INT);
//        $stmt->bindParam(':description', $book->description, PDO::PARAM_STR);

        $stmt->execute();

        // Get the id of the new record and return the associated book
        $id = $pdo->lastInsertId();

        $pdo->null;

        // Return the lectuer. This info is useful because the caller should see the id assigned to the new record
        return books_show($id);
    }

    function books_edit($id, $book)
    {
        $pdo = new PDO("mysql:host=127.0.0.1;dbname=bookPortal", 'root', 'p03S3rs_44');

        $stmt = $pdo->prepare("UPDATE books SET title=:title, author=:author, copies=:copies, fiction=:fiction, original_lang=:original_lang, cost_buy=:cost_buy, cost_borrow=:cost_borrow, description=:description WHERE id=:id;");
        $stmt->bindParam(':title', $book->title, PDO::PARAM_STR);
        $stmt->bindParam(':author', $book->author, PDO::PARAM_STR);
        $stmt->bindParam(':copies', $book->copies, PDO::PARAM_INT);
        $stmt->bindParam(':fiction', $book->fiction, PDO::PARAM_INT);
        $stmt->bindParam(':original_lang', $book->original_lang, PDO::PARAM_STR);
        $stmt->bindParam(':cost_buy', $book->cost_buy, PDO::PARAM_INT);
        $stmt->bindParam(':cost_borrow', $book->cost_borrow, PDO::PARAM_INT);
//        $stmt->bindParam(':description', $book->description, PDO::PARAM_STR);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);

        $stmt->execute();

        $pdo->null;

        // Unlike with POST, there is no new information the user needs to see
        return null;
    }

    function books_remove($id)
    {
        $pdo = new PDO("mysql:host=127.0.0.1;dbname=bookPortal", 'root', 'p03S3rs_44');

        $stmt = $pdo->prepare("DELETE FROM books WHERE id=:id;");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);

        $stmt->execute();

        $pdo->null;

        // Unlike with POST, there is no new information the user needs to see
        return null;
    }

    class Book
    {
        public $id;
        public $title;
        public $author;
        public $copies;
        public $fiction;
        public $original_lang;
        public $cost_buy;
        public $cost_borrow;
//        public $description;
    }

 ?>