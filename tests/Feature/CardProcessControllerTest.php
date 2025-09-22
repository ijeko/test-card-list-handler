<?php

namespace Tests\Feature;

use App\Http\Controllers\CardProcessController;
use App\Support\Helpers\PathHelper;
use App\Support\Helpers\SignatureHelper;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class CardProcessControllerTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        Queue::fake();
        config(['file-processor.path' => 'test/uploads']);
    }

    protected function tearDown(): void
    {
        Storage::deleteDirectory(PathHelper::uploadPath());

        parent::tearDown();
    }

    public function testSendFileWithWrongSignatureShould403(): void
    {
        $body = [
            'file' => UploadedFile::fake()->create('test.xlsx'),
        ];
        $headers = [
            'X-Url' => 'http://example.com/',
            'X-Signature' => 'wrong-signature',
        ];
        $this->post(action(CardProcessController::class), $body, $headers)
            ->assertStatus(403);
    }

    public function testSendFileWithCorrectSignatureShould200(): void
    {
        $url = 'http://example.com/cards/process';
        $signature = SignatureHelper::generate($url);

        $body = [
            'file' => UploadedFile::fake()->create('test.xlsx'),
        ];
        $headers = [
            'X-Url' => $url,
            'X-Signature' => $signature,
        ];
        $this->post(action(CardProcessController::class), $body, $headers)
            ->assertStatus(200);
    }

    public function testSendFileWithWrongFileTypeShould422(): void
    {
        $url = 'http://example.com/cards/process';
        $signature = SignatureHelper::generate($url);

        $body = [
            'file' => UploadedFile::fake()->create('test.csv'),
        ];
        $headers = [
            'X-Url' => $url,
            'X-Signature' => $signature,
        ];
        $this->post(action(CardProcessController::class), $body, $headers)
            ->assertStatus(422);
    }
}
