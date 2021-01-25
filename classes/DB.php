<?php
class DB 
{
    private static $host       = 'localhost';
    private static $port       = '3306';
    private static $db         = 'socialclone';
    private static $username   = 'root';
    private static $password   = '';
    private static $charset    = 'utf8mb4';

    public static function conn() {
        $dsn = "mysql:host=".self::$host.";port=".self::$port.";dbname=".self::$db.";charset=".self::$charset;

        $pdo = new PDO($dsn, self::$username, self::$password, [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
         PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]);

        return $pdo;
    }

	public static function fetch($query, $parameters = []){
        try
        {   

			$query = self::conn()->prepare($query);
			$query->execute($parameters);
			return $query->fetch();
		}
        catch(PDOException $e)
        {
			return $e->getMessage();
		}
    }
    
    
	public static function fetchAll($query, $parameters = []){
        try
        {   

			$query = self::conn()->prepare($query);
			$query->execute($parameters);
			return $query->fetchAll();
		}
        catch(PDOException $e)
        {
			return $e->getMessage();
		}
    }
    
    public static function count($query, $parameters = []){
        try
        {   

			$query = self::conn()->prepare($query);
			$query->execute($parameters);
			return $query->rowCount();
		}
        catch(PDOException $e)
        {
			return $e->getMessage();
		}
    }
    
    public static function insert($query, $parameters = []){
        try
        {   

			$query = self::conn()->prepare($query);
			$query->execute($parameters);
			return true;
		}
        catch(PDOException $e)
        {
			return $e->getMessage();
		}
    }
    
    public static function update($query, $parameters = []){
        try
        {   
			return self::insert($query, $parameters);
		}
        catch(PDOException $e)
        {
			return $e->getMessage();
		}
    }
    
    public static function delete($query, $parameters = []){
        try
        {   
			return self::insert($query, $parameters);
		}
        catch(PDOException $e)
        {
			return $e->getMessage();
		}
	}
}