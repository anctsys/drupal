<?php

namespace Drupal\requestaction\Plugin\RulesAction;
use Drupal\rules\Core\RulesActionBase;
use Drupal\Core\Logger\LoggerChannelFactoryInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;

/**
 * Provides "Rules RequestAction" rules action.
 *
 * @RulesAction(
 *   id = "RulesRequestAction",
 *   label = @Translation("HTTP Request for rules action"),
 *   category = @Translation("Données"),
 *   context = {
 *     "url" = @ContextDefinition("string",
 *       label = @Translation("URL"),
 *       description = @Translation("The Url address where to post, get and delete request send. <br><b>Example:</b> https://example.com/node?_format=hal_json "),
 *       multiple = TRUE,
 *       required = TRUE,
 *     ),
 *     "linkurl" = @ContextDefinition("string",
 *       label = @Translation("Link URL"),
 *       description = @Translation("The service URL.<br> <b>Example:</b> https://example.com/rest/type/node/article "),
 *       multiple = TRUE,
 *       required = TRUE,
 *     ),
 *     "nodetype" = @ContextDefinition("string",
 *       label = @Translation("Node Type"),
 *       description = @Translation("This holds a value for the content type the API is expecting."),
 *       required = FALSE,
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
 *     "content_author" = @ContextDefinition("string",
 *       label = @Translation("Content Author"),
 *       description = @Translation("This custom field field_content_author Content Author"),
 *       required = FALSE,
 *      ),
 *     "post_title" = @ContextDefinition("string",
 *       label = @Translation("Post Title"),
 *       description = @Translation("A pass through for our content titles."),
 *       required = FALSE,
 *      ),
 *     "post_body" = @ContextDefinition("string",
 *       label = @Translation("Post Body"),
 *       description = @Translation("A pass through for our content body."),
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
class RulesRequestAction extends RulesActionBase implements ContainerFactoryPluginInterface {


}
