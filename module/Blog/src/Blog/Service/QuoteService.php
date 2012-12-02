<?php
/**
 * Rob's Blog (http://www.robkeplin.com)
 *
 * @link http://www.robkeplin.com
 * @copyright Copyright (c) 2012 Rob Keplin
 * @license TBD
 */

namespace Blog\Service;

/**
 * Quote Service
 *
 * @package Blog\Service
 */
class QuoteService
    extends AbstractService
{
    /**
     * @const string
     **/
    const ENTITY_QUOTE = 'Blog\Model\Quote';

    /**
     * Random Quote
     *
     * @param void
     * @return Quote
     **/
    public function getRandom()
    {
        $sql = "SELECT MIN(id) AS min, MAX(id) AS max FROM quotes";

        $stmt = $this->em->getConnection()->prepare($sql);
        $stmt->execute();
        $data = $stmt->fetch();

        $id = rand($data['min'], $data['max']);

        $qb = $this->em->createQueryBuilder();

        $qb->select('q')
           ->from(self::ENTITY_QUOTE, 'q')
           ->where('q.id >= :id')
           ->setMaxResults(1)
           ->setParameters(array(
               'id' => $id,
            ));

        $query = $qb->getQuery();
        $result = $query->getSingleResult();

        return $result;
    }
}