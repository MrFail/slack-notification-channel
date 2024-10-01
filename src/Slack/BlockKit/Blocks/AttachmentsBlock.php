<?php

namespace Illuminate\Notifications\Slack\BlockKit\Blocks;

use Closure;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Notifications\Slack\Contracts\BlockContract;
use Illuminate\Support\Traits\Conditionable;
use InvalidArgumentException;
use LogicException;

class AttachmentsBlock implements BlockContract
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

    public array $blocks = [];
    /**
     * The attachment's fields.
     */
    public array $fields;

    /**
     * The fields containing markdown.
     */
    public array $markdown;

    /**
     * The attachment's image url.
     */
    public ?string $imageUrl = null;

    /**
     * The attachment's thumb url.
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
     * Add a field to the attachment.
     */
    public function blocks(Closure $callback): self
    {
        $this->blocks[] = $block = new Block;

        $callback($block);

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
    public function image(string $url): self
    {
        $this->imageUrl = $url;

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

        if (empty($this->fields) && empty($this->blocks)) {
            throw new LogicException('There must be at least one element in each actions block.');
        }

        $body = (!empty($this->fields) && !empty($this->blocks) || empty($this->fields)) ? 'blocks' : 'fields';

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
        ]);

        return array_merge([
            $body => array_map(fn (Arrayable $element) => $element->toArray(), $this->$body),
        ], $optionalFields);
    }
}
