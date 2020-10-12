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

import { InspectorControls } from '@wordpress/block-editor';
import {
	PanelBody,
	PanelRow,
	ToggleControl,
	RangeControl,
	ColorPicker,
	SelectControl,
	TabPanel
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
		attributes: { category, pagination, numPosts, numCol, bgColor, textColor, boxShadow, bgColorH, textColorH, boxShadowH, borderRadius, idBlock },
		setAttributes,
		className,
	} = props;

	if ( '' === idBlock ) {
		setAttributes( { idBlock: props.clientId } );
	}

	const onChangeCategory = ( value ) => {
		setAttributes( { category: value } );
	};

	const onChangePagination = ( value ) => {
		setAttributes( { pagination: value } );
	};

	const onChangeNumPosts = ( value ) => {
		setAttributes( { numPosts: value } );
	};

	const onChangeNumCol = ( value ) => {
		setAttributes( { numCol: value } );
	};

	const onChangeBackgroundColor = ( value ) => {
		setAttributes( { bgColor: value.hex } );
	};

	const onChangeTextColor = ( value ) => {
		setAttributes( { textColor: value.hex } );
	};

	const onChangeBoxShadow = ( value ) => {
		setAttributes( { boxShadow: value } );
	};

	const onChangeBackgroundColorH = ( value ) => {
		setAttributes( { bgColorH: value.hex } );
	};

	const onChangeTextColorH = ( value ) => {
		setAttributes( { textColorH: value.hex } );
	};

	const onChangeBoxShadowH = ( value ) => {
		setAttributes( { boxShadowH: value } );
	};

	const onChangeBorderRadius = ( value ) => {
		setAttributes( { borderRadius: value } );
	};

	const categorys = variables.categorys;
	const cat_name  = [];
	cat_name.push( { label: __( 'All categorys', 'soivigol-post-archive' ), value: 'all' } );
	Object.entries( categorys ).forEach( element => {
		cat_name.push( { label: element[1]['name'], value: element[1]['slug'] } );
	});

	return (
		<div>
			<InspectorControls>
				<PanelBody
					title={ __( 'Category', 'soivigol-post-archive' ) }
					initialOpen={ true }
				>
					<PanelRow>
						<SelectControl
							label={ __( 'Category', 'soivigol-post-archive' ) }
							options={ cat_name }
							value= { category }
							onChange={ onChangeCategory }
						/>
					</PanelRow>
				</PanelBody>
				<PanelBody
					title={ __( 'Choose layout', 'soivigol-post-archive' ) }
					initialOpen={ false }
				>
					<PanelRow>
						<RangeControl
							label= { __( 'Number of posts', 'soivigol-post-archive' ) }
							value={ numPosts }
							onChange={ onChangeNumPosts }
							min={ 3 }
							max={ 21 }
						/>
					</PanelRow>
					<PanelRow>
						<RangeControl
							label= { __( 'Number of columns', 'soivigol-post-archive' ) }
							value={ numCol }
							onChange={ onChangeNumCol }
							min={ 1 }
							max={ 4 }
						/>
					</PanelRow>
					<PanelRow>
						<ToggleControl
							label= { __( 'Pagination', 'soivigol-post-archive' ) }
							value={ pagination }
							onChange={ onChangePagination }
							checked= { pagination }
						/>
					</PanelRow>
				</PanelBody>
				<PanelBody
					title={ __( 'Design', 'soivigol-post-archive' ) }
					initialOpen={ false }
				>
					<TabPanel className="my-tab-panel"
						activeClass="active-tab"
						tabs={ [
							{
								name: 'tab1',
								title: __( 'Normal', 'soivigol-post-archive' ) ,
								className: 'tab-one',
							},
							{
								name: 'tab2',
								title: __( 'Hover', 'soivigol-post-archive' ),
								className: 'tab-two',
							},
						] }>
						{
							( tab ) => {
								return ('tab1' === tab.name ? (
									<div>
										<PanelRow>
											{ __( 'Background color', 'soivigol-post-archive' ) }
										</PanelRow>
										<PanelRow>
											<ColorPicker
												label={__('Background color', 'soivigol-post-archive')}
												color={bgColor}
												onChangeComplete={onChangeBackgroundColor} />
										</PanelRow>
										<PanelRow>
											{__('Text color', 'soivigol-post-archive')}
										</PanelRow>
										<PanelRow>
											<ColorPicker
												label={__('Text color', 'soivigol-post-archive')}
												color={textColor}
												onChangeComplete={onChangeTextColor} />
										</PanelRow>
										<PanelRow>
											<ToggleControl
												label= { __( 'Shadow', 'soivigol-post-archive' ) }
												value={ boxShadow }
												onChange={ onChangeBoxShadow }
												checked= { boxShadow}
											/>
										</PanelRow>
									</div>
								) : (
										<div>
											<PanelRow>
												{ __( 'Background color hover', 'soivigol-post-archive' ) }
											</PanelRow>
											<PanelRow>
												<ColorPicker
													label={__('Background color hover', 'soivigol-post-archive')}
													color={bgColorH}
													onChangeComplete={onChangeBackgroundColorH} />
											</PanelRow>
											<PanelRow>
												{__('Text color hover', 'soivigol-post-archive')}
											</PanelRow>
											<PanelRow>
												<ColorPicker
													label={__('Text color hover', 'soivigol-post-archive')}
													color={textColorH}
													onChangeComplete={onChangeTextColorH} />
											</PanelRow>
											<PanelRow>
												<ToggleControl
													label= { __( 'Shadow hover', 'soivigol-post-archive' ) }
													value={ boxShadowH }
													onChange={ onChangeBoxShadowH }
													checked= { boxShadowH }
												/>
											</PanelRow>
										</div>
									));
							}
						}
    				</TabPanel>
					<PanelRow>
						<RangeControl
							label= { __( 'Border radius', 'soivigol-post-archive' ) }
							value={ borderRadius }
							onChange={ onChangeBorderRadius }
							min={ 0 }
							max={ 60 }
						/>
					</PanelRow>
				</PanelBody>
			</InspectorControls>
			<div className={ className }>
				{ '' !== idBlock ? (
					<ServerSideRender
						block="post-archive/soivigol-post-archive"
						attributes={ props.attributes }
					/>
				): '' }
				</div>
		</div>
	);
}
