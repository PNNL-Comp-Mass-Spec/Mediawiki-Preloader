<?php
/**
 * Hooks for Preloader extension
 *
 * @file
 * @ingroup Extensions
 */
class PreloaderHooks {
	/**
	 * Register parser hooks
	 * See also http://www.mediawiki.org/wiki/Manual:Parser_functions
	 */
	public static function onParserFirstCallInit( &$parser ) {
		// 'nopreload' tag - text is dropped from display, and not placed on template instances
		$parser->setHook( 'nopreload', 'PreloaderHooks::parserHook' );


		return true;
	}

	/** Hook function for the preloading */
	public static function onEditFormPreloadText( &$text, &$title ) {
		$src = self::preloadSource( $title->getNamespace() );
		if( $src ) {
			$stx = self::sourceText( $src );
			if( $stx )
				$text = $stx;
		}
		return true;
	}

	/** Hook function for the parser */
	public static function parserHook( $content, $attributes, $parser, $frame ) {
		$stripped =  preg_replace( '/<\/?nopreload>/s', '', $content );
		$output = $parser->parse( $stripped, $parser->getTitle(), $parser->getOptions(), false, false );
		return $output->getText();
	}

	/**
	 * Determine what page should be used as the source of preloaded text
	 * for a given namespace and return the title (in text form)
	 *
	 * @param $namespace Namespace to check for
	 * @return mixed
	 */ 
	static function preloadSource( $namespace ) {
		global $wgPreloaderSource;
		if( isset( $wgPreloaderSource[ $namespace ] ) ) {
			return $wgPreloaderSource[ $namespace ];
		} else {
			return false;
		}
	}

	/**
	 * Grab the current text of a given page if it exists
	 *
	 * @param $page Text form of the page title
	 * @return mixed
	 */
	static function sourceText( $page ) {
		$title = Title::newFromText( $page );
		if( $title && $title->exists() ) {
			$revision = Revision::newFromTitle( $title );
			$content = $revision->getContent( Revision::RAW );
			$text = ContentHandler::getContentText( $content );
			return self::transform( $text );
		} else {
			return false;
		}
	}

	/**
	 * Remove <nopreload> sections from the text and trim whitespace
	 *
	 * @param $text
	 * @return string
	 */
	static function transform( $text ) {
		return trim( preg_replace( '/<nopreload>.*<\/nopreload>/s', '', $text ) );
	}
}
