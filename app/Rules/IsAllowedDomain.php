<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class IsAllowedDomain implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $allowedDomains = [
            'www.marshall.edu',
            'jcesom.marshall.edu',
            'www.herdzone.com',
            'www.formarshallu.org',
            'dynamicforms.ngwebsolutions.com',
            'federation.ngwebsolutions.com',
            'livemarshall.sharepoint.com',
            'www.youtube.com',
            'youtube.com',
            'www.marshallhealth.org'
        ];

        $url = parse_url($value);

        if (!in_array($url['host'], $allowedDomains)) {
            $fail("The :attribute must be a valid domain.");
        }
    }
}
