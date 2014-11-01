<?php
namespace Developer\ChkAccess;
use Zend\Http\PhpEnvironment\Response;
/**
 * @author Igor Vorobiov<igor.vorobioff@gmail.com>
 */
interface StrategyInterface
{
	/**
	 * @return int
	 */
	public function getCondition();

	/**
	 * @param int $passCondition
	 * @return Response
	 */
	public function handleAccessDenied($passCondition);
} 