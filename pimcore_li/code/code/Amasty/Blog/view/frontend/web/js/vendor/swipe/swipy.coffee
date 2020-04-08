###
 * MagPleasure Ltd.
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the EULA
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://www.magpleasure.com/LICENSE.txt
*
* @category   Magpleasure
* @package    Magpleasure_Blog
* @copyright  Copyright (c) 2012-2015 MagPleasure Ltd. (http://www.magpleasure.com)
* @license    http://www.magpleasure.com/LICENSE.txt
###

(($)->

  $.fn.swipy = (options) ->

    $config = $.extend
      side: "left"
      ignore: ".mp-content.std p, .mp-content.std table"
      enable_point: 770
      button_selector: "#swipe_me"
    , options

    @.each ->

      # Start extension from here
      $document = $(document)
      $window = $(window)
      $panel = $(@)
      $button = $($config.button_selector)

      onWindowResize = () ->
        height = $window.height()
        width = $window.width()

        if width > $config.enable_point
          disableSwipes()
        else
          enableSwipes()

        $panel.css
          height: '#{height}px'

      disableSwipes = ->
        $($document).swipe 'disable'
        $($panel).swipe 'disable'
        @

      enableSwipes = ->
        $($document).swipe 'enable'
        $($panel).swipe 'enable'
        @

      onDocumentClick = (e) ->
        closePanel()

      onPanelClick = (e) ->
        e.stopPropagation()

      onKeyUp = (e) ->
        closePanel() if e.keyCode == 27

      openPanel = () ->

        $panel.addClass 'active'
        $document.on 'keyup', onKeyUp

        setTimeout (e) ->
          $panel.on 'click', onPanelClick
          $document.on 'click', onDocumentClick
        , 300

      closePanel = () ->
        $panel.removeClass 'active'
        $document.off 'keyup', onKeyUp
        $document.off 'click', onDocumentClick
        $panel.off 'click', onPanelClick

      if $config.side is 'right'

        $($document).swipe
          swipeLeft: (event, direction, distance, duration, fingerCount) ->
            openPanel()
          threshold:100
          maxTimeThreshold: 1500
          excludedElements: $.fn.swipe.defaults.excludedElements + "," + $config.ignore

        $($panel).swipe
          swipeRight: (event, direction, distance, duration, fingerCount) ->
            closePanel()
          threshold:80
          maxTimeThreshold: 1500

      else

        $($document).swipe
          swipeRight: (event, direction, distance, duration, fingerCount) ->
            openPanel()
          threshold:100
          maxTimeThreshold: 1500
          excludedElements: $.fn.swipe.defaults.excludedElements + "," + $config.ignore

        $($panel).swipe
          swipeLeft: (event, direction, distance, duration, fingerCount) ->
            closePanel()
          threshold:80
          maxTimeThreshold: 1500


      $button.click ()->
        if $panel.hasClass "active"
          closePanel()
        else
          openPanel()
        @

      onWindowResize()
      $window.on 'resize', onWindowResize

      @

) jQuery
