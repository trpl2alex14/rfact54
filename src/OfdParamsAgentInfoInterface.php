<?php

namespace RFAct54;

/**
 * Interface OfdParamsAgentInfoInterface  from Order or Item entity
 * @package RFAct54
 **/

interface OfdParamsAgentInfoInterface
{

    /**
     * Agent types.
     */
    public const AGENT_TYPE_BANK_AGENT = 1;
    public const AGENT_TYPE_BANK_SUB_AGENT = 2;
    public const AGENT_TYPE_AGENT = 3;
    public const AGENT_TYPE_SUB_AGENT = 4;
    public const AGENT_TYPE_CONFIDENT = 5;
    public const AGENT_TYPE_BROKER = 6;
    public const AGENT_TYPE_OTHER = 7;

    /**
     *
     * @return int
     */
    public function getAgentType(): int;

    /**
     *
     * @return string
     */
    public function getOperationPayingAgent(): ?string;

    /**
     *
     * @return iterable|string[]
     */
    public function getPhonePayingAgent(): ?array;

    /**
     *
     * @return iterable|string[]
     */
    public function getPhonePaymentsOperator(): ?array;

    /**
     *
     * @return string
     */
    public function getInnMTOperatorOperator(): ?string;

    /**
     *
     * @return string
     */
    public function getAddressMTOperatorOperator(): ?string;

    /**
     *
     * @return string
     */
    public function getNameMTOperatorOperator(): ?string;

    /**
     *
     * @return iterable|string[]
     */
    public function getPhoneMTOperatorOperator(): ?array;

}