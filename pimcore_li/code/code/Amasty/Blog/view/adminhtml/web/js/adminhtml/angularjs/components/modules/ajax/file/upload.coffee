###
 * Magpleasure Ltd.
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the EULA
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://www.magpleasure.com/LICENSE.txt
 *
 * @category   Magpleasure
 * @package    Magpleasure_Common
 * @copyright  Copyright (c) 2014 Magpleasure Ltd. (http://www.magpleasure.com)
 * @license    http://www.magpleasure.com/LICENSE.txt
###

angular.module "com.magpleasure.file.upload", ["oi.file"]
.directive "mpFileUpload", ()->
  restrict: "C"
  replace: true
  templateUrl: 'magpleasure/file/upload.html'
  scope:
    mpConfig: "="
  controller: ["$scope", ($scope)->

    $scope.config = $scope.mpConfig

    if $scope.config.has_file
      $scope.file =
        file_type : $scope.config.file_type
        url  : $scope.config.url
        value: $scope.config.value

      if $scope.config.is_image
        $scope.file.thumbnail_url = $scope.config.thumbnail_url
        $scope.file.is_image = true

    else
      $scope.file = {}

    $scope.delete = []

    $scope.options =
      fieldName: $scope.config.html_id
      change: (file)->
        file.$upload($scope.config.upload_url, $scope.file)
        return

    $scope.showProgress = ()->
      !!$scope.file.uploading

    $scope.hasFile = ()->
      !!$scope.file.value

    $scope.hasError = ()->
      !!$scope.file.error

    $scope.getRequired = ()->
      $scope.config.is_required and not $scope.file.has_thumbnail

    $scope.checkImageExistence = ()->
      $($scope.config.html_id).disabled = $scope.config.has_thumbnail

    $scope.disableLoader = ()->
      $scope.loading = false
      $scope.loading_percent = 0
      return

    $scope.startLoader = ()->
      $scope.error_message = false
      $scope.loading_percent = 0
      $scope.loading = true
      return

    $scope.clearData = ()->
      $scope.delete.push($scope.value)
      $scope.value = ''
      $scope.file = {}
      $scope.checkImageExistence()
      return

    return
  ]

# Add Magento Validator
Validation.add(
  'required-file',
  'This is a required field.',
  (value, field)->
    not Validation.get('IsEmpty') and field.hasClassName('field-ready')
)