<?php

namespace RFAct54;

interface ItemAttributesInterface
{

    /**
     * PAYMENT METHOD.
     */
    public const PAYMENT_METHOD_FULL_BEFORE = 1;
    public const PAYMENT_METHOD_PARTIAL_BEFORE = 2;
    public const PAYMENT_METHOD_PREPAYMENT = 3;
    public const PAYMENT_METHOD_FULL = 4;
    public const PAYMENT_METHOD_PARTIAL_CREDIT = 5;
    public const PAYMENT_METHOD_AFTER_CREDIT = 6;
    public const PAYMENT_METHOD_CREDIT = 7;

    /**
     * PAYMENT OBJECT.
     */
    public const PAYMENT_OBJECT_PRODUCT = 1;
    public const PAYMENT_OBJECT_EXCISE_PRODUCT = 2;
    public const PAYMENT_OBJECT_JOB = 3;
    public const PAYMENT_OBJECT_SERVICE = 4;
    public const PAYMENT_OBJECT_BET = 5;
    public const PAYMENT_OBJECT_PRIZE = 6;
    public const PAYMENT_OBJECT_TICKET = 7;
    public const PAYMENT_OBJECT_WIN_TICKET = 8;
    public const PAYMENT_OBJECT_RID = 9;
    public const PAYMENT_OBJECT_PAY = 10;
    public const PAYMENT_OBJECT_COMPENSATION = 11;
    public const PAYMENT_OBJECT_COMPOSITE = 12;
    public const PAYMENT_OBJECT_OTHER = 13;

    /**
     *
     * @return int
     */
    public function getPaymentMethod(): int;

    /**
     *
     * @return int
     */
    public function getPaymentObject(): int;

    /**
     *
     * @return string
     */
    public function getNomenclature(): ?string;

    /**
     *
     * @return string
     */
    public function getUserData(): ?string;
}
