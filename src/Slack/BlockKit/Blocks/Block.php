<?php

namespace Illuminate\Notifications\Slack\BlockKit\Blocks;

use Closure;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Notifications\Slack\BlockKit\Elements\ImageElement;
use Illuminate\Notifications\Slack\Contracts\BlockContract;
use Illuminate\Support\Traits\Conditionable;
use LogicException;

class Block implements BlockContract
{
    use Conditionable;

    protected array $blocks;

    /**
     * Add an image element to the block.
     */
    public function image(string $imageUrl, string $altText = null): ImageElement
    {
        return tap(new ImageElement($imageUrl, $altText), fn (ImageElement $element) => $this->blocks[] = $element);
    }

    /**
     * Add a new Context block to the message.
     */
    public function context(Closure $callback): self
    {
        $this->blocks[] = $block = new ContextBlock();

        $callback($block);

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

    /**
     * Add a new Section block to the message.
     */
    public function section(Closure $callback): self
    {
        $this->blocks[] = $block = new SectionBlock();

        $callback($block);

        return $this;
    }

    /**
     * Add a new Header block to the message.
     */
    public function header(string $text, Closure $callback = null): self
    {
        $this->blocks[] = new HeaderBlock($text, $callback);

        return $this;
    }

    public function toArray(): array
    {
        if (empty($this->blocks)) {
            throw new LogicException('There must be at least one element in each actions block.');
        }

        return array_map(fn (Arrayable $element) => $element->toArray(), $this->blocks);
    }
}
