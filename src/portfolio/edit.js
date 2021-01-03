import { __ } from '@wordpress/i18n';
import { Component } from '@wordpress/element';
import { InspectorControls } from '@wordpress/editor';
import { PanelBody, Button, PanelRow, RangeControl, ToggleControl, Disabled, SelectControl } from '@wordpress/components';
import ServerSideRender from '@wordpress/server-side-render';
import icons from '../assets/icons/icons';
const { BlockControls, AlignmentToolbar } = wp.blocks; // Import registerBlockType() from wp.blocks

class EditPortfolio extends Component {
	constructor() {
		super( ...arguments );
		this.state = {
			isPressed: false,
			isPressed2: false,
		};
		this.handleButtonClick = this.handleButtonClick.bind( this );
		this.handleButtonClicked = this.handleButtonClicked.bind( this );
		this.customEvent = new Event( 'gutenbergMasonry' );
	}

	handleButtonClick() {
		this.setState( { isPressed: false } );
		this.setState( { isPressed2: false } );
	}
	handleButtonClicked = ( ) => {
		this.setState( { isPressed: true } );
		this.setState( { isPressed2: true } );
	}

	render() {
		const { attributes, setAttributes, className } = this.props;
		const { columns, layout, filter, category, gutter, borderRadius, overlay, filterAlignment } = attributes;
		const { isPressed, isPressed2, handleButtonClicked } = this.state;

		const onChangeColumn = ( columns ) => {
			setAttributes( { columns: columns } );
		};
		const onLayoutChange = ( layout ) => {
			setAttributes( { layout: layout } );
		};
		const onChangeFilter = ( filter ) => {
			setAttributes( { filter: filter } );
		};
		const onChangeOverlay = ( overlay ) => {
			setAttributes( { overlay: overlay } );
		};
		const onChangeCategory = ( category ) => {
			setAttributes( { category: category } );
		};
		const onChangeGutter = ( gutter ) => {
			setAttributes( { gutter: gutter } );
		};
		const onChangeBorderRadius = ( borderRadius ) => {
			setAttributes( { borderRadius: borderRadius } );
		};

		// const onChangeAlignment = ( updatedAlignment ) => {
		// 	setAttributes( { alignment: updatedAlignment } );
		// };

		const tileLayouts = ( layout ) => {
			setAttributes( { layout: 'tiles' } );
			this.setState( { isPressed2: false } );
			this.setState( { isPressed: true } );
		};
		const classicLayouts = ( layout ) => {
			setAttributes( { layout: 'classic' } );
			this.setState( { isPressed: false } );
			this.setState( { isPressed2: true } );
		};
		const onChangeFilterAlignment = ( updatedFilterAlignment ) => {
			setAttributes( { filterAlignment: updatedFilterAlignment } );
		};
		return (
			<fragment>
				<InspectorControls>

					<PanelBody title="Layout Settings" initialOpen={ true }>
						<PanelRow className="__settings-row">
							<Button
								className="__settings-button"
								onClick={ tileLayouts }
								icon={ icons.bloxTiles }
								checked={ layout }
								isPressed={ isPressed }
							>
								<span>{ __( 'grid', 'blox-portfolio' ) }</span>
							</Button>
							<Button
								className="__settings-button"
								onClick={ classicLayouts }
								icon={ icons.bloxMasonry }
								checked={ layout }
								isPressed={ isPressed2 }
							>
								<span>{ __( 'masonry', 'blox-portfolio' ) }</span>
							</Button>
						</PanelRow>
						<PanelRow>
							<RangeControl
								label={ __( 'Columns', 'blox-portfolio' ) }
								value={ columns }
								onChange={ onChangeColumn }
								min={ 1 }
								max={ 6 }
							/>
						</PanelRow>
					</PanelBody>

					<PanelBody title="Design" initialOpen={ true }>
						<PanelRow className="__settings-row">

							<SelectControl
								label={ __( 'Title & Category Display', 'blox-portfolio' ) }
								value={ overlay }
								options={ [
									{ label: 'On Hover', value: 'hover' },
									{ label: 'Modern', value: 'modern' },
									{ label: 'Classic', value: 'classic' },
								] }
								onChange={ onChangeOverlay }
							/>

						</PanelRow>
						<PanelRow className="__settings-row">
							<RangeControl
								label="Border Radius"
								value={ borderRadius }
								onChange={ onChangeBorderRadius }
								min={ 0 }
								max={ 100 }
							/>
						</PanelRow>
						<PanelRow className="__settings-row">
							<RangeControl
								label="Margin"
								value={ gutter }
								onChange={ onChangeGutter }
								min={ 2 }
								max={ 30 }
							/>
						</PanelRow>
						<PanelRow>
							<ToggleControl
								label={ __( 'Show Categories', 'blox-portfolio' ) }
								checked={ category }
								onChange={ onChangeCategory }
							/>
						</PanelRow>
						<ToggleControl
							label={ __( 'Show Filters', 'blox-portfolio' ) }
							checked={ filter }
							onChange={ onChangeFilter }
						/>
						{ filter &&
							<SelectControl
								label={ __( 'Filter Alignment', 'blox-portfolio' ) }
								value={ filterAlignment }
								options={ [
									{ label: 'Left', value: 'left' },
									{ label: 'Center', value: 'center' },
									{ label: 'Right', value: 'flex-end' },
								] }
								onChange={ onChangeFilterAlignment }
							/>
						}
					</PanelBody>

				</InspectorControls>
				{ document.dispatchEvent( this.customEvent ) }
				<div className={ className }>
					<Disabled>
						<ServerSideRender

							block="blox/portfolio-block"
							attributes={ attributes }
							//block="core/archives" attributes={ attributes }
						/>
					</Disabled>
				</div>
			</fragment>
		);
	}
}
export default EditPortfolio;
