<?php

    function employee_register($email, $password) {
        
        $pdo = new PDO("mysql:host=127.0.0.1;dbname=bookPortal", 'root', 'p03S3rs_44');
        
        $stmt = $pdo->query("INSERT INTO employees(name, email, password) VALUES ('$name',$email','$password');");
        
    }

    function employee_authenticate($email, $password)
    {
        $pdo = new PDO("mysql:host=127.0.0.1;dbname=bookPortal", 'root', 'p03S3rs_44');
        
        $stmt = $pdo->query("SELECT * from employees WHERE email='$email' AND password='$password';");
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        
        return $row['id'];
    }

    function employees_index()
    {
        $pdo = new PDO("mysql:host=127.0.0.1;dbname=bookPortal", 'root', 'p03S3rs_44');
        
        $stmt = $pdo->query("SELECT * from employees;");
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        $employees = array();
        
        foreach($rows as $row)
        {
            $employee = new Employee();
    
            $employee->id = $row['id'];
            $employee->name = $row['name'];
            $employee->email = $row['email'];
            
            array_push($employees, $employee);
        }
        
        $pdo->null;
        
        return $employees;
    }
    
    function employees_show($id)
    {
        $pdo = new PDO("mysql:host=127.0.0.1;dbname=bookPortal", 'root', 'p03S3rs_44');
        
        $stmt = $pdo->query("SELECT * from employees WHERE id=" . $id . ";");
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        $employee = new Employee();

        $employee->id = $row['id'];
        $employee->name = $row['name'];
        $employee->email = $row['email'];
        
        $pdo->null;
        
        return $employee;
    }
    
    function employees_add($employee)
    {
        $pdo = new PDO("mysql:host=127.0.0.1;dbname=bookPortal", 'root', 'p03S3rs_44');

        $stmt = $pdo->prepare("INSERT INTO employees(name, email) VALUES (:name,:email);");
        $stmt->bindParam(':name', $employee->name, PDO::PARAM_STR);
        $stmt->bindParam(':email', $employee->email, PDO::PARAM_STR);

        $stmt->execute();

        // Get the id of the new record and return the associated employee
        $id = $pdo->lastInsertId();

        $pdo->null;

        // Return the lectuer. This info is useful because the caller should see the id assigned to the new record
        return employees_show($id);
    }

    function employees_edit($id, $employee)
    {
        $pdo = new PDO("mysql:host=127.0.0.1;dbname=bookPortal", 'root', 'p03S3rs_44');

        $stmt = $pdo->prepare("UPDATE employees SET name=:name, email=:email WHERE id=:id;");
        $stmt->bindParam(':name', $employee->name, PDO::PARAM_STR);
        $stmt->bindParam(':email', $employee->email, PDO::PARAM_STR);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);

        $stmt->execute();

        $pdo->null;

        // Unlike with POST, there is no new information the user needs to see
        return null;
    }

    function employees_remove($id)
    {
        $pdo = new PDO("mysql:host=127.0.0.1;dbname=bookPortal", 'root', 'p03S3rs_44');

        $stmt = $pdo->prepare("DELETE FROM employees WHERE id=:id;");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);

        $stmt->execute();

        $pdo->null;

        // Unlike with POST, there is no new information the user needs to see
        return null;
    }

    class Employee
    {
        public $id;
        public $name;
        public $email;
    }

 ?>