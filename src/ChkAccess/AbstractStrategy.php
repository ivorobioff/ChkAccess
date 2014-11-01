<?php
namespace Developer\ChkAccess;
use Zend\EventManager\EventInterface;
use Zend\Http\PhpEnvironment\Response;
use Zend\Mvc\InjectApplicationEventInterface;
use Zend\Mvc\MvcEvent;
use Zend\Mvc\Router\RouteStackInterface;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorAwareTrait;

/**
 * @author Igor Vorobiov<igor.vorobioff@gmail.com>
 */
abstract class AbstractStrategy implements
	StrategyInterface,
	ServiceLocatorAwareInterface,
	InjectApplicationEventInterface
{
	use ServiceLocatorAwareTrait;

	private $event;

	/**
	 * @param $route
	 * @param array $params
	 * @return Response
	 */
	protected function redirect($route, $params = [])
	{
		/**
		 * @var RouteStackInterface $router
		 */
		$router = $this->getServiceLocator()->get('Router');

		$url = $router->assemble($params, ['name' => $route]);

		/**
		 * @var Response $response
		 */
		$response = $this->getEvent()->getResponse();
		$response->getHeaders()->addHeaderLine('Location', $url);
		$response->setStatusCode(302);

		return $response;
	}

	/**
	 * @return EventInterface|MvcEvent
	 */
	public function getEvent()
	{
		return $this->event;
	}

	public function setEvent(EventInterface $event)
	{
		$this->event = $event;
	}
} 