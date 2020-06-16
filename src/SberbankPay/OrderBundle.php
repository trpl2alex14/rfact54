<?php

namespace RFAct54\SberbankPay;

use RFAct54\OrderBundle as BaseOrderBundle;
use RFAct54\OrderDeliverableInterface;
use RFAct54\CustomerDetailsInterface;
use RFAct54\ItemAgentInterestInterface;
use RFAct54\ItemAttributesInterface;
use RFAct54\ItemInterface;
use RFAct54\ItemTaxableInterface;
use RFAct54\OfdParamsInterface;
use RFAct54\OfdParamsAgentInfoInterface;
use RFAct54\OfdParamsSupplierInfoInterface;
use RFAct54\Exception\InvalidCustomerException;
use RFAct54\Exception\InvalidItemsException;
use RFAct54\OrderInterface;


class OrderBundle extends BaseOrderBundle
{
    /**
     * Cart position id counter.
     *
     * @var int
     */
    protected $positionId = 1;

    /**
     *
     * return orderBundle https://securepayments.sberbank.ru/wiki/doku.php/integration:api:rest:requests:registerpreauth_cart
     * @return array
     * @throws InvalidCustomerException|InvalidItemsException
     *
     **/
    public function toArray(): array
    {
        $array = [
            'orderCreationDate' => date('Y-m-d\TH:i:s', $this->order->getCreationDate()),
            'cartItems' => [],
            'customerDetails' => $this->customerToArray() ?: [],
        ];

        $array['cartItems']['items'] = array_map([$this, 'cartItemToArray'], $this->order->getItems());

        $this->positionId = 1;

        $this->validate($array);

        return $array;
    }

    /**
     *
     * return additionalOfdParams https://securepayments.sberbank.ru/wiki/doku.php/integration:api:rest:requests:registerpreauth_cart
     * @return array
     *
     **/
    public function ofdParamsToArray(): array
    {
        $array = [];
        if ($this->order instanceof OfdParamsInterface) {
            $array = [
                'cashier' => $this->order->getCashier(),
                'additional_check_props' => $this->order->getAdditionalCheckProps(),
            ];

            if ($name = $this->order->getAdditionalUserPropsName()) {
                $array['additional_user_props'] = [
                    'name' => $name,
                    'value' => $this->order->getAdditionalUserPropsValue()
                ];
            }

            $array = array_merge($array, $this->ofdParams($this->order));

        }
        return array_filter($array);
    }

    /**
     *
     * checking required parameters
     * @param $array
     * @throws InvalidCustomerException|InvalidItemsException
     *
     **/
    private function validate($array)
    {
        if(!($array['customerDetails'] && ($array['customerDetails']['phone'] || $array['customerDetails']['email']))){
            throw new InvalidCustomerException();
        }

        if(count($array['cartItems']['items']) === 0){
            throw new InvalidItemsException();
        }
    }

    /**
     *
     * cartItems from orderBundle
     * @param ItemInterface $cartItem
     * @return array
     *
     **/
    protected function cartItemToArray(ItemInterface $cartItem): array
    {
        $array = [
            'positionId' => $this->positionId,
            'name' => $cartItem->getName(),
            'itemDetails' => $cartItem->getDetailParams(),
            'quantity' => [
                'value' => $cartItem->getQuantity(),
                'measure' => $cartItem->getMeasure(),
            ],
            'itemAmount' => $cartItem->getPrice() * $cartItem->getQuantity(),
            'itemCurrency' => $cartItem->getCurrency(),
            'itemCode' => $cartItem->getCode(),
            'itemPrice' => $cartItem->getPrice(),
        ];

        if ($discountValue = $cartItem->getDiscountValue()) {
            $array['discount']['discountValue'] = $discountValue;
            $array['discount']['discountType'] = $cartItem->getDiscountType();
        }

        if ($cartItem instanceof ItemAgentInterestInterface) {
            $array['agentInterest'] = [
                'interestType' => $cartItem->getInterestType(),
                'interestValue' => $cartItem->getInterestValue()
            ];
        }

        if ($cartItem instanceof ItemTaxableInterface) {
            $array['tax'] = array_filter([
                'taxSum' => $cartItem->getTaxSum(),
                'taxType' => $cartItem->getTaxType(),
            ]);
        }

        //version 1.05+
        if ($cartItem instanceof ItemAttributesInterface) {
            $array['itemAttributes'] = $this->itemAttributesToArray($cartItem);
        }

        $this->positionId++;

        return array_filter($array);
    }

