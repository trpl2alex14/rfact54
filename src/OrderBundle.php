<?php

namespace RFAct54;

abstract class OrderBundle
{
    /**
     * Order.
     *
     * @var OrderInterface
     */
    protected $order;

    /**
     * OrderBundle constructor.
     *
     * @param OrderInterface $order
     */
    public function __construct(OrderInterface $order)
    {
        $this->order = $order;
    }

    /**
     * Transform order to array.
     *
     * @return array
     */
    abstract public function toArray():array;
}
