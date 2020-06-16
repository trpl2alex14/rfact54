<?php

namespace RFAct54;

interface ItemAgentInterestInterface
{
    /**
     * Type agent interest
     *
     * @return string|null
     */
    public function getInterestType(): ?string;

    /**
     * Agency Commission for the sale of a product
     *
     * @return int|float|null
     */
    public function getInterestValue(): ?int;
}
