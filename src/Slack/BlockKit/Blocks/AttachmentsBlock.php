<?php

namespace Illuminate\Notifications\Slack\BlockKit\Blocks;

use Closure;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Notifications\Slack\BlockKit\Composites\FieldObject;
use Illuminate\Notifications\Slack\Contracts\BlockContract;
use Illuminate\Notifications\Slack\SlackMessage;
use InvalidArgumentException;
use LogicException;

class AttachmentsBlock implements BlockContract
{
    /**
     * The attachment's title.
     */
    public string $title;

    /**
     * The attachment's URL.
     */
    public string $url;

    /**
     * The attachment's pretext.
     */
    public string $pretext;

    /**
     * The attachment's text content.
     */
    public string $content;

    /**
     * A plain-text summary of the attachment.
     */
    public string $fallback;

    /**
     * The attachment's color.
     */
    public string $color;

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
    public string $imageUrl;

    /**
     * The attachment's thumb url.
     */
    public string $thumbUrl;

    /**
     * The attachment's actions.
     */
    public array $actions = [];

    /**
     * The attachment author's name.
     */
    public string $authorName;

    /**
     * The attachment author's link.
     */
    public string $authorLink;

    /**
     * The attachment author's icon.
     */
    public string $authorIcon;

    /**
     * The attachment's footer.
     */
    public string $footer;

    /**
     * The attachment's footer icon.
     */
    public string $footerIcon;

    /**
     * The attachment's timestamp.
     */
    public int $timestamp;

    /**
     * The attachment's callback ID.
     */
    public int $callbackId;
    protected string $blockId;

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
    public function block(Closure $callback): self
    {
        $this->blocks[] = $message = new SlackMessage();

        $callback($message);

        return $this;
    }

    /**
     * Add a field to the attachment.
     */
    public function field(Closure|string $title, string $content = ''): self
    {
        if (is_callable($title)) {
            $callback = $title;

            $callback($attachmentField = new FieldObject);

            $this->fields[] = $attachmentField;

            return $this;
        }

        $this->fields[$title] = $content;

        return $this;
    }

    /**
     * Set the fields of the attachment.
     */
    public function fields(array $fields): self
    {
        $this->fields = $fields;

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
     * Set the URL to the attachment thumbnail.
     */
    public function thumb(string $url): self
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

        if (empty($this->fields) && empty($this->blocks)) {
            throw new LogicException('There must be at least one element in each actions block.');
        }

        $body = (!empty($this->fields) && !empty($this->blocks) || empty($this->fields)) ? 'blocks' : 'fields';

        $optionalFields = array_filter([
            'block_id' => $this->blockId,
        ]);

        return array_merge([
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
            $body => array_map(fn (Arrayable $element) => $element->toArray(), $this->$body),
        ], $optionalFields);
    }
}
