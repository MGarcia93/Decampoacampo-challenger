<?php

use App\Shared\Http\Request;

describe('Request', function () {
    $snapshot = [];

    beforeEach(function () use (&$snapshot) {
        $snapshot = [
            '_SERVER' => $_SERVER,
            '_GET'    => $_GET,
            '_POST'   => $_POST,
        ];

        $_SERVER = ['REQUEST_URI' => '/'];
        $_GET    = [];
        $_POST   = [];
    });

    afterEach(function () use (&$snapshot) {
        $_SERVER = $snapshot['_SERVER'];
        $_GET    = $snapshot['_GET'];
        $_POST   = $snapshot['_POST'];
    });


    it('detects the HTTP method from REQUEST_METHOD superglobal', function () {
        $_SERVER['REQUEST_METHOD'] = 'POST';

        $request = new Request();

        expect($request->getMethod())->toBe('POST');
    });

    it('defaults to GET when REQUEST_METHOD is not set', function () {
        unset($_SERVER['REQUEST_METHOD']);

        $request = new Request();

        expect($request->getMethod())->toBe('GET');
    });


    it('removes a trailing slash from the URI path', function () {
        $_SERVER['REQUEST_URI'] = '/users/';

        $request = new Request();

        expect($request->getPath())->toBe('/users');
    });

    it('returns empty string for root path "/"', function () {
        $_SERVER['REQUEST_URI'] = '/';

        $request = new Request();

        expect($request->getPath())->toBe('');
    });

    it('excludes the query string from the URI path', function () {
        $_SERVER['REQUEST_URI'] = '/users?page=1';

        $request = new Request();

        expect($request->getPath())->toBe('/users');
    });


    it('merges $_GET into parameters for a GET request', function () {
        $_SERVER['REQUEST_METHOD'] = 'GET';
        $_GET = ['page' => '2'];

        $request = new Request();

        expect($request->getParameter('page'))->toBe('2');
    });

    it('does not overwrite constructor-seeded parameters with $_GET', function () {
        $_SERVER['REQUEST_METHOD'] = 'GET';
        $_GET = ['id' => '99'];

        $request = new Request((object) ['id' => '1']);

        expect($request->getParameter('id'))->toBe('1');
    });

    it('loads POST form data from $_POST', function () {
        $_SERVER['REQUEST_METHOD'] = 'POST';
        $_POST = ['name' => 'test'];

        $request = new Request();

        expect($request->getParameter('name'))->toBe('test');
    });


    it('returns null for a parameter key that was never set', function () {
        $_SERVER['REQUEST_METHOD'] = 'GET';

        $request = new Request();

        expect($request->getParameter('missing'))->toBeNull();
    });


   
});
