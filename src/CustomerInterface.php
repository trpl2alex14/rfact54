<?php

namespace RFAct54;


interface CustomerInterface
{
    /**
     * Customer's email.
     *
     * @return string
     */
    public function getEmail(): ?string;

    /**
     * Customer's phone.
     *
     * @return string
     */
    public function getPhone(): ?string;

    /**
     * Customer's another contact method.
     *
     * @return string
     */
    public function getContact(): ?string;

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
