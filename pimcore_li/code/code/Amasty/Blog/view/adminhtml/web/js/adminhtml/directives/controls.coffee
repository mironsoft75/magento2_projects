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

###
  Directives for Blog Pro Additional Controls
###

module = angular.module 'mpBlogControls', []
module.directive 'mpSelector', [->
  restrict: 'C'
  scope:
    'mpName': '@'
    'mpValue': '@' 
    'mpOptions': '='

  templateUrl: 'mpblog/selector.html'
  controller: ['$scope', ($scope) ->
    
    $scope.name = $scope.mpName
    $scope.options = $scope.mpOptions
    $scope.radio =
      value: $scope.mpValue

    $scope.setValue = (value) ->
      $scope.radio.value = value

    $scope.isActive = (value) ->
      $scope.scope.radio.value is value

    $scope
  ]
]
