<?php

namespace AppBundle\Service;

use Exception;
use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Translation\Translator;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;
use Symfony\Component\Validator\Validator\RecursiveValidator;
use Symfony\Component\HttpFoundation\RequestStack;
use Knp\Component\Pager\Paginator;
use AppBundle\Library\Base\BaseService;
use AppBundle\Library\Exception\AppException;
use AppBundle\Service\Session as SessionService;
use AppBundle\Entity\BaseEntity;

class AppService extends BaseService
{
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
     * @var RequestStack $requestStack
     */
    protected $requestStack;
    
    /**
     *
     * @var ValidatorInterface $validator
     */
    protected $validator;
    
    /**
     *
     * @var Paginator $paginator
     */
    protected $paginator;    
    
    /**
     *
     * @var SessionService $sessionService
     */
    protected $sessionService;

    /**
     * 
     * @param Translator $translator
     * @param TokenStorage $security
     * @param EntityManager $em
     * @param RequestStack $requestStack
     * @param Paginator $paginator
     * @param RecursiveValidator $validator
     * @param SessionService $sessionService
     */
    public function __construct(
        Translator $translator, 
        TokenStorage $security,
        EntityManager $em,
        RequestStack $requestStack,
        Paginator $paginator,
        RecursiveValidator $validator,
        SessionService $sessionService,
        $parameters = array()
        ) 
    {
        $this->translator = $translator;
        $this->security = $security;
        $this->em = $em;
        $this->requestStack = $requestStack;
        $this->paginator = $paginator;
        $this->validator = $validator;
        $this->sessionService = $sessionService;
        $this->setParametrs($parameters);
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
     * 
     * @return RequestStack
     */
    public function getRequestStack()
    {
        return $this->requestStack;
    }


    /**
     * Soft-delete an entity
     * 
     * @param type $entity
     */
    public function deleteEntity($entity)
    {
        if ($entity instanceof BaseEntity) {
            return;
        }
        
        $entity->setDeleted(true);
        $entity->setDeletedTime();
        $this->em->flush();
    }
    
    /**
     * Persist an flush entity manager for this entity
     * 
     * @param type $entity
     * @param type $flush
     * @return \Library\Service\Helper
     */
    public function saveEntity($entity, $flush = true)
    {
        $this->em->persist($entity);
        if ($flush) {
            $this->em->flush();
        }
        
        return $this;
    }
    
    /**
     * use Knp\Component\Pager\Pagination\AbstractPagination;
     * 
     * @return type
     */
    public function getPaginator()
    {
        return $this->paginator;
    }
        
    /**
     * 
     * @param type $query
     * @return type
     */
    public function paginate($query)
    {
        $request = $this->getRequestStack()->getCurrentRequest();
        $limit = $this->getParameter('paginateLimit', 10);
        $itemsPagination = $this->getPaginator()->paginate(
            $query,
            $request->query->getInt('page', 1),
            $limit
        );
        
        $currentPageNumber = $itemsPagination->getCurrentPageNumber();
        $totalItemCount = $itemsPagination->getTotalItemCount();
        $itemNumberPerPage = $itemsPagination->getItemNumberPerPage();
        if (count($itemsPagination) == 0) {
            $currentPageNumber = ceil($totalItemCount / $itemNumberPerPage);
            $itemsPagination = $this->getPaginator()->paginate(
                $query,
                $currentPageNumber,
                $limit
            );
            $itemsPagination->setCurrentPageNumber($currentPageNumber);
        }
        
        return $itemsPagination;
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
     * @param type $messageId
     * @param type $parameters
     * @return type
     */
    public function transMessage($messageId, $parameters = array())
    {
        if (is_array($messageId)) {
            if (array_key_exists(1, $messageId)) {
                $parameters = $messageId[1];
            }
            if (array_key_exists(0, $messageId)) {
                $messageId = $messageId[0];
            } else {
                $messageId = null;
            }
        }
        
        return $this->getTranslator()->trans($messageId, $parameters);
    }     
    
    /**
     * 
     * @param type $messageId
     * @param type $ex
     * @param type $responseParam
     * @return type
     */
    public function getExceptionResponse($messageId, $ex = null, $responseParam = null)
    {
        return $this->getJsonResponse(false, $messageId, null, $responseParam, $ex);
    }
    
    /**
     * 
     * @param type $success
     * @param type $messageId
     * @param type $content
     * @param type $parameters
     * @param type $ex
     * @return JsonResponse
     * @throws \Exception
     */
    public function getJsonResponse(
        $success, 
        $messageId = null, 
        $content = null, 
        $parameters = null, 
        $ex = null
        )
    {
        // Set jason success status
        $response['success'] = $success;
        $response['message'] = $this->transMessage($messageId);
        
        // Get debug parameter injected to this service, if no debug parameter 
        // is defined the false value will be selected
        $debug = $this->getParameter('debug', false);
        // If the exception is instace of AppException then we will display it
        if ($ex instanceof Exception && ($debug || $ex instanceof AppException)) {
            $response['message'] = sprintf(
                '%s [%s]', 
                $response['message'], 
                $ex->getMessage()
                );
        }
        
        // Set jason contet if it is provide
        if (null !== $content) {
            $response['content'] = $content;
        }
        
        // Merge jason responce with some extra user parameters
        if (null !== $parameters) {
            $response = array_merge($response, $parameters);
        }
        
        return new JsonResponse($response);
    }
    
    /**
     * 
     * @param type $messageId
     * @param \Exception $previous
     * @return AppException
     */
    public function getAppException($messageId = null, \Exception $previous = null)
    {
        return new AppException($this->transMessage($messageId), $previous);
    }
}