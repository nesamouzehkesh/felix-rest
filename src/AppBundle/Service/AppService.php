<?php

namespace AppBundle\Service;

use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Translation\Translator;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;
use Symfony\Component\Validator\Validator\RecursiveValidator;
use AppBundle\Library\Exception\VisibleHttpException;
use AppBundle\Service\Session as SessionService;
use AppBundle\Entity\BaseEntity;

class AppService 
{
    const PARAM_SEARCH_TEXT = 'searchText';
    const PARAM_SEARCH_TARGET = 'searchTarget';
    const PARAM_SEARCH_TYPE = 'searchType';
    
    /**
     * Translator services
     * 
     * @var Translator $translator
     */
    protected $translator;  
    
    /**
     *
     * @var TokenStorage $security
     */
    protected $security;    
    
    /**
     *
     * @var EntityManager $em
     */
    protected $em;
    
    /**
     *
     * @var ValidatorInterface $validator
     */
    protected $validator;
    
    /**
     *
     * @var SessionService $sessionService
     */
    protected $sessionService;

    /**
     * 
     * @param Translator $translator
     * @param Security $security
     * @param EntityManager $em
     * @param RecursiveValidator $validator
     * @param SessionService $sessionService
     */
    public function __construct(
        Translator $translator, 
        TokenStorage $security,
        EntityManager $em,
        RecursiveValidator $validator,
        SessionService $sessionService
        ) 
    {
        $this->translator = $translator;
        $this->security = $security;
        $this->em = $em;
        $this->validator = $validator;
        $this->sessionService = $sessionService;
    }   
    
    /**
     * Starts a transaction by suspending auto-commit mode.
     */
    public function transactionBegin()
    {
        $this->getEntityManager()->getConnection()->beginTransaction();
    }
    
    /**
     * Commits the current transaction
     */
    public function transactionCommit()
    {
        $this->getEntityManager()->getConnection()->commit();
    }
    
    /**
     * Cancels any database changes done during the current transaction.
     *
     * This method can be listened with onPreTransactionRollback and onTransactionRollback
     * eventlistener methods.
     */    
    public function transactionRollback()
    {
        $this->getEntityManager()->getConnection()->rollback();
    }
    
    /**
     * 
     * @return SessionService
     */
    public function getSession()
    {
        return $this->sessionService;
    }

    /**
     * Get a user from the Security Context
     *
     * @return mixed
     * @throws \LogicException If SecurityBundle is not available
     * @see Symfony\Component\Security\Core\Authentication\Token\TokenInterface::getUser()
     */
    public function getUser()
    {
        if (null === $token = $this->security->getToken()) {
            return;
        }

        if (!is_object($user = $token->getUser())) {
            return;
        }

        return $user;
    }    
    
    /**
     * 
     * @return \Doctrine\ORM\EntityManager
     */
    public function getEntityManager()
    {
        return $this->em;
    }
    
    /**
     * Soft-delete an entity
     * 
     * @param type $entity
     */
    public function deleteEntity($entity)
    {
        if ($entity instanceof BaseEntity) {
            $entity->setDeleted(true);
            $entity->setDeletedTime();
        
            $this->em->flush();
        }
    }
    
    /**
     * Persist an entity in entity manager
     * 
     * @param type $entity
     */
    public function persistEntity($entity)
    {
        $this->em->persist($entity);
    }
    
    /**
     * Flush entity manager
     */
    public function flushEntityManager()
    {
        $this->em->flush();        
    }
    
    /**
     * Persist an flush entity manager for this entity
     * 
     * @param type $entity
     * @param type $flushEntityManager
     * @return \Library\Service\Helper
     */
    public function saveEntity($entity, $flushEntityManager = true)
    {
        $this->persistEntity($entity);
        if ($flushEntityManager) {
            $this->flushEntityManager();
        }
        
        return $this;
    }
    
    /**
     * 
     * @return type $validator
     */
    public function getValidator()
    {
        return $this->validator;
    }

    /**
     * 
     * @return Translator service
     */
    public function getTranslator()
    {
        return $this->translator;
    }
    
    /**
     * Returns the Unix timestamp representing the date. 
     * 
     * @param \DateTime $date
     * @return type
     */
    public function getTimestamp(\DateTime $date = null)
    {
        if (null === $date) {
            $date = new \DateTime();
        }
        
        return $date->getTimestamp();
    }
    
    /**
     * 
     * @param type $message
     * @return type
     * @throws \Exception
     */
    public function transMessage($message)
    {
        if (is_array($message)) {
            if (array_key_exists(0, $message)) {
                return $this->getTranslator()->trans($message[0], $message[1]);
            } else {
                throw new \Exception('Message format is not right');
            }
        }
        
        return $this->getTranslator()->trans($message);
    }     
    
    /**
     * 
     * @param type $message
     * @param type $ex
     * @param type $responseParam
     * @return type
     */
    public function getExceptionResponse($message, $ex = null, $responseParam = null)
    {
        return $this->getJsonResponse(false, $message, null, $responseParam, $ex);
    }
    
    /**
     * 
     * @param type $success
     * @param type $message
     * @param type $content
     * @param type $responseParam
     * @param type $ex
     * @return JsonResponse
     * @throws \Exception
     */
    public function getJsonResponse(
        $success, 
        $message = null, 
        $content = null, 
        $responseParam = null, 
        $ex = null
        )
    {
        // Set jason success status
        $response['success'] = $success;
        $response['message'] = $this->transMessage($message);
        
        // Get exception error and add it to responce message
        $exceptionError = $this->getExceptionError($ex);
        if (null !== $exceptionError) {
            $response['message'] = sprintf('%s [%s]', $response['message'], $exceptionError);
        }
        
        // Set jason contet if it is provide
        if (null !== $content) {
            $response['content'] = $content;
        }
        
        // Merge jason responce with some extra user parameters
        if (null !== $responseParam) {
            $response = array_merge($response, $responseParam);
        }
        
        return new JsonResponse($response);
    }
    
    /**
     * Get exception error
     *
     * @param \Exception $ex
     * @param null|bool $forceToShow
     * @return string $exceptionError
     */
    private function getExceptionError($ex, $forceToShow = false)
    {
        // If the exception is instace of PmsHttpException then we will display
        // its message to the end user. 
        if ($ex instanceof VisibleHttpException) {
            $forceToShow = true;
        }
        
        $exceptionError = null;
        // $showErrorConfig = $this->container->getParameter('elmo_show_exception_error_detail');
        $showErrorConfig = true;
        if (null !== $ex and is_a($ex, '\Exception')) {
            $exceptionError = ($showErrorConfig || $forceToShow)? $ex->getMessage() : '';
        }
        
        return $exceptionError;
    }
}