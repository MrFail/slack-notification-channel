<?php

namespace Illuminate\Notifications\Slack\BlockKit\Blocks;

use Closure;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Notifications\Slack\BlockKit\Elements\ImageElement;
use Illuminate\Notifications\Slack\Contracts\BlockContract;
use Illuminate\Support\Traits\Conditionable;
use LogicException;

class FieldBlock implements BlockContract
{
    use Conditionable;

    /**
     * The title field of the attachment field.
     */
    protected string $title;

    /**
     * The content of the attachment field.
     */
    protected string $content;

    /**
     * Whether the content is short.
     */
    protected bool $short = true;

    /**
     * Set the title of the field.
     */
    public function title(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Set the content of the field.
     */
    public function content(string $content): self
    {
        $this->content = $content;

        return $this;
    }

    /**
     * Indicates that the content should not be displayed side-by-side with other fields.
     */
    public function long(): self
    {
        $this->short = false;

        return $this;
    }

    /**
     * Get the array representation of the attachment field.
     */
    public function toArray(): array
    {
        return [
            'title' => $this->title,
            'value' => $this->content,
            'short' => $this->short,
        ];
    }
}
