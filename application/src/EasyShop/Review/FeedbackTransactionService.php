<?php

namespace EasyShop\Review;

use EasyShop\Entities\EsPointType as EsPointType;
use EasyShop\Entities\EsPaymentMethod as EsPaymentMethod;

/**
 * Search Product Class
 *
 */
class FeedbackTransactionService
{
    /**
     * Entity Manager instance
     *
     * @var Doctrine\ORM\EntityManager
     */
    private $em;

    /**
     * Point Tracker instance
     *
     * @var EasyShop\PointTracker\PointTracker
     */
    private $pointTracker;

    /**
     * Form Validation
     */
    private $formValidation;

    /**
     * Form Factory service
     */
    private $formFactory;

    /**
     * Form error helper
     */
    private $formErrorHelper;

    /**
     * Transaction Manager
     *
     * @var EasyShop\Transaction\TransactionManager
     */
    public $transactionManager;

    /**
     * Constructor. Retrieves Entity Manager instance
     *
     */
    public function __construct(
        $em,
        $pointTracker,
        $formValidation,
        $formFactory,
        $formErrorHelper,
        $transactionManager
    )
    {
        $this->em = $em;
        $this->pointTracker = $pointTracker;
        $this->formFactory = $formFactory;
        $this->formValidation = $formValidation;
        $this->formErrorHelper = $formErrorHelper;
        $this->transactionManager = $transactionManager;
    }

    /**
     * Create feedback on transaction
     * @param  EasyShop\Entites\EsMember $member
     * @param  EasyShop\Entites\EsMember $forMemberId
     * @param  string                    $feedbackMessage
     * @param  integer                   $feedbackKind
     * @param  EasyShop\Entites\EsOrder  $order
     * @param  integer                   $rating1
     * @param  integer                   $rating2
     * @param  integer                   $rating3
     * @return EasyShop\Entites\EsMemberFeedback
     */
    public function createTransactionFeedback(
        $member,
        $forMember,
        $feedbackMessage,
        $feedbackKind,
        $order,
        $rating1,
        $rating2,
        $rating3
    )
    {
        $message = "";
        $isSuccess = false;
        $esMemberFeedbackRepo = $this->em->getRepository('EasyShop\Entities\EsMemberFeedback');

        if (!(bool)$feedbackKind) {
            $transacData = [
                'buyer' => $member->getIdMember(),
                'seller' => $forMember->getIdMember(),
                'order_id' => $order->getIdOrder()
            ];
        }
        else if ((bool)$feedbackKind) {
            $transacData = [
                'buyer' => $forMember->getIdMember(),
                'seller' => $member->getIdMember(),
                'order_id' => $order->getIdOrder()
            ];
        }

        $rules = $this->formValidation->getRules('user_feedback');
        $formBuild = $this->formFactory->createBuilder('form', null, ['csrf_protection' => false])
                                       ->setMethod('POST');
        $formBuild->add('message', 'text', ['constraints' => $rules['message']]);
        $formBuild->add('rating1', 'text', ['constraints' => $rules['rating']]);
        $formBuild->add('rating2', 'text', ['constraints' => $rules['rating']]);
        $formBuild->add('rating3', 'text', ['constraints' => $rules['rating']]);
        $formData["message"] = $feedbackMessage;
        $formData["rating1"] = $rating1;
        $formData["rating2"] = $rating2;
        $formData["rating3"] = $rating3;
        $form = $formBuild->getForm();
        $form->submit($formData);
        if ($form->isValid()) {
            $doesTransactionExists = $this->transactionManager->doesTransactionExist($transacData['order_id'], $transacData['buyer'], $transacData['seller']);
            if ($doesTransactionExists) {
                $doesFeedbackExists = $esMemberFeedbackRepo->findOneBy([
                    'member' => $member,
                    'forMemberid' => $forMember,
                    'feedbKind' => $feedbackKind,
                    'order' => $order
                ]);

                if ($doesFeedbackExists === null) {
                    $newFeedback = $esMemberFeedbackRepo->addFeedback(
                        $member,
                        $forMember,
                        $feedbackMessage,
                        $feedbackKind,
                        $order,
                        $rating1,
                        $rating2,
                        $rating3
                    );

                    if($this->transactionManager->isTransactionCompletePerSeller($order->getIdOrder(), $transacData['seller'])){
                        if ($order->getPaymentMethod()->getIdPaymentMethod() !== EsPaymentMethod::PAYMENT_CASHONDELIVERY) {
                            $this->pointTracker
                                 ->addUserPoint($member->getIdMember(), EsPointType::TYPE_TRANSACTION_FEEDBACK);
                        }
                    }

                    $isSuccess = true;
                }
                else {
                    $message = "You already write feedback to this order.";
                }
            }
            else {
                $message = "Transaction not exist";
            }
        }
        else {
            $message = reset($this->formErrorHelper->getFormErrors($form))[0];
        }

        return [
            'isSuccess' => $isSuccess,
            'error' => $message,
        ];
    }
}
