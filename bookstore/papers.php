<?php

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
            $paper->year = $row['year'];
            $paper->semester = $row['semester'];
            $paper->type = $row['type'];
            $paper->cost_buy = $row['cost_buy'];
            
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
        $paper->year = $row['year'];
        $paper->semester = $row['semester'];
        $paper->type = $row['type'];
        $paper->cost_buy = $row['cost_buy'];
        
        $pdo->null;
        
        return $paper;
    }
    
    function papers_add($paper)
    {
        $pdo = new PDO("mysql:host=127.0.0.1;dbname=bookPortal", 'root', 'p03S3rs_44');

        $stmt = $pdo->prepare("INSERT INTO papers(subject, year, semester, type, cost_buy) VALUES (:subject,:year, :semester, :type, :cost_buy);");
        $stmt->bindParam(':subject', $paper->subject, PDO::PARAM_STR);
        $stmt->bindParam(':year', $paper->type, PDO::PARAM_INT);
        $stmt->bindParam(':semester', $paper->type, PDO::PARAM_INT);
        $stmt->bindParam(':type', $paper->type, PDO::PARAM_STR);
        $stmt->bindParam(':cost_buy', $paper->type, PDO::PARAM_INT);

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

        $stmt = $pdo->prepare("UPDATE papers SET subject=:subject, year=:year, semester=:semester, type=:type, cost_buy=:cost_buy WHERE id=:id;");
        $stmt->bindParam(':subject', $paper->subject, PDO::PARAM_STR);
        $stmt->bindParam(':year', $paper->type, PDO::PARAM_INT);
        $stmt->bindParam(':semester', $paper->type, PDO::PARAM_INT);
        $stmt->bindParam(':type', $paper->type, PDO::PARAM_STR);
        $stmt->bindParam(':cost_buy', $paper->type, PDO::PARAM_INT);
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
        public $year;
        public $semester;
        public $type;
        public $cost_buy;
    }
 ?>