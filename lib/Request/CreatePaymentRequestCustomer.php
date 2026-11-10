<?php

namespace VoucherlyApi\Request;

use VoucherlyApi\VoucherlyObject;

/**
 * Inline description of the customer of the Payment. The ID of an existing Customer is not part of it: send it as `customerId`, next to `customer`.
 * - With `customerId` alone, the Payment goes to that Customer.
 * - With `referenceId` alone, the Payment goes to the Customer with that reference, and a new Customer with that reference is created when there is none.
 * - With both `customerId` and `referenceId`, they must name the same Customer. A Customer with no reference takes `referenceId`, unless another Customer already has it, which fails with `409 Conflict`. A Customer with a different reference fails with `400 Bad Request` and code `CUSTOMER_REFERENCE_ID_MISMATCH`: change the reference by updating the Customer.
 * - With neither, a new Customer is created, unless `emailVerified` or `phoneVerified` find the Customer that already has that email or phone.
 * A verified email or phone binds the Payment to an existing Customer only when no Customer was found by `customerId` or `referenceId`. The email is tried first, then the phone. With a `referenceId` no Customer has yet, only a Customer without a reference is found, and it takes `referenceId`. The checkout of a Payment shows the saved payment methods and the credit of its Customer: declare a contact verified only when the customer proved it is theirs, for example by logging in with a confirmed email.
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

    /**
     * Whether the customer proved that `email` is theirs. Requires `email`, or the request fails with code `CUSTOMER_EMAIL_REQUIRED`. Defaults to false.
     */
    public ?bool $emailVerified;

    /**
     * Whether the customer proved that `phoneNumber` is theirs. Requires `phoneNumber`, or the request fails with code `CUSTOMER_PHONE_REQUIRED`. Defaults to false.
     */
    public ?bool $phoneVerified;
}
