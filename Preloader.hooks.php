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

		// 'preloadonly' tag - text is not displayed, except on instances of the template
		$parser->setHook( 'preloadonly', 'PreloaderHooks::preloadOnlyHook' );

		// 'preloadsubst' function: '#preloadsubst' is replaced by 'subst:' and processed when creating template instances
		// On display, the contents are replaced by "(Calculated on preload)"
		$parser->setFunctionHook( 'preloadsubst', 'PreloaderHooks::parserFunctionPreloadSubst' );

		return true;
	}

	/** Hook function for the preloading */
	public static function onEditFormPreloadText( &$text, &$title ) {
		$src = self::preloadSource( $title->getNamespace() );
		if( $src ) {
			$stx = self::sourceText( $src, $title);
			if( $stx )
				$text = $stx;
		}
		return true;
	}

	public static function preloadOnlyHook( $content, $attributes, $parser, $frame ) {
		$stripped =  preg_replace( '/<\/?preloadonly>/s', '', $content );
		$output = $parser->parse( $stripped, $parser->getTitle(), $parser->getOptions(), false, false );
		return $output->getText();
	}

	/** Hook function for the parser */
	public static function parserHook( $content, $attributes, $parser, $frame ) {
		$stripped =  preg_replace( '/<\/?nopreload>/s', '', $content );
		$output = $parser->parse( $stripped, $parser->getTitle(), $parser->getOptions(), false, false );
		return $output->getText();
	}

	/**
	 * Parser function handler for {{#preloadsubst: .. }}
	 *
	 * @param Parser $parser
	 * @param string $value
	 *
	 * @return string: text to insert in the page.
	 */
	public static function parserFunctionPreloadSubst( $parser, $value ) {
		//return htmlspecialchars( $value );
		return "(Calculated on preload)";
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
	 * @param $newTitle title object of the new page
	 * @return mixed
	 */
	static function sourceText( $page, &$newTitle ) {
		$title = Title::newFromText( $page );
		if( $title && $title->exists() ) {
			$revision = Revision::newFromTitle( $title );
			$content = $revision->getContent( Revision::RAW );
			$text = ContentHandler::getContentText( $content );
			return self::transform( $text, $newTitle );
		} else {
			return false;
		}
	}

	/**
	 * Remove <nopreload> sections from the text and trim whitespace
	 *
	 * @param $text
	 * @param $newTitle title object of the new page
	 * @return string
	 */
	static function transform( $text, &$newTitle ) {
		//return trim( preg_replace( '/<nopreload>.*?<\/nopreload>/s', '', $text ) );
		$nopreload = trim( preg_replace( '/<nopreload>.*?<\/nopreload>/s', '', $text ) );
		$preloadonly = trim( preg_replace( '/<preloadonly>(.*?)<\/preloadonly>/s', '$1', $nopreload ) );
		$preloadsubst = trim( preg_replace( '/#preload(subst:)/s', '$1', $preloadonly ) );

		// Get and set up a parser for pre-parsing content in "preloadsubst" tags
		global $wgParser;
		$parser = $wgParser->getFreshParser();  // Since MW 1.24
		
		$parserOptions = is_null( $wgParser->getOptions() ) ? new ParserOptions : $wgParser->getOptions();
		
		return $parser->preSaveTransform( $preloadsubst, $newTitle, $parserOptions->getUser(), $parserOptions );
	}
}
