<?php


namespace App\Service;


use App\Objects\PropertiesPowerpoint;
use PhpOffice\PhpPresentation\DocumentLayout;
use PhpOffice\PhpPresentation\IOFactory;
use PhpOffice\PhpPresentation\PhpPresentation;
use PhpOffice\PhpPresentation\Slide\Background\Image;
use PhpOffice\PhpPresentation\Style\Alignment;
use PhpOffice\PhpPresentation\Style\Color;

class PowerPointGenerator
{
    public function main($danse, $powerpoint){
        //Récupération des valeurs personnalisée
        $propertiesPowerpoint = new PropertiesPowerpoint();

        $propertiesPowerpoint->setNbDansesSlides($powerpoint->getNbDansesSlides());
        $propertiesPowerpoint->setPrimaryDanseColor(str_replace("#",'', $powerpoint->getPrimaryDanseColor()));
        $propertiesPowerpoint->setSecondaryDanseColor(str_replace("#", "", $powerpoint->getSecondaryDanseColor()));

        if( !empty($powerpoint->getBackgroundSlides()) ) {
            $backgroundSlides = $_SERVER['DOCUMENT_ROOT']."/uploads/images/backgroundSlides/".$powerpoint->getBackgroundSlides();
        }
        else {
            $backgroundSlides = $_SERVER['DOCUMENT_ROOT']."/build/images/powerpoint/background_slide.jpg";
        }

        $propertiesPowerpoint->setBackgroundSlides($backgroundSlides);

        //Creation du powerpoint
       $presentation = $this->newPresentation($danse, $propertiesPowerpoint);

       //mise en fichier et telechargement
       $urlPowerpoint= $this->savePowerpointPPTX($presentation);

       return $urlPowerpoint;
    }

    public function savePowerpointPPTX($objPHPPowerPoint){
        //Sauvegarde au format PPTX
        $oWriterPPTX = IOFactory::createWriter($objPHPPowerPoint, 'PowerPoint2007');

        //Création url avec date + suite de chiffre aléatoire
        $url = date("Y-m-d-H-i-s").mt_rand().'.pptx';

        $oWriterPPTX->save($_SERVER['DOCUMENT_ROOT']."/uploads/powerpoint/".$url);

        return $url;
    }


    public function newPresentation($danses, $propertiesPowerpoint){

        //Définition d'un nouveau PowerPoint
       $objPHPPowerPoint = new PhpPresentation();

       $objPHPPowerPoint->getLayout()->setDocumentLayout(DocumentLayout::LAYOUT_SCREEN_16X9);
       $objPHPPowerPoint->getPresentationProperties()->setZoom(1);

       $objPHPPowerPoint->removeSlideByIndex(0);

       //Definition variable incrementale et recuperation nb danse
        $i =0;
        $countDanse = count($danses);

       foreach($danses as $danse)
       {
           //create slide
           $currentSlide = $objPHPPowerPoint->createSlide();

           //Creation du background
           $oBkgImage = new Image();
           $oBkgImage->setPath($propertiesPowerpoint->getBackgroundSlides());
           $currentSlide->setBackground($oBkgImage);

           //Ajout d'un texte
           $shape = $currentSlide->createRichTextShape()
               ->setHeight(684)
               ->setWidth(640)
               ->setOffsetX(38)
               ->setOffsetY(38);

           $shape->getActiveParagraph()->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);

           $textRun = $shape->createTextRun($danse->getPositionPlaylist().' - '.$danse->getName());
           $test = $textRun->getFont()->setName('Arial Black')->setSize(42)->getColor()->setRGB($propertiesPowerpoint->getPrimaryDanseColor());

          for($j=$i+1, $y=1; $y<$propertiesPowerpoint->getNbDansesSlides(); $j++, $y++)
          {
              if($y<($countDanse-$i))
              {
                  $shape->createBreak();

                  $nameDanse = $danses[$j]->getName();
                  $positionPlaylist= $danses[$j]->getPositionPlaylist();
                  $textRun = $shape->createTextRun($positionPlaylist.' - '.$nameDanse);
                  $textRun->getFont()->setName('Arial Black')->setSize(42)->setColor(new Color($propertiesPowerpoint->getSecondaryDanseColor()));
              }

          }

           //Ajout du logo LDA
           $shape = $currentSlide->createDrawingShape();
           $shape->setName('Logo LDA')
               ->setDescription('Logo LDA')
               ->setPath($_SERVER['DOCUMENT_ROOT']."build/images/powerpoint/logo_LDA.png")
               ->setHeight(135)
               ->SetoffsetX(580)
               ->setOffsetY(400)
           ;

           //incrementation variable $i
           $i++;
       }

       return $objPHPPowerPoint;
    }

}