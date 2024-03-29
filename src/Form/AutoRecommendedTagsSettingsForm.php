<?php

namespace Drupal\auto_recommended_tags\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Config\ConfigFactoryInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Configure example settings for this site.
 */
class AutoRecommendedTagsSettingsForm extends ConfigFormBase {

  /**
   * Drupal\Core\Config\ConfigFactoryInterface definition.
   *
   * @var Drupal\Core\Config\ConfigFactoryInterface
   */
  protected $configFactory;

  /**
   * {@inheritdoc}
   *
   * @param \Drupal\Core\Config\ConfigFactoryInterface $config_factory
   *   A config factory for retrieving required config objects.
   */
  public function __construct(ConfigFactoryInterface $config_factory) {
    $this->configFactory = $config_factory;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('config.factory')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'auto_recommended_tags_admin_settings';
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return [
      'auto_recommended_tags.settings',
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->config('auto_recommended_tags.settings');

    $form['stanbol_socket_url'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Apache Stanbol socket URL'),
      '#default_value' => $config->get('stanbol_socket_url'),
      "#description" => $this->t('The URL and port for the Socket.IO connector. For more information, see the <a href="https://www.drupal.org/project/auto_recommended_tags">module page</a> for installing Apache Stanbol with Socket.IO using a Docker container.'),
    ];

    $form['fields_selector'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Field selectors'),
      '#default_value' => $config->get('fields_selector'),
      "#description" => $this->t('CSS selectors of the fields to be analyzed by Apache Stanbol to recommend tags. Example: (#body, input.article-title, ...)'),
    ];

    $form['show_groups'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Show grouped tags'),
      '#default_value' => $config->get('show_groups'),
      "#description" => $this->t('Show tags in grouped entities as returned by Apache Stanbol. If unchecked, tags will be flattened.'),
    ];

    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $config = $this->configFactory->getEditable('auto_recommended_tags.settings');
    $config->set('stanbol_socket_url', $form_state->getValue('stanbol_socket_url'))
      ->save();
    $config->set('fields_selector', $form_state->getValue('fields_selector'))
      ->save();
    $config->set('show_groups', $form_state->getValue('show_groups'))
      ->save();
    parent::submitForm($form, $form_state);
  }

}
