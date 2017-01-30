/**
 * @file
 * Contains the definition of the behaviour stanbol tags javascript handler.
 */

(function ($, Drupal, drupalSettings) {

  'use strict';
  /**
   * Attaches the JS test behavior to to weight div.
   */
  Drupal.behaviors.stanbolTags = {
    attach: function (context, settings) {
      var timeout;

      if (!drupalSettings.auto_recommended_tags) {
        return;
      }
      if (drupalSettings.auto_recommended_tags
          && !drupalSettings.auto_recommended_tags.stanbol_socket_url) {
        return;
      }

      var socket = io(drupalSettings.auto_recommended_tags.stanbol_socket_url);
      var loadTags = function() {
        $(".stanbol-tags-suggestions").html('<span>Loading tags...</span>');
        socket.emit("stanbol", {text: this.getData()}, function(data){
          if (data && data.tags) {
            if (drupalSettings.auto_recommended_tags.show_groups) {
              if (data && data.groupedTags) {
                $(".stanbol-tags-suggestions").html("<label class='suggested-tags-title'>Suggested Tags</label>");
                $(".stanbol-tags-suggestions").append("<div class='grouped-tags'></div>");
                for (var gid in data.groupedTags) {
                  var tags = data.groupedTags[gid];
                  $(".stanbol-tags-suggestions .grouped-tags").append("<div data-name='" + gid.toLowerCase() + "' class='group'><label class='group-title'>" + gid + "</label></div>");
                  for (var id in tags) {
                    var tag = tags[id];
                    $(".stanbol-tags-suggestions .grouped-tags .group[data-name='" + gid.toLowerCase() + "']").append("<span data-name='" + id + "' class='tag'>" + tag + "</span>");
                  }
                }
              }
            }
            else {
              var tags = data.tags;
              $(".stanbol-tags-suggestions").html("<label class='suggested-tags-title'>Suggested Tags</label>");
              $(".stanbol-tags-suggestions").append("<div class='flat-tags'></div>");
              for (var id in tags) {
                var tag = tags[id];
                $(".stanbol-tags-suggestions .flat-tags").append("<span data-name='" + id + "' class='tag'>" + tag + "</span>")
              }
            }
          }
          else {
            $(".stanbol-tags-suggestions").html('');
          }
        });
      }
      if (drupalSettings.auto_recommended_tags.fields_selector) {
        $(drupalSettings.auto_recommended_tags.fields_selector).off("keyup blur change").on("keyup blur change", function(){
          if (!$(this).val()) {
            return $(".stanbol-tags-suggestions").html("");
          }
          if (timeout) {
            clearTimeout(timeout);
          }
          timeout = setTimeout(loadTags.bind(this), 500);
        });
      }
      if (CKEDITOR) {
        CKEDITOR.on('instanceReady', function(evt) {
          var instance = evt.editor;
          timeout = setTimeout(loadTags.bind(instance), 500);
          instance.on("change", function(){
            if (!this.getData()) {
              return $(".stanbol-tags-suggestions").html("");
            }
            if (timeout) {
              clearTimeout(timeout);
            }
            timeout = setTimeout(loadTags.bind(this), 500);
          });
        });
      }

      $(".stanbol-tags-suggestions").off('click').on('click', function(evt){
        if (evt && evt.target && $(evt.target).is('.tag')) {
          var autoCompleteElement = $(".stanbol-tags-suggestions").parent().find("input.form-autocomplete");
          var value = autoCompleteElement.val();
          if (value) {
            value += ", " + $(evt.target).text();
          }
          else {
            value = $(evt.target).text();
          }
          autoCompleteElement.val(value);
        }
      });
    }
  };
})(jQuery, Drupal, drupalSettings);
