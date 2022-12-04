( function ( blocks, element, blockEditor ) {
    var el = element.createElement;

    blocks.registerBlockType( 'flexwall/flexwall-break', {
        edit: function ( props ) {
            return el(
                'p',
                blockEditor.useBlockProps( {
                    style: {
                        backgroundColor: '#000',
                        color: '#fff',
                        padding: '20px'
                    }
                } ),
                'FlexWall Break'
            );
        },
        save: function () {
            return el('flexwall-break');
        },
    } );
} )( window.wp.blocks, window.wp.element, window.wp.blockEditor );