<?php

namespace VoucherlyApi\Request;

use VoucherlyApi\VoucherlyObject;

/**
 * The Company of the customer, identified by your own company ID, so you never have to create it in advance or store its Voucherly ID.
 * The Payment looks for the Company with `referenceId`. When there is none, it creates one with `name` and `joinCode`. When there is one with a different `name`, it renames it: the name in your system wins.
 * The Customer of the Payment then joins the Company and stays in it, however it was found: a new Customer is created in it, and a Customer in another Company moves to it, as when a person changes employer in your system.
 * The same applies to `companyId`. You cannot specify both `company` and `companyId`.
 */
class CreatePaymentRequestCompany extends VoucherlyObject
{
    /**
     * The ID of the Company in your own system, unique per environment.
     * Required.
     */
    public string $referenceId;

    /**
     * The name of the Company. It renames the Company when it differs.
     * Required.
     */
    public string $name;

    /**
     * The 6-character code employees use to join the Company, used only when the Company is created. When it is missing, is not 6 characters long or another Company already has it, Voucherly generates one, so the Payment never fails because of it.
     */
    public ?string $joinCode;
}
