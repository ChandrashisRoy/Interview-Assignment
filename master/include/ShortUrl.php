<?php

    //This class provides all of the functionality needed to create (encode) and decode shortened URLs.
    class ShortUrl
    {
        //to hold the characters used in building the short URL
        
        protected static $chars = "123456789bcdfghjkmnpqrstvwxyzBCDFGHJKLMNPQRSTVWXYZ";

        //to hold the name of the database table to use 
        
        protected static $table = "short_url";

        //to hold whether to connect to a URL to check if it exists

        protected static $checkUrlExists = true;

        //to hold a reference to a PDO object
        
        protected $pdo;

        //to hold the current timestamp
        
        protected $timestamp;

        // Constructor which Sets the class variable $pdo with a reference to a PDO object and the class variable $timestamp with the current timestamp.
        public function __construct(PDO $pdo) {
            $this->pdo = $pdo;
            $this->timestamp = $_SERVER["REQUEST_TIME"];
        }

        /**
         * Create a short code from a long URL.
         * @param string $url the long URL to be shortened
         * @return string the short code on success
         * @throws Exception if an error occurs
         */    
        public function urlToShortCode($url) {
            if (empty($url)) {
                throw new \Exception("No URL was supplied.");
            }

            if ($this->validateUrlFormat($url) == false) {
                throw new \Exception("URL does not have a valid format.");
            }

            if (self::$checkUrlExists) {
                if (!$this->verifyUrlExists($url)) {
                    throw new \Exception("URL does not appear to exist.");
                }
            }

            $shortCode = $this->urlExistsInDb($url);
            if ($shortCode == false) {
                $shortCode = $this->createShortCode($url);
            }

            return $shortCode;
        }

        /**
         * Retrieve a long URL from a short code.
         * @param string $code the short code associated with a long URL
         * @param boolean $increment whether to increment the record's counter
         * @return string the long URL
         * @throws Exception if an error occurs
         */
        public function shortCodeToUrl($code, $increment = true) {
            if (empty($code)) {
                throw new \Exception("No short code was supplied.");
            }

            if ($this->validateShortCode($code) == false) {
                throw new \Exception(
                    "Short code does not have a valid format.");
            }

            $urlRow = $this->getUrlFromDb($code);
            if (empty($urlRow)) {
                throw new \Exception(
                    "Short code does not appear to exist.");
            }

            if ($increment == true) {
                $this->incrementCounter($urlRow["id"]);
            }

            return $urlRow["long_url"];
        }

        /**
         * Check to see if the supplied URL is a valid format
         * @param string $url the long URL
         * @return boolean whether URL is a valid format
         */
        protected function validateUrlFormat($url) {
            return filter_var($url, FILTER_VALIDATE_URL, FILTER_FLAG_HOST_REQUIRED);
        }

        /* Check to see if the URL exists
         * @param string $url the long URL
         * @return boolean whether the URL does not return a 404 code
         */
        protected function verifyUrlExists($url) {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_NOBODY, true);
            curl_setopt($ch,  CURLOPT_RETURNTRANSFER, true);
            curl_exec($ch);
            $response = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);

            return (!empty($response) && $response != 404);
        }

        /**
         * Check the database for the long URL.  
         * @param string $url the long URL
         * @return string|boolean the short code if it exists - false if it does not
         * @throws PDOException if a database exception occurs
         */
        protected function urlExistsInDb($url) {
            $query = "SELECT short_code FROM " . self::$table .
                " WHERE long_url = :long_url LIMIT 1";
            $stmt = $this->pdo->prepare($query);
            $params = array(
                "long_url" => $url
            );
            $stmt->execute($params);

            $result = $stmt->fetch();
            return (empty($result)) ? false : $result["short_code"];
        }

        /**
         * Delegates creating a short code from a long URL.
         * @param string $url the long URL
         * @return string the created short code
         * @throws Exception if an error occurs 
         */
        protected function createShortCode($url) {
            $id = $this->insertUrlInDb($url);
            $shortCode = $this->convertIntToShortCode($id);    
            $this->insertShortCodeInDb($id, $shortCode);
            return $shortCode;
        }
        
        /**
         * Inserts a new row into the database.
         * @param string $url the long URL
         * @return integer on success the integer of the newly inserted  row
         * @throws PDOException if an error occurs
         */
        protected function insertUrlInDb($url) {
            $query = "INSERT INTO " . self::$table .
                " (long_url, date_created) " .
                " VALUES (:long_url, :timestamp)";
            $stmnt = $this->pdo->prepare($query);
            $params = array(
                "long_url" => $url,
                "timestamp" => $this->timestamp
            );
            $stmnt->execute($params);

            return $this->pdo->lastInsertId();
        }

        /**
         * Convert an integer to a short code.
         * @param int $id the integer to be converted
         * @return string the created short code
         * @throws Exception if an error occurs
         */
        protected function convertIntToShortCode($id) {
            $id = intval($id);
            if ($id < 1) {
                throw new \Exception(
                    "The ID is not a valid integer");
            }

            $length = strlen(self::$chars);
            // make sure length of available characters is at least a reasonable minimum - there should be at least 10 characters
            if ($length < 10) {
                throw new \Exception("Length of chars is too small");
            }

            $code = "";
            while ($id > $length - 1) {
                // determine the value of the next higher character in the short code should be and prepend
                $code = self::$chars[fmod($id, $length)] . 
                    $code;
                // reset $id to remaining value to be converted
                $id = floor($id / $length);
            }

            // remaining value of $id is less than the length of self::$chars
            $code = self::$chars[$id] . $code;

            return $code;
        }

        /**
         * Updates the database row with the short code.
         * @param int $id the ID of the database row to update
         * @param string $code the short code to associate with the row
         * @return boolean on success
         * @throws Exception if an error occurs
         */
        protected function insertShortCodeInDb($id, $code) {
            if ($id == null || $code == null) {
                throw new \Exception("Input parameter(s) invalid.");
            }
            $query = "UPDATE " . self::$table .
                " SET short_code = :short_code WHERE id = :id";
            $stmnt = $this->pdo->prepare($query);
            $params = array(
                "short_code" => $code,
                "id" => $id
            );
            $stmnt->execute($params);

            if ($stmnt->rowCount() < 1) {
                throw new \Exception(
                    "Row was not updated with short code.");
            }

            return true;
        }

        /**
         * Check to see if the supplied short code is a valid format
         * @param string $code the short code
         * @return boolean whether the short code is a valid format
         */
        protected function validateShortCode($code) {
            return preg_match("|[" . self::$chars . "]+|", $code);
        }

        /**
         * Get the long URL from the database.
         * @param string $code the short code to look for in the database
         * @return string|boolean the long URL or false if it does not exist
         * @throws PDOException if an error occurs
         */
        protected function getUrlFromDb($code) {
            $query = "SELECT id, long_url FROM " . self::$table .
                " WHERE short_code = :short_code LIMIT 1";
            $stmt = $this->pdo->prepare($query);
            $params=array(
                "short_code" => $code
            );
            $stmt->execute($params);  

            $result = $stmt->fetch();
            return (empty($result)) ? false : $result;
        }

        /**
         * Increment the record's access count.
         * @param integer $id the ID of the row to increment
         * @throws PDOException if an error occurs
         */
        protected function incrementCounter($id) {
            $query = "UPDATE " . self::$table .
                " SET counter = counter + 1 WHERE id = :id";
            $stmt = $this->pdo->prepare($query);
            $params = array(
                "id" => $id
            );
            $stmt->execute($params);  
        }
    }
