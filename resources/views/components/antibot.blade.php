@use('Zima\Antibot\View\Components\Antibot')

<div style="display: none;">
    @foreach ($fields as $field => $type)
        @if ($type === Antibot::TYPE_FIELD_INPUT)
            <input type="text" name="{{ $field }}" value="">
        @elseif ($type === Antibot::TYPE_FIELD_CHECKBOX)
            <input type="checkbox" name="{{ $field }}" value="1">
        @endif
    @endforeach
    <input type="hidden" name="{{ Antibot::TOKEN_FIELD_NAME }}" value="{{ $token  }}">
</div>
