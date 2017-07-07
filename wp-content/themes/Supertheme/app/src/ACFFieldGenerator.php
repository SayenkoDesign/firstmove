<?php
namespace Supertheme;

class ACFFieldGenerator
{
    const TAB_PLACEMENT_LEFT = 'left';
    const TAB_PLACEMENT_TOP = 'top';
    const WYSIWYG_TAB_ALL = 'all';
    const WYSIWYG_TAB_VISUAL = 'visual';
    const WYSIWYG_TAB_TEXT = 'text';
    const WYSIWYG_TOOLBAR_FULL = 'full';
    const WYSIWYG_TOOLBAR_BASIC = 'basic';
    const IMAGE_RETURN_ARRAY = 'array';
    const IMAGE_RETURN_URL = 'url';
    const IMAGE_RETURN_ID = 'id';
    const FILE_RETURN_ARRAY = 'array';
    const FILE_RETURN_URL = 'url';
    const FILE_RETURN_ID = 'id';

    public static function wysiwyg(
        string $key,
        string $label,
        string $name,
        string $tabs = 'all',
        string $toolbar = 'full',
        bool $media_upload = true,
        string $instructions = '',
        string $default_value = null,
        bool $required = true,
        int $width = null,
        string $class = '',
        string $id = ''
    ): array
    {
        return [
            'key'   => $key,
            'label' =>  $label,
            'name'  => $name,
            'type'  => "wysiwyg",
            'required' => (int) $required,
            'conditional_logic' => 0,
            'instructions' => $instructions,
            'default_value' => $default_value,
            'tabs' => $tabs,
            'toolbar' => $toolbar,
            'media_upload' => (int) $media_upload,
            'wrapper' => [
                'width' => $width,
                'class' => $class,
                'id' => $id,
            ],
        ];
    }

    public static function text(
        string $key,
        string $label,
        string $name,
        string $instructions = '',
        string $default_value = null,
        bool $required = true,
        int $width = null,
        string $class = '',
        string $id = ''
    ): array
    {
        return [
            'key'   => $key,
            'label' =>  $label,
            'name'  => $name,
            'type'  => "text",
            'required' => (int) $required,
            'conditional_logic' => 0,
            'instructions' => $instructions,
            'default_value' => $default_value,
            'wrapper' => [
                'width' => $width,
                'class' => $class,
                'id' => $id,
            ],
        ];
    }

    public static function email(
        string $key,
        string $label,
        string $name,
        string $instructions = '',
        string $default_value = null,
        bool $required = true,
        int $width = null,
        string $class = '',
        string $id = ''
    ): array
    {
        return [
            'key'   => $key,
            'label' =>  $label,
            'name'  => $name,
            'type'  => "email",
            'required' => (int) $required,
            'conditional_logic' => 0,
            'instructions' => $instructions,
            'default_value' => $default_value,
            'wrapper' => [
                'width' => $width,
                'class' => $class,
                'id' => $id,
            ],
        ];
    }

    public static function url(
        string $key,
        string $label,
        string $name,
        string $instructions = '',
        string $default_value = null,
        bool $required = true,
        int $width = null,
        string $class = '',
        string $id = ''
    ): array
    {
        return [
            'key'   => $key,
            'label' =>  $label,
            'name'  => $name,
            'type'  => "url",
            'required' => (int) $required,
            'conditional_logic' => 0,
            'instructions' => $instructions,
            'default_value' => $default_value,
            'wrapper' => [
                'width' => $width,
                'class' => $class,
                'id' => $id,
            ],
        ];
    }

    public static function image(
        string $key,
        string $label,
        string $name,
        string $return_format = 'array',
        string $instructions = '',
        bool $required = true,
        int $width = null,
        string $class = '',
        string $id = ''
    ): array
    {
        return [
            'key'   => $key,
            'label' =>  $label,
            'name'  => $name,
            'type'  => "image",
            'required' => (int) $required,
            'conditional_logic' => 0,
            'instructions' => $instructions,
            'return_format' => $return_format,
            'wrapper' => [
                'width' => $width,
                'class' => $class,
                'id' => $id,
            ],
        ];
    }

    public static function file(
        string $key,
        string $label,
        string $name,
        string $instructions = '',
        string $return_format = 'array',
        string $default_value = null,
        bool $required = true,
        int $width = null,
        string $class = '',
        string $id = ''
    ): array
    {
        return [
            'key'   => $key,
            'label' =>  $label,
            'name'  => $name,
            'type'  => "file",
            'required' => (int) $required,
            'conditional_logic' => 0,
            'instructions' => $instructions,
            'default_value' => $default_value,
            'wrapper' => [
                'width' => $width,
                'class' => $class,
                'id' => $id,
            ],
            'return_format' => $return_format,
        ];
    }

    public static function tab(
        string $key,
        string $label,
        string $name,
        bool $required = true,
        string $placement = 'left',
        bool $endpoint = false
    ): array
    {
        return [
            'key'   => $key,
            'label' =>  $label,
            'name'  => $name,
            'type'  => "tab",
            'required' => (int) $required,
            'conditional_logic' => 0,
            'placement' => $placement,
            'endpoint' => (int) $endpoint,
        ];
    }

    public static function repeater(
        string $key,
        string $label,
        string $name,
        array $fields,
        int $min = 1,
        int $max = null,
        bool $required = true,
        int $width = null,
        string $class = '',
        string $id = ''
    ): array
    {
        return [
            'key'   => $key,
            'label' =>  $label,
            'name'  => $name,
            'type'  => "repeater",
            'min' => $min,
            'max' => $max,
            'layout' => 'block',
            'sub_fields' => $fields,
            'required' => (int) $required,
            'conditional_logic' => 0,
            'wrapper' => [
                'width' => $width,
                'class' => $class,
                'id' => $id,
            ],
        ];
    }

}