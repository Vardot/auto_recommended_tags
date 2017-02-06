<?php

namespace Drupal\auto_recommended_tags\Plugin\Field\FieldWidget;

use Drupal;
use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Field\Plugin\Field\FieldWidget\EntityReferenceAutocompleteWidget;

/**
 * Plugin implementation of 'entity_reference_autocomplete_tags_stanbol' widget.
 *
 * @FieldWidget(
 *   id = "entity_reference_autocomplete_tags_stanbol",
 *   label = @Translation("Autocomplete (Tags style) with Apache Stanbol suggestions"),
 *   description = @Translation("An autocomplete text field with tagging support, with an option of Apache Stanbol suggestions."),
 *   field_types = {
 *     "entity_reference"
 *   },
 *   multiple_values = TRUE
 * )
 */
class EntityReferenceAutocompleteTagsStanbolWidget extends EntityReferenceAutocompleteWidget {

  /**
   * {@inheritdoc}
   */
  public function formElement(FieldItemListInterface $items, $delta, array $element, array &$form, FormStateInterface $form_state) {
    $config = Drupal::config('auto_recommended_tags.settings');
    $element = parent::formElement($items, $delta, $element, $form, $form_state);

    $selection_settings = $this->getFieldSetting('handler_settings') + ['match_operator' => $this->getSetting('match_operator')];

    $element['target_id']['#tags'] = TRUE;
    $element['target_id']['#default_value'] = $items->referencedEntities();
    $element['target_id']['#suffix'] = '<div class="stanbol-tags-suggestions"></div>';
    $element['target_id']['#attached'] = array(
      'library' => array(
        'auto_recommended_tags/socket-io',
        'auto_recommended_tags/stanbol-tag-suggestions',
      ),
      'drupalSettings' => array(
        'auto_recommended_tags' => array(
          'stanbol_socket_url' => rtrim($config->get('stanbol_socket_url'), "/"),
          'fields_selector' => $config->get('fields_selector'),
          'show_groups' => $config->get('show_groups'),
        ),
      ),
    );

    return $element;
  }

  /**
   * {@inheritdoc}
   */
  public function massageFormValues(array $values, array $form, FormStateInterface $form_state) {
    return $values['target_id'];
  }

}
