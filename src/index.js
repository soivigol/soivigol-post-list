/**
 * Registers a new block provided a unique name and an object defining its behavior.
 *
 * @see https://developer.wordpress.org/block-editor/developers/block-api/#registering-a-block
 */
import { registerBlockType } from '@wordpress/blocks';

/**
 * Retrieves the translation of text.
 *
 * @see https://developer.wordpress.org/block-editor/packages/packages-i18n/
 */
import { __ } from '@wordpress/i18n';

/**
 * Lets webpack process CSS, SASS or SCSS files referenced in JavaScript files.
 * All files containing `style` keyword are bundled together. The code used
 * gets applied both to the front of your site and to the editor.
 *
 * @see https://www.npmjs.com/package/@wordpress/scripts#using-css
 */
import './style.scss';

/**
 * Internal dependencies
 */
import Edit from './edit';

/**
 * Every block starts by registering a new block type definition.
 *
 * @see https://developer.wordpress.org/block-editor/developers/block-api/#registering-a-block
 */
registerBlockType( 'post-list/soivigol-post-list', {
	/**
	 * This is the display title for your block, which can be translated with `i18n` functions.
	 * The block inserter will show this name.
	 */
	title: __( 'Posts list', 'soivigol-post-list' ),

	/**
	 * This is a short description for your block, can be translated with `i18n` functions.
	 * It will be shown in the Block Tab in the Settings Sidebar.
	 */
	description: __(
		'List of posts with pagination, possibility to filter by category, number of posts per page, number of columns and customization of the design of the items.',
		'soivigol-post-list'
	),

	/**
	 * Blocks are grouped into categories to help users browse and discover them.
	 * The categories provided by core are `common`, `embed`, `formatting`, `layout` and `widgets`.
	 */
	category: 'common',

	/**
	 * An icon property should be specified to make it easier to identify a block.
	 * These can be any of WordPress’ Dashicons, or a custom svg element.
	 */
	icon: 'grid-view',

	/**
	 * Optional block extended support features.
	 */
	supports: {
		// Removes support for an HTML mode.
		html: false,
	},

	/**
	 * Attributes for the different options that are rendered on the front.
	 */
	attributes: {
		tipoPost: {
			type: 'string',
			default: 'post'
		},
		category: {
			type: 'string',
		},
		pagination: {
			type: 'boolean',
		},
		numPosts: {
			type: 'number',
		},
		numCol: {
			type: 'number',
		},
		bgColor: {
			type: 'string',
			default: '#ffffff',
		},
		textColor: {
			type: 'string',
			default: '#333333'
		},
		boxShadow: {
			type: 'boolean',
		},
		bgColorH: {
			type: 'string',
			default: '#ffffff',
		},
		textColorH: {
			type: 'string',
			default: '#333333'
		},
		boxShadowH: {
			type: 'boolean',
		},
		borderRadius: {
			type: 'boolean',
		},
		idBlock:{
			type: 'number',
		}
	},

	/**
	 * @see ./edit.js
	 */
	edit: Edit,

	/**
	 * @see ./save.js
	 */
	save() {
		return null;
	},
} );
