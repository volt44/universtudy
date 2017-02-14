<?php

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
            $journal->type = $row['type'];
            $journal->ISSN = $row['ISSN'];
            $journal->total_doc = $row['total_doc'];
            $journal->avg_cit = $row['avg_cit'];
            $journal->country = $row['country'];
            $journal->cost_doc = $row['cost_doc'];
            
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
        $journal->type = $row['type'];
        $journal->ISSN = $row['ISSN'];
        $journal->total_doc = $row['total_doc'];
        $journal->avg_cit = $row['avg_cit'];
        $journal->country = $row['country'];
        $journal->cost_doc = $row['cost_doc'];
        
        $pdo->null;
        
        return $journal;
    }
    
    function journals_add($journal)
    {
        $pdo = new PDO("mysql:host=127.0.0.1;dbname=bookPortal", 'root', 'p03S3rs_44');

        $stmt = $pdo->prepare("INSERT INTO journals(title, type, ISSN, total_doc, avg_cit, country, cost_doc) VALUES (:title,:type,:ISSN,:total_doc,:avg_cit,:country,:cost_doc);");
        $stmt->bindParam(':title', $journal->title, PDO::PARAM_STR);
        $stmt->bindParam(':type', $journal->type, PDO::PARAM_STR);
        $stmt->bindParam(':ISSN', $journal->ISSN, PDO::PARAM_INT);
        $stmt->bindParam(':total_doc', $journal->total_doc, PDO::PARAM_INT);
        $stmt->bindParam(':avg_cit', $journal->avg_cit, PDO::PARAM_INT);
        $stmt->bindParam(':country', $journal->country, PDO::PARAM_STR);
        $stmt->bindParam(':cost_doc', $journal->cost_doc, PDO::PARAM_INT);

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
        $stmt->bindParam(':ISSN', $journal->ISSN, PDO::PARAM_INT);
        $stmt->bindParam(':total_doc', $journal->total_doc, PDO::PARAM_INT);
        $stmt->bindParam(':avg_cit', $journal->avg_cit, PDO::PARAM_INT);
        $stmt->bindParam(':country', $journal->country, PDO::PARAM_STR);
        $stmt->bindParam(':cost_doc', $journal->cost_doc, PDO::PARAM_INT);
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
        public $ISSN;
        public $total_docs;
        public $avg_cit;
        public $country;
        public $cost_doc;
        
    }

 ?>