<?php

namespace RFAct54;


interface CustomerDetailsInterface
{
    /**
     * Customer's full name.
     *
     * @return string
     */
    public function getFullName(): ?string;

    /**
     * Customer's passport serial & number, format: 2222888888.
     *
     * @return string
     */
    public function getPassportNumber(): ?string;

    /**
     * Customer's INN, format 10 or 12 numbers.
     *
     * @return string
     */
    public function getInn(): ?string;
}
