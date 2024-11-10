<?php

namespace Illuminate\Notifications\Slack\BlockKit\Blocks;

use Closure;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Notifications\Slack\Contracts\BlockContract;
use Illuminate\Support\Traits\Conditionable;
use LogicException;

class AttachmentBlock implements BlockContract
{
    use Conditionable;

    /**
     * The attachment's color.
     */
    public ?string $color = '#f2c744';

    /**
     * The attachment's blocks.
     */
    public array $blocks = [];


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
     * Add a new Divider block to the message.
     */
    public function divider(): self
    {
        $this->blocks[] = new DividerBlock();

        return $this;
    }

    public function header($text, $link = null): self
    {
        $section = new SectionBlock();

        if(!empty($link)) {
            $text = "*<$link|$text>*";
        }

        $section->text($text)->markdown();

        $this->blocks[] = $section;

        return $this;
    }

    public function image($url, $alt): self
    {
        $this->blocks[] = new ImageBlock($url, $alt);

        return $this;
    }

    /**
     * Add a new Section block to the message.
     */
    public function section(Closure $callback): self
    {
        $this->blocks[] = $block = new SectionBlock();

        $callback($block);

        return $this;
    }

    public function footer($text, $icon, $date = null): self
    {
        $block = new ContextBlock();

        $block->image($icon, 'icon');

        $block->text(empty($data) ? "$text | $date" : $text);

        $this->blocks[] = $block;

        return $this;
    }

    /**
     * Get the instance as an array.
     */
    public function toArray(): array
    {
        if (!empty($this->blocks) && count($this->blocks) > 25) {
            throw new LogicException('Maximum limit of blocks are 25.');
        }

        $optionalFields = array_filter([
            'color' => $this->color,
        ], fn($value) => !empty($value));

        return array_merge([
            'blocks' => array_map(fn(Arrayable $block) => $block->toArray(), $this->blocks),
        ], $optionalFields);
    }
}
