/**
 * Retrieves the translation of text.
 *
 * @see https://developer.wordpress.org/block-editor/packages/packages-i18n/
 */
import { __ } from '@wordpress/i18n';

/**
 * Lets webpack process CSS, SASS or SCSS files referenced in JavaScript files.
 * Those files can contain any CSS code that gets applied to the editor.
 *
 * @see https://www.npmjs.com/package/@wordpress/scripts#using-css
 */
import './editor.scss';

import { InspectorControls, ColorPalette } from '@wordpress/block-editor';
import {
	PanelBody,
	PanelRow,
	ToggleControl,
	RangeControl,
	SelectControl,
	TabPanel,
	TextControl,
	FontSizePicker,
} from '@wordpress/components';

import ServerSideRender from '@wordpress/server-side-render';

/**
 * The edit function describes the structure of your block in the context of the
 * editor. This represents what the editor will render when the block is used.
 *
 * @see https://developer.wordpress.org/block-editor/developers/block-api/block-edit-save/#edit
 *
 * @param {Object} [props]           Properties passed from the editor.
 * @param {string} [props.className] Class name generated for the block.
 *
 * @return {WPElement} Element to render.
 */
export default function Edit( props ) {
	const {
		attributes,
		setAttributes,
		className,
	} = props;

	if ( ! attributes.idBlock ) {
		setAttributes( { idBlock: Math.floor( Math.random() * 10000 ) } );
	}

	const categorys = variables.categorys;
	const cat_name  = [];
	cat_name.push( { label: __( 'All categories', 'soivigol-post-list' ), value: 'all' } );
	Object.entries( categorys ).forEach( element => {
		cat_name.push( { label: element[1]['name'], value: element[1]['slug'] } );
	});

	const allPostTypes = variables.custom_posts;
	const postTypes = [];
	allPostTypes.forEach(element => {
		const eleUppercase = element.charAt(0).toUpperCase() + element.slice(1);
		postTypes.push( { label: eleUppercase, value: element } );
	});

	const titleTags = [
		{ label: 'H2', value:'h2'},
		{ label: 'H3', value:'h3'},
		{ label: 'H4', value:'h4'},
		{ label: 'H5', value:'h5'},
		{ label: 'H6', value:'h6'},
	];

	const fontSizes = [
		{
			name: __( 'Small' ),
			slug: 'small',
			size: 12,
		},
		{
			name: __( 'Medium' ),
			slug: 'medium',
			size: 20,
		},
		{
			name: __( 'Big' ),
			slug: 'big',
			size: 26,
		},
	];

	const fallbackFontSize = 16;

	const aspectRatios = [
		{ label: '1:1', value:'aspect-1x1'},
		{ label: '3:2', value:'aspect-3x2'},
		{ label: '2:3', value:'aspect-2x3'},
		{ label: '4:3', value:'aspect-4x3'},
		{ label: '3:4', value:'aspect-3x4'},
		{ label: '16:9', value:'aspect-16x9'},
		{ label: '9:16', value:'aspect-9x16'},
	];

	return (
		<div>
			<InspectorControls>
				<PanelBody
					title={ __( 'Post type', 'soivigol-post-list' ) }
					initialOpen={ true }
				>
					<PanelRow>
						<SelectControl
							label={ __( 'Post type', 'soivigol-post-list' ) }
							options={ postTypes }
							value= { attributes.tipoPost }
							onChange={ (value) => setAttributes( { tipoPost: value } ) }
						/>
					</PanelRow>
				</PanelBody>
				<PanelBody
					title={ __( 'Category', 'soivigol-post-list' ) }
					initialOpen={ false }
				>
					<PanelRow>
						<SelectControl
							label={ __( 'Category', 'soivigol-post-list' ) }
							options={ cat_name }
							value= { attributes.category }
							onChange={ (value) => setAttributes( { category: value } ) }
						/>
					</PanelRow>
				</PanelBody>
				<PanelBody
					title={ __( 'Choose layout', 'soivigol-post-list' ) }
					initialOpen={ false }
				>
					<PanelRow>
						<RangeControl
							label= { __( 'Number of posts', 'soivigol-post-list' ) }
							value={ attributes.numPosts }
							onChange={ (value) => setAttributes( { numPosts: value } ) }
							min={ 3 }
							max={ 21 }
						/>
					</PanelRow>
					<PanelRow>
						<RangeControl
							label= { __( 'Number of columns', 'soivigol-post-list' ) }
							value={ attributes.numCol }
							onChange={ (value) => setAttributes( { numCol: value } ) }
							min={ 1 }
							max={ 4 }
						/>
					</PanelRow>
					<PanelRow>
						<ToggleControl
							label= { __( 'Pagination', 'soivigol-post-list' ) }
							value={ attributes.pagination }
							onChange={  (value) => setAttributes( { pagination: value } ) }
							checked= { attributes.pagination }
						/>
					</PanelRow>
				</PanelBody>
				<PanelBody
					title={ __( 'Colors', 'soivigol-post-list' ) }
					initialOpen={ false }
				>
					<TabPanel className="my-tab-panel"
						activeClass="active-tab"
						tabs={ [
							{
								name: 'tab1',
								title: __( 'Normal', 'soivigol-post-list' ) ,
								className: 'tab-one',
							},
							{
								name: 'tab2',
								title: __( 'Hover', 'soivigol-post-list' ),
								className: 'tab-two',
							},
						] }>
							{
							( tab ) => {
								return ('tab1' === tab.name ? (
									<>
										<PanelRow>
											{ __( 'Background color', 'soivigol-post-list' ) }
										</PanelRow>
										<PanelRow>
										<ColorPalette
												label={__('Background color', 'soivigol-post-list')}
												value={attributes.bgColor}
												onChange={(value) => setAttributes( { bgColor: value } )} />
										</PanelRow>
										<PanelRow>
											{__('Text color', 'soivigol-post-list')}
										</PanelRow>
										<PanelRow>
											<ColorPalette
												label={__('Text color', 'soivigol-post-list')}
												value={attributes.textColor}
												onChange={(value) => setAttributes( { textColor: value } )} />
										</PanelRow>
										<PanelRow>
											<ToggleControl
												label= { __( 'Shadow', 'soivigol-post-list' ) }
												value={ attributes.boxShadow }
												onChange={ (value) => setAttributes( { boxShadow: value } ) }
												checked= { attributes.boxShadow}
											/>
										</PanelRow>
									</>
								) : (
										<div>
											<PanelRow>
												{ __( 'Background color hover', 'soivigol-post-list' ) }
											</PanelRow>
											<PanelRow>
												<ColorPalette
													label={__('Background color hover', 'soivigol-post-list')}
													value={attributes.bgColorH}
													onChange={(value) => setAttributes( { bgColorH: value } )} />
											</PanelRow>
											<PanelRow>
												{__('Text color hover', 'soivigol-post-list')}
											</PanelRow>
											<PanelRow>
												<ColorPalette
													label={__('Text color hover', 'soivigol-post-list')}
													value={attributes.textColorH}
													onChange={(value) => setAttributes( { textColorH: value } )} />
											</PanelRow>
											<PanelRow>
												<ToggleControl
													label= { __( 'Shadow hover', 'soivigol-post-list' ) }
													value={ attributes.boxShadowH }
													onChange={ (value) => setAttributes( { boxShadowH: value } ) }
													checked= { attributes.boxShadowH }
												/>
											</PanelRow>
										</div>
									));
							}
						}
    				</TabPanel>
				</PanelBody>
				<PanelBody
					title={ __( 'Box border', 'soivigol-post-list' ) }
					initialOpen={ false }
					>
					<PanelRow>
						<RangeControl
							label= { __( 'Border radius', 'soivigol-post-list' ) }
							value={ attributes.borderRadius }
							onChange={ (value) => setAttributes( { borderRadius: value } ) }
							min={ 0 }
							max={ 60 }
						/>
					</PanelRow>
				</PanelBody>
				<PanelBody
					title={ __( 'Image', 'soivigol-post-list' ) }
					initialOpen={ false }>
					<PanelRow>
						<SelectControl
							label={ __( 'Aspect ratio of featured image', 'soivigol-post-list' ) }
							options={ aspectRatios }
							value= { attributes.aspectImage }
							onChange={ (value) => setAttributes( { aspectImage: value } ) }
						/>
					</PanelRow>
				</PanelBody>
				<PanelBody
					title={ __( 'Title', 'soivigol-post-list' ) }
					initialOpen={ false }
					>
					<TabPanel className="my-tab-panel"
						activeClass="active-tab"
						tabs={ [
							{
								name: 'tab3',
								title: __( 'Normal', 'soivigol-post-list' ) ,
								className: 'tab-one',
							},
							{
								name: 'tab4',
								title: __( 'Hover', 'soivigol-post-list' ),
								className: 'tab-two',
							},
						] }>
							{
							( tab ) => {
								return ('tab3' === tab.name ? (
									<>
									<PanelRow>
										<ColorPalette
											label={__('Color title', 'soivigol-post-list')}
											value={attributes.titleColor}
											onChange={(value) => setAttributes( { titleColor: value } )} />
									</PanelRow>
									</>
								):(
									<>
									<PanelRow>
										<ColorPalette
											label={__('Color title hover', 'soivigol-post-list')}
											value={attributes.titleColorH}
											onChange={(value) => setAttributes( { titleColorH: value } )} />
									</PanelRow>
									</>
								));
							}}
					</TabPanel>

					<PanelRow>
						<SelectControl
							label={ __( 'Title tag', 'soivigol-post-list' ) }
							options={ titleTags }
							value= { attributes.titleTag }
							onChange={ (value) => setAttributes( { titleTag: value } ) }
						/>
					</PanelRow>
					<PanelRow>
						<FontSizePicker
							fontSizes={ fontSizes }
							value={ attributes.titleSize }
							onChange={ (value) => setAttributes( { titleSize: value } ) }
							fallbackFontSize={ fallbackFontSize }
						/>
					</PanelRow>
				</PanelBody>
				<PanelBody
					title={ __( 'Content', 'soivigol-post-list' ) }
					initialOpen={ false }
					>
					<PanelRow>
						<ToggleControl
							label= { __( 'Excertp', 'soivigol-post-list' ) }
							value={ attributes.excertp }
							onChange={ (value) => setAttributes( { excertp: value } ) }
							checked= { attributes.excertp }
						/>
					</PanelRow>
					<PanelRow>
						<RangeControl
							label= { __( 'Excertp length', 'soivigol-post-list' ) }
							value={ attributes.excertpLenth }
							onChange={ (value) => setAttributes( { excertpLenth: value } ) }
							min={ 10 }
							max={ 500 }
						/>
					</PanelRow>
					<PanelRow>
						<ToggleControl
							label= { __( 'Link in button "Read more"', 'soivigol-post-list' ) }
							value={ attributes.readMore }
							onChange={ (value) => setAttributes( { readMore: value } ) }
							checked= { attributes.readMore }
						/>
					</PanelRow>
					<PanelRow>
						<TextControl
							label={ __( 'Label "Read more"', 'soivigol-post-list' ) }
							value= { attributes.labelReadMore }
							onChange={ (value) => setAttributes( { labelReadMore: value } ) }
						/>
					</PanelRow>
					<PanelRow>
						<RangeControl
							label= { __( 'Content padding in px', 'soivigol-post-list' ) }
							value={ attributes.contentPadding }
							onChange={ (value) => setAttributes( { contentPadding: value } ) }
							min={ 0 }
							max={ 80 }
						/>
					</PanelRow>
				</PanelBody>
			</InspectorControls>
			<div className={ className }>
				{ '' !== attributes.idBlock ? (
					<ServerSideRender
						block="post-list/soivigol-post-list"
						attributes={ props.attributes }
					/>
				): '' }
			</div>
		</div>
	);
}
