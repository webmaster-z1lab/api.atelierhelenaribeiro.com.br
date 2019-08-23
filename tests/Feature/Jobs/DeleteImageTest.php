<?php
/**
 * Created by Olimar Ferraz
 * webmaster@z1lab.com.br
 * Date: 22/08/2019
 * Time: 16:37
 */

namespace Tests\Feature\Jobs;

use App\Jobs\DeleteImage;
use App\Jobs\ProcessImage;
use App\Models\Image;
use App\Repositories\ImageRepository;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Storage;
use Modules\Catalog\Models\Template;
use Tests\ImageFiles;
use Tests\TestCase;

class DeleteImageTest extends TestCase
{
    use ImageFiles;

    /**
     * @test
     *
     * @throws \Exception
     */
    public function test_image_delete(): void
    {
        Bus::fake();

        $image = $this->persist();

        Bus::assertDispatched(ProcessImage::class, function (ProcessImage $job) use ($image) {
            $job->handle();

            return $job->image->id === $image->id;
        });

        $image = $image->fresh();

        $album = $image->album;

        foreach ($album as $item) {
            Storage::assertExists($item);
        }

        $image->delete();

        Bus::assertDispatched(DeleteImage::class, function (DeleteImage $job) use ($image) {
            $job->handle();

            return count($image->album->diffAssoc($job->album)->all()) === 0;
        });

        foreach ($album as $item) {
            Storage::assertMissing($item);
        }
    }

    /**
     * @return \App\Models\Image
     */
    private function persist(): Image
    {
        $template = factory(Template::class)->create();

        $file = $this->getImage();

        $image = (new ImageRepository())->create($file);
        $image->template()->associate($template);
        $image->save();

        return $image;
    }

}
