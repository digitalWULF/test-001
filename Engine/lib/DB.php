<?php
/**
 * Created by IntelliJ IDEA.
 * User: dw01f
 * Date: 25.09.18
 * Time: 12:30
 */

namespace Engine\lib;

use \PDO as DBDriver;

class DB extends DBDriver
{

    /**
     * класс адаптер управления соединением с БД хранения
     * Class engineDB
     * @package Engine\lib
     */

    use SingletonTrait;

    private $db_conf;


    public function instance()
    {

        $this->db_conf = [
            'host'    => 'localhost',
            'user'    => 'mediatech_test',
            'db'      => 'mediatech_test',
            'passwd'  => 'B8BTeV5BhXrcmE8z',
            'options' => []
        ];

        try {
            parent::__construct($this->getMysqlDSN(), $this->db_conf['user'], $this->db_conf['passwd'], $this->db_conf['options']);
            $this->setAttribute(DBDriver::ATTR_ERRMODE, DBDriver::ERRMODE_EXCEPTION);
        } catch (\PDOException $e) {
            throw new \Exception('Не могу соединиться с базой данных. Код ошибки: ' . $e->getCode());
        }
    }

    private function getMysqlDSN(){
        return 'mysql:host='.$this->db_conf['host'].';dbname='.$this->db_conf['db'];
    }

    public function getDBConnection()
    {
        try {
            $this->query("SET NAMES utf8");
            $this->query("SET sql_mode = ''");
        } catch (\PDOException $e) {
            throw new \Exception('Не могу установить опции соединения с базой данных. Код ошибки: ' . $e->getCode());
        }
    }

}