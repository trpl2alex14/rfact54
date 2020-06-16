<?php

namespace RFAct54;

/**
 * Interface OfdParamsInterface  from Order or Item entity
 * @package RFAct54
 */

interface OfdParamsInterface
{
    /**
     *
     * @return string
     */
    public function getCashier(): ?string;

    /**
     *
     * @return string
     */
    public function getAdditionalCheckProps(): ?string;

    /**
     *
     * @return string
     */
    public function getAdditionalUserPropsName(): ?string;

    /**
     *
     * @return string
     */
    public function getAdditionalUserPropsValue(): ?string;
}
