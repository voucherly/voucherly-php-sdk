<?php

namespace VoucherlyApi\Request;

use VoucherlyApi\VoucherlyObject;

/**
 * Inline description of the customer of the Payment. The ID of an existing Customer is not part of it: send it as `customerId`, next to `customer`.
 * - With `customerId` alone, the Payment goes to that Customer.
 * - With `referenceId` alone, the Payment goes to the Customer with that reference, and a new Customer with that reference is created when there is none.
 * - With both `customerId` and `referenceId`, they must name the same Customer. A Customer with no reference takes `referenceId`, unless another Customer already has it, which fails with `409 Conflict`. A Customer with a different reference fails with `400 Bad Request` and code `CUSTOMER_REFERENCE_ID_MISMATCH`: change the reference by updating the Customer.
 * - With neither, a new Customer is created.
 * It replaces the deprecated `customerEmail`, `customerFirstName`, `customerLastName` and `customerPhoneNumber`: do not send them together with it.
 * When both are sent, whichever comes later in the body wins: `customer` replaces all of them, while each of them overwrites only its own field of `customer`.
 */
class CreatePaymentRequestCustomer extends VoucherlyObject
{
    /**
     * The ID of the customer in your own system, such as the user ID of your website, unique per environment. It lets you charge the same Customer again without storing its Voucherly ID.
     */
    public ?string $referenceId;

    /**
     * The email address of the customer associated with this Payment. Required when `customerId` is not provided.
     */
    public ?string $email;

    /**
     * The first name of the customer associated with this Payment.
     */
    public ?string $firstName;

    /**
     * The last name of the customer associated with this Payment.
     */
    public ?string $lastName;

    /**
     * The phone number of the customer associated with this Payment.
     */
    public ?string $phoneNumber;
}
