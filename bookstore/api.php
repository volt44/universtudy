<?php

    require_once __DIR__.'/vendor/autoload.php';
    require_once 'tokens.php';
    require_once 'employees.php';
    require_once 'students.php';
    require_once 'books.php';
    require_once 'journals.php';
    require_once 'papers.php';

    // Silex support for accessing the HTTP Request and Response
    use Symfony\Component\HttpFoundation\Request;
    use Symfony\Component\HttpFoundation\Response;
    use Symfony\Component\HttpFoundation\ParameterBag;
    
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, HEAD, OPTIONS");
    header("Access-Control-Allow-Headers: Content-Type, X-Bearer-Token");


    date_default_timezone_set("Africa/Johannesburg");
    $app = new Silex\Application();

    // After receiving a request, before doing anything else
    $app->before(function (Request $request)
    {
        //check the route
        $route = $request->get('_route');
        //check the method
        $method = $request->getMethod();
        // if the request is a login request or a preflight request, let it through
        if (($route != "POST_tokens" && $route != "POST_register") && $method != "OPTIONS")
        {
            // check that we have recieved a token and that it has been assigned to a user
            $token = $request->headers->get('X-Bearer-Token');
            $user_id = token_check($request->headers->get('X-Bearer-Token'));
            
            if ($user_id != null)
            {
                $request->headers->set('X-User', $user_id);
            }
            else
            {
                return new Response('Forbidden', 403);
            }
        }
        
        // If we received JSON
        if (0 === strpos($request->headers->get('Content-Type'), 'application/json'))
        {
            // Decode it
            $data = json_decode($request->getContent(), true);
            // And replace the encoded data with the decoded data
            $request->request->replace(is_array($data) ? $data : array());
        }
    });
    
    $app->get('/', function()
    { 
        return json_encode("Hi");
    });
    
    // Return OK for all OPTIONS, no matter the URL
    $app->match("{url}", function ($url) use ($app)
                {
                    return "OK";
                })->assert('url','.*')->method("OPTIONS");

    // Login Request
    $app->post('/tokens', function(Request $request)
    {
        $email = $request->request->get('email');
        $password = $request->request->get('password');
        
        $employee_id = employee_authenticate($email, $password);
        $student_id = student_authenticate($email, $password);
    
        if ($employee_id != null)
        {
            return json_encode(token_create($employee_id, 'employee'));
        }
        else if ($student_id != null)
        {
            return json_encode(token_create($student_id, 'student'));
        }
        else
        {
            return new Response('Unauthorized', 401);
        }
    });

///////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////// R E G I S T E R ///////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////

    // Register Request
    $app->post('/register', function(Request $request)
    {
        $name = $request->request->get('name');
        $email = $request->request->get('email');
        $password = $request->request->get('password');
        $userType = $request->request->get('userType');
    
        if ($userType == 'employee')
        {
            employee_register($name, $email, $password);
            return new Response('Success', 200);
        }
        else if ($userType == 'student')
        {
            student_register($name, $email, $password);
            return new Response('Success', 200);
        }
        else
        {
            return new Response('Error', 404);
        }
    });

/////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////// E M P L O Y E E S ///////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////////

    $app->get('/employees', function(Request $request)
    {
        return json_encode(employees_index());
    });

    $app->get('/employees/{id}', function($id)
    {
        return json_encode(employees_show($id));
    });

    $app->post('/employees', function(Request $request)
    {
        $employee = new Employee();
        
        // Grab data from the request
        $employee->name = $request->request->get('name');
        $employee->email = $request->request->get('email');

        return json_encode(employees_add($employee));
    });

    $app->put('/employees/{id}', function(Request $request, $id)
    {
        $employee = new Employee();

        // Grab data from the request
        $employee->name = $request->request->get('name');
        $employee->email = $request->request->get('email');

        employees_edit($id, $employee);

        return '{"success":true}';
    });

    $app->delete('/employees/{id}', function($id)
    {
        employees_remove($id);

        return '{"success":true}';
    });

///////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////// S T U D E N T S ///////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////


    $app->get('/students', function(Request $request)
    {
        return json_encode(students_index());
    });

    $app->get('/students/{id}', function($id)
    {
        return json_encode(students_show($id));
    });

    $app->post('/students', function(Request $request)
    {
        $student = new Student();
        
        // Grab data from the request
        $student->name = $request->request->get('name');
        $student->email = $request->request->get('email');

        return json_encode(students_add($student));
    });

    $app->put('/students/{id}', function(Request $request, $id)
    {
        $student = new Student();

        // Grab data from the request
        $student->name = $request->request->get('name');
        $student->email = $request->request->get('email');

        students_edit($id, $student);

        return '{"success":true}';
    });

    $app->delete('/students/{id}', function($id)
    {
        students_remove($id);

        return '{"success":true}';
    });

/////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////// B O O K S ///////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////

    $app->get('/books', function(Request $request)
    {
        return json_encode(books_index());
    });

    $app->get('/books/{id}', function($id)
    {
        return json_encode(books_show($id));
    });

    $app->post('/books', function(Request $request)
    {
        $book = new Book();
        
        // Grab data from the request
        $book->title = $request->request->get('title');
        $book->author = $request->request->get('author');

        return json_encode(books_add($book));
    });

    $app->put('/books/{id}', function(Request $request, $id)
    {
        $book = new Book();

        // Grab data from the request
        $book->title = $request->request->get('title');
        $book->author = $request->request->get('author');

        books_edit($id, $book);

        return '{"success":true}';
    });

    $app->delete('/books/{id}', function($id)
    {
        books_remove($id);

        return '{"success":true}';
    });

///////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////// J O U R N A L S ///////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////

    $app->get('/journals', function(Request $request)
    {
        return json_encode(journals_index());
    });

    $app->get('/journals/{id}', function($id)
    {
        return json_encode(journals_show($id));
    });

    $app->post('/journals', function(Request $request)
    {
        $journal = new Journal();
        
        // Grab data from the request
        $journal->title = $request->request->get('title');
        $journal->type = $request->request->get('type');

        return json_encode(journals_add($journal));
    });

    $app->put('/journals/{id}', function(Request $request, $id)
    {
        $journal = new Journal();

        // Grab data from the request
        $journal->title = $request->request->get('title');
        $journal->type = $request->request->get('type');

        journals_edit($id, $journal);

        return '{"success":true}';
    });

    $app->delete('/journals/{id}', function($id)
    {
        journals_remove($id);

        return '{"success":true}';
    });

///////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////// P A P E R S ///////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////

    $app->get('/papers', function(Request $request)
    {
        return json_encode(papers_index());
    });

    $app->get('/papers/{id}', function($id)
    {
        return json_encode(papers_show($id));
    });

    $app->post('/papers', function(Request $request)
    {
        $paper = new Paper();
        
        // Grab data from the request
        $paper->subject = $request->request->get('subject');
        $paper->type = $request->request->get('type');

        return json_encode(papers_add($paper));
    });

    $app->put('/papers/{id}', function(Request $request, $id)
    {
        $paper = new Paper();

        // Grab data from the request
        $paper->subject = $request->request->get('subject');
        $paper->type = $request->request->get('type');

        papers_edit($id, $paper);

        return '{"success":true}';
    });

    $app->delete('/papers/{id}', function($id)
    {
        papers_remove($id);

        return '{"success":true}';
    });

///////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////// I M A G E S ///////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////


    $app->run();

?>