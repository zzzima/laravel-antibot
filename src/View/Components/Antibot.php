<?php

declare(strict_types=1);

namespace Zima\Antibot\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Str;
use Illuminate\View\Component;

class Antibot extends Component
{
    public const TYPE_FIELD_INPUT = 'input';

    public const TYPE_FIELD_CHECKBOX = 'checkbox';

    public const TOKEN_FIELD_NAME = '_abt_token';

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        $config = config('antibot');

        return view('antibot::components.antibot', [
            'fields' => ($config['fields'] ?? []),
            'token' => (string) Str::uuid(),
        ]);
    }
}
