<?php

    namespace _Database;

    class Database {
        
        private const Host = "localhost", User = "root", Psw = "", Database = "";
    
        private $connection;
    
        // Apri Connessione col DB
        public function ConnectToDb() {
            $this->connection = mysqli_connect(Database::Host, Database::User, Database::Psw, Database::Database);
    
            if(!$this->connection) {
                return false;
            } else {
                return true;
            }
        }
    
        // Chiudi Connessione col DB
        public function CloseConnection() {
            if($this->connection)
                mysqli_close($this->connection);
        }
    

        public function LoginMatch($Mail, $Cypher) {
           
            $sql = "SELECT *
                    FROM Credentials
                    WHERE EMail = '$Mail' AND Passwrd = '$Cypher'";

            $QResult = mysqli_query($this->connection, $sql);

            if(mysqli_num_rows($QResult) == 1) {
                $user = mysqli_fetch_assoc($QResult);
                return $user["Code_User"];
            }
            return false;
        }

    }

?>