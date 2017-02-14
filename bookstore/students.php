<?php

    function student_register($name, $email, $password) {
        
        $pdo = new PDO("mysql:host=127.0.0.1;dbname=bookPortal", 'root', 'p03S3rs_44');
        
        $stmt = $pdo->query("INSERT INTO students(name, email, password) VALUES ('$name',$email','$password');");
        
    }

    function student_authenticate($email, $password)
    {
        $pdo = new PDO("mysql:host=127.0.0.1;dbname=bookPortal", 'root', 'p03S3rs_44');
        
        $stmt = $pdo->query("SELECT * from students WHERE email='$email' AND password='$password';");
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        
        return $row['id'];
    }

    function students_index()
    {
        $pdo = new PDO("mysql:host=127.0.0.1;dbname=bookPortal", 'root', 'p03S3rs_44');
        
        $stmt = $pdo->query("SELECT * from students;");
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        $students = array();
        
        foreach($rows as $row)
        {
            $student = new Student();
    
            $student->id = $row['id'];
            $student->name = $row['name'];
            $student->email = $row['email'];
            
            array_push($students, $student);
        }
        
        $pdo->null;
        
        return $students;
    }
    
    function students_show($id)
    {
        $pdo = new PDO("mysql:host=127.0.0.1;dbname=bookPortal", 'root', 'p03S3rs_44');
        
        $stmt = $pdo->query("SELECT * from students WHERE id=" . $id . ";");
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        $student = new Student();

        $student->id = $row['id'];
        $student->name = $row['name'];
        $student->email = $row['email'];
        
        $pdo->null;
        
        return $student;
    }
    
    function students_add($student)
    {
        $pdo = new PDO("mysql:host=127.0.0.1;dbname=bookPortal", 'root', 'p03S3rs_44');

        $stmt = $pdo->prepare("INSERT INTO students(name, email) VALUES (:name,:email);");
        $stmt->bindParam(':name', $student->name, PDO::PARAM_STR);
        $stmt->bindParam(':email', $student->email, PDO::PARAM_STR);

        $stmt->execute();

        // Get the id of the new record and return the associated student
        $id = $pdo->lastInsertId();

        $pdo->null;

        // Return the lectuer. This info is useful because the caller should see the id assigned to the new record
        return students_show($id);
    }

    function students_edit($id, $student)
    {
        $pdo = new PDO("mysql:host=127.0.0.1;dbname=bookPortal", 'root', 'p03S3rs_44');

        $stmt = $pdo->prepare("UPDATE students SET name=:name, email=:email WHERE id=:id;");
        $stmt->bindParam(':name', $student->name, PDO::PARAM_STR);
        $stmt->bindParam(':email', $student->email, PDO::PARAM_STR);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);

        $stmt->execute();

        $pdo->null;

        // Unlike with POST, there is no new information the user needs to see
        return null;
    }

    function students_remove($id)
    {
        $pdo = new PDO("mysql:host=127.0.0.1;dbname=bookPortal", 'root', 'p03S3rs_44');

        $stmt = $pdo->prepare("DELETE FROM students WHERE id=:id;");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);

        $stmt->execute();

        $pdo->null;

        // Unlike with POST, there is no new information the user needs to see
        return null;
    }

    class Student
    {
        public $id;
        public $name;
        public $email;
    }
 ?>