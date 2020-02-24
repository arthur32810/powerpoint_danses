<?php


namespace App\Service;


use Doctrine\Common\Collections\ArrayCollection;

class OrderObject
{

    public function ordrePostionPlaylist(ArrayCollection $fields): ArrayCollection
    {
        $iterator = $fields->getIterator();

        $iterator->uasort(function($a, $b){
            return $a->getPositionPlaylist() <=> $b->getPositionPlaylist();
        });

        return new ArrayCollection(iterator_to_array($iterator));
    }

}