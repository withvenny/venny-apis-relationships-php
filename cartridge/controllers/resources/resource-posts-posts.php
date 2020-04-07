<?php

    //
    header('Content-Type: application/json');

    //
    use Posts\Connection as Connection;
    use Posts\Token as Token;
    use Posts\Post as Post;

    // connect to the PostgreSQL database
    $pdo = Connection::get()->connect();

    // STEP 1. Receive passed variables / information
    if(isset($_REQUEST['app'])){$request['app'] = clean($_REQUEST['app']);}
    if(isset($_REQUEST['domain'])){$request['domain'] = clean($_REQUEST['domain']);}
    if(isset($_REQUEST['token'])){$request['token'] = clean($_REQUEST['token']);}

    // INITIATE DATA CLEANSE
    if(isset($_REQUEST['id'])){$request['id'] = clean($_REQUEST['id']);}		
    if(isset($_REQUEST['attributes'])){$request['attributes'] = clean($_REQUEST['attributes']);}		
    if(isset($_REQUEST['body'])){$request['body'] = clean($_REQUEST['body']);}		
    if(isset($_REQUEST['images'])){$request['images'] = clean($_REQUEST['images']);}		
    if(isset($_REQUEST['deleted'])){$request['deleted'] = clean($_REQUEST['deleted']);}
    if(isset($_REQUEST['thread'])){$request['thread'] = clean($_REQUEST['thread']);}
    if(isset($_REQUEST['profile'])){$request['profile'] = clean($_REQUEST['profile']);}

    //
    switch ($_SERVER['REQUEST_METHOD']) {

        //
        case 'POST':

            try {

                // 
                $message = new Message($pdo);
            
                // insert a stock into the stocks table
                $id = $message->insertMessage($request);

                $request['id'] = $id;

                $results = $message->selectMessages($request);

                $results = json_encode($results);
                
                //
                echo $results;
            
            } catch (\PDOException $e) {

                echo $e->getMessage();

            }

        break;

        //
        case 'GET':

            //
            if(isset($_REQUEST['per'])){$request['per'] = clean($_REQUEST['per']);}
            if(isset($_REQUEST['page'])){$request['page'] = clean($_REQUEST['page']);}
            if(isset($_REQUEST['limit'])){$request['limit'] = clean($_REQUEST['limit']);}        

            try {

                // 
                $message = new Message($pdo);

                // get all stocks data
                $results = $message->selectMessages($request);

                $results = json_encode($results);

                echo $results;

            } catch (\PDOException $e) {

                echo $e->getMessage();

            }

        break;

        //
        case 'PUT':

            try {

                // 
                $message = new Message($pdo);
            
                // insert a stock into the stocks table
                $id = $message->updateMessage($request);

                $request['id'] = $id;

                $results = $message->selectMessages($request);

                $results = json_encode($results);

                echo $results;
            
            } catch (\PDOException $e) {

                echo $e->getMessage();

            }

        break;

        //
        case 'DELETE':

            try {

                // 
                $message = new Message($pdo);
            
                // insert a stock into the stocks table
                $id = $message->deleteMessage($request);

                echo 'The record ' . $id . ' has been deleted';
            
            } catch (\PDOException $e) {

                echo $e->getMessage();

            }

        break;

    }

?>
