<?php

namespace Agenda\Helpers;

use Agenda\Entities\Contacts;

trait validatorTrait
{
    /**
     * Valida os dados vindos da Request depois de decodificados de JSON para array
     * @param array $contactsRequest
     * @return bool
     */
    private function validateDataFromRequest(array|null $contactsRequest): bool
    {
        if (!$contactsRequest){
            return false;
        }

        $expectedFields = [
            "name",
            "phone_number",
            "email", 
            "address"
        ];

        for ($i = 0; $i < count($expectedFields); $i++) {
            if (!array_key_exists($expectedFields[$i], $contactsRequest)) {
                return false;
            }
        }

        return true;
    }

    private function validateIntAndReturnResponse(string $args): bool
    {
        return filter_var($args, FILTER_VALIDATE_INT);
    }

    private function createObj(array $contactsRequest): Contacts
    {
        return new Contacts(
            $contactsRequest['name'],
            $contactsRequest['phone_number'],
            $contactsRequest['email'],
            $contactsRequest['address']
        );
    }
}
