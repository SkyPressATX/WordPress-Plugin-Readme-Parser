<?php
/**
 * Parse readme command
 *
 * <message>
 * : An awesome message to display
 *
 * --append=<message>
 * : An awesome message to append to the original message.
 *
 * @when before_wp_load
 */

namespace ReadmeParser;

if ( defined( 'WP_CLI' ) && WP_CLI ) {
    require_once( 'parse-readme.php' );

    function parse( $args, $assoc_args ) {
        $file = $assoc_args['file'];
        if ( isset($assoc_args['output']) ) {
            $outfile = $assoc_args['output'];
        } else {
            $outfile = "$file.dat";
        }
        if ( ! $file ) {
            \WP_CLI::error('You need to specify a readme file. Ex. --file=readme.txt');
        }
        if ( ! is_readable($file) ) {
            \WP_CLI::error("File '$file' is not readable");
        }
        $p = new \WordPress_Readme_Parser();
        $o = $p->parse_readme( $assoc_args['file'] );
        $data = serialize($o);
        if ( false === file_put_contents($outfile, $data) ) {
            \WP_CLI::error("There was an error saving parsed data to file $outfile");
        }
        \WP_CLI::success( $outfile );
    };

    \WP_CLI::add_command( 'parse', __NAMESPACE__ . '\parse' );
}
