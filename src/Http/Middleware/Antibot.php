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
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @param string $data
     *
     * @return Response
     */
    public function handle(Request $request, Closure $next, string $data): Response
    {
        //to avoid check for "open page" requests
        //check for bot only if form submitted and antibot token exists in request fields
        $token = $request->input(AntibotComponent::TOKEN_FIELD_NAME);
        if ($token) {
            $this->config = config('antibot');

            $data = unserialize($data);
            $requiredFields = $data['requiredFields'] ?? [];
            $contentFields = $data['contentFields'] ?? [];

            if ($this->isBot($request, $requiredFields, $contentFields)) {
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
     * @param array $requiredFields
     * @param array $contentFields
     *
     * @return bool
     */
    protected function isBot(Request $request, array $requiredFields, array $contentFields): bool
    {
        $emptyFields = $this->config['fields'] ?? [];

        //check if antibot fields are empty
        //bot detected if they are NOT empty
        foreach ($emptyFields as $field) {
            if (!empty($request->input($field))) {
                return true;
            }
        }

        //check if required fields are not empty
        //bot detected if they are empty
        foreach ($requiredFields as $field) {
            if (empty($request->input($field))) {
                return true;
            }
        }

        //check if content fields contains spam
        foreach ($contentFields as $field) {
            $value = $request->input($field);
            if ($value && $this->isSpam($value)) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param string $value
     *
     * @return bool
     */
    protected function isSpam(string $value): bool
    {
        $stopList = $this->config['fields'] ?? [];
        $allowLinks = $this->config['allow_links'] ?? false;

        if ($stopList) {
            $pattern = '/(' . implode('|', $stopList) . ')+/i';

            return preg_match_all($pattern, $value);
        }

        //if links not allowed in config, try to find links, bot is detected if found
        if (!$allowLinks) {
            $pattern = '/(https?:\/\/|ftps?:\/\/|www\.)((?![.,?!;:()]*(\s|$))[^\s]){2,}/i';

            return preg_match_all($pattern, $value);
        }

        return false;
    }
}
