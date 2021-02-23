<?php

namespace Drupal\rules_http_request\Plugin\RulesAction;

use Drupal\rules\Core\RulesActionBase;
use Drupal\Core\Logger\LoggerChannelFactoryInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\RequestException;

/**
 * Provides "Rules API Post" rules action.
 *
 * @RulesAction(
 *   id = "RulesHttpRequest",
 *   label = @Translation("Rules HTTP Request"),
 *   category = @Translation("Data"),
 *   context = {
 *     "url" = @ContextDefinition("string",
 *       label = @Translation("URL"),
 *       description = @Translation("The Url address where to post, get and delete request send. <br><b>Example:</b> https://example.com/node?_format=hal_json "),
 *       multiple = TRUE,
 *       required = TRUE,
 *     ),
 *     "methode" = @ContextDefinition("string",
 *       label = @Translation("Request method"),
 *       description = @Translation("Request method POST,PUT,GET..."),
 *       required = TRUE,
 *      ),
 *     "apiuser" = @ContextDefinition("string",
 *       label = @Translation("API User Name"),
 *       description = @Translation("Username for API Access"),
 *       required = FALSE,
 *      ),
 *     "apipass" = @ContextDefinition("string",
 *       label = @Translation("API User Password"),
 *       description = @Translation("Password for API Access"),
 *       required = FALSE,
 *      ),
 *     "apitoken" = @ContextDefinition("string",
 *       label = @Translation("API Session Token"),
 *       description = @Translation("Session Token for API Access"),
 *       required = FALSE,
 *      ),
 *     "post_title" = @ContextDefinition("string",
 *       label = @Translation("Post Title"),
 *       description = @Translation("A pass through for our content titles."),
 *       required = FALSE,
 *      ),
 *     "extra_data" = @ContextDefinition("string",
 *       label = @Translation("Extra data to send to api"),
 *       description = @Translation("A pass through for our content extra data field."),
 *       required = FALSE,
 *      ),
 *     "node_body" = @ContextDefinition("entity:node",
 *       label = @Translation("Node Content"),
 *       description = @Translation("Pass node content entity"),
 *       required = FALSE,
 *      ),
 *   },
 *   provides = {
 *     "http_response" = @ContextDefinition("string",
 *       label = @Translation("HTTP data")
 *     )
 *   }
 * )
 *
 */
class RulesHttpRequest extends RulesActionBase implements ContainerFactoryPluginInterface {

  /**
   * The logger for the rules channel.
   *
   * @var \Psr\Log\LoggerInterface
   */
  protected $logger;

  /**
   * The HTTP client to fetch the feed data with.
   *
   * @var \GuzzleHttp\ClientInterface
   */
  protected $httpClient;

  /**
   * Constructs a httpClient object.
   *
   * @param array $configuration
   *   A configuration array containing information about the plugin instance.
   * @param string $plugin_id
   *   The plugin ID for the plugin instance.
   * @param mixed $plugin_definition
   *   The plugin implementation definition.
   * @param \Drupal\Core\Logger\LoggerChannelFactoryInterface $logger_factory
   *   The logger factory service.
   * @param GuzzleHttp\ClientInterface $http_client
   *   The guzzle http client instance.
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, LoggerChannelFactoryInterface $logger_factory, ClientInterface $http_client) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->logger = $logger_factory->get('rules_http_request');
    $this->http_client = $http_client;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('logger.factory'),
      $container->get('http_client')
    );
  }

  /**
   * Set up form variables
   *
   * @param string[] $url
   *   Url addresses HTTP request.
   * @param string[] $methode
   *   (optional) Request method to call API
   * @param string[] $apiuser
   *   (optional) The User Name for API call
   * @param string[] $apipass
   *   (optional) The User Passord for API call
   * @param string[] $apitoken
   *   (optional) The Session Token for API call
   * @param string[] $post_title
   *   (optional) A passthrough for content titles.
   * @param string[] $extra_data
   *   (optional) A passthrough for content titles.
   * @param  $node_body
   *   (optional) A passthrough the node content.
   */

//protected function doExecute () {
protected function doExecute(array $url,$methode, $apiuser, $apipass, $apitoken, $post_title, $extra_data ,$node_body) {
// Debug message
drupal_set_message(t("Activating Rules API POST ..."), 'status');


/** @var \Symfony\Component\Serializer\Encoder\DecoderInterface $serializer */
$serializer = \Drupal::service('serializer');
$data = $serializer->serialize($node_body, 'json', ['plugin_id' => 'entity']);

//Message d'erreur
$messenger = \Drupal::messenger();
$messenger->addMessage('Start Rules', $messenger::TYPE_WARNING);



$serialized_entity = json_encode([
  'title' => [['value' => $post_title]],
  'extra_data' => [['value' => $extra_data, 'format' => 'full_html']],
  'jsonnode' => [['nodevalue' => $data]],
]) ;

$client = \Drupal::httpClient();
$url =$url[0];
//$method = 'POST';
$options = [
  'auth' => [
    $apiuser,
    $apipass
  ],
'timeout' => '2',
'body' => $serialized_entity,
//'node' => $data
'headers' => [
'Content-Type' => 'application/hal+json',
'Accept' => 'application/hal+json',
'X-CSRF-Token' => $apitoken
    ],
];
try {
  $response = $client->request($method, $url, $options);
  $code = $response->getStatusCode();
  if ($code == 200) {
    $body = $response->getBody()->getContents();
    $messenger->addMessage($body, $messenger::TYPE_WARNING);
    return $body;
  }
}
catch (RequestException $e) {
  watchdog_exception('rules_http_request', $e);
  }
 }
}
