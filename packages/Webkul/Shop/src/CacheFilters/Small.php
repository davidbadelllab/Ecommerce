<?php

namespace Webkul\Shop\CacheFilters;

use Illuminate\Support\Str;
use Intervention\Image\Filters\FilterInterface;
use Intervention\Image\Image;

class Small implements FilterInterface
{
  public function applyFilter(Image $image)
    {
        return $image->resize(768, null, function ($constraint) {
            $constraint->aspectRatio();
            $constraint->upsize();
        });
    }
}
