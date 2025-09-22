<?php

namespace Tests\Unit;

use App\Jobs\ProcessCardsJob;
use App\Jobs\SendProcessedFileJob;
use App\Services\CardProcessor\PackageAdapterInterface;
use App\Services\ProcessorFactory\ProcessorFactory;
use App\Support\Enums\FileType;
use App\Support\Helpers\PathHelper;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ProcessCardsJobTest extends TestCase
{
    private string $path;

    protected function setUp(): void
    {
        parent::setUp();

        Queue::fake();
        config(['file-processor.path' => 'test/uploads']);
        $this->path = Storage::path(PathHelper::uploadPath().'uploaded.xlsx');
    }

    protected function tearDown(): void
    {
        Storage::deleteDirectory(PathHelper::uploadPath());

        parent::tearDown();
    }

    public function testFileProcessedWithOneChunk(): void
    {
        $this->prepareXlsx(10, $this->path);

        $uploadedFile = new UploadedFile($this->path, 'uploaded.xlsx', FileType::Xlsx->value, null, true);
        $request = new Request();
        $request->files->set('file', $uploadedFile);
        $adapter = App::make(PackageAdapterInterface::class);

        $factory  = new ProcessorFactory($adapter, $request);
        $processor = $factory->create();

        $uuid = 'uploaded.xlsx';

        Cache::put($uuid, 'http://url.com');

        $job = new ProcessCardsJob($processor ,$uuid);
        $job->handle();

        Queue::assertPushed(SendProcessedFileJob::class);

        $this->assertTrue(File::exists($this->path));
        $this->assertTrue(Storage::exists(PathHelper::processedPath().$uuid));
    }

    public function testFileProcessedWithByChunks(): void
    {
        config(['file-processor.chunk_size' => 2]);

        $this->prepareXlsx(10, $this->path);

        $uploadedFile = new UploadedFile($this->path, 'uploaded.xlsx', FileType::Xlsx->value, null, true);
        $request = new Request();
        $request->files->set('file', $uploadedFile);
        $adapter = App::make(PackageAdapterInterface::class);

        $factory  = new ProcessorFactory($adapter, $request);
        $processor = $factory->create();

        $uuid = 'uploaded.xlsx';

        Cache::put($uuid, 'http://url.com');

        $job = new ProcessCardsJob($processor ,$uuid);
        $job->handle();

        Queue::assertPushed(SendProcessedFileJob::class);

        $this->assertTrue(File::exists($this->path));
        $this->assertTrue(Storage::exists(PathHelper::processedPath().$uuid));
    }
}
