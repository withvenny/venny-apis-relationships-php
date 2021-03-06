<?php

    //
    header('Content-Type: application/json');

    //
    use Relationships\Connection as Connection;
    use Relationships\Token as Token;
    use Relationships\Followship as Followship;

    // connect to the PostgreSQL database
    $pdo = Connection::get()->connect();

    // STEP 1. Receive passed variables / information
    if(isset($_REQUEST['app'])){$request['app'] = clean($_REQUEST['app']);}
    if(isset($_REQUEST['domain'])){$request['domain'] = clean($_REQUEST['domain']);}
    if(isset($_REQUEST['token'])){$request['token'] = clean($_REQUEST['token']);}

    // INITIATE DATA CLEANSE
    if(isset($_REQUEST['id'])){$request['id'] = clean($_REQUEST['id']);}
    if(isset($_REQUEST['attributes'])){$request['attributes'] = clean($_REQUEST['attributes']);}
    if(isset($_REQUEST['recipient'])){$request['recipient'] = clean($_REQUEST['recipient']);}
    if(isset($_REQUEST['sender'])){$request['sender'] = clean($_REQUEST['sender']);}
    if(isset($_REQUEST['status'])){$request['status'] = clean($_REQUEST['status']);}
    if(isset($_REQUEST['profile'])){$request['profile'] = clean($_REQUEST['profile']);}

    //
    switch ($_SERVER['REQUEST_METHOD']) {

        //
        case 'POST':

            try {

                // 
                $followship = new Followship($pdo);
            
                // insert a stock into the stocks table
                $id = $followship->insertFollowship($request);

                $request['id'] = $id;

                $results = $followship->selectFollowships($request);

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
                $followship = new Followship($pdo);

                // get all stocks data
                $results = $followship->selectFollowships($request);

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
                $followship = new Followship($pdo);
            
                // insert a stock into the stocks table
                $id = $followship->updateFollowship($request);

                $request['id'] = $id;

                $results = $followship->selectFollowships($request);

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
                $followship = new Followship($pdo);
            
                // insert a stock into the stocks table
                $id = $followship->deleteFollowship($request);

                echo 'The record ' . $id . ' has been deleted';
            
            } catch (\PDOException $e) {

                echo $e->getMessage();

            }

        break;

    }

?>
