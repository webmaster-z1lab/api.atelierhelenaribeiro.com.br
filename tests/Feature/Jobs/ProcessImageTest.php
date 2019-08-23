<?php
/**
 * Created by Olimar Ferraz
 * webmaster@z1lab.com.br
 * Date: 22/08/2019
 * Time: 16:37
 */

namespace Tests\Feature\Jobs;

use App\Jobs\ProcessImage;
use App\Models\Image;
use App\Repositories\ImageRepository;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Storage;
use Modules\Catalog\Models\Template;
use Tests\ImageFiles;
use Tests\TestCase;

class ProcessImageTest extends TestCase
{
    use ImageFiles;

    /**
     * @test
     */
    public function test_image_process(): void
    {
        Bus::fake();

        $image = $this->persist();

        Bus::assertDispatched(ProcessImage::class, function (ProcessImage $job) use ($image) {
            $job->handle();

            return $job->image->id === $image->id;
        });

        $this->assertDatabaseHas('images', [
            'is_processed' => TRUE
        ]);

        $image = $image->fresh();

        $album = $image->album;

        foreach ($album as $item) {
            Storage::assertExists($item);
        }
    }

    /**
     * @throws \Throwable
     */
    public function tearDown(): void
    {
        Image::truncate();

        $this->destroyImages();

        parent::tearDown();
    }

    /**
     * @return \App\Models\Image
     */
    private function persist(): Image
    {
        /** @var Template $template */
        $template = factory(Template::class)->create();

        return factory(Image::class)->create(['template_id' => $template->id]);
    }
}
