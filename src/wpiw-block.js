const { __ } = wp.i18n;
const {
	registerBlockType,
	Editable,
	BlockControls,
	AlignmentToolbar,
	InspectorControls,
	BlockDescription,
	Toolbar,
	source: { children }
} = wp.blocks;

registerBlockType( 'wpiw/instagram-feed', {
	title: __( 'Instagram Feed' ),
	icon: 'images-alt',
	category: 'widgets',
	keywords: [ __( 'widget' ), __( 'wp' ) ],
	supportHTML: false,
/*	attributes: {
		username: {
			type: 'string',
		},
		number: {
			type: 'number',
		},
		size: {
			type: 'string',
		},
		layout: {
			type: 'string',
		}
	},*/

	edit: props => {
		var content = props.attributes.content,
			align = props.attributes.align,
			focus = props.focus;
			layout = props.attributes.layout;

		const layoutControls = [
			{
				icon: 'list-view',
				title: __( 'List View' ),
				//onClick: () => setAttributes( { layout: 'list' } ),
				isActive: layout === 'list',
			},
			{
				icon: 'grid-view',
				title: __( 'Grid View' ),
				//onClick: () => setAttributes( { layout: 'grid' } ),
				isActive: layout === 'grid',
			},
		];

		function onChangeAlignment( newAlignment ) {
			props.setAttributes( { align: newAlignment } );
		}

		return (
			<div>
				{
				// toolbar controls
				!! focus && (
						<BlockControls key="controls">
							<AlignmentToolbar
								value={ align }
								onChange={ onChangeAlignment }
								controls={ [ 'center', 'wide', 'full' ] }
							/>
						</BlockControls>
					)
				}
				{
				// editng ui
				!! focus && (
					<InspectorControls key="inspector">
						<BlockDescription>
							<p>{ __( 'Shows your latest Instagram images.' ) }</p>
						</BlockDescription>
					</InspectorControls>
					)	
					
				}

				<ul>
					<li>Hello</li>
					<li>Hello again</li>
				</ul>
			</div>
		);
	},

	save: props => {
		const { attributes: { title } } = props;
		return <h1> { title } </h1>;
	},
	
} );