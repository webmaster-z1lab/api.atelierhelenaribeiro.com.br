<?php

namespace Modules\Catalog\Observers;

use Modules\Catalog\Models\Template;

class TemplateObserver
{
    /**
     * @param  \Modules\Catalog\Models\Template  $template
     */
    public function creating(Template $template): void
    {
        $template->thumbnail = config('image.sizes.thumbnail.placeholder');
    }
}
