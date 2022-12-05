<?php

namespace Palasthotel\WordPress\FlexWall;


use Palasthotel\WordPress\FlexWall\Components\Component;

/**
 * Class GutenbergBlock
 *
 * @package FlexWall
 */
class GutenbergBlock extends Component {

    /**
     * @var string[] $blocks
     */
    public $blocks = [
        'flexwall-break'
    ];

    public function onCreate() {
        add_action( 'init', array( $this, 'init' ), 10, 0 );
    }

    public function init() {
        foreach ($this->blocks as $block) {
            register_block_type( __DIR__ . '/../blocks/' . $block );
        }
    }

}
