<?php

namespace Illuminate\Notifications\Slack\BlockKit\Blocks;

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


    public function toArray(): array
    {
        if (empty($this->blocks)) {
            throw new LogicException('There must be at least one element in each actions block.');
        }

        return array_map(fn (Arrayable $element) => $element->toArray(), $this->blocks);
    }
}
