<?php

use App\Shared\Http\Response;


describe('Response::json()', function () {
    it('encodes data with default status 200 and sets content type', function () {
        $response = Response::json(['ok' => true]);

        expect($response->content)->toBe(json_encode(['ok' => true]));
        expect($response->statusCode)->toBe(200);
        expect($response->contentType)->toBe('application/json; charset=utf-8');
    });

    it('accepts a custom status code', function () {
        $response = Response::json(['ok' => true], 201);

        expect($response->statusCode)->toBe(201);
    });

    it('preserves Unicode characters via JSON_UNESCAPED_UNICODE', function () {
        $response = Response::json(['name' => 'José', 'emoji' => '✓']);

        expect($response->content)->toBe(json_encode(
            ['name' => 'José', 'emoji' => '✓'],
            JSON_UNESCAPED_UNICODE
        ));
        expect($response->content)->toContain('é');
    });

    it('throws JsonException for unencodable data', function () {
        $resource = fopen('php://memory', 'r');

        expect(fn () => Response::json(['data' => $resource]))
            ->toThrow(\JsonException::class);

        fclose($resource);
    });
});


describe('Response::text()', function () {
    it('sets content with default status 200 and content type', function () {
        $response = Response::text('hello');

        expect($response->content)->toBe('hello');
        expect($response->statusCode)->toBe(200);
        expect($response->contentType)->toBe('text/plain; charset=utf-8');
    });

    it('accepts a custom status code', function () {
        $response = Response::text('', 204);

        expect($response->statusCode)->toBe(204);
    });
});




describe('Response::send()', function () {
    it('emits text body content via output buffering', function () {
        $response = Response::text('hello');

        ob_start();
        $response->send();
        $output = ob_get_clean();

        expect($output)->toBe('hello');
    });

    it('emits JSON body content via output buffering', function () {
        $response = Response::json(['ok' => true]);

        ob_start();
        $response->send();
        $output = ob_get_clean();

        expect($output)->toBe(json_encode(['ok' => true]));
    });
});
