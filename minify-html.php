<?php
namespace Grav\Plugin;

use Grav\Common\Plugin;
use Grav\Plugin\MinifyHtml\Compressor;

/**
 * Class MinifyHtmlPlugin
 * @package Grav\Plugin
 */
class MinifyHtmlPlugin extends Plugin
{
    /**
     * @return array
     *
     * The getSubscribedEvents() gives the core a list of events
     *     that the plugin wants to listen to. The key of each
     *     array section is the event that the plugin listens to
     *     and the value (in the form of an array) contains the
     *     callable (or function) as well as the priority. The
     *     higher the number the higher the priority.
     */
    public static function getSubscribedEvents()
    {
        return [
            'onPluginsInitialized' => ['onPluginsInitialized', 0]
        ];
    }

    /**
     * Initialize the plugin
     */
    public function onPluginsInitialized()
    {
        // Don't proceed if we are in the admin plugin
        if ($this->isAdmin()) {
            return;
        }

        // Enable the main event we are interested in
        $this->enable([
            'onOutputGenerated' => ['onOutputGenerated', 0]
        ]);
    }

    /**
     * On Page Content Raw Hook
     */
    public function onOutputGenerated()
    {
        if ($this->config['plugins.minify-html.enabled']) {
            require_once(__DIR__ . '/vendor/autoload.php');

            // HTML input (not compressed)
            $sourceHtml = $this->grav['output'];

            // Instantiate the compressor
            $compressor = \WyriHaximus\HtmlCompress\Factory::construct();

            // HTML output (compressed)
            $compressedHtml = $compressor->compress($sourceHtml);

            // Return the compressed HTML
            $this->grav->output = $compressedHtml;
        }
    }
}
