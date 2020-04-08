###*
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

angular
.module 'mp.blog.layout', ['ngDraggable']
# Element #
.directive 'mpLayoutElement', ()->
  restrict: 'C'
  templateUrl: 'layout/element.html'
  controller: 'LayoutController'
  scope:
    mpName: '@'
    mpId: '@'
    mpValue: '@'
    mpConfig: '@'

# Column #
.directive 'mpLayoutColumn', ()->
  restrict: 'C'
  templateUrl: 'layout/column.html'
  scope: true

# Logic of Controller #
.controller 'LayoutController', ["$scope", ($scope)->
  $scope.config = JSON.parse($scope.mpConfig)
  $scope.value = JSON.parse($scope.mpValue)
  $scope.name = $scope.mpName
  $scope.id = $scope.mpId
  $scope.drag = false

  $scope.adding = false
  $scope.available =
    content: []
    sidebar: []

  # Init Values #
  $scope.initValue = ()->
    $scope.value.layout = false if not $scope.value.layout
    $scope.value.left_side = [] if not $scope.value.left_side
    $scope.value.right_side = [] if not $scope.value.right_side
    $scope.value.content = [] if not $scope.value.content
    return $scope

  # Refresh Available Blocks #
  $scope.refreshAvailable = (type)->
    $scope.available[type] = []

    angular.forEach $scope.config[type], (block)->

      if (type is 'content')
        if ($scope.getBlockId('content', block.value) is false)
          $scope.available[type].push(block)
      else if (type is 'sidebar')
        if (($scope.getBlockId('left_side', block.value) is false) and ($scope.getBlockId('right_side', block.value) is false))
          $scope.available[type].push(block)

      return

    return $scope

  # Retrieve Selected Label #
  $scope.getSelectedLabel = ()->
    $scope.config.layouts[$scope.value.layout]

  $scope.setLayout = (layout)->

    $scope.value.layout = layout
    $scope.adding = false

    if (layout == 'two-columns-left')
      # Move everything to left column
      angular.forEach $scope.value['right_side'], (el)->
        $scope.value['left_side'].push(el)
      $scope.value['right_side'] = []

    else if (layout == 'two-columns-right')
      # Move everything to right column
      angular.forEach $scope.value['left_side'], (el)->
        $scope.value['right_side'].push(el)
      $scope.value['left_side'] = []

    else if (layout == 'one-column')

      # Clean everything
      # TODO: Save it somewhere to prevent loosing
      $scope.value['left_side'] = [];
      $scope.value['right_side'] = [];

    $scope.refreshAvailable('content')
    $scope.refreshAvailable('sidebar')

    false


  $scope.displayLeftSidebar = ()->
    ($scope.value.layout == 'two-columns-left') || ($scope.value.layout == 'three-columns')

  $scope.displayRightSidebar = ()->
    ($scope.value.layout == 'two-columns-right') || ($scope.value.layout == 'three-columns')

  $scope.showVariantsForColumn = (type)->
    $scope.adding = type
    return

  $scope.isActive = (layout)->
    $scope.value.layout == layout

  $scope.displayAddButton = (type, subtype)->
    $scope.available[type].length && ($scope.adding != subtype)

  $scope.getBlockLabel = (type, name)->

    result = false
    angular.forEach($scope.config[type], ((block)->
      result = block.label if name is block.value
    ).bind(result))
    result

  $scope.getBackendImage = (type, name)->

    result = false
    angular.forEach($scope.config[type], ((block)->
      result = block.backend_image if name is block.value
    ).bind(result))
    result

  $scope.getBlockId = (subtype, name)->

    result = false
    angular.forEach($scope.value[subtype], ((el, index)->
      result = index if (el is name)
    ).bind(result))
    result

  $scope.removeBlock = (subtype, index)->

    if (confirm($scope.config.delete_message))
      $scope.value[subtype].splice(index, 1)

    $scope
    .refreshAvailable('content')
    .refreshAvailable('sidebar')

    return $scope

  $scope.addToColumn = (type, block)->

    $scope.value[type].push(block)
    $scope.adding = false
    $scope
    .refreshAvailable('content')
    .refreshAvailable('sidebar')

  $scope.onDragBegin = (data, attrs)->
    $scope.drag =
      type : attrs.ngType
      block: data

    return $scope

  $scope.onDragCancel = (data, attrs)->
    $scope.drag = false
    return

  $scope.onDropSuccess = (data, attrs, oAttrs)->
    $scope.drag = false

    indexToInsert = false
    indexToRemove = $scope.getBlockId(oAttrs.ngSubType, data)

    $scope.value[oAttrs.ngSubType].splice(indexToRemove, 1) if (indexToRemove isnt false)

    if (typeof (attrs.ngDropData) != 'undefined')
      indexToInsert = $scope.getBlockId(attrs.ngSubType, attrs.ngDropData)
      if (indexToInsert isnt false)
        if (indexToInsert is $scope.value[attrs.ngSubType].length)
          $scope.value[attrs.ngSubType].push(data)
        else
          $scope.value[attrs.ngSubType].splice(indexToInsert + 1, 0, data)

      else
        $scope.value[attrs.ngSubType].splice(0, 0, data)

    else
      $scope.value[attrs.ngSubType].splice(0, 0, data)

    return


  $scope.isMyDrag = (type, block)->
    result = ($scope.drag.type is type)
    if (typeof (block) != 'undefined')
      return result && ($scope.drag.block != block)
    return result

  $scope
  .initValue()
  .refreshAvailable('content')
  .refreshAvailable('sidebar')

  return
]



