<?php

namespace PWW\DataFactoryBundle\Repository;

use Doctrine\ORM\EntityRepository;
use PWW\DataFactoryBundle\Connector\XMLExtractor;

class HelperRepository extends EntityRepository
{
    
    private $em;
    private $class;
    
    public function __construct($em, \Doctrine\ORM\Mapping\ClassMetadata $class) {
        parent::__construct($em, $class);
        $this->class = $class->name;
        $this->em = $em;
    }
    
    public function findUniqueUsers($userFieldName) {
         
        $query = $this->getEntityManager($this->em)
            ->createQueryBuilder('u')
                ->select('u.'.$userFieldName)
                ->from($this->class, 'u')
                ->groupBy('u.'.$userFieldName)
                ->getQuery();
        
        return $query->getResult();
    }
    
    public function getUniqueUserCount($userFieldName) {
        
        return count($this->findUniqueUsers($userFieldName));
    }
    
    public function getOneXmlByUser($user) {
        $query = $this->getEntityManager($this->em)
            ->createQueryBuilder('u')
                ->select('u.xml')
                ->from($this->class, 'u')
                ->where('u.user = :username')
                ->setParameter('username', $user)
                ->setMaxResults(1)
                ->getQuery();
        
        return $query->getSingleResult();
    }
    
    public function getXmlByUser($user) {
        $query = $this->getEntityManager($this->em)
            ->createQueryBuilder('u')
                ->select('u.xml')
                ->from($this->class, 'u')
                ->where('u.user = :username')
                ->setParameter('username', $user)
                ->getQuery();
        
        return $query->getResult();
    }
    
    public function queryUserStatsByDates($user, $start, $end)
    {
        $query = $this->getEntityManager($this->em)
            ->createQueryBuilder('u')
                ->select('u.time, u.stopped, u.pausedCounter')
                ->from($this->class, 'u')
                ->where('u.user = :user')
                ->andWhere('u.stopped BETWEEN :start AND :end')
                ->setParameter('user', $user)
                ->setParameter('start', $start)
                ->setParameter('end', $end)
                ->getQuery();
        
        return $query->getResult();
    }
    
    public function queryPlatformStatsByUser($user)
    {
        $query = $this->getEntityManager($this->em)
            ->createQueryBuilder('p')
                ->select('p.xml, count(p.platform) as platformCount')
                ->from($this->class, 'p')
                ->where('p.user = :user')
                ->setParameter('user', $user)
                ->groupBy('p.platform')
                ->orderBy('platformCount', 'desc')
                ->getQuery();
        
        return $query->getResult();
    }
    
    public function queryRecentlyWatchedByUser($user)
    {
        $query = $this->getEntityManager($this->em)
            ->createQueryBuilder('u')
                ->select('u.ratingkey')
                ->from($this->class, 'u')
                ->where('u.user = :user')
                ->setParameter('user', $user)
                ->orderBy('u.time', 'desc')
                ->setMaxResults(10)
                ->getQuery();
        
        return $query->getResult();
    }
    
    public function queryMostWatchedEpisodes($grandparentRatingKey)
    {
        $query = $this->getEntityManager($this->em)
            ->createQueryBuilder('u')
                ->select('u.ratingkey, count(u.ratingkey) as playCount')
                ->from($this->class, 'u')
                ->where('u.grandparentratingkey = :grandparentratingkey')
                ->setParameter('grandparentratingkey', $grandparentRatingKey)
                ->groupBy('u.ratingkey')
                ->orderBy('playCount', 'desc')
                ->addOrderBy('u.title', 'asc')
                ->setMaxResults(10)
                ->getQuery();
        
        return $query->getResult();
    }
    
    public function queryTop10All()
    {
        $query = $this->getEntityManager($this->em)
            ->createQueryBuilder('u')
                ->select('u.ratingkey, u.origTitle, u.origTitleEp, u.episode, u.season, u.year, u.xml, count(u.title) as playCount')
                ->from($this->class, 'u')
                ->groupBy('u.title')
                ->orderBy('playCount', 'desc')
                ->addOrderBy('u.ratingkey', 'desc')
                ->setMaxResults(10)
                ->getQuery();
        
        return $query->getResult();
    }
    
