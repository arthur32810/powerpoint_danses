<?php


namespace App\Service;


use PhpOffice\PhpPresentation\DocumentLayout;
use PhpOffice\PhpPresentation\IOFactory;
use PhpOffice\PhpPresentation\PhpPresentation;
use PhpOffice\PhpPresentation\Slide\Background\Image;
use PhpOffice\PhpPresentation\Style\Alignment;
use PhpOffice\PhpPresentation\Style\Color;

class PowerPointGenerator
{
    public function main($danses){

        //Définition d'un nouveau PowerPoint
       $objPHPPowerPoint = new PhpPresentation();

       $objPHPPowerPoint->getLayout()->setDocumentLayout(DocumentLayout::LAYOUT_SCREEN_16X9);
       $objPHPPowerPoint->getPresentationProperties()->setZoom(1);

       $objPHPPowerPoint->removeSlideByIndex(0);

       foreach($danses as $danse)
       {
           //create slide
           $currentSlide = $objPHPPowerPoint->createSlide();

           //Creation du background
           $oBkgImage = new Image();
           $oBkgImage->setPath($_SERVER['DOCUMENT_ROOT']."build/images/powerpoint/background_slide.jpg"); //Modifier la valeur par l'image choisie de l'utilisateur
           $currentSlide->setBackground($oBkgImage);

           //Ajout d'un texte
           $shape = $currentSlide->createRichTextShape()
               ->setHeight(684)
               ->setWidth(640)
               ->setOffsetX(38)
               ->setOffsetY(38);

           $shape->getActiveParagraph()->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);

           $textRun = $shape->createTextRun($danse->getPositionPlaylist().' - '.$danse->getName());

           $textRun->getFont()->setName('Arial Black')->setSize(42)->setColor(new Color('ffff00'));

           //Ajout du logo LDA
           $shape = $currentSlide->createDrawingShape();
           $shape->setName('Logo LDA')
               ->setDescription('Logo LDA')
               ->setPath($_SERVER['DOCUMENT_ROOT']."build/images/powerpoint/logo_LDA.png")
               ->setHeight(135)
               ->SetoffsetX(580)
               ->setOffsetY(400)
           ;

           //Sauvegarde au format PPTX
           $oWriterPPTX = IOFactory::createWriter($objPHPPowerPoint, 'PowerPoint2007');
           $oWriterPPTX->save($_SERVER['DOCUMENT_ROOT']."/uploads/powerpoint/sample.pptx");

       }

       /*

        //Ajout d'un texte
        $shape = $currentSlide->createRichTextShape()
            ->setHeight(684)
            ->setWidth(640)
            ->setOffsetX(38)
            ->setOffsetY(38);

        $shape->getActiveParagraph()->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
        $textRun = $shape->createTextRun('American Kids'); //Changer le texte par le nom de la danse
        $textRun->getFont()->setName('Arial Black')->setSize(42)->setColor(new Color('ffff00'));

        $shape->createBreak();
        $shape->createBreak();

        $textRun = $shape->createTextRun('TH Guest Ranch');
        $textRun->getFont()->setName('Arial Black')->setSize(42)->setColor(new Color('ffffff'));

        //Ajout du logo LDA
        $shape = $currentSlide->createDrawingShape();
        $shape->setName('Logo LDA')
            ->setDescription('Logo LDA')
            ->setPath($_SERVER['DOCUMENT_ROOT']."build/images/powerpoint/logo_LDA.png")
            ->setHeight(135)
            ->SetoffsetX(580)
            ->setOffsetY(400)
        ;


       /* //Sauvegarde au format PPTX
        $oWriterPPTX = IOFactory::createWriter($objPHPPowerPoint, 'PowerPoint2007');
        $oWriterPPTX->save($_SERVER['DOCUMENT_ROOT']."/uploads/powerpoint/sample.pptx");*/

        /*//Sauvegarde au format ODP
        $oWriterODP = IOFactory::createWriter($objPHPPowerPoint, 'ODPresentation');
        $oWriterODP->save(__DIR__."/sample.odp");*/


    }

}