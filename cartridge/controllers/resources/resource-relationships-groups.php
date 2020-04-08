<?php

    //
    header('Content-Type: application/json');

    //
    use Relationships\Connection as Connection;
    use Relationships\Token as Token;
    use Relationships\Group as Group;

    // connect to the PostgreSQL database
    $pdo = Connection::get()->connect();

    // STEP 1. Receive passed variables / information
    if(isset($_REQUEST['app'])){$request['app'] = clean($_REQUEST['app']);}
    if(isset($_REQUEST['domain'])){$request['domain'] = clean($_REQUEST['domain']);}
    if(isset($_REQUEST['token'])){$request['token'] = clean($_REQUEST['token']);}

    // INITIATE DATA CLEANSE
    if(isset($_REQUEST['id'])){$request['id'] = clean($_REQUEST['id']);}
    if(isset($_REQUEST['attributes'])){$request['attributes'] = clean($_REQUEST['attributes']);}
    if(isset($_REQUEST['title'])){$request['title'] = clean($_REQUEST['title']);}
    if(isset($_REQUEST['headline'])){$request['headline'] = clean($_REQUEST['headline']);}
    if(isset($_REQUEST['access'])){$request['access'] = clean($_REQUEST['access']);}
    if(isset($_REQUEST['participants'])){$request['participants'] = clean($_REQUEST['participants']);}
    if(isset($_REQUEST['images'])){$request['images'] = clean($_REQUEST['images']);}
    if(isset($_REQUEST['author'])){$request['author'] = clean($_REQUEST['author']);}
    if(isset($_REQUEST['profile'])){$request['profile'] = clean($_REQUEST['profile']);}

    //
    switch ($_SERVER['REQUEST_METHOD']) {

        //
        case 'POST':

            try {

                // 
                $group = new Group($pdo);
            
                // insert a stock into the stocks table
                $id = $group->insertGroup($request);

                $request['id'] = $id;

                $results = $group->selectGroups($request);

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
                $group = new Group($pdo);

                // get all stocks data
                $results = $group->selectGroups($request);

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
                $group = new Group($pdo);
            
                // insert a stock into the stocks table
                $id = $group->updateGroup($request);

                $request['id'] = $id;

                $results = $group->selectGroups($request);

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
                $group = new Group($pdo);
            
                // insert a stock into the stocks table
                $id = $group->deleteGroup($request);

                echo 'The record ' . $id . ' has been deleted';
            
            } catch (\PDOException $e) {

                echo $e->getMessage();

            }

        break;

    }

?>
