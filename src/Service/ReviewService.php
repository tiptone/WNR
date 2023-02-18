<?php

namespace Tiptone\Mvc\Service;

use Tiptone\Mvc\Database\Db;
use Tiptone\Mvc\Model\Review;

class ReviewService
{
    /**
     * @var Db
     */
    protected $db;

    public function __construct(Db $db) {
        $this->db = $db;
    }

    /**
     * @param $search
     * @return Review[]
     * @throws \Exception
     */
    public function findFuzzyMatches($search) {
        $reviews = [];

        $pieces = explode(' ', $search);

        $where = "'%" . implode("%' or '%", $pieces) . "%'";
        $sql = "select *
                from reviews
                where whiskey like $where";

        try {
            $result = $this->db->query($sql);
            while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
                $reviews[] = new Review($row);
            }
        } catch (\Exception $e) {
            throw new \Exception($e);
        }

        return $reviews;
    }

    /**
     * @param $search
     * @return Review[]
     * @throws \Exception
     */
    public function findFuzzyUser($search) {
        $reviews = [];

        $sql = "select *
                from reviews
                where reviewer like :search";

        try {
            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(':search', "%$search%");
            $result = $stmt->execute();
        } catch (\Exception $e) {
            throw new \Exception($e);
        }

        while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
            $reviews[] = new Review($row);
        }

        return $reviews;
    }

    /**
     * @param $whiskey
     * @return Review[]
     * @throws \Exception
     */
    public function findByName($whiskey) {
        $reviews = [];

        $sql = "select *
                from reviews
                where whiskey = :whiskey";

        try {
            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(':whiskey', $whiskey);
            $result = $stmt->execute();
        } catch (\Exception $e) {
            throw new \Exception($e);
        }

        while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
            $reviews[] = new Review($row);
        }

        return $reviews;
    }

    /**
     * @param $reviewer
     * @return Review[]
     * @throws \Exception
     */
    public function findByReviewer($reviewer) {
        $reviews = [];

        $sql = "select *
                from reviews
                where reviewer = :reviewer";

        try {
            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(':reviewer', $reviewer);
            $result = $stmt->execute();
        } catch (\Exception $e) {
            throw new \Exception($e);
        }

        while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
            $reviews[] = new Review($row);
        }

        return $reviews;
    }
}
