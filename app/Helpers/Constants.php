<?php

declare(strict_types=1);

namespace App\Helpers;

class Constants
{
    public const CACHE_TIME_1                  = 1;     // 1 sec
    public const CACHE_TIME_30                 = 1800;  // 30 min
    public const CACHE_TIME_60                 = 3600;  // 60 min
    public const CACHE_TIME_1_DAY              = 86400; // 60*24 min
    public const DEFAULT_DATE_FORMAT           = 'Y/m/d';
    public const DEFAULT_DATE_FORMAT_WITH_TIME = 'H:i , Y/m/d';
    public const MAX_IMAGE_SIZE                = 2;  // 2MB
    public const MAX_VIDEO_SIZE                = 20; // 20MB
    public const DEFAULT_PAGINATE              = 15;
    public const DEFAULT_PAGINATE_WEB          = 21;
    
    // 1:1
    public const RESOLUTION_50_SQUARE   = '50x50';
    public const RESOLUTION_100_SQUARE  = '100x100';
    public const RESOLUTION_480_SQUARE  = '480x480';
    public const RESOLUTION_512_SQUARE  = '512x512';
    public const RESOLUTION_720_SQUARE  = '720x720';
    public const RESOLUTION_1080_SQUARE = '1080x1080';

    // 4:3
    public const RESOLUTION_400_300 = '400x300';
    public const RESOLUTION_800_600 = '800x600';

    // 16:9
    public const RESOLUTION_854_480    = '854x480';
    public const RESOLUTION_1280_720   = '1280x720';
    public const RESOLUTION_1920_1080  = '1920x1080';

    public const RESOLUTION_1280_400   = '1280x400'; // for full width slider
}
