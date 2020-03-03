<?php


namespace App\Service;


use Doctrine\Common\Collections\ArrayCollection;

class OrderObject
{

    public function main($danses){
        $dansesOrdonner = $this->ordrePositionPlaylist($danses);
        $dansesOrdonner = $this->ordrePositionPlaylistReIndex($dansesOrdonner);

        return $dansesOrdonner;
    }

    public function ordrePositionPlaylist(ArrayCollection $fields): ArrayCollection
    {
        $iterator = $fields->getIterator();

        $iterator->uasort(function($a, $b){
            return $a->getPositionPlaylist() <=> $b->getPositionPlaylist();
        });

        return new ArrayCollection(iterator_to_array($iterator));
    }

    public function ordrePositionPlaylistReIndex($danses)
    {
        $dansesOrdonner = new \ArrayObject();

        foreach($danses as $danse)
        {
            $dansesOrdonner->append($danse);

        }

       return $dansesOrdonner;
    }

}