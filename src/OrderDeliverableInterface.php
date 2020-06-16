<?php

namespace RFAct54;


interface OrderDeliverableInterface
{
    /**
     * Delivery type.
     *
     * @return string|null
     */
    public function getDeliveryType();

    /**
     * 2-symbol country code.
     *
     * @return string
     */
    public function getCountry();

    /**
     * City name.
     *
     * @return string
     */
    public function getCity();

    /**
     * Address.
     *
     * @return string
     */
    public function getPostAddress();
}
