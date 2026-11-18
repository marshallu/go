<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Translation\PotentiallyTranslatedString;

class IsAllowedDomain implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  Closure(string): PotentiallyTranslatedString  $fail
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
            'www.marshallhealth.org',
            'auth.marshall.edu',
            'apps.marshall.edu',
        ];

        $host = is_string($value) ? parse_url($value, PHP_URL_HOST) : null;

        if (! in_array($host, $allowedDomains, true)) {
            $fail('The :attribute must be a valid domain.');
        }
    }
}
