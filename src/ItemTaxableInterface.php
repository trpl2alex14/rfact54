<?php

namespace RFAct54;

interface ItemTaxableInterface
{
    /**
     * Tax types.
     */
    public const TAX_TYPE_NO_VAT = 0;
    public const TAX_TYPE_0 = 1;
    public const TAX_TYPE_10 = 2;
    public const TAX_TYPE_20 = 3;
    public const TAX_TYPE_10_110 = 4;
    public const TAX_TYPE_20_120 = 5;

    /**
     * Tax type.
     *
     * @return int
     */
    public function getTaxType(): int;

    /**
     * Tax sum.
     *
     * @return int
     */
    public function getTaxSum(): int;
}
