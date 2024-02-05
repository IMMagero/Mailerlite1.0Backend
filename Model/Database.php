<?php
class Database
{
    protected $connection = null;
    public function __construct()
    {
        try {
            $this->connection = new mysqli(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_DATABASE_NAME);
    	
            if ( mysqli_connect_errno()) {
                throw new Exception("Could not connect to database.");   
            }
        } catch (Exception $e) {
            throw new Exception($e->getMessage());   
        }			
    }
    public function select($query = "" , $params = [])
    {
        try {
            $stmt = $this->executeStatement( $query , $params );
            $result = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);				
            $stmt->close();
            return $result;
        } catch(Exception $e) {
            throw New Exception( $e->getMessage() );
        }
        return false;
    }

    public function insert($data)
    {            
        try
         {
            //Check if user exist by email else create the user
            $query = 'SELECT * FROM user where email_address = ? LIMIT 1';
            $stmt = $this->connection->prepare($query);
            $stmt->bind_param("s",
            $data['email_address']);
            $stmt->execute();
            $user = $stmt->fetch();
            
            if($user){

                return "User already Exists!";

            }else {
                $query  = 'INSERT INTO user (name, last_name, email_address, status, user_type) VALUES (?,?,?,?,?)';
                $stmt = $this->connection->prepare($query);
                /* Bind variables to parameters */
                $stmt->bind_param("sssss",
                 $data['name'],
                 $data['last_name'], 
                 $data['email_address'],
                 $data['status'],
                 $data['user_type']);
                $stmt->execute();
                
                return 'User created successfully!';
            }
            
        } catch(Exception $e) {
            throw New Exception( $e->getMessage());
        }
        
    }
    private function executeStatement($query = "" , $params = [])
    {
        try {
            $stmt = $this->connection->prepare( $query );
            if($stmt === false) {
                throw New Exception("Unable to do prepared statement: " . $query);
            }
            if( $params ) {
                $stmt->bind_param($params[0], $params[1]);
            }
            $stmt->execute();
            return $stmt;
        } catch(Exception $e) {
            throw New Exception( $e->getMessage() );
        }	
    }

    public function findSubscriberbyEmail($data)
    {
        try
        {
            //Find user by email
            $query = 'SELECT * FROM user where email_address = ? LIMIT 1';
            $stmt = $this->connection->prepare($query);
            $stmt->execute([$data]); 
            $user = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
            $result = (empty($user)) ? 'User Not Found!' : $user;

            return $result;

        } catch(Exception $e) {
            throw New Exception( $e->getMessage() );
        }

    }
}