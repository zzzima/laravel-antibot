<?php

declare(strict_types=1);

namespace Zima\Antibot\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Zima\Antibot\View\Components\Antibot as AntibotComponent;

class Antibot
{
    protected array $config = [];

    /**
     * @param Request $request
     * @param Closure $next
     * @param string|null $preset
     *
     * @return Response
     */
    public function handle(Request $request, Closure $next, string $preset = null): Response
    {
        //to avoid check for "open page" requests
        //check for bot only if form submitted and antibot token exists in request fields
        $token = $request->input(AntibotComponent::TOKEN_FIELD_NAME);
        if ($token) {
            $this->config = config('antibot');

            if ($this->isBot($request, $preset)) {
                if ($request->ajax()) {
                    return response('', 200)
                        ->header('Content-Type', 'text/plain');
                }

                return redirect()->back();
            }
        }

        return $next($request);
    }

    /**
     * @param Request $request
     * @param string|null $preset
     *
     * @return bool
     */
    protected function isBot(Request $request, ?string $preset = null): bool
    {
        $emptyFields = $this->config['fields'] ?? [];

        //check if antibot fields are empty
        //bot detected if they are NOT empty
        foreach ($emptyFields as $field) {
            if (!empty($request->input($field))) {
                return true;
            }
        }

        if ($preset) {
            $requiredFields = $this->config[$preset]['required_fields'] ?? [];

            //check if required fields are not empty
            //bot detected if they are empty
            foreach ($requiredFields as $field) {
                if (empty($request->input($field))) {
                    return true;
                }
            }

            $contentFields = $this->config[$preset]['content_fields'] ?? [];
            $allowLinks = $this->config[$preset]['allow_links'] ?? ($this->config['allow_links'] ?? false);

            //check if content fields contains spam
            foreach ($contentFields as $field) {
                $value = $request->input($field);
                if ($value && $this->isSpam($value, $allowLinks)) {
                    return true;
                }
            }
        }

        return false;
    }

    /**
     * @param string $value
     * @param bool $allowLinks
     *
     * @return bool
     */
    protected function isSpam(string $value, bool $allowLinks): bool
    {
        $value = mb_strtolower($value);
        $stopList = $this->config['stop_list'] ?? [];

        if ($stopList) {
            $pattern = '/(' . implode('|', $stopList) . ')+/im';

            if (preg_match($pattern, $value)) {
                return true;
            }
        }

        //if links not allowed in config, try to find links, bot is detected if found
        if (!$allowLinks) {
            $pattern = '/(https?:\/\/|ftps?:\/\/|www\.)((?![.,?!;:()]*(\s|$))[^\s]){2,}/im';

            if (preg_match($pattern, $value)) {
                return true;
            }
        }

        return false;
    }
}
