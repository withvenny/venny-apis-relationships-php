<?php

    //
    namespace Posts;

    //
    class Connection {
    
        /**
         * Connection
         * @var type 
         */
        private static $conn;
    
        /**
         * Connect to the database and return an instance of \PDO object
         * @return \PDO
         * @throws \Exception
         */
        public function connect() {

            // read parameters in the ini configuration file
            //$params = parse_ini_file('database.ini');
            $db = parse_url(getenv("DATABASE_URL"));

            //if ($params === false) {throw new \Exception("Error reading database configuration file");}
            if ($db === false) {throw new \Exception("Error reading database configuration file");}
            // connect to the postgresql database
            $conStr = sprintf("pgsql:host=%s;port=%d;dbname=%s;user=%s;password=%s", 
                    $db['host'],
                    $db['port'], 
                    ltrim($db["path"], "/"), 
                    $db['user'], 
                    $db['pass']);
    
            $pdo = new \PDO($conStr);
            $pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
    
            return $pdo;
        }
    
        /**
         * return an instance of the Connection object
         * @return type
         */
        public static function get() {
            if (null === static::$conn) {
                static::$conn = new static();
            }
    
            return static::$conn;
        }
    
        protected function __construct() {
            
        }
    
        private function __clone() {
            
        }
    
        private function __wakeup() {
            
        }
    
    }

    //
    class Token {

        /**
         * PDO object
         * @var \PDO
         */
        private $pdo;
    
        /**
         * init the object with a \PDO object
         * @param type $pdo
         */
        public function __construct($pdo) {
            $this->pdo = $pdo;
        }

        /**
         * Return all rows in the stocks table
         * @return array
         */
        public function all() {
            $stmt = $this->pdo->query('SELECT id, symbol, company '
                    . 'FROM stocks '
                    . 'ORDER BY symbol');
            $stocks = [];
            while ($row = $stmt->fetch(\PDO::FETCH_ASSOC)) {
                $stocks[] = [
                    'id' => $row['id'],
                    'symbol' => $row['symbol'],
                    'company' => $row['company']
                ];
            }
            return $stocks;
        }

        //
        public function validatedToken() {
            
            //
            return true;
            
            //exit;

        }

        //
        public function process_id($object='obj') {

            //
            $id = substr(md5(uniqid(microtime(true),true)),0,13);

            $id = $object.'_'.$id;

            //
            return $id;
            
            //exit;

        }
        
        //
        public function event_id($object='obj') {

            //
            $id = substr(md5(uniqid(microtime(true),true)),0,13);

            $id = $object.'_'.$id;
    
            //
            return $id;
            
            //exit;

        }

        //
        public function new_id($object='obj') {

            //
            $id = substr(md5(uniqid(microtime(true),true)),0,13);
            $id = $object . "_" . $id;
    
            //
            return $id;
            
            //exit;

        }

        /**
         * Find stock by id
         * @param int $id
         * @return a stock object
         */
        public function check($id) {

            //
            $sql = "SELECT message_id FROM messages WHERE id = :id AND active = 1";

            // prepare SELECT statement
            $statement = $this->pdo->prepare($sql);
            // bind value to the :id parameter
            $statement->bindValue(':id', $id);
            
            // execute the statement
            $stmt->execute();
    
            // return the result set as an object
            return $stmt->fetchObject();
        }

        /**
         * Delete a row in the stocks table specified by id
         * @param int $id
         * @return the number row deleted
         */
        public function delete($id) {
            $sql = 'DELETE FROM stocks WHERE id = :id';
    
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindValue(':id', $id);
    
            $stmt->execute();
    
            return $stmt->rowCount();
        }

        /**
         * Delete all rows in the stocks table
         * @return int the number of rows deleted
         */
        public function deleteAll() {
    
            $stmt = $this->pdo->prepare('DELETE FROM stocks');
            $stmt->execute();
            return $stmt->rowCount();
        }

    }

    //
    class Post {

        //
        private $pdo;
    
        //
        public function __construct($pdo) {

            //
            $this->pdo = $pdo;

            //
            $this->token = new \Posts\Token($this->pdo);

        }

        //
        public function insertPost($request) {

            //generate ID
            if(!isset($request['id'])){$request['id'] = $this->token->new_id('pst');}

            $columns = "";

            // INSERT OBJECT - COLUMNS
            if(isset($_REQUEST['id'])){$request['id'] = clean($_REQUEST['id']);}
            if(isset($_REQUEST['attributes'])){$request['attributes'] = clean($_REQUEST['attributes']);}
            if(isset($_REQUEST['body'])){$request['body'] = clean($_REQUEST['body']);}
            if(isset($_REQUEST['images'])){$request['images'] = clean($_REQUEST['images']);}
            if(isset($_REQUEST['closed'])){$request['closed'] = clean($_REQUEST['closed']);}
            if(isset($_REQUEST['deleted'])){$request['deleted'] = clean($_REQUEST['deleted']);}
            if(isset($_REQUEST['access'])){$request['access'] = clean($_REQUEST['access']);}
            if(isset($_REQUEST['host'])){$request['host'] = clean($_REQUEST['host']);}
            if(isset($_REQUEST['profile'])){$request['profile'] = clean($_REQUEST['profile']);}

            $columns.= "app_id,";
            $columns.= "event_id,";
            $columns.= "process_id";

            $values = "";

            // INSERT OBJECT - VALUES
            if(isset($request['id'])){$values.=":post_id,";}
            if(isset($request['attributes'])){$values.=":post_attributes,";}
            if(isset($request['body'])){$values.=":post_body,";}
            if(isset($request['images'])){$values.=":post_images,";}
            if(isset($request['closed'])){$values.=":post_closed,";}
            if(isset($request['deleted'])){$values.=":post_deleted,";}
            if(isset($request['access'])){$values.=":post_access,";}
            if(isset($request['host'])){$values.=":post_host,";}
            if(isset($request['profile'])){$values.=":profile_id,";}

            $values.= ":app_id,";
            $values.= ":event_id,";
            $values.= ":process_id";

            // prepare statement for insert
            $sql = "INSERT INTO {$request['domain']} (";
            $sql.= $columns;
            $sql.= ") VALUES (";
            $sql.= $values;
            $sql.= ")";
            $sql.= " RETURNING " . prefixed($request['domain']) . "_id";
    
            //
            $statement = $this->pdo->prepare($sql);
            
            // INSERT OBJECT - BIND VALUES
            if(isset($request['id'])){$statement->bindValue('post_id',$request['id']);}
            if(isset($request['attributes'])){$statement->bindValue('post_attributes',$request['attributes']);}
            if(isset($request['body'])){$statement->bindValue('post_body',$request['body']);}
            if(isset($request['images'])){$statement->bindValue('post_images',$request['images']);}
            if(isset($request['closed'])){$statement->bindValue('post_closed',$request['closed']);}
            if(isset($request['deleted'])){$statement->bindValue('post_deleted',$request['deleted']);}
            if(isset($request['access'])){$statement->bindValue('post_access',$request['access']);}
            if(isset($request['host'])){$statement->bindValue('post_host',$request['host']);}
            if(isset($request['profile'])){$statement->bindValue('profile_id',$request['profile']);}
 
            $statement->bindValue(':app_id', $request['app']);
            $statement->bindValue(':event_id', $this->token->event_id());
            $statement->bindValue(':process_id', $this->token->process_id());
            
            // execute the insert statement
            $statement->execute();

            $data = $statement->fetchAll();
            
            $data = $data[0]['post_id'];

            return $data;
        
        }

        //
        public function selectPosts($request) {

            //echo json_encode($request); exit;

            //$token = new \Core\Token($this->pdo);
            $token = $this->token->validatedToken($request['token']);

            // Retrieve data ONLY if token  
            if($token) {
                
                // domain, app always present
                if(!isset($request['per'])){$request['per']=20;}
                if(!isset($request['page'])){$request['page']=1;}
                if(!isset($request['limit'])){$request['limit']=100;}

                //
                $conditions = "";
                $domain = $request['domain'];
                $prefix = prefixed($domain);

                // SELECT OBJECT - COLUMNS
                $columns = "

                post_ID,		
                post_attributes,		
                post_body,		
                post_images,		
                post_closed,		
                post_deleted,		
                post_access,		
                post_host,
                profile_ID,
                app_ID

                ";

                $table = $domain;

                //
                $start = 0;

                //
                if(isset($request['page'])) {

                    //
                    $start = ($request['page'] - 1) * $request['per'];
                
                }

                //
                if(!empty($request['id'])) {

                    $conditions.= ' WHERE ';
                    $conditions.= ' ' . $prefix . '_id = :id ';
                    $conditions.= ' AND active = 1 ';
                    $conditions.= ' ORDER BY time_finished DESC ';

                    $subset = " LIMIT 1";

                    $sql = "SELECT ";
                    $sql.= $columns;
                    $sql.= " FROM " . $table;
                    $sql.= $conditions;
                    $sql.= $subset;
                    
                    //echo json_encode($request['id']);
                    //echo '<br/>';
                    //echo $sql; exit;

                    //
                    $statement = $this->pdo->prepare($sql);

                    // bind value to the :id parameter
                    $statement->bindValue(':id', $request['id']);

                    //echo $sql; exit;

                } else {

                    $conditions = "";
                    $refinements = "";

                    // SELECT OBJECT - WHERE CLAUSES
                    // SKIP ID		
                    if(isset($request['body'])){$refinements.="post_body"." ILIKE "."'%".$request['body']."%' AND ";}		
                    if(isset($request['closed'])){$refinements.="post_closed"." = "."'".$request['closed']."' AND ";}		
                    if(isset($request['deleted'])){$refinements.="post_deleted"." = "."'".$request['deleted']."' AND ";}		
                    if(isset($request['access'])){$refinements.="post_access"." = "."'".$request['access']."' AND ";}		
                    if(isset($request['host'])){$refinements.="post_host"." = "."'".$request['host']."' AND ";}		

                    //echo $conditions . 'conditions1<br/>';
                    //echo $refinements . 'refinements1<br/>';
                    
                    $conditions.= " WHERE ";
                    $conditions.= $refinements;
                    $conditions.= " active = 1 ";
                    $conditions.= ' AND app_id = \'' . $request['app'] . '\' ';
                    $conditions.= ' AND profile_id = \'' . $request['profile'] . '\' ';
                    $conditions.= " ORDER BY time_finished DESC ";
                    $subset = " OFFSET {$start}" . " LIMIT {$request['per']}";
                    $sql = "SELECT ";
                    $sql.= $columns;
                    $sql.= "FROM " . $table;
                    $sql.= $conditions;
                    $sql.= $subset;

                    //echo $conditions . 'conditions2<br/>';
                    //echo $refinements . 'refinements2<br/>';

                    //echo $sql; exit;
                    
                    //
                    $statement = $this->pdo->prepare($sql);

                }
                    
                // execute the statement
                $statement->execute();

                //
                $results = [];
                $total = $statement->rowCount();
                $pages = ceil($total/$request['per']); //
                //$current = 1; // current page
                //$limit = $result['limit'];
                //$max = $result['max'];

                //
                if($statement->rowCount() > 0) {

                    //
                    $data = array();
                
                    //
                    while($row = $statement->fetch(\PDO::FETCH_ASSOC)) {
        
                        //
                        $data[] = [

                            'id' => $row['post_id'],
                            'attributes' => json_decode($row['post_attributes']),
                            'body' => $row['post_body'],
                            'images' => json_decode($row['post_images']),
                            'closed' => $row['post_closed'],
                            'deleted' => $row['post_deleted'],
                            'access' => $row['post_access'],
                            'host' => $row['post_host'],
                            'profile' => $row['profile_id']

                        ];

                    }

                    $code = 200;
                    $message = "OK";

                } else {

                    //
                    $data = NULL;
                    $code = 204;
                    $message = "No Content";

                }

            } else {

                //
                $data[] = NULL;
                $code = 401;
                $message = "Forbidden - Valid token required";

            }

            $results = array(

                'status' => $code,
                'message' => $message,
                'metadata' => [
                    'page' => $request['page'],
                    'pages' => $pages,
                    'total' => $total
                ],
                'data' => $data,
                'log' => [
                    'process' => $process_id = $this->token->process_id(),
                    'event' => $event_id = $this->token->event_id($process_id)
                ]

            );

            //
            return $results;

        }

        //
        public function updatePost($request) {

            //
            $domain = $request['domain'];
            $table = prefixed($domain);
            $id = $request['id'];

            //
            $set = "";

            // UPDATE OBJECT - SET
            //if(isset($request['id'])){$set.= " thread_id = :thread_id ";}
            if(isset($request['attributes'])){$set.= " thread_attributes = :thread_attributes ";}
            if(isset($request['title'])){$set.= " thread_title = :thread_title ";}
            if(isset($request['participants'])){$set.= " thread_participants = :thread_participants ";}
            if(isset($request['preview'])){$set.= " thread_preview = :thread_preview ";}
            //if(isset($request['profile'])){$set.= " profile_id = :profile_id ";}

            //
            $set = str_replace('  ',',',$set);

            // GET table name
            $condition = $table."_id = :id";
            $condition.= " RETURNING " . $table . "_id";

            // sql statement to update a row in the stock table
            $sql = "UPDATE {$domain} SET ";
            $sql.= $set;
            $sql.= " WHERE ";
            $sql.= $condition;

            //echo $sql; exit;

            $statement = $this->pdo->prepare($sql);
    
            // UPDATE OBJECT - BIND VALUES
            //if(isset($request['id'])){$statement->bindValue(':thread_id', $request['id']);}
            if(isset($request['attributes'])){$statement->bindValue(':thread_attributes', $request['attributes']);}
            if(isset($request['title'])){$statement->bindValue(':thread_title', $request['title']);}
            if(isset($request['participants'])){$statement->bindValue(':thread_participants', $request['participants']);}
            if(isset($request['preview'])){$statement->bindValue(':thread_preview', $request['preview']);}
            //if(isset($request['profile'])){$statement->bindValue(':profile_id', $request['profile']);}
            //if(isset($request['app'])){$statement->bindValue(':app_id', $request['app']);}

            $statement->bindValue(':id', $id);

            // update data in the database
            $statement->execute();

            $data = $statement->fetchAll();
            
            $data = $data[0]['thread_id'];

            // return generated id
            return $data;

        }

        //
        public function deletePost($request) {

            $id = $request['id'];
            $domain = $request['domain'];
            $column = prefixed($domain) . '_id';
            $sql = 'DELETE FROM ' . $domain . ' WHERE '.$column.' = :id';
            //echo $id; //exit
            //echo $column; //exit;
            //echo $domain; //exit;
            //echo $sql; //exit

            $statement = $this->pdo->prepare($sql);
            //$statement->bindParam(':column', $column);
            $statement->bindValue(':id', $id);
            $statement->execute();
            return $statement->rowCount();

        }

    }
    
?>