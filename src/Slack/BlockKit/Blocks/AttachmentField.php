<?php

namespace Illuminate\Notifications\Slack\BlockKit\Blocks;

use Closure;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Notifications\Slack\Contracts\BlockContract;
use Illuminate\Support\Traits\Conditionable;
use InvalidArgumentException;
use LogicException;

class AttachmentField implements BlockContract
{
    use Conditionable;
    /**
     * The attachment's title.
     */
    public ?string $title = null;

    /**
     * The attachment's URL.
     */
    public ?string $url = null;

    /**
     * The attachment's pretext.
     */
    public ?string $pretext = null;

    /**
     * The attachment's text content.
     */
    public ?string $content = null;

    /**
     * A plain-text summary of the attachment.
     */
    public ?string $fallback = null;

    /**
     * The attachment's color.
     */
    public ?string $color = '#f2c744';

    public array $fields = [];

    /**
     * The fields containing markdown.
     */
    public array $markdown;

    /**
     * The attachment's image url.
     */
    public ?string $thumbUrl = null;

    /**
     * The attachment's actions.
     */
    public array $actions = [];

    /**
     * The attachment author's name.
     */
    public ?string $authorName = null;

    /**
     * The attachment author's link.
     */
    public ?string $authorLink = null;

    /**
     * The attachment author's icon.
     */
    public ?string $authorIcon = null;

    /**
     * The attachment's footer.
     */
    public ?string $footer = null;

    /**
     * The attachment's footer icon.
     */
    public ?string $footerIcon = null;

    /**
     * The attachment's timestamp.
     */
    public ?int $timestamp = null;

    /**
     * The attachment's callback ID.
     */
    public ?int $callbackId = null;
    protected ?string $blockId = null;

    /**
     * Set the title of the attachment.
     */
    public function title(string $title, string $url = null): self
    {
        $this->title = $title;
        $this->url = $url;

        return $this;
    }

    /**
     * Set the pretext of the attachment.
     */
    public function pretext(string $pretext): self
    {
        $this->pretext = $pretext;

        return $this;
    }

    /**
     * Set the content (text) of the attachment.
     */
    public function content(string $content): self
    {
        $this->content = $content;

        return $this;
    }

    /**
     * A plain-text summary of the attachment.
     */
    public function fallback(string $fallback): self
    {
        $this->fallback = $fallback;

        return $this;
    }

    /**
     * Set the color of the attachment.
     */
    public function color(string $color): self
    {
        $this->color = $color;

        return $this;
    }

    /**
     * Set the color of the attachment.
     */
    public function success(): self
    {
        $this->color = '#28a745';

        return $this;
    }

    /**
     * Set the color of the attachment.
     */
    public function warning(): self
    {
        $this->color = '#ffc107';

        return $this;
    }


    /**
     * Set the color of the attachment.
     */
    public function info(): self
    {
        $this->color = '#17a2b8';

        return $this;
    }

    /**
     * Set the color of the attachment.
     */
    public function error(): self
    {
        $this->color = '#dc3545';

        return $this;
    }

    /**
     * Add a field to the attachment.
     */
    public function field( $title, $content, $long = false): self
    {
        $field = new FieldBlock();

        $field->title($title)->content($content)->when($long, fn(FieldBlock $field) => $field->long());

        $this->fields[] =  $field;

        return $this;
    }

    /**
     * Add a field to the attachment.
     */
    public function fields(Closure $callback): self
    {
        $this->fields[] = $field = new FieldBlock;

        $callback($field);

        return $this;
    }

    /**
     * Set the fields containing markdown.
     */
    public function markdown(array $fields): self
    {
        $this->markdown = $fields;

        return $this;
    }

    /**
     * Set the image URL.
     */
    public function thumbnail(string $url): self
    {
        $this->thumbUrl = $url;

        return $this;
    }

    /**
     * Add an action (button) under the attachment.
     */
    public function action(string $title, string $url, string $style = ''): self
    {
        $this->actions[] = [
            'type' => 'button',
            'text' => $title,
            'url' => $url,
            'style' => $style,
        ];

        return $this;
    }

    /**
     * Set the author of the attachment.
     */
    public function author(string $name, ?string $link = null, ?string $icon = null): self
    {
        $this->authorName = $name;
        $this->authorLink = $link;
        $this->authorIcon = $icon;

        return $this;
    }

    /**
     * Set the footer content.
     */
    public function footer(string $footer): self
    {
        $this->footer = $footer;

        return $this;
    }

    /**
     * Set the footer icon.
     */
    public function footerIcon(string $icon): self
    {
        $this->footerIcon = $icon;

        return $this;
    }

    /**
     * Set the timestamp a DateTimeInterface, DateInterval, or the number of seconds that should be added to the current time.
     */
    public function timestamp(int $timestamp): self
    {
        $this->timestamp = $timestamp;

        return $this;
    }

    /**
     * Set the callback ID.
     */
    public function callbackId(string $callbackId): self
    {
        $this->callbackId = $callbackId;

        return $this;
    }

    /**
     * Get the instance as an array.
     */
    public function toArray(): array
    {
        if ($this->blockId && strlen($this->blockId) > 255) {
            throw new InvalidArgumentException('Maximum length for the block_id field is 255 characters.');
        }

        if (!empty($this->fields) && count($this->fields) > 25) {
            throw new LogicException('Maximum limit of fields are 25.');
        }

        $optionalFields = array_filter([
            'block_id' => $this->blockId,
            'author_name' => $this->authorName,
            'author_link' => $this->authorLink,
            'color' => $this->color,
            'fallback' => $this->fallback,
            'footer' => $this->footer,
            'footer_icon' => $this->footerIcon,
            'title' => $this->title,
            'title_link' => $this->url,
            'text' => $this->content,
            'pretext' => $this->pretext,
            'ts' => $this->timestamp,
            'actions' => $this->actions,
            'thumb_url' => $this->thumbUrl
        ], fn ($value) => !empty($value));

        return array_merge([
            'fields' => array_map(fn (Arrayable $field) => $field->toArray(), $this->fields),
        ], $optionalFields);
    }
}
