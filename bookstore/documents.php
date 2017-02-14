<?php

/////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////// B O O K S ///////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////

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
        
        $pdo->null;
        
        return $book;
    }
    
    function books_add($book)
    {
        $pdo = new PDO("mysql:host=127.0.0.1;dbname=bookPortal", 'root', 'p03S3rs_44');

        $stmt = $pdo->prepare("INSERT INTO books(title, author) VALUES (:title,:author);");
        $stmt->bindParam(':title', $book->title, PDO::PARAM_STR);
        $stmt->bindParam(':author', $book->author, PDO::PARAM_STR);

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

        $stmt = $pdo->prepare("UPDATE books SET title=:title, author=:author WHERE id=:id;");
        $stmt->bindParam(':title', $book->title, PDO::PARAM_STR);
        $stmt->bindParam(':author', $book->author, PDO::PARAM_STR);
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
    }

///////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////// J O U R N A L S ///////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////

    function journals_index()
    {
        $pdo = new PDO("mysql:host=127.0.0.1;dbname=bookPortal", 'root', 'p03S3rs_44');
        
        $stmt = $pdo->query("SELECT * from journals;");
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        $journals = array();
        
        foreach($rows as $row)
        {
            $journal = new Journal();
    
            $journal->id = $row['id'];
            $journal->title = $row['title'];
            
            array_push($journals, $journal);
        }
        
        $pdo->null;
        
        return $journals;
    }
    
    function journals_show($id)
    {
        $pdo = new PDO("mysql:host=127.0.0.1;dbname=bookPortal", 'root', 'p03S3rs_44');
        
        $stmt = $pdo->query("SELECT * from journals WHERE id=" . $id . ";");
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        $journal = new Journal();

        $journal->id = $row['id'];
        $journal->title = $row['title'];
        
        $pdo->null;
        
        return $journal;
    }
    
    function journals_add($journal)
    {
        $pdo = new PDO("mysql:host=127.0.0.1;dbname=bookPortal", 'root', 'p03S3rs_44');

        $stmt = $pdo->prepare("INSERT INTO journals(title, type) VALUES (:title,:type);");
        $stmt->bindParam(':title', $journal->title, PDO::PARAM_STR);
        $stmt->bindParam(':type', $journal->type, PDO::PARAM_STR);

        $stmt->execute();

        // Get the id of the new record and return the associated journal
        $id = $pdo->lastInsertId();

        $pdo->null;

        // Return the lectuer. This info is useful because the caller should see the id assigned to the new record
        return journals_show($id);
    }

    function journals_edit($id, $journal)
    {
        $pdo = new PDO("mysql:host=127.0.0.1;dbname=bookPortal", 'root', 'p03S3rs_44');

        $stmt = $pdo->prepare("UPDATE journals SET title=:title, type=:type WHERE id=:id;");
        $stmt->bindParam(':title', $journal->title, PDO::PARAM_STR);
        $stmt->bindParam(':type', $journal->type, PDO::PARAM_STR);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);

        $stmt->execute();

        $pdo->null;

        // Unlike with POST, there is no new information the user needs to see
        return null;
    }

    function journals_remove($id)
    {
        $pdo = new PDO("mysql:host=127.0.0.1;dbname=bookPortal", 'root', 'p03S3rs_44');

        $stmt = $pdo->prepare("DELETE FROM journals WHERE id=:id;");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);

        $stmt->execute();

        $pdo->null;

        // Unlike with POST, there is no new information the user needs to see
        return null;
    }

    class Journal
    {
        public $id;
        public $title;
        public $type;
    }

///////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////// P A P E R S ///////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////

    function papers_index()
    {
        $pdo = new PDO("mysql:host=127.0.0.1;dbname=bookPortal", 'root', 'p03S3rs_44');
        
        $stmt = $pdo->query("SELECT * from papers;");
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        $papers = array();
        
        foreach($rows as $row)
        {
            $paper = new Paper();
    
            $paper->id = $row['id'];
            $paper->subject = $row['subject'];
            
            array_push($papers, $paper);
        }
        
        $pdo->null;
        
        return $papers;
    }
    
    function papers_show($id)
    {
        $pdo = new PDO("mysql:host=127.0.0.1;dbname=bookPortal", 'root', 'p03S3rs_44');
        
        $stmt = $pdo->query("SELECT * from papers WHERE id=" . $id . ";");
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        $paper = new Paper();

        $paper->id = $row['id'];
        $paper->subject = $row['subject'];
        
        $pdo->null;
        
        return $paper;
    }
    
    function papers_add($paper)
    {
        $pdo = new PDO("mysql:host=127.0.0.1;dbname=bookPortal", 'root', 'p03S3rs_44');

        $stmt = $pdo->prepare("INSERT INTO papers(subject, type) VALUES (:subject,:type);");
        $stmt->bindParam(':subject', $paper->subject, PDO::PARAM_STR);
        $stmt->bindParam(':type', $paper->type, PDO::PARAM_STR);

        $stmt->execute();

        // Get the id of the new record and return the associated paper
        $id = $pdo->lastInsertId();

        $pdo->null;

        // Return the lectuer. This info is useful because the caller should see the id assigned to the new record
        return papers_show($id);
    }

    function papers_edit($id, $paper)
    {
        $pdo = new PDO("mysql:host=127.0.0.1;dbname=bookPortal", 'root', 'p03S3rs_44');

        $stmt = $pdo->prepare("UPDATE papers SET subject=:subject, type=:type WHERE id=:id;");
        $stmt->bindParam(':subject', $paper->subject, PDO::PARAM_STR);
        $stmt->bindParam(':type', $paper->type, PDO::PARAM_STR);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);

        $stmt->execute();

        $pdo->null;

        // Unlike with POST, there is no new information the user needs to see
        return null;
    }

    function papers_remove($id)
    {
        $pdo = new PDO("mysql:host=127.0.0.1;dbname=bookPortal", 'root', 'p03S3rs_44');

        $stmt = $pdo->prepare("DELETE FROM papers WHERE id=:id;");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);

        $stmt->execute();

        $pdo->null;

        // Unlike with POST, there is no new information the user needs to see
        return null;
    }

    class Paper
    {
        public $id;
        public $subject;
        public $type;
    }
 ?>