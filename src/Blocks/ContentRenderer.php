<?php

namespace VanOns\Laraberg\Blocks;

class ContentRenderer
{
    /**
     * @var BlockParser
     */
    private $parser;

    public function __construct(BlockParser $parser)
    {
        $this->parser = $parser;
    }

    public function render(string $content): string
    {
        $output = '';
        $blocks = $this->parser->parse($content);

        foreach ($blocks as $block) {
            $output .= $block->render();
        }

        return $output;
    }

    public function renderIntro(string $content, int $num_blocks = null): string
    {
        $output = '';
        $blocks = $this->parser->parse($content);

        // Render up to a `core/more` block if one exists, or else render blocks
        $num_blocks = $num_blocks ?? config('laraberg.render.intro_blocks', 2);
        foreach ($blocks as $block_key => $block) {
            if ($block->blockName === 'core/more') {
                $num_blocks = $block_key + 1;
                break;
            }
        }

        foreach ($blocks as $block_key => $block) {
            if ($block_key >= $num_blocks) {
                break;
            }
            $output .= $block->render();
        }

        return $output;
    }
}
