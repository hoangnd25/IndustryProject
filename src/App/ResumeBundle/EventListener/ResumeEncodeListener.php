<?php

namespace App\ResumeBundle\EventListener;

use App\ResumeBundle\Entity\StudentProfile;
use App\ResumeBundle\Entity\StudentResume;
use Elastica\Document;
use FOS\ElasticaBundle\Event\TransformEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Vich\UploaderBundle\Storage\StorageInterface;

class ResumeEncodeListener implements EventSubscriberInterface
{
    /** @var  StorageInterface $storage */
    private $storage;

    /**
     * ResumeEncodeListener constructor.
     * @param StorageInterface $storage
     */
    public function __construct(StorageInterface $storage)
    {
        $this->storage = $storage;
    }

    public function addEncodedResumeProperty(TransformEvent $event)
    {
        /** @var Document $document */
        $document = $event->getDocument();
        $resume = $event->getObject();

        if(!$resume)
            return;

        if(!$resume instanceof StudentResume)
            return;

        try{
            $filePath = $this->storage->resolveUri($resume, "file");
            if($resume->getFileName()){
                $document->set('encodedFile',base64_encode(file_get_contents($filePath)));
            }else{
                $document->set('encodedFile', '');
            }
        }catch (\Exception $ex){
        }
    }

    public static function getSubscribedEvents()
    {
        return array(
            TransformEvent::POST_TRANSFORM => 'addEncodedResumeProperty',
        );
    }
}