    /**
     *
     * extension itemAttributes ver 1.05 FN
     * @param ItemInterface|OrderInterface  $item
     * @return array
     *
     **/
    protected function ofdParams($item): array
    {
        $array = [];
        if ($item instanceof OfdParamsAgentInfoInterface) {
            $array = [
                [
                    'name' => 'agent_info.type',
                    'value' => $item->getAgentType()
                ],

                [
                    'name' => 'agent_info.paying.operation',
                    'value' => $item->getOperationPayingAgent()
                ],
                [
                    'name' => 'agent_info.paying.phones',
                    'value' => $item->getPhonePayingAgent()
                ],
                [
                    'name' => 'agent_info.paymentsOperator.phones',
                    'value' => $item->getPhonePaymentsOperator()
                ],
                [
                    'name' => 'agent_info.MTOperator.address',
                    'value' => $item->getAddressMTOperatorOperator(),
                ],
                [
                    'name' => 'agent_info.MTOperator.inn',
                    'value' => $item->getInnMTOperatorOperator(),
                ],
                [
                    'name' => 'agent_info.MTOperator.name',
                    'value' => $item->getNameMTOperatorOperator(),
                ],
                [
                    'name' => 'agent_info.MTOperator.phones',
                    'value' => $item->getPhoneMTOperatorOperator()
                ]
            ];

        }
        if ($item instanceof OfdParamsSupplierInfoInterface) {
            $array [] = [
                'name' => 'supplier_info.inn',
                'value' => $item->getInnSupplier(),
            ];
            $array [] = [
                'name' => 'supplier_info.phones',
                'value' => $item->getPhonesSupplier(),
            ];
            $array [] = [
                'name' => 'supplier_info.name',
                'value' => $item->getNameSupplier(),
            ];
        }

        return array_filter($array, function($v, $k) {
            return isset($v['value']) && $v['value'];
        }, ARRAY_FILTER_USE_BOTH);
    }

    /**
     *
     * itemAttributes ffd 1.05+
     * @param ItemInterface $cartItem
     * @return array
     *
     **/
    protected function itemAttributesToArray(ItemInterface $cartItem): array
    {
        $array = [ 'attributes' => [] ];
        foreach (['paymentMethod', 'paymentObject', 'nomenclature', 'userData'] as $field) {
            $method = 'get' . ucfirst($field);
            $array['attributes'][]=[
                'name' => $field,
                'value' => $cartItem->{$method}()
            ];
        }

        $array['attributes'] = array_filter($array['attributes'], function($v, $k) {
            return isset($v['value']) && $v['value'];
        }, ARRAY_FILTER_USE_BOTH);

        $array['attributes'] = array_merge($array['attributes'], $this->ofdParams($cartItem));

        return array_filter($array);
    }

    /**
     *
     * customer from orderBundle
     * @return array
     *
     **/
    protected function customerToArray(): array
    {
        $customer = $this->order->getCustomer();
        $array = [];

        foreach (['email', 'phone', 'contact'] as $field) {
            $method = 'get' . ucfirst($field);
            $array[$field] = $customer->{$method}();
        }

        if($array['phone'] && (strlen($array['phone']) < 7  || strlen($array['phone']) > 15)) {
            unset($array['phone']);
        }

        $array['deliveryInfo'] = $this->deliveryInfoToArray();

        //version 1.05+
        if($customer instanceof CustomerDetailsInterface) {
            foreach (['fullName', 'passportNumber', 'inn'] as $field) {
                $method = 'get' . ucfirst($field);
                $array[$field] = $customer->{$method}();
            }

            if ($array['passportNumber'] && strlen($array['passportNumber']) !== 10) {
                unset($array['passportNumber']);
            }

            if ($array['inn'] && (strlen($array['inn']) !== 10 || strlen($array['inn']) !== 12)) {
                unset($array['inn']);
            }
        }

        return array_filter($array);
    }

    /**
     *
     * extension customer from orderBundle
     * @return array
     *
     **/
    protected function deliveryInfoToArray(): array
    {
        $deliveryInfo = [];
        if ($this->order instanceof OrderDeliverableInterface) {
            foreach (['deliveryType', 'country', 'city', 'postAddress'] as $field) {
                $method = 'get' . ucfirst($field);
                $deliveryInfo[$field] = $this->order->{$method}();
            }
        }

        return array_filter($deliveryInfo);
    }
}
