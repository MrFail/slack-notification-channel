<?php

namespace Illuminate\Notifications\Slack\BlockKit\Composites;

use Illuminate\Notifications\Slack\Contracts\ObjectContract;

class FieldObject implements ObjectContract
{
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
