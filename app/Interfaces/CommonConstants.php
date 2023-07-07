<?php

namespace App\Interfaces;

interface CommonConstants
{
    public const READY = 'ready';

    public const ACTIVE = 'active';

    public const INACTIVE = 'inactive';

    public const INPROGRESS = 'in progress';

    public const PENDING = 'pending';

    public const ERROR = 'error';

    public const FREE = 'free';

    public const COMPLETED = 'completed';

    public const INIT = 'init';

    public const REFUNDED = 'refunded';

    public const GENERATED = 'generated';

    public const IN_PROGRESS = 'in_progress';

    public const REQUESTED = 'requested';

    public const PUBLISHED = 'published';

    public const REJECTED = 'rejected';

    public const DRAFT = 'draft';

    public const ONE_TIME = 'one_time';

    public const RECURRING = 'recurring';

    public const MONTHLY = 'monthly';

    public const YEARLY = 'yearly';

    public const DEFAULT_PAGINATION = 10;
    public const THROTTLEABLE_TOKEN_TYPES = ['api'];  // token type countable for api quota
  
    public const DISK_WARN_USER_CHUNK = 10;

    public const PLAN_INTERVAL = [
        'monthly' => 1,
        'yearly' => 2
    ];
   
    public const GALLERY_MEDIA_TYPE = [
        'IMAGE' => 'image',
        'VIDEO' => 'video'
    ];

    public const PARENT = 'parent';

    public const CHILD = 'child';

}
