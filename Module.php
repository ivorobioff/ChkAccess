<?php
namespace Developer\ChkAccess;
use Developer\ChkAccess\Event\AccessListener;
use Zend\EventManager\EventInterface;
use Zend\ModuleManager\Feature\AutoloaderProviderInterface;
use Zend\ModuleManager\Feature\BootstrapListenerInterface;
use Zend\ModuleManager\Feature\ConfigProviderInterface;
use Zend\Mvc\MvcEvent;

/**
 * @author Igor Vorobiov<igor.vorobioff@gmail.com>
 */
class Module implements ConfigProviderInterface, AutoloaderProviderInterface, BootstrapListenerInterface
{
	/**
	 * Returns configuration to merge with application configuration
	 * @return array|\Traversable
	 */
	public function getConfig()
	{
		return [
			'access' => [
				'strategies' => [
//					'authentication' => [
//						'priority' => 1,
//						'class' => '',
//						'default_pass_condition' => Condition::POSITIVE,
//
//						'overrides' => [
//							[
//								'controller' => '',
//								'action' => '',
//								'pass_condition' => Condition::NEGATIVE
//							],
//
//							[
//								'controller' => '',
//								'pass_condition' => Condition::BYPASS
//							]
//						]
//					]
				]
			]
		];
	}

	/**
	 * Listen to the bootstrap event
	 * @param EventInterface|MvcEvent $event
	 * @return array
	 */
	public function onBootstrap(EventInterface $event)
	{
		$event->getApplication()
			->getEventManager()
			->attach(MvcEvent::EVENT_ROUTE, new AccessListener(), 0);
	}

	/**
	 * Return an array for passing to Zend\Loader\AutoloaderFactory.
	 * @return array
	 */
	public function getAutoloaderConfig()
	{
		return [
			'Zend\Loader\StandardAutoloader' => [
				'namespaces' => [
					__NAMESPACE__ => __DIR__ . '/src/ChkAccess'
				],
			],
		];
	}
}