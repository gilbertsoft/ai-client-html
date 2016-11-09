<?php

/**
 * @copyright Metaways Infosystems GmbH, 2013
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2015
 * @package Client
 * @subpackage Html
 */


namespace Aimeos\Client\Html\Catalog\Detail\Basket\Selection;


/**
 * Default implementation of catalog detail basket selection section for HTML clients.
 *
 * @package Client
 * @subpackage Html
 */
class Standard
	extends \Aimeos\Client\Html\Common\Client\Factory\Base
	implements \Aimeos\Client\Html\Common\Client\Factory\Iface
{
	/** client/html/catalog/detail/basket/selection/standard/subparts
	 * List of HTML sub-clients rendered within the catalog detail basket selection section
	 *
	 * The output of the frontend is composed of the code generated by the HTML
	 * clients. Each HTML client can consist of serveral (or none) sub-clients
	 * that are responsible for rendering certain sub-parts of the output. The
	 * sub-clients can contain HTML clients themselves and therefore a
	 * hierarchical tree of HTML clients is composed. Each HTML client creates
	 * the output that is placed inside the container of its parent.
	 *
	 * At first, always the HTML code generated by the parent is printed, then
	 * the HTML code of its sub-clients. The order of the HTML sub-clients
	 * determines the order of the output of these sub-clients inside the parent
	 * container. If the configured list of clients is
	 *
	 *  array( "subclient1", "subclient2" )
	 *
	 * you can easily change the order of the output by reordering the subparts:
	 *
	 *  client/html/<clients>/subparts = array( "subclient1", "subclient2" )
	 *
	 * You can also remove one or more parts if they shouldn't be rendered:
	 *
	 *  client/html/<clients>/subparts = array( "subclient1" )
	 *
	 * As the clients only generates structural HTML, the layout defined via CSS
	 * should support adding, removing or reordering content by a fluid like
	 * design.
	 *
	 * @param array List of sub-client names
	 * @since 2014.03
	 * @category Developer
	 */
	private $subPartPath = 'client/html/catalog/detail/basket/selection/standard/subparts';
	private $subPartNames = array();
	private $tags = array();
	private $expire;
	private $cache;


	/**
	 * Returns the HTML code for insertion into the body.
	 *
	 * @param string $uid Unique identifier for the output if the content is placed more than once on the same page
	 * @param array &$tags Result array for the list of tags that are associated to the output
	 * @param string|null &$expire Result variable for the expiration date of the output (null for no expiry)
	 * @return string HTML code
	 */
	public function getBody( $uid = '', array &$tags = array(), &$expire = null )
	{
		$view = $this->setViewParams( $this->getView(), $tags, $expire );

		$html = '';
		foreach( $this->getSubClients() as $subclient ) {
			$html .= $subclient->setView( $view )->getBody( $uid, $tags, $expire );
		}
		$view->selectionBody = $html;

		/** client/html/catalog/detail/basket/selection/standard/template-body
		 * Relative path to the HTML body template of the catalog detail basket selection client.
		 *
		 * The template file contains the HTML code and processing instructions
		 * to generate the result shown in the body of the frontend. The
		 * configuration string is the path to the template file relative
		 * to the templates directory (usually in client/html/templates).
		 *
		 * You can overwrite the template file configuration in extensions and
		 * provide alternative templates. These alternative templates should be
		 * named like the default one but with the string "standard" replaced by
		 * an unique name. You may use the name of your project for this. If
		 * you've implemented an alternative client class as well, "standard"
		 * should be replaced by the name of the new class.
		 *
		 * @param string Relative path to the template creating code for the HTML page body
		 * @since 2014.03
		 * @category Developer
		 * @see client/html/catalog/detail/basket/selection/standard/template-header
		 */
		$tplconf = 'client/html/catalog/detail/basket/selection/standard/template-body';
		$default = 'catalog/detail/basket-selection-body-default.php';

		return $view->render( $view->config( $tplconf, $default ) );
	}


	/**
	 * Returns the sub-client given by its name.
	 *
	 * @param string $type Name of the client type
	 * @param string|null $name Name of the sub-client (Default if null)
	 * @return \Aimeos\Client\Html\Iface Sub-client object
	 */
	public function getSubClient( $type, $name = null )
	{
		/** client/html/catalog/detail/basket/selection/decorators/excludes
		 * Excludes decorators added by the "common" option from the catalog detail basket selection html client
		 *
		 * Decorators extend the functionality of a class by adding new aspects
		 * (e.g. log what is currently done), executing the methods of the underlying
		 * class only in certain conditions (e.g. only for logged in users) or
		 * modify what is returned to the caller.
		 *
		 * This option allows you to remove a decorator added via
		 * "client/html/common/decorators/default" before they are wrapped
		 * around the html client.
		 *
		 *  client/html/catalog/detail/basket/selection/decorators/excludes = array( 'decorator1' )
		 *
		 * This would remove the decorator named "decorator1" from the list of
		 * common decorators ("\Aimeos\Client\Html\Common\Decorator\*") added via
		 * "client/html/common/decorators/default" to the html client.
		 *
		 * @param array List of decorator names
		 * @since 2015.08
		 * @category Developer
		 * @see client/html/common/decorators/default
		 * @see client/html/catalog/detail/basket/selection/decorators/global
		 * @see client/html/catalog/detail/basket/selection/decorators/local
		 */

		/** client/html/catalog/detail/basket/selection/decorators/global
		 * Adds a list of globally available decorators only to the catalog detail basket selection html client
		 *
		 * Decorators extend the functionality of a class by adding new aspects
		 * (e.g. log what is currently done), executing the methods of the underlying
		 * class only in certain conditions (e.g. only for logged in users) or
		 * modify what is returned to the caller.
		 *
		 * This option allows you to wrap global decorators
		 * ("\Aimeos\Client\Html\Common\Decorator\*") around the html client.
		 *
		 *  client/html/catalog/detail/basket/selection/decorators/global = array( 'decorator1' )
		 *
		 * This would add the decorator named "decorator1" defined by
		 * "\Aimeos\Client\Html\Common\Decorator\Decorator1" only to the html client.
		 *
		 * @param array List of decorator names
		 * @since 2015.08
		 * @category Developer
		 * @see client/html/common/decorators/default
		 * @see client/html/catalog/detail/basket/selection/decorators/excludes
		 * @see client/html/catalog/detail/basket/selection/decorators/local
		 */

		/** client/html/catalog/detail/basket/selection/decorators/local
		 * Adds a list of local decorators only to the catalog detail basket selection html client
		 *
		 * Decorators extend the functionality of a class by adding new aspects
		 * (e.g. log what is currently done), executing the methods of the underlying
		 * class only in certain conditions (e.g. only for logged in users) or
		 * modify what is returned to the caller.
		 *
		 * This option allows you to wrap local decorators
		 * ("\Aimeos\Client\Html\Catalog\Decorator\*") around the html client.
		 *
		 *  client/html/catalog/detail/basket/selection/decorators/local = array( 'decorator2' )
		 *
		 * This would add the decorator named "decorator2" defined by
		 * "\Aimeos\Client\Html\Catalog\Decorator\Decorator2" only to the html client.
		 *
		 * @param array List of decorator names
		 * @since 2015.08
		 * @category Developer
		 * @see client/html/common/decorators/default
		 * @see client/html/catalog/detail/basket/selection/decorators/excludes
		 * @see client/html/catalog/detail/basket/selection/decorators/global
		 */

		return $this->createSubClient( 'catalog/detail/basket/selection/' . $type, $name );
	}


	/**
	 * Returns the list of sub-client names configured for the client.
	 *
	 * @return array List of HTML client names
	 */
	protected function getSubClientNames()
	{
		return $this->getContext()->getConfig()->get( $this->subPartPath, $this->subPartNames );
	}


	/**
	 * Sets the necessary parameter values in the view.
	 *
	 * @param \Aimeos\MW\View\Iface $view The view object which generates the HTML output
	 * @param array &$tags Result array for the list of tags that are associated to the output
	 * @param string|null &$expire Result variable for the expiration date of the output (null for no expiry)
	 * @return \Aimeos\MW\View\Iface Modified view object
	 */
	protected function setViewParams( \Aimeos\MW\View\Iface $view, array &$tags = array(), &$expire = null )
	{
		if( !isset( $this->cache ) )
		{
			if( $view->detailProductItem->getType() === 'select' )
			{
				$context = $this->getContext();
				$config = $context->getConfig();
				$domains = array( 'text', 'price', 'media', 'attribute' );
				$products = $view->detailProductItem->getRefItems( 'product', 'default', 'default' );

				/** client/html/catalog/detail/basket/selection/domains
				 * A list of domain names whose items should be available in the basket
				 * selection part of the catalog detail view templates
				 *
				 * The templates rendering basket selection related data usually add
				 * the images and texts associated to each item. If you want to
				 * display additional content like the attributes, you can configure
				 * your own list of domains (attribute, media, price, product, text,
				 * etc. are domains) whose items are fetched from the storage.
				 * Please keep in mind that the more domains you add to the
				 * configuration, the more time is required for fetching the content!
				 *
				 * @param array List of domain names
				 * @since 2015.09
				 * @category Developer
				 * @see client/html/catalog/detail/basket/selection/domains-attributes
				*/
				$domains = $config->get( 'client/html/catalog/detail/basket/selection/domains', $domains );

				$controller = \Aimeos\Controller\Frontend\Factory::createController( $context, 'catalog' );
				$subproducts = $controller->getProductItems( array_keys( $products ), $domains );
				$attrIds = $prodDeps = $attrDeps = $attrTypeDeps = array();

				foreach( $subproducts as $subProdId => $subProduct )
				{
					foreach( $subProduct->getRefItems( 'attribute', null, 'variant' ) as $attrId => $attrItem )
					{
						$attrTypeDeps[$attrItem->getType()][$attrId] = $attrItem->getPosition();
						$attrDeps[$attrId][] = $subProdId;
						$prodDeps[$subProdId][] = $attrId;
						$attrIds[] = $attrId;
					}
				}

				ksort( $attrTypeDeps );

				$this->addMetaItem( $subproducts, 'product', $this->expire, $this->tags );
				$this->addMetaList( array_keys( $subproducts ), 'product', $this->expire );


				$attrManager = $controller->createManager( 'attribute' );

				$search = $attrManager->createSearch( true );
				$expr = array(
					$search->compare( '==', 'attribute.id', $attrIds ),
					$search->getConditions(),
				);
				$search->setConditions( $search->combine( '&&', $expr ) );

				/** client/html/catalog/detail/basket/selection/domains-attributes
				 * A list of domain names whose items should be available for the attributes
				 * in the basket selection part of the catalog detail view templates
				 *
				 * The templates rendering basket selection related data usually add
				 * the images and texts associated to each item. If you want to
				 * display additional content like the attributes, you can configure
				 * your own list of domains (attribute, media, price, product, text,
				 * etc. are domains) whose items are fetched from the storage.
				 * Please keep in mind that the more domains you add to the
				 * configuration, the more time is required for fetching the content!
				 *
				 * @param array List of domain names
				 * @since 2015.09
				 * @category Developer
				 * @see client/html/catalog/detail/basket/selection/domains
				 */
				$domains = array( 'text', 'media' );
				$domains = $config->get( 'client/html/catalog/detail/basket/selection/domains-attributes', $domains );

				$attributes = $attrManager->searchItems( $search, $domains );

				$this->addMetaItem( $attributes, 'attribute', $this->expire, $this->tags );
				$this->addMetaList( array_keys( $attributes ), 'attribute', $this->expire );


				$view->selectionProducts = $subproducts;
				$view->selectionProductDependencies = $prodDeps;
				$view->selectionAttributeDependencies = $attrDeps;
				$view->selectionAttributeTypeDependencies = $attrTypeDeps;
				$view->selectionAttributeItems = $attributes;
			}

			$this->cache = $view;
		}

		$expire = $this->expires( $this->expire, $expire );
		$tags = array_merge( $tags, $this->tags );

		return $this->cache;
	}
}