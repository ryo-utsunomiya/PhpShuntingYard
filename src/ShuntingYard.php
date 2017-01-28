<?php namespace ryo511;

/**
 * @see https://en.wikipedia.org/wiki/Shunting-yard_algorithm
 */
class ShuntingYard
{
    // operator => priority(larger is higher)
    const OPERATORS_TO_PRIORITY = [
        '+' => 1,
        '-' => 1,
        '*' => 2,
        '/' => 2,
        '(' => -1, // should not output
        ')' => -1, // should not output
    ];

    /** @var \SplQueue */
    private $outputQueue;

    /** @var \SplStack */
    private $operatorStack;

    public function __construct()
    {
        $this->outputQueue = new \SplQueue();
        $this->operatorStack = new \SplStack();
    }

    /**
     * @param string $statement
     * @return string
     */
    public function infixToPrefix($statement)
    {
        for ($i = 0, $len = strlen($statement); $i < $len; $i++) {
            $char = $statement[$i];

            if ($this->isOperator($char)) {
                $this->computeOperator($char);
            } elseif ($this->isNumber($char)) {
                $this->addOutput($char);
            }
        }

        foreach ($this->operatorStack as $operation) {
            $this->addOutput($operation);
        }

        return implode(' ', $this->queueToArray($this->outputQueue));
    }

    /**
     * @param \SplQueue $queue
     * @return array
     */
    private function queueToArray(\SplQueue $queue)
    {
        $result = [];

        foreach ($queue as $value) {
            $result[] = $value;
        }

        return $result;
    }

    /**
     * @param string $char
     */
    private function addOutput($char)
    {
        if ($this->shouldOutput($char)) {
            $this->outputQueue->enqueue($char);
        }
    }

    /**
     * @param string $char
     * @return bool
     */
    private function isNumber($char)
    {
        return ctype_digit((string)$char);
    }

    /**
     * @param string $char
     * @return bool
     */
    private function isOperator($char)
    {
        return in_array($char, array_keys(self::OPERATORS_TO_PRIORITY));
    }

    /**
     * @param string $char
     * @return bool
     */
    private function shouldOutput($char)
    {
        return $this->isNumber($char) ||
            ($this->isOperator($char) && self::OPERATORS_TO_PRIORITY[$char] > 0);
    }

    /**
     * @param string $o1
     */
    private function computeOperator($o1)
    {
        if ($this->operatorStack->isEmpty()) {
            $this->operatorStack->push($o1);
        } elseif (')' === $o1) {
            while (!$this->operatorStack->isEmpty() && '(' !== ($o2 = $this->operatorStack->pop())) {
                $this->addOutput($o2);
            }
        } else {
            $o2 = $this->operatorStack->top();

            if ($this->priorityDiff($o1, $o2) > 0) {
                $this->operatorStack->push($o1);
            } else {
                $this->addOutput($this->operatorStack->pop());
                $this->computeOperator($o1);
            }
        }
    }

    /**
     * @param string $o1
     * @param string $o2
     * @return int
     */
    private function priorityDiff($o1, $o2)
    {
        return self::OPERATORS_TO_PRIORITY[$o1] - self::OPERATORS_TO_PRIORITY[$o2];
    }
}
