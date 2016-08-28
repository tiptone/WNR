<?php

namespace Reviews\Model;

use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Sql\Select;
use Zend\Db\Sql\Predicate\Expression;

class ReviewTable
{
    protected $tableGateway;

    public function __construct(TableGateway $tableGateway)
    {
        $this->tableGateway = $tableGateway;
    }

    public function findMatches($name)
    {
        $where = array(
            new Expression("whiskey like ?", '%' . $name . '%')
        );

        $resultSet = $this->tableGateway->select($where);

        return $resultSet;
    }

    public function findUserMatches($name)
    {
        $where = array(
            new Expression("reviewer like ?", '%' . $name . '%')
        );

        $resultSet = $this->tableGateway->select($where);

        return $resultSet;
    }

    // @signde likes it Fuzzy
    public function findFuzzyMatches($name)
    {
        $pieces = explode(' ', $name);

        $resultSet = $this->tableGateway->select(function(Select $select) use ($pieces) {
            foreach ($pieces as $piece) {
                $select->where->OR->like('whiskey', "%$piece%");
            }
        });

        return $resultSet;
    }

    public function findByName($name)
    {
        $where = array(
            'whiskey = ?' => $name
        );

        $resultSet = $this->tableGateway->select($where);

        $resultSet->buffer();

        return $resultSet;
    }

    public function findByReviewer($reviewer)
    {
        $where = array(
            'reviewer' => $reviewer
        );

        $resultSet = $this->tableGateway->select($where);

        return $resultSet;
    }

    public function fetchAll()
    {
        $resultSet = $this->tableGateway->select();
        return $resultSet;
    }
}
