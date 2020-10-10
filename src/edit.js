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
	cat_name.push( { label: __( 'Todos', 'soivigol-post-archive' ), value: 'all' } );
	categorys.map( ( cat ) => (
		cat_name.push( { label: cat['name'], value: cat['slug'] } )
	) );

	return (
		<div>
			<InspectorControls>
				<PanelBody
					title={ __( 'Categoria', 'soivigol-post-archive' ) }
					initialOpen={ true }
				>
					<PanelRow>
						<SelectControl
							label={ __( 'Categoria', 'soivigol-post-archive' ) }
							options={ cat_name }
							value= { category }
							onChange={ onChangeCategory }
						/>
					</PanelRow>
				</PanelBody>
				<PanelBody
					title={ __( 'Elegir disposición', 'soivigol-post-archive' ) }
					initialOpen={ false }
				>
					<PanelRow>
						<RangeControl
							label= { __( 'Número de entradas', 'soivigol-post-archive' ) }
							value={ numPosts }
							onChange={ onChangeNumPosts }
							min={ 3 }
							max={ 21 }
						/>
					</PanelRow>
					<PanelRow>
						<RangeControl
							label= { __( 'Número de columnas', 'soivigol-post-archive' ) }
							value={ numCol }
							onChange={ onChangeNumCol }
							min={ 1 }
							max={ 4 }
						/>
					</PanelRow>
					<PanelRow>
						<ToggleControl
							label= { __( 'Paginación', 'soivigol-post-archive' ) }
							value={ pagination }
							onChange={ onChangePagination }
							checked= { pagination }
						/>
					</PanelRow>
				</PanelBody>
				<PanelBody
					title={ __( 'Diseño', 'soivigol-post-archive' ) }
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
											{ __( 'Color de fondo', 'soivigol-post-archive' ) }
										</PanelRow>
										<PanelRow>
											<ColorPicker
												label={__('Color de fondo', 'soivigol-post-archive')}
												color={bgColor}
												onChangeComplete={onChangeBackgroundColor} />
										</PanelRow>
										<PanelRow>
											{__('Color del texto', 'soivigol-post-archive')}
										</PanelRow>
										<PanelRow>
											<ColorPicker
												label={__('Color del texto', 'soivigol-post-archive')}
												color={textColor}
												onChangeComplete={onChangeTextColor} />
										</PanelRow>
										<PanelRow>
											<ToggleControl
												label= { __( 'Sobra en la caja', 'soivigol-post-archive' ) }
												value={ boxShadow }
												onChange={ onChangeBoxShadow }
												checked= { boxShadow}
											/>
										</PanelRow>
									</div>
								) : (
										<div>
											<PanelRow>
												{ __( 'Color de fondo hover', 'soivigol-post-archive' ) }
											</PanelRow>
											<PanelRow>
												<ColorPicker
													label={__('Color de fondo hover', 'soivigol-post-archive')}
													color={bgColorH}
													onChangeComplete={onChangeBackgroundColorH} />
											</PanelRow>
											<PanelRow>
												{__('Color del texto hover', 'soivigol-post-archive')}
											</PanelRow>
											<PanelRow>
												<ColorPicker
													label={__('Color del texto hover', 'soivigol-post-archive')}
													color={textColorH}
													onChangeComplete={onChangeTextColorH} />
											</PanelRow>
											<PanelRow>
												<ToggleControl
													label= { __( 'Sobra en la caja hover', 'soivigol-post-archive' ) }
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
