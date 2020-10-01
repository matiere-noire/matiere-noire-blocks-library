const { registerBlockType } = wp.blocks
const { serverSideRender: ServerSideRender } = wp
const { useSelect } = wp.data
const { InspectorControls } = wp.blockEditor
const { PanelBody, PanelRow, TextControl, Popover, Button, Disabled, SelectControl } = wp.components

registerBlockType('mn-blocks/post-type-archive', {
    title: 'Archive de type de contenu',
    icon: 'admin-home',
    category: 'mn-blocks',
    attributes: {
        postType: {
            type: 'string',
            default: 'post'
        },
        numberOfPosts: {
            type: 'integer',
            default: 3
        },
    },
    edit: ({ attributes, setAttributes }) => {
        const {postType, numberOfPosts} = attributes
        const allPostType = useSelect(( select ) => {
            return select('core').getPostTypes()
        }, []);
        return (
            <>
                <InspectorControls>
                    <PanelBody title="Contenu">
                        <PanelRow>
                            <TextControl label="Nombre de publication à afficher" type="number" value={numberOfPosts} onChange={(nb) => setAttributes({numberOfPosts: nb})} />
                        </PanelRow>
                        <PanelRow>
                            <SelectControl
                                label="Type de contenu"
                                value={postType}
                                onChange={(postType) => setAttributes({postType: postType})}
                                options={ allPostType && allPostType.map(p => ({ label: p.name, value: p.slug } )) }
                            />
                        </PanelRow>

                    </PanelBody>
                </InspectorControls>
                <Disabled>
                    <ServerSideRender
                        block="mn-blocks/post-type-archive"
                        attributes={ {
                            postType,
                            numberOfPosts,
                            } }
                    />
                </Disabled>
            </>
        )
    },
})