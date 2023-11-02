<?php

namespace App\Console\Commands;

if (!class_exists('WP_CLI')) {
    return;
}

class BlockCommand extends \WP_CLI_Command {

    private $blocks = [
        'Header' => [
            '001', '002', '003', 'Site'
        ],
        'Footer' => [
            '001', '002', '003', '004', '005', '006', '007', '008', '009', 'Site'
        ],
        'Hero' => [
            '001', '002'
        ],
        'Content' => [],
        'Show' => [],
        'Form' => [],
        'Other' => []
    ];

    private function get_block_type() {
        \WP_CLI::line('What type of block would you like to import?');
    
        $block_keys = array_keys($this->blocks);
        foreach ($block_keys as $index => $block) {
            \WP_CLI::line("[$index] $block");
        }
    
        $valid = false;
        do {
            $selection = (int) \cli\prompt('Please enter the number of your choice', false);
            if (isset($block_keys[$selection])) {
                $valid = true;
            } else {
                \WP_CLI::warning('Invalid choice. Please try again.');
            }
        } while (!$valid);
        
        return $block_keys[$selection];
    }
    
    private function get_block_name($block_type) {
        \WP_CLI::line('Great! Which block would you like to import?');
    
        $block_values = $this->blocks[$block_type];
        foreach ($block_values as $index => $block) {
            \WP_CLI::line("[$index] $block_type/$block");
        }
    
        $valid = false;
        do {
            $selection = (int) \cli\prompt('Please enter the number of your choice', false);
            if (isset($block_values[$selection])) {
                $valid = true;
            } else {
                \WP_CLI::warning('Invalid choice. Please try again.');
            }
        } while (!$valid);
    
        return $block_values[$selection];
    }

    private function ensure_directories_exist() {
        $directories = [
            get_theme_file_path("app/Blocks"),
            get_theme_file_path("resources/views/blocks"),
            get_theme_file_path("resources/styles/blocks"),
            get_theme_file_path("resources/scripts/blocks"),
        ];
        
        foreach ($directories as $dir) {
            if (!is_dir($dir)) {
                mkdir($dir, 0755, true);
            }
        }
    }

    /**
     * Import a block.
     *
     * @when after_wp_load
     */
    public function __invoke( $args, $assoc_args ) {
        // Get block type and block name from user
        $block_type = $this->get_block_type();
        $block_name = $this->get_block_name($block_type);

        // Normalize block name for filesystem
        $normalized_block_name = strtolower(str_replace('/', '-', "$block_type-$block_name"));

        // Check if block already exists
        if (file_exists(get_theme_file_path("app/Blocks/$normalized_block_name.php"))) {
            \WP_CLI::error('This block already exists!');
            return;
        }

        $githubToken = $_ENV['GITHUB_PERSONAL_ACCESS_TOKEN'];

        if ($githubToken === false) {
            \WP_CLI::error('Could not find the GitHub personal access token in the environment. Please ensure that it is set in your .env.local file.');
            return;
        }

        // Authenticate with GitHub
        exec('composer config github-oauth.github.com ' . $githubToken);

        // Require the necessary package
        $repository_url = "https://github.com/kibria4/wpsage-blocks-$normalized_block_name";
        exec("composer config repositories.block vcs $repository_url");
        exec("composer require kibria4/wpsage-blocks-$normalized_block_name");

        // Make sure directories exist
        $this->ensure_directories_exist();

        // Copy files to the necessary directories
        $vendorDir = "vendor/kibria4/wpsage-blocks-$normalized_block_name/src/files";
        $themeDir = get_theme_file_path();
        
        if (is_dir("$vendorDir/app/Blocks/")) {
            exec("cp -R $vendorDir/app/Blocks/* $themeDir/app/Blocks/");
        }

        if (is_dir("$vendorDir/resources/views/blocks/")) {
            exec("cp -R $vendorDir/resources/views/blocks/* $themeDir/resources/views/blocks/");
        }

        if (is_dir("$vendorDir/resources/scripts/blocks/")) {
            exec("cp -R $vendorDir/resources/scripts/blocks/* $themeDir/resources/scripts/blocks/");
        }

        if (is_dir("$vendorDir/resources/styles/blocks/")) {
            exec("cp -R $vendorDir/resources/styles/blocks/* $themeDir/resources/styles/blocks/");
        }

        \WP_CLI::success( "Successfully installed and moved files for $normalized_block_name! Please remember to import the SCSS and JS files in resources/styles/app.scss and resources/scripts/app.js respectively." );
    }
}

\WP_CLI::add_command( 'import-ohmblock', BlockCommand::class );
