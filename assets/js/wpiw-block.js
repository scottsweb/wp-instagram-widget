/******/ (function(modules) { // webpackBootstrap
/******/ 	// The module cache
/******/ 	var installedModules = {};
/******/
/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {
/******/
/******/ 		// Check if module is in cache
/******/ 		if(installedModules[moduleId]) {
/******/ 			return installedModules[moduleId].exports;
/******/ 		}
/******/ 		// Create a new module (and put it into the cache)
/******/ 		var module = installedModules[moduleId] = {
/******/ 			i: moduleId,
/******/ 			l: false,
/******/ 			exports: {}
/******/ 		};
/******/
/******/ 		// Execute the module function
/******/ 		modules[moduleId].call(module.exports, module, module.exports, __webpack_require__);
/******/
/******/ 		// Flag the module as loaded
/******/ 		module.l = true;
/******/
/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}
/******/
/******/
/******/ 	// expose the modules object (__webpack_modules__)
/******/ 	__webpack_require__.m = modules;
/******/
/******/ 	// expose the module cache
/******/ 	__webpack_require__.c = installedModules;
/******/
/******/ 	// define getter function for harmony exports
/******/ 	__webpack_require__.d = function(exports, name, getter) {
/******/ 		if(!__webpack_require__.o(exports, name)) {
/******/ 			Object.defineProperty(exports, name, {
/******/ 				configurable: false,
/******/ 				enumerable: true,
/******/ 				get: getter
/******/ 			});
/******/ 		}
/******/ 	};
/******/
/******/ 	// getDefaultExport function for compatibility with non-harmony modules
/******/ 	__webpack_require__.n = function(module) {
/******/ 		var getter = module && module.__esModule ?
/******/ 			function getDefault() { return module['default']; } :
/******/ 			function getModuleExports() { return module; };
/******/ 		__webpack_require__.d(getter, 'a', getter);
/******/ 		return getter;
/******/ 	};
/******/
/******/ 	// Object.prototype.hasOwnProperty.call
/******/ 	__webpack_require__.o = function(object, property) { return Object.prototype.hasOwnProperty.call(object, property); };
/******/
/******/ 	// __webpack_public_path__
/******/ 	__webpack_require__.p = "";
/******/
/******/ 	// Load entry module and return exports
/******/ 	return __webpack_require__(__webpack_require__.s = 0);
/******/ })
/************************************************************************/
/******/ ([
/* 0 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


var __ = wp.i18n.__;
var _wp$blocks = wp.blocks,
    registerBlockType = _wp$blocks.registerBlockType,
    Editable = _wp$blocks.Editable,
    BlockControls = _wp$blocks.BlockControls,
    BlockAlignmentToolbar = _wp$blocks.BlockAlignmentToolbar,
    InspectorControls = _wp$blocks.InspectorControls,
    BlockDescription = _wp$blocks.BlockDescription,
    RangeControl = _wp$blocks.RangeControl,
    Toolbar = _wp$blocks.Toolbar,
    children = _wp$blocks.source.children;


var MIN_POSTS = 1;
var MAX_POSTS = 12;
var MAX_POSTS_COLUMNS = 6;

registerBlockType('wpiw/instagram-feed', {
	title: __('Instagram Feed'),
	icon: 'images-alt',
	category: 'widgets',
	keywords: [__('widget'), __('wp')],
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

	edit: function edit(props) {
		var content = props.attributes.content,
		    align = props.attributes.align,
		    focus = props.focus,
		    layout = props.attributes.layout,
		    columns = props.attributes.columns;

		var layoutControls = [{
			icon: 'list-view',
			title: __('List View'),
			onClick: function onClick() {
				return setAttributes({ layout: 'list' });
			},
			isActive: layout === 'list'
		}, {
			icon: 'grid-view',
			title: __('Grid View'),
			onClick: function onClick() {
				return setAttributes({ layout: 'grid' });
			},
			isActive: layout === 'grid'
		}];

		function onChangeAlignment(newAlignment) {
			props.setAttributes({ align: newAlignment });
		}

		return React.createElement(
			'div',
			null,

			// toolbar controls
			!!focus && React.createElement(
				BlockControls,
				{ key: 'controls' },
				React.createElement(BlockAlignmentToolbar, {
					value: align,
					onChange: onChangeAlignment,
					controls: ['center', 'wide', 'full']
				})
			),

			// editng ui
			!!focus && React.createElement(
				InspectorControls,
				{ key: 'inspector' },
				React.createElement(
					BlockDescription,
					null,
					React.createElement(
						'p',
						null,
						__('Shows your latest Instagram images.')
					)
				),
				React.createElement(RangeControl, {
					label: __('Columns'),
					value: columns,
					onChange: function onChange(value) {
						return setAttributes({ columns: value });
					},
					min: 1,
					max: MAX_POSTS_COLUMNS
				})
			),
			React.createElement(
				'ul',
				null,
				React.createElement(
					'li',
					null,
					'Hello'
				),
				React.createElement(
					'li',
					null,
					'Hello again'
				)
			)
		);
	},

	save: function save(props) {
		var title = props.attributes.title;

		return React.createElement(
			'h1',
			null,
			' ',
			title,
			' '
		);
	}

});

/***/ })
/******/ ]);