<?php
namespace Developer\ChkAccess\Event;

/**
 * @author Igor Vorobiov<igor.vorobioff@gmail.com>
 */
class PriorityHeap extends \SplHeap
{
	protected function compare($strategy1, $strategy2)
	{
		$p1 = $this->getPriority($strategy1);
		$p2 = $this->getPriority($strategy2);

		if ($p1 < $p2) return 1;
		if ($p1 > $p2) return -1;
		return 0;
	}

	private function getPriority($config)
	{
		if (!isset($config['priority'])) return 0;
		return $config['priority'];
	}

	public function populate(array $strategies)
	{
		foreach ($strategies as $strategy)
		{
			$this->insert($strategy);
		}
	}
}