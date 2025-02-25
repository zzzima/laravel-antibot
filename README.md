Installation
------------

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Either run

```
php composer.phar require --prefer-dist zima/laravel-antibot "*"
```

or add

```
"zima/laravel-antibot": "*"
```

## Publish config

```
php artisan vendor:publish --tag=antibot-config
```

## Usage

In published config file, you are able to change names of antibot fields, define list of stop words
and decide whether to allow links in your content fields.

```php
    // List of antibot fields which will be added to form
    'fields' => [
        'telephone' => Antibot::TYPE_FIELD_INPUT,
        'verify' => Antibot::TYPE_FIELD_CHECKBOX,
    ],
```
```php
    // Whether to allow links in form fields
    'allow_links' => false,
```

```php
    // List of words for detecting bot
    'stop_list' => [
        'погиб', 'плен', 'украин', 'акци', 'скидк', 'всу', 'вооружен',
        'вооруж', 'силы', 'сил', 'солдат', 'биткоин', 'помощь', 'помощ', 'боев',
        'деньг', 'денег', 'free', 'sale', 'porn',
    ],
```

Please configure your presets if necessary

```php
    'feedback' => [
        'required_fields' => ['name', 'email'], // bot is detected if these fields are empty
        'content_fields' => ['description'],    // fields in which spam is searched (ignored if empty or not specified)
        'allow_links' => true,                  // use it if you need to overwrite global 'allow_links
    ],
```

Use parameter *required_fields* when you need to define fields that should not be empty.

Use parameter *content_fields* when you need to define the fields where words form config "stop_list" will be searched for.

Use parameter *allow links* when you need to overwrite global param *allow_links* for special preset.

All params within preset are optional, if they are not defined or empty this level of bot-check will be ignored.

Use dot notation for nested fields e.g. for fields named MyForm['text'] use 'MyForm.text'.

Assign middleware to route

```php
Route::match(['get', 'post'], '/test', [TestController::class, 'index'])
    ->name('test')
    ->middleware('antibot:feedback');

# or without preset for base level of antibot detecting only

Route::match(['get', 'post'], '/test', [TestController::class, 'index'])
    ->name('test')
    ->middleware('antibot');

```

## Output the Antibot use Blade Component

You can use the output component, please add it inside form tags:

```blade
<form>
    <x-zima-antibot/>

    <button type="submit">Save</button>
</form>
```

## License

[MIT license](LICENSE.md).