    public function queryGrouped()
    {
        $query = $this->getEntityManager($this->em)
            ->createQueryBuilder('u')
                ->select('u.ratingkey, u.origTitle, u.origTitleEp, u.episode, u.season, u.year, u.xml, count(u.title) as playCount')
                ->from($this->class, 'u')
                ->groupBy('u.title')
                ->orderBy('playCount', 'desc')
                ->addOrderBy('u.ratingkey', 'desc')
                ->getQuery();
        
        return $query->getResult();
    }
    
    public function queryGroupedShows()
    {
        $query = $this->getEntityManager($this->em)
            ->createQueryBuilder('u')
                ->select('u.ratingkey, u.origTitle, u.origTitleEp, u.episode, u.season, u.year, u.xml, count(u.title) as playCount')
                ->from($this->class, 'u')
                ->where('u.parentratingkey IS NOT null')
                ->groupBy('u.origTitle')
                ->orderBy('playCount', 'desc')
                ->addOrderBy('u.ratingkey', 'desc')
                ->setMaxResults(10)
                ->getQuery();
        
        return $query->getResult();
    }
    
    public function queryTop10Films()
    {
        $query = $this->getEntityManager($this->em)
            ->createQueryBuilder('u')
                ->select('u.ratingkey, u.origTitle, u.year, u.xml, count(u.title) as playCount')
                ->from($this->class, 'u')
                ->where('u.parentratingkey is null')
                ->groupBy('u.title')
                ->orderBy('playCount', 'desc')
                ->addOrderBy('u.ratingkey', 'desc')
                ->setMaxResults(10)
                ->getQuery();
        
        return $query->getResult();
    }
    
           
    public function getInfoMostWatchedEpisodes($grandparentRatingKey)
    {
        $results = $this->queryMostWatchedEpisodes($grandparentRatingKey);
        
        return $results;
    }
      
    public function getUserStatsLastDay($user) {
        
        $todayStart = new \DateTime();
        
        $start = new \DateTime($todayStart->format('Y-m-d'));
        $end = new \DateTime();
        
        //$start->modify('-1 days');
                
        $results = $this->queryUserStatsByDates($user, $start->format('U'), $end->format('U'));

        $i = 0;
        $totalTime = 0;
        
        foreach($results as $item) {
            $totalTime += ($item['stopped'] - $item['time']) - $item['pausedCounter'];
            $i++;
        }
        
        return array(
          "totalPlays" => $i,
          "totalTime" => $totalTime
        );
    }
    
    public function getUserStatsLastWeek($user) {
        
        $start = new \DateTime();
        $end = new \DateTime();
        
        $start->modify('-7 days');
                
        $results = $this->queryUserStatsByDates($user, $start->format('U'), $end->format('U'));

        $i = 0;
        $totalTime = 0;
        
        foreach($results as $item) {
            $totalTime += ($item['stopped'] - $item['time']) - $item['pausedCounter'];
            $i++;
        }
        
        return array(
          "totalPlays" => $i,
          "totalTime" => $totalTime
        );
        
    }
    
    public function getUserStatsLastMonth($user) {
        
        $start = new \DateTime();
        $end = new \DateTime();
        
        $start->modify('-1 month');
                
        $results = $this->queryUserStatsByDates($user, $start->format('U'), $end->format('U'));

        $i = 0;
        $totalTime = 0;
        
        foreach($results as $item) {
            $totalTime += ($item['stopped'] - $item['time']) - $item['pausedCounter'];
            $i++;
        }
        
        return array(
          "totalPlays" => $i,
          "totalTime" => $totalTime
        );
    }
    
    public function getUserStatsAllTime($user) {
        
        $start = new \DateTime();
        $end = new \DateTime();
        
        $start->modify('-100 years');
                
        $results = $this->queryUserStatsByDates($user, $start->format('U'), $end->format('U'));

        $i = 0;
        $totalTime = 0;
        
        foreach($results as $item) {
            $totalTime += ($item['stopped'] - $item['time']) - $item['pausedCounter'];
            $i++;
        }
        
        return array(
          "totalPlays" => $i,
          "totalTime" => $totalTime
        );
    }
    
    
}