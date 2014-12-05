<?php
namespace EasyShop\Doctrine\Listeners;
use Doctrine\Common\Persistence\Event\LifecycleEventArgs;
use EasyShop\Entities\EsMember;
class ProductImageExistenceListener
{
    /**
    * Post load event to check if the image exists in the appropriate asset directory
    *
    * @param LifecycleEventArgs $event
    */
    public function postPersist(LifecycleEventArgs $event)
    {
        $em = $event->getEntityManager();
        $entity = $event->getEntity();
        if ( ! $entity instanceOf EsMember ) {
            return;
        }
    }
}