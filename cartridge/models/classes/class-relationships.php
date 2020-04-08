<?php

    //
    namespace Relationships;

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
        public function process_id($object='prc') {

            //
            $id = substr(md5(uniqid(microtime(true),true)),0,13);

            $id = $object.'_'.$id;

            //
            return $id;
            
            //exit;

        }
        
        //
        public function event_id($object='evt') {

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
    class Followship {

        //
        private $pdo;
    
        //
        public function __construct($pdo) {

            //
            $this->pdo = $pdo;

            //
            $this->token = new \Relationships\Token($this->pdo);

        }

        //
        public function insertFollowship($request) {

            //generate ID
            if(!isset($request['id'])){$request['id'] = $this->token->new_id('fol');}

            $columns = "";

            // INSERT OBJECT - COLUMNS
            if(isset($request['id'])){$columns.="followship_id,";}
            if(isset($request['attributes'])){$columns.="followship_attributes,";}
            if(isset($request['recipient'])){$columns.="followship_recipient,";}
            if(isset($request['sender'])){$columns.="followship_sender,";}
            if(isset($request['status'])){$columns.="followship_status,";}
            if(isset($request['profile'])){$columns.="profile_id,";}
                        
            $columns.= "app_id,";
            $columns.= "event_id,";
            $columns.= "process_id";

            $values = "";

            // INSERT OBJECT - VALUES
            if(isset($request['id'])){$values.=":followship_id,";}
            if(isset($request['attributes'])){$values.=":followship_attributes,";}
            if(isset($request['recipient'])){$values.=":followship_recipient,";}
            if(isset($request['sender'])){$values.=":followship_sender,";}
            if(isset($request['status'])){$values.=":followship_status,";}
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

            //echo $sql;
    
            //
            $statement = $this->pdo->prepare($sql);
            
            // INSERT OBJECT - BIND VALUES
            if(isset($request['id'])){$statement->bindValue('followship_id',$request['id']);}
            if(isset($request['attributes'])){$statement->bindValue('followship_attributes',$request['attributes']);}
            if(isset($request['recipient'])){$statement->bindValue('followship_recipient',$request['recipient']);}
            if(isset($request['sender'])){$statement->bindValue('followship_sender',$request['sender']);}
            if(isset($request['status'])){$statement->bindValue('followship_status',$request['status']);}
            if(isset($request['profile'])){$statement->bindValue('profile_id',$request['profile']);}

            $statement->bindValue(':app_id', $request['app']);
            $statement->bindValue(':event_id', $this->token->event_id());
            $statement->bindValue(':process_id', $this->token->process_id());
            
            // execute the insert statement
            $statement->execute();

            $data = $statement->fetchAll();
            
            $data = $data[0]['followship_id'];

            return $data;
        
        }

        //
        public function selectFollowships($request) {

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

                followship_ID,
                followship_attributes,
                followship_recipient,
                followship_sender,
                followship_status,
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
                    //if(isset($request['attributes'])){$refinements.="followship_attributes"." ILIKE "."'%".$request['attributes']."%' AND ";}
                    if(isset($request['recipient'])){$refinements.="followship_recipient"." = "."'".$request['recipient']."' AND ";}
                    if(isset($request['sender'])){$refinements.="followship_sender"." = "."'".$request['sender']."' AND ";}
                    if(isset($request['status'])){$refinements.="followship_status"." = "."'".$request['status']."' AND ";}
                    if(isset($request['profile'])){$refinements.="profile_id"." = "."'".$request['profile']."' AND ";}

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

                            'id' => $row['followship_id'],
                            'attributes' => json_decode($row['followship_attributes']),
                            'recipient' => $row['followship_recipient'],
                            'sender' => $row['followship_sender'],
                            'status' => $row['followship_status'],
                            'profile' => $row['profile_id'],
                            'app' => $row['app_id']

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
        public function updateFollowship($request) {

            //
            $domain = $request['domain'];
            $table = prefixed($domain);
            $id = $request['id'];

            //
            $set = "";

            // UPDATE OBJECT - SET
            // SKIP as ID won't be getting UPDATED
            if(isset($request['attributes'])){$set.= " followship_attributes = :followship_attributes ";}
            if(isset($request['recipient'])){$set.= " followship_recipient = :followship_recipient ";}
            if(isset($request['sender'])){$set.= " followship_sender = :followship_sender ";}
            if(isset($request['status'])){$set.= " followship_status = :followship_status ";}

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
            //if(isset($request['id'])){$statement->bindValue(':followship_id', $request['id']);}
            if(isset($request['attributes'])){$statement->bindValue(':followship_attributes', $request['attributes']);}
            if(isset($request['recipient'])){$statement->bindValue(':followship_recipient', $request['recipient']);}
            if(isset($request['sender'])){$statement->bindValue(':followship_sender', $request['sender']);}
            if(isset($request['status'])){$statement->bindValue(':followship_status', $request['status']);}
            //if(isset($request['profile_id'])){$statement->bindValue(':profile_id', $request['profile_id']);}

            $statement->bindValue(':id', $id);

            // update data in the database
            $statement->execute();

            $data = $statement->fetchAll();
            
            $data = $data[0]['followship_id'];

            // return generated id
            return $data;

        }

        //
        public function deleteFollowship($request) {

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

    //
    class Group {

        //
        private $pdo;
    
        //
        public function __construct($pdo) {

            //
            $this->pdo = $pdo;

            //
            $this->token = new \Relationships\Token($this->pdo);

        }

        //
        public function insertGroup($request) {

            //generate ID
            if(!isset($request['id'])){$request['id'] = $this->token->new_id('grp');}

            $columns = "";

            // INSERT OBJECT - COLUMNS
            if(isset($request['id'])){$columns.="group_id,";}
            if(isset($request['attributes'])){$columns.="group_attributes,";}
            if(isset($request['title'])){$columns.="group_title,";}
            if(isset($request['headline'])){$columns.="group_headline,";}
            if(isset($request['access'])){$columns.="group_access,";}
            if(isset($request['participants'])){$columns.="group_participants,";}
            if(isset($request['images'])){$columns.="group_images,";}
            if(isset($request['author'])){$columns.="group_author,";}
            if(isset($request['profile'])){$columns.="profile_id,";}
                                    
            $columns.= "app_id,";
            $columns.= "event_id,";
            $columns.= "process_id";

            $values = "";

            // INSERT OBJECT - VALUES
            if(isset($request['id'])){$values.=":group_id,";}
            if(isset($request['attributes'])){$values.=":group_attributes,";}
            if(isset($request['title'])){$values.=":group_title,";}
            if(isset($request['headline'])){$values.=":group_headline,";}
            if(isset($request['access'])){$values.=":group_access,";}
            if(isset($request['participants'])){$values.=":group_participants,";}
            if(isset($request['images'])){$values.=":group_images,";}
            if(isset($request['author'])){$values.=":group_author,";}
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

            //echo $sql;
    
            //
            $statement = $this->pdo->prepare($sql);
            
            // INSERT OBJECT - BIND VALUES
            if(isset($request['id'])){$statement->bindValue('group_id',$request['id']);}
            if(isset($request['attributes'])){$statement->bindValue('group_attributes',$request['attributes']);}
            if(isset($request['title'])){$statement->bindValue('group_title',$request['title']);}
            if(isset($request['headline'])){$statement->bindValue('group_headline',$request['headline']);}
            if(isset($request['access'])){$statement->bindValue('group_access',$request['access']);}
            if(isset($request['participants'])){$statement->bindValue('group_participants',$request['participants']);}
            if(isset($request['images'])){$statement->bindValue('group_images',$request['images']);}
            if(isset($request['author'])){$statement->bindValue('group_author',$request['author']);}
            if(isset($request['profile'])){$statement->bindValue('profile_id',$request['profile']);}

            $statement->bindValue(':app_id', $request['app']);
            $statement->bindValue(':event_id', $this->token->event_id());
            $statement->bindValue(':process_id', $this->token->process_id());
            
            // execute the insert statement
            $statement->execute();

            $data = $statement->fetchAll();
            
            $data = $data[0]['group_id'];

            return $data;
        
        }

        //
        public function selectGroups($request) {

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

                group_ID,
                group_attributes,
                group_title,
                group_headline,
                group_access,
                group_participants,
                group_images,
                group_author,
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
                    if(isset($request['attributes'])){$refinements.="group_attributes"." ILIKE "."'%".$request['attributes']."%' AND ";}
                    if(isset($request['title'])){$refinements.="group_title"." ILIKE "."'%".$request['title']."%' AND ";}
                    if(isset($request['headline'])){$refinements.="group_headline"." ILIKE "."'%".$request['headline']."%' AND ";}
                    if(isset($request['access'])){$refinements.="group_access"." = "."'".$request['access']."' AND ";}
                    //if(isset($request['participants'])){$refinements.="group_participants"." ILIKE "."'%".$request['participants']."%' AND ";}
                    if(isset($request['images'])){$refinements.="group_images"." = "."'".$request['images']."' AND ";}
                    if(isset($request['author'])){$refinements.="group_author"." = "."'".$request['author']."' AND ";}
                    if(isset($request['profile'])){$refinements.="profile_id"." = "."'".$request['profile']."' AND ";}

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

                            'id' => $row['group_id'],
                            'attributes' => json_decode($row['group_attributes']),
                            'title' => $row['group_title'],
                            'headline' => $row['group_headline'],
                            'access' => $row['group_access'],
                            'participants' => json_decode($row['group_participants']),
                            'images' => json_decode($row['group_images']),
                            'author' => $row['group_author'],
                            'profile_id' => $row['profile_id'],
                            'app_id' => $row['app_id'],

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
        public function updateGroup($request) {

            //
            $domain = $request['domain'];
            $table = prefixed($domain);
            $id = $request['id'];

            //
            $set = "";

            // UPDATE OBJECT - SET
            // SKIP as ID won't be getting UPDATED
            if(isset($request['attributes'])){$set.= " group_attributes = :group_attributes ";}
            if(isset($request['title'])){$set.= " group_title = :group_title ";}
            if(isset($request['headline'])){$set.= " group_headline = :group_headline ";}
            if(isset($request['access'])){$set.= " group_access = :group_access ";}
            if(isset($request['participants'])){$set.= " group_participants = :group_participants ";}
            if(isset($request['images'])){$set.= " group_images = :group_images ";}
            //if(isset($request['author'])){$set.= " group_author = :group_author ";}

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
            //if(isset($request['id'])){$statement->bindValue(':group_id', $request['id']);}
            if(isset($request['attributes'])){$statement->bindValue(':group_attributes', $request['attributes']);}
            if(isset($request['title'])){$statement->bindValue(':group_title', $request['title']);}
            if(isset($request['headline'])){$statement->bindValue(':group_headline', $request['headline']);}
            if(isset($request['access'])){$statement->bindValue(':group_access', $request['access']);}
            if(isset($request['participants'])){$statement->bindValue(':group_participants', $request['participants']);}
            if(isset($request['images'])){$statement->bindValue(':group_images', $request['images']);}
            //if(isset($request['author'])){$statement->bindValue(':group_author', $request['author']);}
            //if(isset($request['profile'])){$statement->bindValue(':profile_id', $request['profile']);}

            $statement->bindValue(':id', $id);

            // update data in the database
            $statement->execute();

            $data = $statement->fetchAll();
            
            $data = $data[0]['group_id'];

            // return generated id
            return $data;

        }

        //
        public function deleteGroup($request) {

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