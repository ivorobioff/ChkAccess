<?php
namespace Developer\ChkAccess\Event;

use Developer\ChkAccess\Condition;
use Developer\ChkAccess\StrategyInterface;
use Zend\Mvc\InjectApplicationEventInterface;
use Zend\Mvc\MvcEvent;
use Zend\ServiceManager\ServiceLocatorAwareInterface;

/**
 * @author Igor Vorobiov<igor.vorobioff@gmail.com>
 */
class AccessListener 
{
	public function __invoke(MvcEvent $event)
	{
		$routeMatch = $event->getRouteMatch();
		if (!$routeMatch) return null;

		$controller = $routeMatch->getParam('controller');
		$action = $routeMatch->getParam('action');

		if (!$controller) return null;

		$serviceLocator = $event->getApplication()->getServiceManager();

		$config = $serviceLocator->get('Config');
		$strategiesConfig = $config['access']['strategies'];

		$prioritizedStrategies = new PriorityHeap();
		$prioritizedStrategies->populate($strategiesConfig);

		foreach ($prioritizedStrategies as $strategyConfig)
		{
			$strategyClass = $strategyConfig['class'];

			/**
			 * @var StrategyInterface|ServiceLocatorAwareInterface|InjectApplicationEventInterface $strategy
			 */
			$strategy =  new $strategyClass();

			if ($strategy instanceof ServiceLocatorAwareInterface)
			{
				$strategy->setServiceLocator($serviceLocator);
			}

			if ($strategy instanceof InjectApplicationEventInterface)
			{
				$strategy->setEvent($event);
			}

			$passCondition = $strategyConfig['default_pass_condition'];

			if (!empty($strategyConfig['overrides']))
			{
				foreach ($strategyConfig['overrides'] as $override)
				{
					if ($override['controller'] != $controller)
					{
						continue;
					}

					if (isset($override['action']) && $override['action'] != $action)
					{
						continue;
					}

					$passCondition = $override['pass_condition'];
				}
			}

			if ($passCondition === Condition::BYPASS)
			{
				continue ;
			}

			$condition = $strategy->getCondition();

			if ($condition === Condition::BYPASS)
			{
				continue ;
			}

			if ($condition !== $passCondition)
			{
				return $strategy->handleAccessDenied($passCondition);
			}
		}
	}
} 