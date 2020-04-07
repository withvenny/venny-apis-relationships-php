<?php

    //
    header('Content-Type: application/json');

    //
    use Reactions\Connection as Connection;
    use Reactions\Token as Token;
    use Reactions\Acknowledgement as Acknowledgement;

    // connect to the PostgreSQL database
    $pdo = Connection::get()->connect();

    // STEP 1. Receive passed variables / information
    if(isset($_REQUEST['app'])){$request['app'] = clean($_REQUEST['app']);}
    if(isset($_REQUEST['domain'])){$request['domain'] = clean($_REQUEST['domain']);}
    if(isset($_REQUEST['token'])){$request['token'] = clean($_REQUEST['token']);}

    // INITIATE DATA CLEANSE
    if(isset($_REQUEST['id'])){$request['id'] = clean($_REQUEST['id']);}
    if(isset($_REQUEST['attributes'])){$request['attributes'] = clean($_REQUEST['attributes']);}
    if(isset($_REQUEST['type'])){$request['type'] = clean($_REQUEST['type']);}
    if(isset($_REQUEST['object'])){$request['object'] = clean($_REQUEST['object']);}
    if(isset($_REQUEST['profile'])){$request['profile'] = clean($_REQUEST['profile']);}
    
    //
    switch ($_SERVER['REQUEST_METHOD']) {

        //
        case 'POST':

            try {

                // 
                $acknowledgement = new Acknowledgement($pdo);
            
                // insert a stock into the stocks table
                $id = $acknowledgement->insertAcknowledgement($request);

                $request['id'] = $id;

                $results = $acknowledgement->selectAcknowledgements($request);

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
                $acknowledgement = new Acknowledgement($pdo);

                // get all stocks data
                $results = $acknowledgement->selectAcknowledgements($request);

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
                $acknowledgement = new Acknowledgement($pdo);
            
                // insert a stock into the stocks table
                $id = $acknowledgement->updateAcknowledgement($request);

                $request['id'] = $id;

                $results = $acknowledgement->selectAcknowledgements($request);

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
                $acknowledgement = new Acknowledgement($pdo);
            
                // insert a stock into the stocks table
                $id = $acknowledgement->deleteAcknowledgement($request);

                echo 'The record ' . $id . ' has been deleted';
            
            } catch (\PDOException $e) {

                echo $e->getMessage();

            }

        break;

    }

?>
