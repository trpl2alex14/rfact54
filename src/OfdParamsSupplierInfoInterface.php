<?php


namespace RFAct54;

/**
 * Interface OfdParamsSupplierInfoInterface from Order or Item entity
 * @package RFAct54
 */

interface OfdParamsSupplierInfoInterface
{
    /**
     *
     * @return string
     */
    public function getInnSupplier(): ?string;

    /**
     *
     * @return string
     */
    public function getNameSupplier(): ?string;

    /**
     *
     * @return iterable|string[]
     */
    public function getPhonesSupplier(): ?array;
}