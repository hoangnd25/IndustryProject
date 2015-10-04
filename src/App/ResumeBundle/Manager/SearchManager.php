<?php

namespace App\ResumeBundle\Manager;


use Elastica\Query;
use Elastica\SearchableInterface;

class SearchManager
{
    /** @var  SearchableInterface $search */
    protected $search;

    /**
     * SearchManager constructor.
     * @param SearchableInterface $search
     */
    public function __construct(SearchableInterface $search)
    {
        $this->search = $search;
    }

    /**
     * @param $keyword
     * @return \Elastica\ResultSet
     */
    public function search($keyword){
        $query = new Query();
        $query->setSize(1000);

        $fuzzyQuery = new Query\FuzzyLikeThis();
        $fuzzyQuery->setLikeText($keyword);
        $fuzzyQuery->setMinSimilarity(0.7);

        $query->setQuery($fuzzyQuery);

        $results = $this->search->search($query);
        return $results;
    }

}