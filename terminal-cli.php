<?php

namespace ReadmeParser;

require_once 'parse-readme.php';

function parse( string $input, string $format ) : string {
	$parser = new \WordPress_Readme_Parser();
	$output = $parser->parse_readme( $input );

	if ( 'json' === $format ) {
		return json_encode( $output );
	}

	return serialize( $output );
}

function get_arg_value( string $arg, array $assoc_args ): ?string {
	if ( ! isset( $assoc_args[ $arg ] ) ) {
		return null;
	}
	return $assoc_args[ $arg ];
}

function init( array $assoc_args ) {
	$input  = get_arg_value( 'file', $assoc_args );
	$format = get_arg_value( 'format', $assoc_args ) ?: 'json';
	$output = get_arg_value( 'output', $assoc_args );

	if ( ! is_readable( $input ) ) {
		die( "Invalid input path\n\n" );
	}

	$data = parse( $input, $format );
	if ( ! $output ) {
		echo "Results: {$data}\n\n";
		return;
	}

	return \file_put_contents( $output, $data );
}

function get_args(): array {
	$arguments = array(
		'file:',
		'format::',
		'output::',
	);
	return getopt( '', $arguments );
}

init( get_args() );
