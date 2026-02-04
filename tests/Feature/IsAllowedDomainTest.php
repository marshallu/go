<?php

use App\Rules\IsAllowedDomain;
use Illuminate\Support\Facades\Validator;

test('allows marshall.edu domain', function () {
    $validator = Validator::make(
        ['url' => 'https://www.marshall.edu/admissions'],
        ['url' => ['required', 'url', new IsAllowedDomain]]
    );

    expect($validator->passes())->toBeTrue();
});

test('allows jcesom.marshall.edu domain', function () {
    $validator = Validator::make(
        ['url' => 'https://jcesom.marshall.edu/page'],
        ['url' => ['required', 'url', new IsAllowedDomain]]
    );

    expect($validator->passes())->toBeTrue();
});

test('allows herdzone.com domain', function () {
    $validator = Validator::make(
        ['url' => 'https://www.herdzone.com/sports'],
        ['url' => ['required', 'url', new IsAllowedDomain]]
    );

    expect($validator->passes())->toBeTrue();
});

test('allows formarshallu.org domain', function () {
    $validator = Validator::make(
        ['url' => 'https://www.formarshallu.org/donate'],
        ['url' => ['required', 'url', new IsAllowedDomain]]
    );

    expect($validator->passes())->toBeTrue();
});

test('allows youtube.com domain', function () {
    $validator = Validator::make(
        ['url' => 'https://www.youtube.com/watch?v=123'],
        ['url' => ['required', 'url', new IsAllowedDomain]]
    );

    expect($validator->passes())->toBeTrue();
});

test('allows youtube.com without www', function () {
    $validator = Validator::make(
        ['url' => 'https://youtube.com/watch?v=123'],
        ['url' => ['required', 'url', new IsAllowedDomain]]
    );

    expect($validator->passes())->toBeTrue();
});

test('allows marshallhealth.org domain', function () {
    $validator = Validator::make(
        ['url' => 'https://www.marshallhealth.org/services'],
        ['url' => ['required', 'url', new IsAllowedDomain]]
    );

    expect($validator->passes())->toBeTrue();
});

test('allows dynamicforms.ngwebsolutions.com domain', function () {
    $validator = Validator::make(
        ['url' => 'https://dynamicforms.ngwebsolutions.com/Submit/Form'],
        ['url' => ['required', 'url', new IsAllowedDomain]]
    );

    expect($validator->passes())->toBeTrue();
});

test('allows auth.marshall.edu domain', function () {
    $validator = Validator::make(
        ['url' => 'https://auth.marshall.edu/login'],
        ['url' => ['required', 'url', new IsAllowedDomain]]
    );

    expect($validator->passes())->toBeTrue();
});

test('rejects google.com domain', function () {
    $validator = Validator::make(
        ['url' => 'https://www.google.com'],
        ['url' => ['required', 'url', new IsAllowedDomain]]
    );

    expect($validator->fails())->toBeTrue();
    expect($validator->errors()->first('url'))->toBe('The url must be a valid domain.');
});

test('rejects random domain', function () {
    $validator = Validator::make(
        ['url' => 'https://example.com/page'],
        ['url' => ['required', 'url', new IsAllowedDomain]]
    );

    expect($validator->fails())->toBeTrue();
});

test('rejects marshall.edu without www', function () {
    $validator = Validator::make(
        ['url' => 'https://marshall.edu/page'],
        ['url' => ['required', 'url', new IsAllowedDomain]]
    );

    expect($validator->fails())->toBeTrue();
});
